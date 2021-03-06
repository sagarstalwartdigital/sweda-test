<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface MaterialRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface MaterialRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\MaterialInterface $data
	* @return \Stalwart\Sweda\Api\Data\MaterialInterface
	*/ 
	public function save(Data\MaterialInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\MaterialInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\MaterialInterface $data
	 * @return bool
	 */
	public function delete(Data\MaterialInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\MaterialSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}