<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface OrderRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface OrderRepositoryInterface
{
    /**
     * Save a Order
     *
     * @param \Stalwart\Sweda\Api\Data\OrderInterface $order
     * @return \Stalwart\Sweda\Api\Data\OrderInterface
     */
    public function save(Data\OrderInterface $order);

    /**
     * Get Order by an OrderNumber
     *
     * @param int $orderNumber
     * @return \Stalwart\Sweda\Api\Data\OrderInterface
     */
    public function getById($orderNumber);

    /**
     * Delete a Order
     *
     * @param \Stalwart\Sweda\Api\Data\OrderInterface $order
     * @return bool
     */
    public function delete(Data\OrderInterface $order);

    /**
     * Delete a Order by an OrderNumber
     *
     * @param int $orderNumber
     * @return bool
     */
    public function deleteById($orderNumber);

    /**
     * @param Stalwart\Sweda\Api\Data\OrderSearchResultInterface
     * @return Magento\Framework\Api\SearchCriteriaInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}