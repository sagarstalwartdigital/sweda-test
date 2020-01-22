<?php
 
namespace Stalwart\Smartcart\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class SmartCartFrontend extends Template
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
        \Stalwart\Smartcart\Model\SmartcartFactory $smartcartfactory,
        CustomerRepositoryInterface $customerRepository,
        Context $context,
        \Magento\Framework\UrlInterface $urlInterface,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->smartcartfactory = $smartcartfactory;
        $this->urlInterface = $context->getUrlBuilder();

        parent::__construct($context, $data);
    }

    public function getSmartCartFront() {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
		$limit = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 10;
        $sortingParams = $this->getSortingParams();
        $smartCartSearchQuery = $this->getRequest()->getParam('smart-cart-search-query');

		$collection = $this->smartcartfactory->create()->getCollection();

        $collection->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId());

        if ($smartCartSearchQuery && !empty($smartCartSearchQuery)) {
            $collection->addFieldToFilter('title', array(
                array('like' => '%'.$smartCartSearchQuery.'%'),
                array('like' => '%'.$smartCartSearchQuery),
                array('like' => $smartCartSearchQuery.'%')
            ));
        }

        if(!empty($sortingParams))
            $collection->setOrder($sortingParams["sortby"],$sortingParams["dir"]);

		if ($limit != 'all') {
			$collection->setPageSize($limit);
			$collection->setCurPage($page);
		}
		return $collection;
    }

    protected function _prepareLayout(){
		parent::_prepareLayout();
		$this->pageConfig->getTitle()->set(__('Your Smart Carts'));
		
		if ($this->getSmartCartFront()){
			$pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'customer.smartcart.pager')
									->setTemplate('Stalwart_Smartcart::html/pager.phtml')
									->setAvailableLimit(array(10=>10,20=>20,50=>50,'all'=>'Show all'))
									->setShowPerPage(true)
									->setCollection($this->getSmartCartFront());

			$this->setChild('pager', $pager);

			$this->getSmartCartFront()->load();
		}
        return $this;
	}
	
	public function getSortingParams()
    {
        $doSort = false;
        $sortBy = ($this->getRequest()->getParam('sort-by'))? $this->getRequest()->getParam('sort-by') : 'title';
        if($sortBy)
        {
            $sortingAllowedFields = array("title","event_name","event_date","created_at");
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
                return $this->getUrl("smartcart/cartindex/smartcartfront",array("_query" => array("sort-by"=>$fieldName,"dir"=>$dir)));
        }
        return $this->getUrl("smartcart/cartindex/smartcartfront");
    }
    public function getPagerHtml()
	{
		return $this->getChildHtml('pager');
	}

    public function getIsLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }

    public function redirectIfNotLoggedIn()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->customerSession->authenticate();
        }
    }
    public function getIsBtobPending(){
        return $this->customerRepository->getById($this->customerSession->getCustomerId())->getCustomAttribute('is_btob')->getValue();
    }
}
