<?php

namespace Realmoxy\Sweda\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'realmoxy_sweda_post';

    protected $_cacheTag = 'realmoxy_sweda_post';

    protected $_eventPrefix = 'realmoxy_sweda_post';

    protected function _construct()
    {
        $this->_init('Realmoxy\Sweda\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}