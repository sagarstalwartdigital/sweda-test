<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductpricedata;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductpricedata', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductpricedata');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>