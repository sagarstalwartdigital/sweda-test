<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductPriceRegularRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductPriceRegularRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface
	*/ 
	public function save(Data\ProductPriceRegularInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductPriceRegularInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductPriceRegularInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductPriceRegularSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}