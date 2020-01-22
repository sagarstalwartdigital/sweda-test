<?php

namespace Biztech\PrintingMethods\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Serialize\Serializer\Serialize;

class afterAddToCartPrintingMethod implements ObserverInterface {

    protected $_priceHelper;
    protected $_storeManager;
    protected $directoryHelper;
    protected $_eavAttributeModel;
    protected $_printingMethodsFactory;
    protected $_objectManager;
    protected $_serialize;

    public function __construct(
    \Magento\Framework\App\Request\Http $request, \Magento\Framework\Pricing\Helper\Data $priceHelper, \Magento\Directory\Helper\Data $directoryHelper, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttributeModel, \Biztech\PrintingMethods\Model\Mysql4\Printingmethod\CollectionFactory $printingMethodsFactory, \Magento\Framework\ObjectManagerInterface $objectManager, Serialize $serialize
    ) {
        $this->_priceHelper = $priceHelper;
        $this->directoryHelper = $directoryHelper;
        $this->_storeManager = $storeManager;
        $this->_eavAttributeModel = $eavAttributeModel;
        $this->_printingMethodsFactory = $printingMethodsFactory;
        $this->_objectManager = $objectManager;
        $this->_serialize = $serialize;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $used_color_count = 0;
        if (isset($data['used_color_count'])) {
            $used_color_count = $data['used_color_count'];
        }
        if (isset($data['printingMethodId'])) {
            $printing_type_id = $data['printingMethodId'];
        }
        $item = $observer->getQuoteItem();

        if (isset($printing_type_id)) {
            $printing_data = $this->_printingMethodsFactory->create()->addFieldToFilter('printing_id', $printing_type_id)->getData();
            $method_name = $printing_data[0]['printing_name'];
            if ($additionalOption = $item->getOptionByCode('additional_options')) {
                $additionalOptions = json_decode($additionalOption->getValue(), TRUE);
            }
            $additional_price = 0;
            if (isset($data['additionalPrice'])) {
                $additional_price = $data['additionalPrice'];
            }
            $additionalOptions[] = array(
                'product_id' => $data['productId'],
                'code' => 'printing_method',
                'label' => 'Printing Method',
                'value' => $method_name,
                'custom_view' => false
            );
            $additionalOptions[] = array(
                'product_id' => $data['productId'],
                'code' => 'printing_surcharge',
                'label' => 'Printing Price',
                'value' => $this->_priceHelper->currency($additional_price, true, false),
                'custom_view' => false,
            );
            $item->addOption(
                    array(
                        'product_id' => $data['productId'],
                        'code' => 'additional_options',
                        'label' => 'Product Design',
                        'value' => $this->serializeData($additionalOptions)
                    )
            );
        }
    }

    protected function serializeData($value) {
        $magentoVersion = $this->_objectManager->get('\Magento\Framework\App\ProductMetadataInterface');
        $string = '';
        if (version_compare($magentoVersion->getVersion(), '2.2.0', '>=')) {
            $string = json_encode($value);
        } else {
            // $string = serialize($value);
            $string = $this->_serialize->serialize($value);
        }
        return $string;
    }

}
