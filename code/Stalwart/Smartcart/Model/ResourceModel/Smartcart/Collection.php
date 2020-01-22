<?php

namespace Stalwart\Smartcart\Model\ResourceModel\Smartcart;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Smartcart\Model\Smartcart', 'Stalwart\Smartcart\Model\ResourceModel\Smartcart');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>