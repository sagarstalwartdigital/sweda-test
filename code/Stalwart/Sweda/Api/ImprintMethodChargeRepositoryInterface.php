<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ImprintMethodChargeRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ImprintMethodChargeRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ImprintMethodChargeInterface $data
	* @return \Stalwart\Sweda\Api\Data\ImprintMethodChargeInterface
	*/ 
	public function save(Data\ImprintMethodChargeInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ImprintMethodChargeInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ImprintMethodChargeInterface $data
	 * @return bool
	 */
	public function delete(Data\ImprintMethodChargeInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ImprintMethodChargeSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}