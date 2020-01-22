<?php

namespace  Stalwart\Sweda\Model\ResourceModel\ImportJob;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'stalwart_sweda_importjobs_collection';
    protected $_eventObject = 'importjobs_collection';

    protected function _construct()
    {
        $this->_init('Stalwart\Sweda\Model\ImportJob', 'Stalwart\Sweda\Model\ResourceModel\ImportJob');
    }
}