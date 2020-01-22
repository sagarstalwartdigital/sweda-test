<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductImprintMethodRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductImprintMethodRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductImprintMethodInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodInterface
	*/ 
	public function save(Data\ProductImprintMethodInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductImprintMethodInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductImprintMethodInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductImprintMethodInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductImprintMethodSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}