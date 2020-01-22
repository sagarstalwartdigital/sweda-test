<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductImprintMethodAdvancedChargeRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductImprintMethodAdvancedChargeRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeInterface
	*/ 
	public function save(Data\ProductImprintMethodAdvancedChargeInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductImprintMethodAdvancedChargeInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}