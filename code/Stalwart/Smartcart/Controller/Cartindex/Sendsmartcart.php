<?php
namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Stalwart\Smartcart\Model\SmartcartFactory;
use Zend\Log\Filter\Timestamp;

class Sendsmartcart extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Session
     */
    protected $urlFactory;

    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
    private $fileUploaderFactory;
    private $fileSystem;
 
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context, 
        Session $customerSession, 
        UrlInterface $urlFactory, 
        PageFactory $resultPageFactory, 
        SmartcartFactory $smartcartFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\Escaper $escaper
    )
    {
        $this->urlFactory = $urlFactory;
        $this->customerSession = $customerSession;
        $this->_smartcartFactory = $smartcartFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->_escaper = $escaper;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
 
        parent::__construct($context);
    }


    /**
     * Confirm customer account by id and confirmation key
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $isAjax = $this->getRequest()->getParam('isajax', false);
        
        $jsonResult = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $smartCartId = $this->getRequest()->getParam('smartcartid','0');
        
        if($isAjax)
        {

            if ($this->customerSession->isLoggedIn()) {
                $template = 'Stalwart_Smartcart::send_smart_cart_modal.phtml';
                $popupHtml = $resultPage->getLayout()
                                ->createBlock('Stalwart\Smartcart\Block\SmartCartSend')
                                ->setTemplate($template)
                                ->setSmartCartID($smartCartId)
                                ->toHtml();
                $jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
            }else{
                $redirectReferer = $this->getRequest()->getParam('currenturl');
                if($redirectReferer)
                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
                else
                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login');
                $jsonResult->setData(['logged'  => false, "redirectUrl" => $redirectReferer]);
            }
            return $jsonResult;
            exit;
        }

        $isSendAndAdd = $this->getRequest()->getPost('add-and-send');
        $isMultiSend = $this->getRequest()->getPost('send-multi-smartcart');

        if (isset($isSendAndAdd) && !empty($isSendAndAdd) && $isSendAndAdd == 'addandsend' || isset($isMultiSend) && !empty($isMultiSend) && $isMultiSend == 'yes') {
            
            $post = $this->getRequest()->getPostValue();
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            
            $base_url = 'https://sweda.realmoxy.com/smartcart/cartindex/';
            $smart_cart_code = $this->getRequest()->getPost('add-and-send-smartcart-id');

            $collectionSmartCart = $this->_smartcartFactory->create()->getCollection()
                            ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId())
                            ->addFieldToFilter('id',$smart_cart_code)->getFirstItem();

            $customer_id = $this->customerSession->getCustomer()->getId();
            $post['subject'] = $this->customerSession->getCustomer()->getName().' has shared a '.$collectionSmartCart->getTitle().' with you!';
            $post['sendername'] = $this->customerSession->getCustomer()->getName();


            $post['sharedSmartCartUrl'] = $storeManager->getStore()->getBaseUrl().'smartcart/cartindex/sharedsmartcart/?share='.urlencode(base64_encode('id='.$smart_cart_code.'&name='.$this->customerSession->getCustomer()->getName()));

            if (isset($post['link-remove']) && !empty($post['link-remove']) && $post['link-remove'] == 'Remove') {
                $this->editSmartCart();
                $this->messageManager->addSuccess('Recipients Removed successfully');
                $this->_redirect($this->_redirect->getRefererUrl());
            } else {
                if ($isMultiSend != 'yes') {

                    $this->editSmartCart();

                    foreach ($post['recipientemail'] as $resiEmail) {
                        $mail_id = $resiEmail;
                    }

                    $post['trackurl'] = $base_url.'emailtrack?totrack='.urlencode(base64_encode('mailid='.$mail_id.'&smart_cart_id='.$smart_cart_code.'&cus_id='.$customer_id));

                    $postObject = new \Magento\Framework\DataObject();
                    $postObject->setData($post);

                    try
                    {
                        $this->_inlineTranslation->suspend();
                        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                         
                        $sender = [
                            'name' => $this->_scopeConfig ->getValue('trans_email/ident_general/name'),
                            'email' => $this->_scopeConfig ->getValue('trans_email/ident_general/email'),
                        ];
                         
                        $sentToEmail = $this->_escaper->escapeHtml($post['recipientemail']);
                        
                        // $sentToName = $this->_escaper->escapeHtml($post['recipientname']);
                        
                        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                        $transport = $this->_transportBuilder
                            ->setTemplateIdentifier('smart_cart_email_sender',$storeScope)
                            ->setTemplateOptions(
                                [
                                    'area' => 'frontend',
                                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                                ]
                                )
                            ->setTemplateVars(['subject' => $post['subject'], 'smartcart_id' => $smart_cart_code, 'sender_name' => $this->customerSession->getCustomer()->getName()])
                            ->setFrom($sender)
                            ->addTo($sentToEmail)
                            ->getTransport();

                            $transport->sendMessage();
                            $this->_inlineTranslation->resume();
                            $this->messageManager->addSuccess('Email sent successfully');
                            $this->_redirect($this->_redirect->getRefererUrl());
                             
                    } catch(\Exception $e){
                        $this->messageManager->addError($e->getMessage());
                        $this->_logLoggerInterface->debug($e->getMessage());
                        //exit;
                        $this->_redirect($this->_redirect->getRefererUrl());
                    }

                } else {
                    if (isset($post['recipientemail']) && !empty($post['recipientemail']) || !isset($post['link-remove'])) {
                        foreach ($post['recipientemail'] as $reciEmails) {
                            
                            $post['trackurl'] = $base_url.'emailtrack?totrack='.urlencode(base64_encode('mailid='.$reciEmails.'&smart_cart_id='.$smart_cart_code.'&cus_id='.$customer_id));

                            $postObject = new \Magento\Framework\DataObject();
                            $postObject->setData($post);
                            $post['smartcart-id'] = $smart_cart_code;
                            try
                            {
                                $this->_inlineTranslation->suspend();
                                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                                 
                                $sender = [
                                    'name' => $this->_scopeConfig ->getValue('trans_email/ident_general/name'),
                                    'email' => $this->_scopeConfig ->getValue('trans_email/ident_general/email'),
                                ];
                                 
                               
                                $sentToEmail = $this->_escaper->escapeHtml($reciEmails);
                                
                                // $sentToName = $this->_escaper->escapeHtml($post['recipientname']);
                                
                                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                                $transport = $this->_transportBuilder
                                    ->setTemplateIdentifier('smart_cart_email_sender',$storeScope)
                                    ->setTemplateOptions(
                                        [
                                            'area' => 'frontend',
                                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                                        ]
                                        )
                                    ->setTemplateVars(['subject' => $post['subject'], 'smartcart_id' => $smart_cart_code, 'sender_name' => $this->customerSession->getCustomer()->getName()])
                                    ->setFrom($sender)
                                    ->addTo($sentToEmail)
                                    ->getTransport();

                                    $transport->sendMessage();
                                    $this->_inlineTranslation->resume();$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                                    $this->messageManager->addSuccess('Email sent successfully');
                                    $this->_redirect($this->_redirect->getRefererUrl());
                                     
                            } catch(\Exception $e){
                                $this->messageManager->addError($e->getMessage());
                                $this->_logLoggerInterface->debug($e->getMessage());
                                //exit;
                                $this->_redirect($this->_redirect->getRefererUrl());
                            }
                        }
                    }
                }
            }
        }
    }

    public function editSmartCart() {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $postedData = $this->getRequest()->getPostValue();

        $smartcartid = $this->getRequest()->getPost('add-and-send-smartcart-id');

        if ($smartcartid && !empty($smartcartid)) {
            
            $saveData = array();

            $collection = $this->_smartcartFactory->create()->getCollection()
                            ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId())
                            ->addFieldToFilter('id',$smartcartid);

            if (count($collection->getData())) {

                $smartcartcollection = $collection->getFirstItem();

                $saveData['id'] = $smartcartcollection->getId();

                $recipientData = array();
                if (isset($postedData['link-remove']) && !empty($postedData['link-remove']) && $postedData['link-remove'] == 'Remove') {

                    if (json_decode($smartcartcollection->getRecepientData(), true) && !empty(json_decode($smartcartcollection->getRecepientData(), true))) {
                        $recipientData = json_decode($smartcartcollection->getRecepientData(), true);
                        foreach ($postedData['recipientemail'] as $emailToUnset) {
                            if (isset($recipientData[$emailToUnset])) {
                                unset($recipientData[$emailToUnset]);
                            }
                        }
                    }
                } else {
                    if((isset($postedData["recipientname"]) && !empty($postedData["recipientname"])) && (isset($postedData["recipientemail"]) && !empty($postedData["recipientemail"]))) {
                        if(count($postedData["recipientname"]) == count($postedData["recipientemail"]))
                        {
                            foreach($postedData["recipientemail"] as $key => $value)
                            {
                                if(!empty($value) && (isset($postedData["recipientname"][$key]) && !empty($postedData["recipientname"][$key])))
                                    $recipientData[$value]['name'] = $postedData["recipientname"][$key];
                            }

                        }else{
                           
                        }
                    }
                    if (json_decode($smartcartcollection->getRecepientData(), true) && !empty(json_decode($smartcartcollection->getRecepientData(), true))) {
                        foreach (json_decode($smartcartcollection->getRecepientData(), true) as $customerSmartCartsRecipientEmail => $customerSmartCartsRecipientDetail) {
                            $recipientData[$customerSmartCartsRecipientEmail] = $customerSmartCartsRecipientDetail;
                        }
                    } 
                }
                $saveData["recepient_data"] = json_encode($recipientData);
                
                $smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($smartcartcollection->getId());
                $smartCartObject->setData($saveData);
                $smartCartObject->save();
            } else {
                
            }
        }
    }
}
