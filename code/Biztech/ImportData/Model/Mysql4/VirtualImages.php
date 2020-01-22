<?php
namespace Biztech\ImportData\Model\Mysql4;

class VirtualImages extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('productdesigner_virtual_images', 'id');
    }
}
