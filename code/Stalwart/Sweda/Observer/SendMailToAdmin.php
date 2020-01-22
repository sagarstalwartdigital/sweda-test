<?php
/**
* Copyright � 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Stalwart\Sweda\Observer;

use Magento\Framework\Event\ObserverInterface;
 
class SendMailToAdmin implements ObserverInterface
{
 
    const XML_PATH_EMAIL_RECIPIENT = 'trans_email/ident_general/email';
    protected $_transportBuilder;
    protected $inlineTranslation;
    protected $scopeConfig;
    protected $storeManager;
    protected $_escaper;
    protected $_messageManager;
    
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_request = $request;
        $this->_escaper = $escaper;
        $this->_messageManager = $messageManager;
    }
 
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        
        $customer = $observer->getData('customer');

        $postData = $this->_request->getPost();

        $post = array();

        if (isset($postData['firstname']) && !empty($postData['firstname'])) {
            $post['firstname'] = $postData['firstname'];
        } else {
            $post['firstname'] = '';
        }

        if (isset($postData['lastname']) && !empty($postData['lastname'])) {
            $post['lastname'] = $postData['lastname'];
        } else {
            $post['lastname'] = '';
        }

        if (isset($postData['company_name']) && !empty($postData['company_name'])) {
            $post['company_name'] = $postData['company_name'];
        } else {
            $post['company_name'] = '';
        }

        if (isset($postData['asi_number']) && !empty($postData['asi_number'])) {
            $post['asi_number'] = $postData['asi_number'];
        } else {
            $post['asi_number'] = '';
        }

        if (isset($postData['tax_id_number']) && !empty($postData['tax_id_number'])) {
            $post['tax_id_number'] = $postData['tax_id_number'];
        } else {
            $post['tax_id_number'] = '';
        }

        if (isset($postData['federal_classification']) && !empty($postData['federal_classification'])) {
            $post['federal_classification'] = $postData['federal_classification'];
        } else {
            $post['federal_classification'] = '';
        }

        if (isset($postData['contact_email']) && !empty($postData['contact_email'])) {
            $post['contact_email'] = $postData['contact_email']; 
        } else {
            $post['contact_email'] = ''; 
        }

        if (isset($postData['telephone']) && !empty($postData['telephone'])) {
            $post['telephone'] = $postData['telephone']; 
        } else {
            $post['telephone'] = ''; 
        }

        if (isset($postData['street']) && !empty($postData['street'])) {
            $post['street'] = implode(', ', $postData['street']);
        } else {
            $post['street'] = '';
        }

        if (isset($postData['city']) && !empty($postData['city'])) {
            $post['city'] = $postData['city'];
        } else {
            $post['city'] = '';
        }

        if (isset($postData['region_id']) && !empty($postData['region_id'])) {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $region = $objectManager->create('Magento\Directory\Model\Region')
                                    ->load($postData['region_id']);
            $post['region'] = $region->getName();
        } else {
            $post['region'] = '';
        }

        if (isset($postData['postcode']) && !empty($postData['postcode'])) {
            $post['postcode'] = $postData['postcode']; 
        } else {
            $post['postcode'] = ''; 
        }

        if (isset($postData['ap_contact']) && !empty($postData['ap_contact'])) {
            $post['ap_contact'] = $postData['ap_contact'];
        } else {
            $post['ap_contact'] = '';
        }

        if (isset($postData['ap_email_address']) && !empty($postData['ap_email_address'])) {
            $post['ap_email_address'] = $postData['ap_email_address'];
        } else {
            $post['ap_email_address'] = '';
        }

        if (isset($postData['email']) && !empty($postData['email'])) {
            $post['email'] = $postData['email'];
        } else {
            $post['email'] = '';
        }

        if (isset($postData['password']) && !empty($postData['password'])) {
            $post['password'] = $postData['password'];
        } else {
            $post['password'] = '';
        }
        
        $this->inlineTranslation->suspend();
        
        try 
        {
            
            $sender = [
                'name' => $this->scopeConfig->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'email' => $this->scopeConfig->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ];

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
            $transport = 
                $this->_transportBuilder
                ->setTemplateIdentifier('send_to_admin') 
                ->setTemplateOptions(
                    ['area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($sender)
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->getTransport();
            $transport->sendMessage();

            $this->inlineTranslation->resume();
        } 
        catch (\Exception $e) 
        {
            $this->_messageManager->addError($e->getMessage());
        }
    
    }
 
}