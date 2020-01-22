<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Areasize;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\PrintingMethods\Controller\Adminhtml\Areasize
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
