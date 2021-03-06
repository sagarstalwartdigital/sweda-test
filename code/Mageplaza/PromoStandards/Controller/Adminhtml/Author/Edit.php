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
 * @package     Mageplaza_PromoStandards
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Author;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\PromoStandards\Controller\Adminhtml\Author;
use Mageplaza\PromoStandards\Model\AuthorFactory;

/**
 * Class Edit
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Author
 */
class Edit extends Author
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Mageplaza\PromoStandards\Model\AuthorFactory $authorFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AuthorFactory $authorFactory,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context, $registry, $authorFactory);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Mageplaza\PromoStandards\Model\Author $author */
        $author = $this->initAuthor();
        if (!$author) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*');

            return $resultRedirect;
        }

        /** Set entered data if was error when we do save */
        $data = $this->_session->getData('mageplaza_promostandards_author_data', true);
        if (!empty($data)) {
            $author->addData($data);
        }

        $this->coreRegistry->register('mageplaza_promostandards_author', $author);

        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageplaza_PromoStandards::author');
        $resultPage->getConfig()->getTitle()->set(__('Author Management'));

        $resultPage->getConfig()->getTitle()->prepend($this->_auth->getUser()->getName());

        return $resultPage;
    }
}
