<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductprice;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductprice', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductprice');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>