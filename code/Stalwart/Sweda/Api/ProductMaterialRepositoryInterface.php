<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductMaterialRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductMaterialRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductMaterialInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductMaterialInterface
	*/ 
	public function save(Data\ProductMaterialInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductMaterialInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductMaterialInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductMaterialInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductMaterialSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}