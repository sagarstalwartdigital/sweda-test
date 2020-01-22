<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductTagRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductTagRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductTagInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductTagInterface
	*/ 
	public function save(Data\ProductTagInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductTagInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductTagInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductTagInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductTagSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}