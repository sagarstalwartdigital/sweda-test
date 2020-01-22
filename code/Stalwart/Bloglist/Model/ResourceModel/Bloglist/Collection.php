<?php

namespace Stalwart\Bloglist\Model\ResourceModel\Bloglist;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Bloglist\Model\Bloglist', 'Stalwart\Bloglist\Model\ResourceModel\Bloglist');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>