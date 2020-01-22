<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductmasterimprintcharges;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductmasterimprintcharges', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductmasterimprintcharges');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>