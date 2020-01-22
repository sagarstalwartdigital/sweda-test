<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
