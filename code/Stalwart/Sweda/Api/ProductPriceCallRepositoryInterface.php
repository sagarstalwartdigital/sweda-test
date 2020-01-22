<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductPriceCallRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductPriceCallRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductPriceCallInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductPriceCallInterface
	*/ 
	public function save(Data\ProductPriceCallInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductPriceCallInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductPriceCallInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductPriceCallInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductPriceCallSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}