<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface PackagingMethodRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface PackagingMethodRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\PackagingMethodInterface $data
	* @return \Stalwart\Sweda\Api\Data\PackagingMethodInterface
	*/ 
	public function save(Data\PackagingMethodInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\PackagingMethodInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\PackagingMethodInterface $data
	 * @return bool
	 */
	public function delete(Data\PackagingMethodInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\PackagingMethodSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}