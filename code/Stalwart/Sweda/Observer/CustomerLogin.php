<?php

namespace Stalwart\Sweda\Observer;

use Magento\Framework\Event\ObserverInterface;

class CustomerLogin implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $responseFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;
    private $customerSession;
    private $messageManager;

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerData = $_objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface')->getById($customer->getId()); 
        $chekIsBtob = $customerData->getCustomAttribute('is_btob')->getValue();
        if($chekIsBtob == 2){
            $this->customerSession->logout();
            $this->messageManager->addErrorMessage(__('You are Rejected.'));
            $redirectionUrl = $this->url->getUrl('customer/account/login/');
            $this->responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();

            return $this;
        }
    }
}