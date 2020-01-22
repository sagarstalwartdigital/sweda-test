<?php
namespace Stalwart\Smartcart\Model\ResourceModel;

class Smartcartitem extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('invi_smartcart_item', 'id');
    }
}
?>