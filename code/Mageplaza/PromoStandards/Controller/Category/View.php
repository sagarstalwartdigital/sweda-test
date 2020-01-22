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

namespace Mageplaza\PromoStandards\Controller\Category;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\PromoStandards\Helper\Data as HelperPromoStandards;

/**
 * Class View
 * @package Mageplaza\PromoStandards\Controller\Category
 */
class View extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @type ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var HelperPromoStandards
     */
    public $helperPromoStandards;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param HelperPromoStandards $helperPromoStandards
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        HelperPromoStandards $helperPromoStandards
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->helperPromoStandards = $helperPromoStandards;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $category = $this->helperPromoStandards->getFactoryByType(HelperPromoStandards::TYPE_CATEGORY)->create()->load($id);

        if (!$this->helperPromoStandards->checkStore($category)) {
            return $this->_redirect('noroute');
        }

        $page = $this->resultPageFactory->create();
        $page->getConfig()->setPageLayout($this->helperPromoStandards->getSidebarLayout());

        return $category->getEnabled() ? $page : $this->_redirect('noroute');
    }
}
