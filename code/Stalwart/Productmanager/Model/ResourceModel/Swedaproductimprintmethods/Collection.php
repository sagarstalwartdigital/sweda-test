<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductimprintmethods;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductimprintmethods', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductimprintmethods');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>