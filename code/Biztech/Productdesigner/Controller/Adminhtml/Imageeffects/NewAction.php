<?php
namespace Biztech\Productdesigner\Controller\Adminhtml\Imageeffects;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\Productdesigner\Controller\Adminhtml\Imageeffects
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
