<?php
namespace Biztech\Productdesigner\Controller\Adminhtml\Productdesigner;

class generateSvg extends \Magento\Backend\App\Action {

    protected $orderItemRepository;
    protected $layoutFactory;
    protected $_storeManager;
    protected $_infoHelper;
    protected $_pdHelper;
    protected $scopeConfig;

    public function __construct(
        \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Biztech\Productdesigner\Helper\Info $infoHelper,
        \Biztech\Productdesigner\Helper\Data $dataHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->layoutFactory = $layoutFactory;
        $this->_storeManager = $storeManager;
        $this->_infoHelper = $infoHelper;
        $this->_pdHelper = $dataHelper;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function execute() {
        try {
            $productParams = $this->getRequest()->getParams();
            /* No need to getItem Id to genrate Svg Image  On 25-09-2019: PS */
           /* Previous code
        $itemId = $productParams['item_id'];
        $itemCollection = $this->orderItemRepository->get($itemId);
        $itemCollectionData = $itemCollection->getData();
            */
        $layout = $this->layoutFactory->create()->createBlock('Biztech\Productdesigner\Block\generateSvg');
        $store = $this->_storeManager->getStore();
        $storeId = $store->getId();
        $currencyCode = $store->getCurrentCurrencyCode();
        $baseUrl = $store->getBaseUrl();
        $isEnable = $this->_infoHelper->isEnable($storeId);
        $isPdEnable = $this->_infoHelper->isPdEnable($productParams['id'],$storeId);
        $pageTitle= $this->_pdHelper->getConfig('productdesigner/general/page_title', $storeId);
        $folderName = \Magento\Config\Model\Config\Backend\Image\Favicon::UPLOAD_DIR;
        $scopeConfig = $this->scopeConfig->getValue(
            'design/head/shortcut_icon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $scopeConfig;
        $pageLogo = $this->_storeManager->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
        $productParams['store_id'] = base64_encode($storeId);
        $productParams['currency_code'] = base64_encode($currencyCode);
        $productParams['mage_base_url'] = base64_encode($baseUrl);
        $productParams['isEnable'] = $isEnable;
        $productParams['isPdEnable'] = $isPdEnable;
        $result = $layout->setData(array("product_params" => $productParams,"page_title" => $pageTitle,"page_logo" => $pageLogo))->setTemplate('Biztech_Productdesigner::generateSvg/generateSvg.phtml')->toHtml();
        $this->getResponse()->setBody($result);
    } catch (\Exception $e) {
        $response = $this->_infoHelper->throwException($e, self::class);
        $this->getResponse()->setBody(json_encode($response));
    }
}

}
