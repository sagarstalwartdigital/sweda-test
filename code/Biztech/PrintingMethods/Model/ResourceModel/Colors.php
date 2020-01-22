<?php
namespace Biztech\PrintingMethods\Model\ResourceModel;

class Colors extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('productdesigner_colors', 'colors_id');
    }
}
