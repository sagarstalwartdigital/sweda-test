<?php

namespace MGS\QuickView\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH_QUICKVIEW_ENABLED = 'mgs_quickview/general/enabled';

    protected $_customerSession;

    protected $customerRepository;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $CustomerRepositoryInterface,
        \Magento\Customer\Model\Session $customerSession
    ) 
    {
        $this->_customerSession = $customerSession;
        $this->customerRepository = $CustomerRepositoryInterface; 
        parent::__construct($context);   
    }


	public function aroundQuickViewHtml(
    \Magento\Catalog\Model\Product $product
    ) {
        $result = '';
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            if ($this->_customerSession->isLoggedIn()) {
                if ($this->isBtob() == 0) {
                    return $result . '<button data-title="'. __("Product Add To Smart Cart") .'" class="action mgs-quickview-notbtob grid" id=' . $product->getId() . ' title="' . __("Quick View") . '"><span class="ico-plus"></span></button>';
                } else {
                    $productUrl = $this->_urlBuilder->getUrl('mgs_quickview/catalog_product/view', array('id' => $product->getId()));
                    return $result . '<button data-title="'. __("Product Add To Smart Cart") .'" class="action mgs-quickview" data-quickview-url=' . $productUrl . ' title="' . __("Quick View") . '"><span class="ico-plus"></span></button>';
                }
            } else {
                return $result . '<button data-title="'. __("Product Add To Smart Cart") .'" class="action mgs-quickview-login customer-login-link" id=' . $product->getId() . ' title="' . __("Quick View") . '"><span class="ico-plus"></span></button>';
            }
        }
        return $result;
    }


    public function isBtob() {
        $customer = $this->customerRepository->getById($this->_customerSession->getId());
        return $customer->getCustomAttribute('is_btob')->getValue();
    }

}
?>