<?php

namespace Biztech\PrintingMethods\Model\Mysql4\Areasize;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'areasize_id';
    protected function _construct()
    {
        $this->_init('Biztech\PrintingMethods\Model\Areasize', 'Biztech\PrintingMethods\Model\Mysql4\Areasize');
    }
}
