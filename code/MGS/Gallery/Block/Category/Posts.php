<?php

namespace MGS\Gallery\Block\Category;

use Magento\Customer\Model\Context as CustomerContext;

class Posts extends \Magento\Framework\View\Element\Template
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
        $this->_post = $post;
        $this->_category = $category;
        $this->_coreRegistry = $registry;
        $this->_galleryHelper = $galleryHelper;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        if (!$this->getConfig('general_settings/enabled')) return;
        parent::_construct();
        $category = $this->getCurrentCategory();
        $post = $this->_post;
        $postCollection = $post->getCollection()
            ->addFieldToFilter('status', 1)
            ->addCategoryFilter($category->getId())
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', $this->getConfig('general_settings/default_sort'));
        $this->setCollection($postCollection);
    }

    public function getCacheKeyInfo()
    {
        return [
            'Gallery_POST_LIST',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP),
            'template' => $this->getTemplate()
        ];
    }

    protected function _addBreadcrumbs()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $pageTitle = $this->_galleryHelper->getConfig('general_settings/title');
        $category = $this->getCurrentCategory();
        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $baseUrl
            ]
        );
        $breadcrumbsBlock->addCrumb(
            'gallery',
            [
                'label' => __('Tools'),
                'title' => __('Go to Tools Page'),
               'link' => $baseUrl.'tools'
            ]
        );
        $breadcrumbsBlock->addCrumb(
            'category',
            [
                'label' => $category->getTitle(),
                'title' => $category->getTitle(),
                'link' => ''
            ]
        );
    }

    public function getParentCategory($postId){
        $postCollection = $this->_category->getCollection()
                                ->addFieldToFilter('status', 1)
                                ->addPostFilter($postId);
        return $postCollection;
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

    public function getCurrentCategory()
    {
        $category = $this->_coreRegistry->registry('current_category');
        if ($category) {
            $this->setData('current_category', $category);
        }
        return $category;
    }

    protected function _prepareLayout()
    {
        $category = $this->getCurrentCategory();
        $pageTitle = $category->getTitle();
        $metaKeywords = $category->getMetaKeywords();
        $metaDescription = $category->getMetaDescription();
        $this->_addBreadcrumbs();
        $this->pageConfig->addBodyClass('gallery-post-list');
        if ($pageTitle) {
            $this->pageConfig->getTitle()->set($pageTitle);
        }
        if ($metaKeywords) {
            $this->pageConfig->setKeywords($metaKeywords);
        }
        if ($metaDescription) {
            $this->pageConfig->setDescription($metaDescription);
        }
        if ($this->getCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'gallery.post.list.pager'
            );
            $pager->setLimit($this->getConfig('general_settings/posts_per_page'))->setCollection(
                $this->getCollection()
            );
            $this->setChild('pager', $pager);
        }
        return parent::_prepareLayout();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
