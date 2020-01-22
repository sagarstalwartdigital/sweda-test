<?php

namespace MGS\Landing\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Category;

class Landing extends Template{

    /**
     * @var Category
     */
    protected $_categoryInstance;
    
    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Category collection factory
     *
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryModel;
    
    /**
     * Store Config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model $categoryModel
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
	
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Category $categoryModel,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {       
         parent::__construct($context);
         $this->_catalogLayer = $layerResolver->get();
         $this->_productCollectionFactory = $productCollectionFactory;
         $this->_categoryModel = $categoryModel;
         $this->_categoryInstance = $categoryFactory->create();
         $this->_storeManager = $storeManager;
         $this->_scopeConfig = $scopeConfig;
    }
    
    /**
     * Retrieve child categories of current category
     *
     * @return \Magento\Framework\Data\Tree\Node\Collection
     */
    public function getChildCategory()
    {

        $searchQuery = $this->getRequest()->getPost('search_cat');
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $subCats = $this->_catalogLayer->getCurrentCategory()->getChildrenCategories();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $subCatsIds = array();
        foreach($subCats as $subCat)
        {
            $subCatsIds[$subCat->getId()] = $subCat->getId();
        }
        
        $categoryCollection = $objectManager->create('Magento\Catalog\Model\Category')->getCollection();
        $categoryCollection->addAttributeToFilter('entity_id',array('in'=>$subCatsIds));
        $categoryCollection->addAttributeToFilter('is_active',1);
        $categoryCollection->addAttributeToFilter('name', array(
            array('like' => '%'.$searchQuery.'%'),
            array('like' => '%'.$searchQuery),
            array('like' => $searchQuery.'%')
        ));
        // $productCollection = $this->_productCollectionFactory->create();
        // $this->_catalogLayer->prepareProductCollection($productCollection);
        // $productCollection->addCountToCategories($categories);
        return $categoryCollection;
    }
    
    /**
     * Get url for category data
     *
     * @param Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_categoryInstance->setData($category->getData())->getUrl();
        }

        return $url;
    }
    
    /**
     * Get Description for category data
     *
     * @param Category Id $subCatid
     * @return string
     */
    public function getCateDescription($subCatid)
    {
        $_category = $this->_categoryModel->load($subCatid);
        $character = $this->getConfig('mgs_landing/general/character');
        if(!$character) {
            $character = 120;
        }
        
        $description = $_category->getDescription();
        
        if($description){
            $description = substr($description,0,$character) . '...';
            return $description;
        }
        
        return;
    }
    
    /**
     * @param string $subCatid
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImageUrl($subCatid)
    {
        $_category = $this->_categoryModel->load($subCatid);
        $image = $_category->getMgsCateThumb();
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $url = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
    /**
     * @param string $config_path
     * @return bool|string
     */
    public function getConfig($config_path)
    {
        return $this->_scopeConfig->getValue(
                $config_path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
    }
    
    /**
     * @param string $position
     * @return string
     */
    public function getColClass($position,$layout)
    {
        $class = '';
        $perrow = $this->getConfig('mgs_landing/general/per_row');
        
        switch($perrow){
			case 2:
				$class .= 'col-md-6 col-sm-6 col-xs-12';
                if($position % 2  == 1 && $layout == 'grid'){
                    $class .= ' first-item-md';
                }
				break;
			case 3:
				$class .= 'col-md-4 col-sm-6 col-xs-12';
                if($position % 3  == 1 && $layout == 'grid'){
                    $class .= ' first-item-md';
                }
				break;
			case 4:
				$class .= 'col-md-3 col-sm-6 col-xs-12';
                if($position % 4  == 1 && $layout == 'grid'){
                    $class .= ' first-item-md';
                }
				break;
			case 5:
				$class .= 'col-item-5 col-sm-6 col-xs-12';
                if($position % 5  == 1 && $layout == 'grid'){
                    $class .= ' first-item-md';
                }
				break;
		}
        if($position % 2  == 1 && $layout == 'grid'){
            $class .= ' first-item-sm';
        }
        
        return $class;
    }

    public function getSearchQuery() {
        return $this->getRequest()->getPost('search_cat');
    }

    // const XML_PATH_SELECTED_CATEGORY_ID = 'category_ads/parameters/select_cats';
    // const XML_PATH_ADDVERTISE_CONTENT = 'category_ads/parameters/ads_content';
    // const XML_PATH_MAIN_HEADING = 'category_ads/parameters/main_heading';
    // const XML_PATH_SECONDARY_HEADING = 'category_ads/parameters/secondary_heading';
    // const XML_PATH_BANNER_IMAGE = 'category_ads/parameters/banner_image_1';

    // public function getSecondaryHeading() {
       
    // }
    // public function getMainHeading() {
        
    // }
    // public function getAdsContent() {
        
    // }
    // public function getBannerImage() {
        
    // }
    public function getSelectedCategoryId() {
        return $this->_catalogLayer->getCurrentCategory()->getId();
    }
}
?>