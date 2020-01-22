<?php

namespace Realmoxy\Sweda\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_eventPrefix = 'realmoxy_sweda_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Realmoxy\Sweda\Model\Post', 'Realmoxy\Sweda\Model\ResourceModel\Post');
    }

}
