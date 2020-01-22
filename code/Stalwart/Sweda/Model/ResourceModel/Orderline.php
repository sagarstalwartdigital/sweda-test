<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Orderline extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sweda_order_lines', 'id');
    }
}