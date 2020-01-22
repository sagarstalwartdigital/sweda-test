<?php
 
namespace Stalwart\Sweda\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class OrderHistory extends Template
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
        \Stalwart\Sweda\Model\OrderFactory $orderfactory,
        \Stalwart\Sweda\Model\InvoiceFactory $invoicefactory,
         \Magento\Framework\UrlInterface $urlInterface,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->orderfactory = $orderfactory;
        $this->customerRepository = $customerRepository;
        $this->invoicefactory = $invoicefactory;
        $this->urlInterface = $context->getUrlBuilder();

        parent::__construct($context, $data);
    }
    public function getSwedaOrders() {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $limit = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 10;

        $sortingParams = $this->getSortingParams();
        
        $params = $this->getRequest()->getParams();

        $customerPoNum = (isset($params['customer_po_number'])) ? $params['customer_po_number'] : $this->customerSession->getCustomerPoNum();
        $this->customerSession->setCustomerPoNum($customerPoNum);
        
        $customerOrderNum = (isset($params['order_number'])) ? $params['order_number'] : $this->customerSession->getCustomerOrderNum();
        $this->customerSession->setCustomerOrderNum($customerOrderNum);

        $flowStatusCode = (isset($params['flow_status_code'])) ? $params['flow_status_code'] : $this->customerSession->getFlowStatusCode();
        $this->customerSession->setFlowStatusCode($flowStatusCode);

        $dateRange = (isset($params['startDate'])) ? $params['startDate'] : $this->customerSession->getDateRange();
        $this->customerSession->setDateRange($dateRange);

        $collection = $this->orderfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getCustomerId());
                        
        if (!empty($this->customerSession->getCustomerPoNum())) {
            $collection->addFieldToFilter('customer_po_number',$this->customerSession->getCustomerPoNum());
        }
        if (!empty($this->customerSession->getCustomerOrderNum())) {
            $collection->addFieldToFilter('order_number',$this->customerSession->getCustomerOrderNum());
        }
        if (!empty($this->customerSession->getFlowStatusCode())) {
            if ($flowStatusCode != 'ALL') {
                $collection->addFieldToFilter('flow_status_code',$this->customerSession->getFlowStatusCode());
            }
        }
        if (!empty($this->customerSession->getDateRange())) {
            $dateRangeArray = explode(" - ",$this->customerSession->getDateRange());
            $startdate = date('Y-m-d',strtotime($dateRangeArray['0']));
            $enddate = date('Y-m-d',strtotime($dateRangeArray['1']));
            $collection->addFieldToFilter('ordered_date',["from" => $startdate, "to" => $enddate]);
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
        $this->pageConfig->getTitle()->set(__('Order History'));
        
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
        $sortBy = ($this->getRequest()->getParam('sort-by'))? $this->getRequest()->getParam('sort-by') : 'order_number';
        if($sortBy)
        {
            $sortingAllowedFields = array("order_number","customer_po_number","flow_status_code","ordered_date","booked_date");
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
                return $this->getUrl("sweda/order/orderhistory",array("_query" => array("sort-by"=>$fieldName,"dir"=>$dir)));
        }
        return $this->getUrl("sweda/order/orderhistory");
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getInvoiceForTracking() {
        $collection = $this->invoicefactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getCustomerId())
                        ->addFieldToFilter('order_number',$this->getRequest()->getPost('order_num'));
        return $collection;
    }

    public function getOrdersForTracking() {
        $collection = $this->orderfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getCustomerId())
                        ->addFieldToFilter('order_number',$this->getRequest()->getPost('order_num'));
        return $collection;
    }

    public function redirectIfNotLoggedIn() {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->customerSession->authenticate();
        }
    }

    public function getIsLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }

    public function getIsBtobPending(){
        return $this->customerRepository->getById($this->customerSession->getCustomerId())->getCustomAttribute('is_btob')->getValue();
    }
}
