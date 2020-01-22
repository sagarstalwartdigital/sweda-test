<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;

class ImportJob extends AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Stalwart\Sweda\Model\ResourceModel\ImportJob');
    }
}