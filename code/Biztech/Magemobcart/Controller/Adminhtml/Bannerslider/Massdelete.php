<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Magemobcart\Controller\Adminhtml\Bannerslider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Massdelete extends Action
{
    protected $bannersliderModel;

    public function __construct(
        Context $context,
        \Biztech\Magemobcart\Model\Bannerslider $bannersliderModel
    ) {
        parent::__construct($context);
        $this->_bannersliderModel = $bannersliderModel;
    }
    public function execute()
    {
        $bannersliderIds = $this->getRequest()->getParam('bannerslider');
        if (!is_array($bannersliderIds)) {
            $this->messageManager->addError(__('Please select banners'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        } else {
            foreach ($bannersliderIds as $bannersliderId) {
                $bannerslider = $this->_bannersliderModel->load($bannersliderId);
                $bannerslider->delete();
            }
            $this->messageManager->addSuccess(__('Successfully banner slider removed'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}
