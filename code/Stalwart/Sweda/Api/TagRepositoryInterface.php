<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface TagRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface TagRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\TagInterface $data
	* @return \Stalwart\Sweda\Api\Data\TagInterface
	*/ 
	public function save(Data\TagInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\TagInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\TagInterface $data
	 * @return bool
	 */
	public function delete(Data\TagInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\TagSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}