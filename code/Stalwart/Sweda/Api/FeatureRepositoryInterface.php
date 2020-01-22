<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface FeatureRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface FeatureRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\FeatureInterface $data
	* @return \Stalwart\Sweda\Api\Data\FeatureInterface
	*/ 
	public function save(Data\FeatureInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\FeatureInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\FeatureInterface $data
	 * @return bool
	 */
	public function delete(Data\FeatureInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\FeatureSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}