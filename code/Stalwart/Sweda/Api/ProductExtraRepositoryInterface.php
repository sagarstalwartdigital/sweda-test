<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface ProductExtraRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface ProductExtraRepositoryInterface
{
	/**
	*@param \Stalwart\Sweda\Api\Data\ProductExtraInterface $data
	* @return \Stalwart\Sweda\Api\Data\ProductExtraInterface
	*/ 
	public function save(Data\ProductExtraInterface $data);

	/**
	*@param int $id
	* @return \Stalwart\Sweda\Api\Data\ProductExtraInterface
	*/
	public function getById($id);

	/**
	 *@param \Stalwart\Sweda\Api\Data\ProductExtraInterface $data
	 * @return bool
	 */
	public function delete(Data\ProductExtraInterface $data);

	/**
	*@param int $id
	* @return bool
	*/
	public function deleteById($id);

	/**
	* @param Stalwart\Sweda\Api\Data\ProductExtraSearchResultInterface
	* @return Magento\Framework\Api\SearchCriteriaInterface
	*/
	public function getList(SearchCriteriaInterface $searchCriteria);
}