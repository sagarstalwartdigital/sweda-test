<?php

namespace Stalwart\Sweda\Api\Data;

/**
 * Interface ProductPriceRegularSearchResultInterface
 */
interface ProductPriceRegularSearchResultInterface
{
	/**
	* @return \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface[]
	*/
	public function getItems();

	/**
	* @param \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface[] $items
	* @return void
	*/
	public function setItems(array $items); 
}