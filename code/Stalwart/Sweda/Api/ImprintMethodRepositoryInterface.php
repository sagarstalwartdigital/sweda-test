<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ImprintMethodRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ImprintMethodRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ImprintMethodInterface $data
	* @return \Stalwart\Sweda\Api\Data\ImprintMethodInterface
	*/ 
	public function save(Data\ImprintMethodInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ImprintMethodInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ImprintMethodInterface $data
	 * @return bool
	 */
	public function delete(Data\ImprintMethodInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ImprintMethodSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}