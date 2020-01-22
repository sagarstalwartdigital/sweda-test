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

namespace Mageplaza\Vlibrary\Controller\Tag;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\Vlibrary\Helper\Data as HelperVlibrary;

/**
 * Class View
 * @package Mageplaza\Vlibrary\Controller\Tag
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
     * @var HelperVlibrary
     */
    public $helperVlibrary;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param HelperVlibrary $helperVlibrary
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        HelperVlibrary $helperVlibrary
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->helperVlibrary = $helperVlibrary;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $tag = $this->helperVlibrary->getFactoryByType(HelperVlibrary::TYPE_TAG)->create()->load($id);

        if (!$this->helperVlibrary->checkStore($tag)) {
            return $this->_redirect('noroute');
        }

        $page = $this->resultPageFactory->create();
        $page->getConfig()->setPageLayout($this->helperVlibrary->getSidebarLayout());

        return $tag->getEnabled() ? $page : $this->_redirect('noroute');
    }
}
