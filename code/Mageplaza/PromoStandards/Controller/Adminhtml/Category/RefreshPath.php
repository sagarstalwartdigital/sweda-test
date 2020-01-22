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

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Category;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Mageplaza\PromoStandards\Controller\Adminhtml\Category;
use Mageplaza\PromoStandards\Model\CategoryFactory;

/**
 * Class RefreshPath
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Category
 */
class RefreshPath extends Category
{
    /**
     * JSON Result Factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * RefreshPath constructor.
     *
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mageplaza\PromoStandards\Model\CategoryFactory $categoryFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CategoryFactory $categoryFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context, $coreRegistry, $categoryFactory);
    }

    /**
     * Build response for refresh input element 'path' in form
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $categoryId = (int) $this->getRequest()->getParam('id');
        if ($categoryId) {
            $category = $this->categoryFactory->create()->load($categoryId);

            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            return $resultJson->setData(['id' => $categoryId, 'path' => $category->getPath()]);
        }
    }
}