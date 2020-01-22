<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Colors;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\PrintingMethods\Controller\Adminhtml\Colors
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
