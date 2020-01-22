<?php

namespace MGS\Gallery\Block;

use Magento\Customer\Model\Context as CustomerContext;

class Sidebar extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    protected $_galleryHelper;
    protected $_post;
    protected $_category;
    protected $httpContext;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \MGS\Gallery\Helper\Data $galleryHelper,
        \MGS\Gallery\Model\Post $post,
        \MGS\Gallery\Model\Category $category,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    )
    {
        $this->_category = $category;
        $this->_post = $post;
        $this->_coreRegistry = $registry;
        $this->_galleryHelper = $galleryHelper;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        if (!$this->getConfig('general_settings/enabled')) return;
        if (!$this->getConfig('sidebar_settings/enabled')) return;
        parent::_construct();
        $post = $this->_post;
        $postCollection = $post->getCollection()
            ->addFieldToFilter('status', 1);
        $postCollection->getSelect()->limit($this->getConfig('sidebar_settings/number_of_recent_posts'));
        $postCollection->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', 'DESC');
        $this->setCollection($postCollection);
    }

    public function getCacheKeyInfo()
    {
        return [
            'Gallery_POSTS_SIDEBAR',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP),
            'template' => $this->getTemplate()
        ];
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getCollection()
    {
        return $this->_collection;
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_galleryHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCategories()
    {
        $category = $this->_category;
        $categoryCollection = $category->getCollection()
            ->addFieldToFilter('status', 1);
        $categoryCollection->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('sort_order', 'ASC');
        return $categoryCollection;
    }

    public function getTags()
    {
        $postCollection = $this->_post->getCollection()
            ->addFieldToFilter('status', 1);
        $postCollection->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', 'DESC');
        $tags = [];
        foreach ($postCollection as $post) {
            $postTags = explode(',', $post->getTags());
            foreach ($postTags as $tag) {
                if ($tag == null || $tag == '') continue;
                $tags[] = trim($tag);
            }
        }
        return array_count_values($tags);
    }
}
