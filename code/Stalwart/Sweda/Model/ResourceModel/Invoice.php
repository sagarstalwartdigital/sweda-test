<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Invoice extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sweda_invoice', 'invoice_number');
    }
}