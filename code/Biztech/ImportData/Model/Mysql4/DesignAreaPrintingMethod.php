<?php
namespace Biztech\ImportData\Model\Mysql4;

class DesignAreaPrintingMethod extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('productdesigner_designarea_printingmethod', 'id');
    }
}
