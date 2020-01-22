<?php

namespace Biztech\ImportData\Model\Mysql4\DesignAreaPrintingMethod;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct() {
        $this->_init('Biztech\ImportData\Model\DesignAreaPrintingMethod', 'Biztech\ImportData\Model\Mysql4\DesignAreaPrintingMethod');
    }
}
