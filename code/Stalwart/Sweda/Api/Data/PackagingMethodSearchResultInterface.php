<?php

namespace Stalwart\Sweda\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PackagingMethodSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Stalwart\Sweda\Api\Data\OrderInterface[]
     */
    
    public function getItems();

    /**
     * @param \Stalwart\Sweda\Api\Data\OrderInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}