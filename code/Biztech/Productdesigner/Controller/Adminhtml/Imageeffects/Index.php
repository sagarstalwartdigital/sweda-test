<?php
namespace Biztech\Productdesigner\Controller\Adminhtml\Imageeffects;
header("Access-Control-Allow-Origin: *");
class Index extends \Biztech\Productdesigner\Controller\Adminhtml\Imageeffects
{
    public function execute()
    {
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_Productdesigner:imageeffects');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Image Effects and Filters'));
        
       
        return $resultPage;
    }
}
