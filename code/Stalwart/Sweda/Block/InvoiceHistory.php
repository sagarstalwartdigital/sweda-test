<?php
 
namespace Stalwart\Sweda\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class InvoiceHistory extends Template
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * ModalOverlay constructor.
     *
     * @param BlockRepositoryInterface $blockRepository
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        \Stalwart\Sweda\Model\InvoiceFactory $invoicefactory,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->invoicefactory = $invoicefactory;

        parent::__construct($context, $data);
    }

    public function getSwedaInvoices() {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $limit = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 10;

        $sortingParams = $this->getSortingParams();
        $params = $this->getRequest()->getParams();

        $customerInvoiceNum = (isset($params['invoice_number'])) ? $params['invoice_number'] : $this->customerSession->getCustomerInvoiceNum();
        $this->customerSession->setCustomerInvoiceNum($customerInvoiceNum);

        $customerPurchaseOrder = (isset($params['purchase_order'])) ? $params['purchase_order'] : $this->customerSession->getCustomerPurchaseOrder();
        $this->customerSession->setCustomerPurchaseOrder($customerPurchaseOrder);

        $customerOrderNumber = (isset($params['order_number'])) ? $params['order_number'] : $this->customerSession->getCustomerOrderNumber();
        $this->customerSession->setCustomerOrderNumber($customerOrderNumber);

        $dateRangeInvoice = (isset($params['startDate'])) ? $params['startDate'] : $this->customerSession->getDateRangeInvoice();
        $this->customerSession->setDateRangeInvoice($dateRangeInvoice);

        $collection = $this->invoicefactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getCustomerId());

        if (!empty($this->customerSession->getCustomerPurchaseOrder())) {
            $collection->addFieldToFilter('purchase_order',$this->customerSession->getCustomerPurchaseOrder());
        }
        if (!empty($this->customerSession->getCustomerInvoiceNum())) {
            $collection->addFieldToFilter('invoice_number',$this->customerSession->getCustomerInvoiceNum());
        }
        if (!empty($this->customerSession->getCustomerOrderNumber())) {
            $collection->addFieldToFilter('order_number',$this->customerSession->getCustomerOrderNumber());
        }
        if (!empty($this->customerSession->getDateRangeInvoice())) {
            $dateRangeArray = explode(" - ",$this->customerSession->getDateRangeInvoice());
            $startdate = date('Y-m-d',strtotime($dateRangeArray['0']));
            $enddate = date('Y-m-d',strtotime($dateRangeArray['1']));
            $collection->addFieldToFilter('trx_date',["from" => $startdate, "to" => $enddate]);
        }

        if(!empty($sortingParams))
            $collection->setOrder($sortingParams["sortby"],$sortingParams["dir"]);

        if ($limit != 'all') {
            $collection->setPageSize($limit);
            $collection->setCurPage($page);
        }
        return $collection;
    }

    public function getCustomer() {
        return $this->customerSession->getCustomer();
    }
    
    public function getCustomerName() {
        return $this->customerSession->getCustomer()->getName();
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Invoice History'));
        
        if ($this->getSwedaOrders()){
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'customer.order.pager')
                                    ->setTemplate('Stalwart_Sweda::html/pager.phtml')
                                    ->setAvailableLimit(array(10=>10,20=>20,50=>50,'all'=>'Show all'))
                                    ->setShowPerPage(true)
                                    ->setCollection($this->getSwedaOrders());

            $this->setChild('pager', $pager);

            $this->getSwedaOrders()->load();
        }
        return $this;
    }

    public function getSortingParams()
    {
        $doSort = false;
        $sortBy = ($this->getRequest()->getParam('sort-by'))? $this->getRequest()->getParam('sort-by') : 'invoice_number';
        if($sortBy)
        {
            $sortingAllowedFields = array("order_number","purchase_order","invoice_number","trx_date","total_amount","due_date");
            $dir = ($this->getRequest()->getParam('dir'))? strtoupper($this->getRequest()->getParam('dir')) : 'ASC';
            if(in_array($sortBy,$sortingAllowedFields))
            {
                if(!in_array($dir, array("ASC","DESC")))
                {
                    $dir = "ASC";
                }
                $doSort = true;
            }

        }
        if($doSort)
            return array("sortby"=>$sortBy, "dir"=>$dir);   

        return array();
    }
    public function getSortUrl($fieldName = "")
    {
        if($fieldName)
        {
            $sortingParams = $this->getSortingParams();
                $dir = $sortingParams["dir"];
                if($sortingParams["sortby"] == $fieldName)
                {
                    if($dir == "ASC")
                        $dir = "DESC";
                    else
                        $dir = "ASC";
                }
                return $this->getUrl("sweda/order/invoicehistory",array("_query" => array("sort-by"=>$fieldName,"dir"=>$dir)));
        }
        return $this->getUrl("sweda/order/invoicehistory");
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getIsBtobPending(){
        return $this->customerRepository->getById($this->customerSession->getCustomerId())->getCustomAttribute('is_btob')->getValue();
    }
    
}
