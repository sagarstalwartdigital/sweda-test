<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductPackagingMethodRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductPackagingMethodRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductPackagingMethodInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductPackagingMethodInterface
	*/ 
	public function save(Data\ProductPackagingMethodInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductPackagingMethodInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductPackagingMethodInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductPackagingMethodInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductPackagingMethodSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}