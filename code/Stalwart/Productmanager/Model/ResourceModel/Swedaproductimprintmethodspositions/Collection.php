<?php

namespace Stalwart\Productmanager\Model\ResourceModel\Swedaproductimprintmethodspositions;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Productmanager\Model\Swedaproductimprintmethodspositions', 'Stalwart\Productmanager\Model\ResourceModel\Swedaproductimprintmethodspositions');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>