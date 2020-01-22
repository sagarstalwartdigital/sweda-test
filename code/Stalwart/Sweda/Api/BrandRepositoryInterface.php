<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface BrandRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface BrandRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\BrandInterface $data
	* @return \Stalwart\Sweda\Api\Data\BrandInterface
	*/ 
	public function save(Data\BrandInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\BrandInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\BrandInterface $data
	 * @return bool
	 */
	public function delete(Data\BrandInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\BrandSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}