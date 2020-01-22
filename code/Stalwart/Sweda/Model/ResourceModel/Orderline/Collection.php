<?php

namespace  Stalwart\Sweda\Model\ResourceModel\Orderline;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'stalwart_sweda_order_line_collection';
    protected $_eventObject = 'order_line_collection';

    protected function _construct()
    {
        $this->_init('Stalwart\Sweda\Model\Orderline', 'Stalwart\Sweda\Model\ResourceModel\Orderline');
    }
}