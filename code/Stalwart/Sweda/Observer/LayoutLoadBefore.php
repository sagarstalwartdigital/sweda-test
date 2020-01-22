<?php
/**
* Copyright � 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Stalwart\Sweda\Observer;

use Magento\Framework\Event\ObserverInterface;
 
class LayoutLoadBefore implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $responseFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    protected $customerRepository;
    private $url;
    private $messageManager;

    public function __construct(
       \Magento\Framework\Registry $registry,
       \Magento\Framework\UrlInterface $url,
       \Magento\Framework\Message\ManagerInterface $messageManager,
       \Magento\Framework\App\ResponseFactory $responseFactory,
       \Magento\Customer\Api\CustomerRepositoryInterface $CustomerRepositoryInterface
    )
    {
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->messageManager = $messageManager;
        $this->_registry = $registry;
        $this->customerRepository = $CustomerRepositoryInterface;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->get('Magento\Customer\Model\Session');

        if($customerSession->isLoggedIn()) {
            $customer = $this->customerRepository->getById($customerSession->getCustomer()->getId());
            if($customer->getCustomAttribute('is_btob'))
            {
	            if ($customer->getCustomAttribute('is_btob')->getValue() == 0) {
	                $layout = $observer->getLayout();
	                $layout->getUpdate()->addHandle("customer_account_is_not_btob");
	            } elseif ($customer->getCustomAttribute('is_btob')->getValue() == 1) {
	                $layout = $observer->getLayout();
	                $layout->getUpdate()->addHandle("customer_account_is_btob");
	            }elseif ($customer->getCustomAttribute('is_btob')->getValue() == 2) {
                    $customerSession->logout();
                    $this->messageManager->addErrorMessage(__('You are Rejected.'));
    
                    $redirect = $objectManager->get('\Magento\Framework\App\Response\Http');
                    $redirect->setRedirect('/customer/account/login');
                    return $redirect;
                    // $this->responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
                } else {
                    $layout = $observer->getLayout();
                    $layout->getUpdate()->addHandle("customer_account_is_not_btob");
                }
	        }else{
	        	$layout = $observer->getLayout();
				$layout->getUpdate()->addHandle("customer_account_is_not_btob");
	        }
        }
        return $this;
    }
}