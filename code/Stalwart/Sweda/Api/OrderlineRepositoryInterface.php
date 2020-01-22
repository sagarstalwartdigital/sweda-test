<?php

namespace Stalwart\Sweda\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface OrderlineRepositoryInterface
 * @package Stalwart\Sweda\Api
 */
interface OrderlineRepositoryInterface
{
    /**
     * Save a Order
     *
     * @param \Stalwart\Sweda\Api\Data\OrderlineInterface $orderline
     * @return \Stalwart\Sweda\Api\Data\OrderlineInterface
     */
    public function save(Data\OrderlineInterface $orderline);

    /**
     * Get Order by an OrderNumber
     *
     * @param int $orderlineId
     * @return \Stalwart\Sweda\Api\Data\OrderLineInterface
     */
    public function getById($orderlineId);

    /**
     * Delete a Order
     *
     * @param \Stalwart\Sweda\Api\Data\OrderInterface $order
     * @return bool
     */
    public function delete(Data\OrderlineInterface $orderline);

    /**
     * Delete a Order by an OrderNumber
     *
     * @param int $orderNumber
     * @return bool
     */
    public function deleteById($orderlineId);

    /**
     * @param Stalwart\Sweda\Api\Data\OrderlineSearchResultInterface
     * @return Magento\Framework\Api\SearchCriteriaInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}