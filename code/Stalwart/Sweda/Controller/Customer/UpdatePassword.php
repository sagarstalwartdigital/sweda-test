<?php

namespace Stalwart\Sweda\Controller\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;


class UpdatePassword extends \Magento\Framework\App\Action\Action
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

        if ($this->getRequest()->getPostValue()) {
            $currPass = $this->getRequest()->getPost('current_password');
            $newPass = $this->getRequest()->getPost('password');
            $confPass = $this->getRequest()->getPost('password_confirmation');
            
            $currentCustomerDataObject = $this->customerRepository->getById($this->session->getCustomerId());

            $customerSecure = $this->_customerRegistry->retrieveSecureData($this->session->getCustomerId());
            $hash = $customerSecure->getPasswordHash();

            if (!$this->_encryptor->validateHash($currPass, $hash)) {

                $this->_messageManager->addError(__("Invalid Login or Password"));
                
            } else {
                if ($newPass != $confPass) {
                    $this->_messageManager->addError(__("Password confirmation doesn't match entered password."));
                } else {
                    $customerSecure->setRpToken(null);
                    $customerSecure->setRpTokenCreatedAt(null);
                    $customerSecure->setPasswordHash($this->_encryptor->getHash($newPass, true));

                    $this->customerRepository->save($currentCustomerDataObject);

                    $this->_messageManager->addSuccess(__("Your Password Udated Successfully."));
                }
                
            }

            
        }  
            
    }

}
