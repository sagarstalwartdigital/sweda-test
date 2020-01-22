<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ImprintPositionRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ImprintPositionRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ImprintPositionInterface $data
	* @return \Stalwart\Sweda\Api\Data\ImprintPositionInterface
	*/ 
	public function save(Data\ImprintPositionInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ImprintPositionInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ImprintPositionInterface $data
	 * @return bool
	 */
	public function delete(Data\ImprintPositionInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ImprintPositionSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}