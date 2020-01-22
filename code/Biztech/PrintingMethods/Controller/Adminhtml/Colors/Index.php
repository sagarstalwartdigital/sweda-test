<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Colors;

class Index extends \Biztech\PrintingMethods\Controller\Adminhtml\Colors {

    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_PrintingMethods:colors');
        
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Color Counter'));

        return $resultPage;
    }

}
