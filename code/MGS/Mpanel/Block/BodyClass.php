<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\Mpanel\Block;

/**
 * Main contact form block
 */
class BodyClass extends \Magento\Framework\View\Element\Template {
	
	protected $_themeHelper;
	
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\MGS\Mpanel\Helper\Data $themeHelper,
		\MGS\Blog\Helper\Data $blogHelper,
		array $data = []
	) 
	{
		$this->_themeHelper = $themeHelper;
		$this->_blogHelper = $blogHelper;
		parent::__construct($context, $data);
	}
	
	protected function _prepareLayout()
    {
		$this->pageConfig->addBodyClass('use-' . $this->_themeHelper->getHeaderClass());
		
		$ratio = $this->_themeHelper->getStoreConfig('mpanel/catalog/picture_ratio');
		$this->pageConfig->addBodyClass('ratio-' . $ratio);
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$_category = $objectManager->get('Magento\Framework\Registry')->registry('current_category');
		$img = "";
		$img = $this->_themeHelper->getPageTitleBackground();
		if(($this->getRequest()->getFullActionName() == 'catalog_category_view') && $this->_themeHelper->getStoreConfig('mpanel/catalog/page_title') && $_category->getImageUrl()) {
			$img = $_category->getImageUrl();
		}
		if($this->_themeHelper->getStoreConfig('mgstheme/header/absolute_header') && ($this->getRequest()->getFullActionName() != 'blog_post_view')){
			if(($img != "") || ($this->getRequest()->getFullActionName() == 'cms_index_index')){
				if(($this->getRequest()->getFullActionName() == 'catalog_product_view') || ($this->getRequest()->getFullActionName() == 'checkout_cart_configure') || ($this->getRequest()->getFullActionName() == 'wishlist_index_configure')){
					if(!$this->_themeHelper->getStoreConfig('mpanel/product_details/page_title')){
						$this->pageConfig->addBodyClass('absolute-header');
					}
				}else{
					$this->pageConfig->addBodyClass('absolute-header');
				}
			}
		}
        
        if($this->_themeHelper->getStoreConfig('mpanel/search/search_full')){
			$this->pageConfig->addBodyClass('search-full');
		}
		
		if($this->_themeHelper->getStoreConfig('mgstheme/general/lazy_load')){
			$this->pageConfig->addBodyClass('lazy-load-img');
		}
		
		if($this->_themeHelper->getStoreConfig('mpanel/minicart/minicart_type') == 2){
			$this->pageConfig->addBodyClass('sidebar-cart-type');
		}else {
			$this->pageConfig->addBodyClass('dropdown-cart-type');
		}
		
		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();

		$registry = $objectManager->get('\Magento\Framework\Registry');

		$currentProduct = $registry->registry('current_product');
		if(($this->getRequest()->getFullActionName() == 'catalog_product_view') || ($this->getRequest()->getFullActionName() == 'checkout_cart_configure') || ($this->getRequest()->getFullActionName() == 'wishlist_index_configure')){
			$template = "template-";
			if($this->_themeHelper->getStoreConfig('mpanel/product_details/product_template')){
				$template .= $this->_themeHelper->getStoreConfig('mpanel/product_details/product_template');
				$this->pageConfig->addBodyClass($template);
			}
		}
		
		if(($this->getRequest()->getFullActionName() == 'catalog_category_view') && ($this->_themeHelper->getStoreConfig('mgstheme/general/width') != 'fullwidth')){
			$width = "fullwidth";
			if($this->_themeHelper->getStoreConfig('mpanel/catalog/full_width')){
				$this->pageConfig->addBodyClass($width);
			}
		}

		$blog = 0;
		if(($this->getRequest()->getFullActionName() == 'blog_tag_view') || ($this->getRequest()->getFullActionName() == 'blog_category_view') || ($this->getRequest()->getFullActionName() == 'blog_index_index')){
			$blog = 1;
		}
		if(($blog == 1) && ($this->_blogHelper->getConfig('general_settings/fullwidth') == 1) && ($this->_themeHelper->getStoreConfig('mgstheme/general/width') != 'fullwidth')){
			$width = "fullwidth";
			$this->pageConfig->addBodyClass($width);
		}
		
        return parent::_prepareLayout();
    }
}

