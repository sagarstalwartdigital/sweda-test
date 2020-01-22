<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Stalwart\Sweda\Model\ResourceModel\Orderline\CollectionFactory as OrderlineCollectionFactory;
use Stalwart\Sweda\Model\ResourceModel\Orderline\Collection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Stalwart\Sweda\Api\Data;
use Stalwart\Sweda\Api\Magento;
use Stalwart\Sweda\Api\OrderlineRepositoryInterface;
use Stalwart\Sweda\Model\ResourceModel;
use Stalwart\Sweda\Api\Data\OrderlineSearchResultInterfaceFactory as SearchResultFactory;

class OrderlineRepository implements OrderlineRepositoryInterface
{
    protected $resourceOrderline;
    protected $orderlineFactory;
    protected $orderlineCollectionFactory;
    protected $searchResultFactory;

    public function __construct(
        ResourceModel\Orderline $resourceOrderline,
        Data\OrderlineInterfaceFactory $orderlineFactory,
        SearchResultFactory $searchResultFactory,
        OrderlineCollectionFactory $orderlineCollectionFactory)
    {
        $this->resourceOrderline = $resourceOrderline;
        $this->orderlineFactory = $orderlineFactory;
        $this->orderlineCollectionFactory = $orderlineCollectionFactory;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Data\OrderlineInterface $orderline)
    {
        try {
            $this->resourceOrderline->save($order);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($orderlineId)
    {
        $orderline = $this->orderlinelineFactory->create();
        $this->resourceOrderline->load($orderline, $orderlineId);
        if (!$orderline->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist', $orderlineId));
        }
        return $orderline;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Data\OrderlineInterface $orderline)
    {
        try {
            $this->resourceOrderline->delete($orderline);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $orderline;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderlineId)
    {
        return $this->delete($this->getById($orderlineId));
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->orderlineCollectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}