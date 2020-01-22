<?php

namespace Biztech\PrintingMethods\Model\Mysql4;

class Areasize extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('productdesigner_areasize', 'areasize_id');
    }
}
