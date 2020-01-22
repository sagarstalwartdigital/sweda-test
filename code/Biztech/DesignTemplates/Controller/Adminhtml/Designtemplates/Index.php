<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\DesignTemplates\Controller\Adminhtml\Designtemplates;
header("Access-Control-Allow-Origin: *");
class Index extends \Biztech\DesignTemplates\Controller\Adminhtml\Index
{

    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
       
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
       
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_DesignTemplates::designtemplate');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Design Templates'));
        
        return $resultPage;
    }
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Biztech_DesignTemplates::designtemplates');
    }
}
