<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductShippingInfoRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductShippingInfoRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductShippingInfoInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductShippingInfoInterface
	*/ 
	public function save(Data\ProductShippingInfoInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductShippingInfoInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductShippingInfoInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductShippingInfoInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductShippingInfoSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}