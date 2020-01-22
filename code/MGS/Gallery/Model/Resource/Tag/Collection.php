<?php

namespace MGS\Gallery\Model\Resource\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MGS\Gallery\Model\Tag', 'MGS\Gallery\Model\Resource\Tag');
    }
}
