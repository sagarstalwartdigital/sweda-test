<?php

namespace Biztech\PrintingMethods\Model;

class Colors extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Biztech\PrintingMethods\Model\ResourceModel\Colors');
    }

}

