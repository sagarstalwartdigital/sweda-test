<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductmasterimprintmethods;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductmasterimprintmethods', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductmasterimprintmethods');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>