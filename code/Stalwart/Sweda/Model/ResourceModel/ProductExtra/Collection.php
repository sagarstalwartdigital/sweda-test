<?php

namespace Stalwart\Sweda\Model\ResourceModel\ProductExtra;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var integer
     */
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'stalwart_sweda_sweda_product_extras_collection';
    protected $_eventObject = 'sweda_product_extras';
    
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
	\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory, 
	\Psr\Log\LoggerInterface $logger, 
	\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, 
	\Magento\Framework\Event\ManagerInterface $eventManager, 
	\Magento\Store\Model\StoreManagerInterface $storeManager, 
	\Magento\Framework\DB\Adapter\AdapterInterface $connection = null, 
	\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Sweda\Model\ProductExtra', 'Stalwart\Sweda\Model\ResourceModel\ProductExtra');
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }
    
    /**
     * Update Data for given condition for collection
     *
     * @param int|string $limit
     * @param int|string $offset
     * @return array
     */
    public function setTableRecords($condition, $columnData)
    {
        return $this->getConnection()->update($this->getTable('custom_records'), $columnData, $where = $condition);
    }
}