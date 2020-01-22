<?php

namespace MGS\Gallery\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use MGS\Gallery\Model\Resource\Tag as TagResource;
use MGS\Gallery\Model\Resource\Tag\Collection;
use MGS\Gallery\Helper\Data;

class Tag extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    protected $storeManager;
    protected $galleryHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        TagResource $resource = null,
        Collection $resourceCollection = null,
        Data $galleryHelper,
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->galleryHelper = $galleryHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('MGS\Gallery\Model\Resource\Tag');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
