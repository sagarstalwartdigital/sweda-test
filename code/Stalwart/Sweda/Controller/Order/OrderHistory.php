<?php
namespace Stalwart\Sweda\Controller\Order;
class OrderHistory extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
   
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        if(isset($_GET["importorders"]) && !empty($_GET["importorders"]))
        {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $swedaHelper = $objectManager->get('\Stalwart\Sweda\Helper\Data');
            if($swedaHelper)
            {
                $ftpOrderFiles = $swedaHelper->GetOrderFilesFTP();
                $newFiles = $swedaHelper->GetNewOrderFiles($ftpOrderFiles);
                foreach($newFiles as $ftpFileName)
                {
                    $fileContent = $swedaHelper->GetOrderFileContent($ftpFileName);
                    $swedaHelper->InsertUpdateOrderfile($fileContent);
                }

                $ftpInvoiceFiles = $swedaHelper->GetInvoiceFilesFTP();
                $newFiles = $swedaHelper->GetNewInvoiceFiles($ftpInvoiceFiles);
                foreach($newFiles as $ftpFileName)
                {
                    $fileContent = $swedaHelper->GetInvoiceFileContent($ftpFileName);
                    $swedaHelper->InsertUpdateInvoicefile($fileContent);
                }
                exit;
            }
        }

        
        $this->_view->loadLayout(); 
        $this->_view->renderLayout();

    }
}