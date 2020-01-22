<?php

namespace Biztech\ImportData\Model\Mysql4\VirtualImages;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct() {
        $this->_init('Biztech\ImportData\Model\VirtualImages', 'Biztech\ImportData\Model\Mysql4\VirtualImages');
    }
}
