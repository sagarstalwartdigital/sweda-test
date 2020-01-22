<?php

namespace Stalwart\HelpCenter\Controller\Index;
 
use Zend\Log\Filter\Timestamp;
 
class Post extends \Magento\Framework\App\Action\Action
{
     
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
    private $fileUploaderFactory;
    private $fileSystem;
    /**
    * @var \Magento\Framework\Escaper
    */
    protected $_escaper;
     
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\Escaper $escaper,
        array $data = []
         
        )
    {
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
     
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        $filesData = $this->getRequest()->getFiles();
        if ($filesData) {
             $uploader = $this->fileUploaderFactory->create(['fileId' => 'my_custom_file']);
             $uploader->setAllowedExtensions(['jpg', 'pdf', 'doc', 'png', 'zip']);
             $uploader->setAllowRenameFiles(true);
             $uploader->setAllowCreateFolders(true);
             $path = $this->fileSystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('test-doc');
             $result = $uploader->save($path);

             $upload_document = 'test-doc'.$uploader->getUploadedFilename();
             $file = $result['path']."/".$result['file'];
             $name = $result['name'];
        } else {
             $upload_document = '';
             $file = '';
             $name = '';
        }

        if (!$post) {
            $this->messageManager->addError('Data Not Found');
            $this->_redirect('artwork-upload');
            return;
        }

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);

        try
        {
            // Send Mail
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
             
            $sender = [
                'name' => $this->_scopeConfig ->getValue('artworksend/general/sender_name'),
                'email' => $this->_scopeConfig ->getValue('artworksend/general/sender_email'),
            ];
             
            $sentToEmail = $this->_escaper->escapeHtml($post['email_address']);
            $sentToName = $this->_escaper->escapeHtml($post['po_number']);
            
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('customform_general_customer_email_sender',$storeScope)
                ->setTemplateOptions(
                    [
                        'area' => 'frontend',
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                    )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($sender)
                ->addTo($sentToEmail,$sentToName)
                ->attachFile($file, $name)
                ->getTransport();
                 
                $transport->sendMessage();
                 
                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Email sent successfully');
                $this->_redirect('artwork-upload');
                 
        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
            //exit;
            $this->_redirect('artwork-upload');
        }
         
         
         
    }
}