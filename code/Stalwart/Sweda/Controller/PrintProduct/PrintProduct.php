<?php
namespace Stalwart\Sweda\Controller\PrintProduct;

class PrintProduct extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {    
        $this->_pageFactory = $pageFactory;
 
        parent::__construct($context);
    }

    /**
     * Confirm customer account by id and confirmation key
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        return $this->_pageFactory->create();
    }
}