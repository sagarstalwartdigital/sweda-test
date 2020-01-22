<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Stalwart\Sweda\Api\Data;
use Stalwart\Sweda\Model\ResourceModel;
use Stalwart\Sweda\Model\ResourceModel\ImprintMethodCharge\Collection;
use Stalwart\Sweda\Api\ImprintMethodChargeRepositoryInterface;
use Stalwart\Sweda\Model\ResourceModel\ImprintMethodCharge\CollectionFactory as ImprintMethodChargeCollectionFactory;
use Stalwart\Sweda\Api\Data\ImprintMethodChargeSearchResultInterfaceFactory as SearchResultFactory;

class ImprintMethodChargeRepository implements ImprintMethodChargeRepositoryInterface
{
    protected $resourceImprintMethodCharge;
    protected $ImprintMethodChargeFactory;
    protected $ImprintMethodChargeCollectionFactory;
    protected $searchResultFactory;

    public function __construct(
        ResourceModel\ImprintMethodCharge $resourceImprintMethodCharge,
        Data\ImprintMethodChargeInterfaceFactory $ImprintMethodChargeFactory,
        SearchResultFactory $searchResultFactory,
        ImprintMethodChargeCollectionFactory $ImprintMethodChargeCollectionFactory)
    {
        $this->resourceImprintMethodCharge = $resourceImprintMethodCharge;
        $this->ImprintMethodChargeFactory = $ImprintMethodChargeFactory;
        $this->ImprintMethodChargeCollectionFactory = $ImprintMethodChargeCollectionFactory;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Data\ImprintMethodChargeInterface $data)
    {
        try {
            $this->resourceImprintMethodCharge->save($data);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $data = $this->ImprintMethodChargelineFactory->create();
        $this->resourceImprintMethodCharge->load($data, $id);
        if (!$data->getId()) {
            throw new NoSuchEntityException(__('Entity with id "%1" does not exist', $id));
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Data\ImprintMethodChargeInterface $data)
    {
        try {
            $this->resourceImprintMethodCharge->delete($data);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->ImprintMethodChargeCollectionFactory->create();

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