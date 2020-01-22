<?php

namespace Stalwart\Sweda\Controller\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;


class Overview extends \Magento\Framework\App\Action\Action
{


    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Session
     */
    protected $session;
   
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface
    )
    {
        $this->session = $customerSession;
        $this->_customerRegistry = $customerRegistry;
        $this->customerRepository = $customerRepository;
        $this->_encryptor = $encryptorInterface;
        $this->_messageManager = $messageManager;
        parent::__construct($context);
    }


    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $this->_view->loadLayout(); 
        $this->_view->renderLayout();  
            
    }

}
