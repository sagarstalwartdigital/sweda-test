<?php

namespace Stalwart\Sweda\Model;


use Magento\Framework\Api\SearchCriteriaInterface;
use Stalwart\Sweda\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Stalwart\Sweda\Model\ResourceModel\Order\Collection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Stalwart\Sweda\Api\Data;
use Stalwart\Sweda\Api\Magento;
use Stalwart\Sweda\Api\OrderRepositoryInterface;
use Stalwart\Sweda\Model\ResourceModel;
use Stalwart\Sweda\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;


class OrderRepository implements OrderRepositoryInterface
{
    protected $resourceOrder;
    protected $orderFactory;
    protected $orderCollectionFactory;
    protected $searchResultFactory;

    public function __construct(
        ResourceModel\Order $resourceOrder,
        Data\OrderInterfaceFactory $orderFactory,
        SearchResultFactory $searchResultFactory,
        OrderCollectionFactory $orderCollectionFactory)
    {
        $this->resourceOrder = $resourceOrder;
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Data\OrderInterface $order)
    {
        try {
            $this->resourceOrder->save($order);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($orderNumber)
    {
        $order = $this->orderFactory->create();
        $this->resourceOrder->load($order, $orderNumber);
        if (!$order->getId()) {
            throw new NoSuchEntityException(__('Order with id "%1" does not exist', $orderNumber));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Data\OrderInterface $order)
    {
        try {
            $this->resourceOrder->delete($order);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($orderNumber)
    {
        return $this->delete($this->getById($orderNumber));
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->orderCollectionFactory->create();

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