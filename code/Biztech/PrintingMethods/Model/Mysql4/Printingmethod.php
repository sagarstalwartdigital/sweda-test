<?php


namespace Biztech\PrintingMethods\Model\Mysql4;

class Printingmethod extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('productdesigner_printing_method', 'printing_id');
    }
}
