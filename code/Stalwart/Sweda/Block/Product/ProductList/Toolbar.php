<?php
namespace Stalwart\Sweda\Block\Product\ProductList;

use \Magento\Catalog\Block\Product\ProductList\Toolbar as ToolbarCore;

class Toolbar extends ToolbarCore
{
	public function setCollection($collection)
	{	
		$this->_collection = $collection;

		$this->_collection->setCurPage($this->getCurrentPage());

		$limit = (int)$this->getLimit();

		if ($limit) {
			$this->_collection->setPageSize($limit);
		}

		switch ($this->getRequest()->getParam('product_list_order')) {
            case "name_atoz":
                $shortBy = 'name';
                $shortAs = 'ASC';
                break;
            case "name_ztoa":
                $shortBy = 'name';
                $shortAs = 'DESC';
                break;
            case "price_hightolow":
                $shortBy = 'price';
                $shortAs = 'DESC';
                break;
            case "price_lowtohigh":
                $shortBy = 'price';
                $shortAs = 'ASC';
                break;
            case "newtoold":
                $shortBy = 'created_at';
                $shortAs = 'DESC';
                break;
            case "oldtonew":
                $shortBy = 'created_at';
                $shortAs = 'ASC';
                break;
            case "item_atoz":
                $shortBy = 'sku';
                $shortAs = 'ASC';
                break;
            case "item_ztoa":
                $shortBy = 'sku';
                $shortAs = 'DESC';
                break;
            default:
                $shortBy = 'name';
                $shortAs = 'ASC';
                break;
        }

		if ($this->getRequest()->getParam('product_list_order')) {
			$this->_collection->setOrder($shortBy, $shortAs);
		}

		return $this;
	}

	public function isMyOrderCurrent() {
		return $this->getRequest()->getParam('product_list_order');
	}
}