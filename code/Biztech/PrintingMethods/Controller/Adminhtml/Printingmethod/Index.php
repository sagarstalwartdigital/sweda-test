<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod;
header("Access-Control-Allow-Origin: *");
class Index extends \Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_PrintingMethods:printingmethod');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Printing Methods'));
        
        return $resultPage;
    }
}
