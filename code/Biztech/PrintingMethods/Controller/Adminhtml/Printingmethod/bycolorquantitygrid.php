<?php


namespace Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod;
header("Access-Control-Allow-Origin: *");
class bycolorquantitygrid extends \Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod {

    public function execute() {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('printing.grid')->setProducts($this->getRequest()->getPost('products', null));
        return $resultLayout;
    }

}
