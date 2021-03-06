<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Vlibrary
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Vlibrary\Controller\Adminhtml\Category;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\Vlibrary\Controller\Adminhtml\Category;
use Mageplaza\Vlibrary\Model\CategoryFactory;

/**
 * Class Edit
 * @package Mageplaza\Vlibrary\Controller\Adminhtml\Category
 */
class Edit extends Category
{
    /**
     * Page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * Result JSON factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Magento\Framework\DataObject
     */
    public $dataObject;

    /**
     * Edit constructor.
     *
     * @param \Magento\Framework\DataObject $dataObject
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mageplaza\Vlibrary\Model\CategoryFactory $categoryFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CategoryFactory $categoryFactory,
        DataObject $dataObject,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->dataObject = $dataObject;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context, $registry, $categoryFactory);
    }

    /**
     * Edit Vlibrary category page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $categoryId = (int) $this->getRequest()->getParam('id');

        $category = $this->initCategory();
        if (!$category) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*');

            return $resultRedirect;
        }

        /**
         * Check if we have data in session (if during Vlibrary category save was exception)
         */
        $data = $this->_getSession()->getData('mageplaza_vlibrary_category_data', true);
        if (isset($data['category'])) {
            $category->addData($data['category']);
        }

        $this->coreRegistry->register('category', $category);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        /** Build response for ajax request */
        if ($this->getRequest()->getQuery('isAjax')) {
            // prepare breadcrumbs of selected Vlibrary category, if any
            $breadcrumbsPath = $category->getPath();
            if (empty($breadcrumbsPath)) {
                // but if no Vlibrary category, and it is deleted - prepare breadcrumbs from path, saved in session
                $breadcrumbsPath = $this->_objectManager->get('Magento\Backend\Model\Auth\Session')
                    ->getDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    // no need to get parent breadcrumbs if deleting Vlibrary category level 1
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            $layout = $resultPage->getLayout();
            $content = $layout->getBlock('mageplaza.vlibrary.category.edit')->getFormHtml()
                       . $layout->getBlock('mageplaza.vlibrary.category.tree')
                           ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingCategoryBreadcrumbs');
            $eventResponse = $this->dataObject->addData([
                'content'  => $content,
                'messages' => $layout->getMessagesBlock()->getGroupedHtml(),
                'toolbar'  => $layout->getBlock('page.actions.toolbar')->toHtml()
            ]);

            $this->_eventManager->dispatch(
                'mageplaza_vlibrary_category_prepare_ajax_response',
                ['response' => $eventResponse, 'controller' => $this]
            );

            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setHeader('Content-type', 'application/json', true);
            $resultJson->setData($eventResponse->getData());

            return $resultJson;
        }

        $resultPage->setActiveMenu('Mageplaza_Vlibrary::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Categories'));

        if ($categoryId) {
            $title = __('%1 (ID: %2)', $category->getName(), $categoryId);
        } else {
            $parentId = (int) $this->getRequest()->getParam('parent');
            if ($parentId && $parentId != CategoryModel::TREE_ROOT_ID) {
                $title = __('New Child Category');
            } else {
                $title = __('New Root Category');
            }
        }
        $resultPage->getConfig()->getTitle()->prepend($title);

        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Categories'));

        return $resultPage;
    }
}
