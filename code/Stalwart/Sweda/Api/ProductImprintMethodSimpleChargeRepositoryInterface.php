<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductImprintMethodSimpleChargeRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductImprintMethodSimpleChargeRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeInterface
	*/ 
	public function save(Data\ProductImprintMethodSimpleChargeInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductImprintMethodSimpleChargeInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}