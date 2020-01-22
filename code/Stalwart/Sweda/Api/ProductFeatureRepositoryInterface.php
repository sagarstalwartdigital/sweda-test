<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductFeatureRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductFeatureRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductFeatureInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductFeatureInterface
	*/ 
	public function save(Data\ProductFeatureInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductFeatureInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductFeatureInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductFeatureInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductFeatureSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}