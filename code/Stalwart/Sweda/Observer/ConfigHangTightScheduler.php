<?php
/**
* Copyright � 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Stalwart\Sweda\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Psr\Log\LoggerInterface as Logger;

class ConfigHangTightScheduler implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected $_configValueFactory;

    protected $_scopeConfig;

    /**
     * Cron string path
     */
    const CRON_STRING_PATH = 'hangtightmail/hangtightschedule/cron_schedule_path';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_configValueFactory = $configValueFactory;
        $this->_scopeConfig = $config;
        $this->logger = $logger;
        
    }

    public function execute(EventObserver $observer)
    {

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;

        $cronExprArray = [
            $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_minut", $storeScope) ? $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_minut", $storeScope) : '*',
            $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_hour", $storeScope) ? $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_hour", $storeScope) : '*',
            $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_day_of_month", $storeScope) ? $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_day_of_month", $storeScope) : '*',
            $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_month", $storeScope) ? $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_month", $storeScope) : '*',
            $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_day_of_week", $storeScope) ? $this->_scopeConfig->getValue("hangtightmail/hangtightschedule/run_every_day_of_week", $storeScope) : '*'
        ];

        $cronExprString = join(' ', $cronExprArray);

        try {
            $this->_configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();
        } catch (\Exception $e) {
            echo $e->getMessage();
            throw new \Exception(__('We can\'t save the cron expression.'));
        }
    }
}
