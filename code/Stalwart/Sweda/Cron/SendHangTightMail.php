<?php
namespace Stalwart\Sweda\Cron;

class SendHangTightMail
{
    protected $_request;
    protected $_layout;
    protected $_scopeConfig;
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_objectManager = null;
    protected $_customerGroup;
    protected $_customerFactory;
    private $logger;
    protected $_messageManager;

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
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){
        $this->_layout = $context->getLayout();
        $this->_request = $context->getRequest();
        $this->_scopeConfig = $scopeConfig;
        $this->_customerFactory = $customerFactory;
        $this->_objectManager = $objectManager;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->logger = $logger;
        $this->_messageManager = $messageManager;
    }

    public function execute()
    {
        $customerCollection = $this->_customerFactory->create()->getCollection()->addAttributeToSelect("*")->addAttributeToFilter("is_btob",0);

        if (!empty($customerCollection) && sizeof($customerCollection) > 0) {
            foreach ($customerCollection as $customerData) {
                try
                    {
                    // Send Mail
                    $this->_inlineTranslation->suspend();
                    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                     
                    $sender = [
                        'name' => $this->_scopeConfig->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                        'email' => $this->_scopeConfig->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                    ];
                     
                    $sentToEmail = $customerData->getEmail();
                     
                    $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('hangtight_email_sender')
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
                         
                } catch(\Exception $e){
                    $this->_messageManager->addError($e->getMessage());
                    exit;
                }   
            }
        }
    }
}