<?php


namespace Biztech\PrintingMethods\Controller\Adminhtml\Areasize;
header("Access-Control-Allow-Origin: *");
class Index extends \Biztech\PrintingMethods\Controller\Adminhtml\Areasize
{
    public function execute()
    {
       
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
       
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_PrintingMethods:areasize');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Area Size'));
        
       
        return $resultPage;
    }
}
