<?php

namespace Stalwart\Sweda\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderlineSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Stalwart\Sweda\Api\Data\OrderLineInterface[]
     */
    
    public function getItems();

    /**
     * @param \Stalwart\Sweda\Api\Data\OrderLineInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}