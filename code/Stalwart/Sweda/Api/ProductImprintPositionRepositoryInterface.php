<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductImprintPositionRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductImprintPositionRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductImprintPositionInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductImprintPositionInterface
	*/ 
	public function save(Data\ProductImprintPositionInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductImprintPositionInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductImprintPositionInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductImprintPositionInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductImprintPositionSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}