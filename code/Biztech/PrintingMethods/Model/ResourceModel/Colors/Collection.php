<?php
namespace Biztech\PrintingMethods\Model\ResourceModel\Colors;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
   
    protected $_idFieldName = 'colors_id';
    
    protected function _construct(){
        $this->_init('Biztech\PrintingMethods\Model\Colors', 'Biztech\PrintingMethods\Model\ResourceModel\Colors');
    }
}
