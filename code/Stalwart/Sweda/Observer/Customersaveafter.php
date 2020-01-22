<?php
/**
* Copyright � 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Stalwart\Sweda\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class Customersaveafter implements ObserverInterface
{
    protected $_request;
    protected $_layout;
    protected $_scopeConfig;
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_objectManager = null;
    protected $_customerGroup;
    private $logger;
    protected $_messageManager;
    protected $_customerRepositoryInterface;

    /**
    * @param \Magento\Framework\ObjectManagerInterface $objectManager
    */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    ){
        $this->_layout = $context->getLayout();
        $this->_request = $context->getRequest();
        $this->_scopeConfig = $scopeConfig;
        $this->_objectManager = $objectManager;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->logger = $logger;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_messageManager = $messageManager;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }

    /**
    * @param \Magento\Framework\Event\Observer $observer
    * @return void
    */
    public function execute(EventObserver $observer)
    {
        $currentPath = $this->_request->getRouteName()."_".$this->_request->getControllerName()."_".$this->_request->getActionName();
        if($currentPath == "customer_account_forgotpasswordpost")
            return;

        $customer = $observer->getCustomerDataObject();
        
        if (!empty($customer->getCustomAttribute('is_btob')->getValue()) && $customer->getCustomAttribute('is_btob')->getValue() == 1) {
            try
            {
                // Send Mail
                $this->_inlineTranslation->suspend();
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                 
                $sender = [
                    'name' => $this->_scopeConfig->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                    'email' => $this->_scopeConfig->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                ];
                 
                $sentToEmail = $customer->getEmail();
                 
                $transport = $this->_transportBuilder
                ->setTemplateIdentifier('btob_email_sender')
                ->setTemplateOptions(
                    [
                        'area' => 'frontend',
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                    )
                    ->setTemplateVars([
                        'name'  => 'chirag',
                        'email'  => 'chirag@invigorate-systems.com'
                    ])
                    ->setFrom($sender)
                    ->addTo($sentToEmail)
                    ->getTransport();
                     
                    $transport->sendMessage();
                     
                    $this->_inlineTranslation->resume();
                    $this->_messageManager->addSuccess('Email sent successfully');
                     
            } catch(\Exception $e){
                $this->_messageManager->addError($e->getMessage());
                exit;
            }
        } else {
            
        }

        $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

    }
}