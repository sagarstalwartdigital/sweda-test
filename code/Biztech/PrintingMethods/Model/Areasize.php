<?php
namespace Biztech\PrintingMethods\Model;

class Areasize extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Biztech\PrintingMethods\Model\Mysql4\Areasize');
    }

}

