<?php

namespace Biztech\PrintingMethods\Controller\Index;

header("Access-Control-Allow-Origin: *");

class getPrintingMethodData extends \Magento\Framework\App\Action\Action {

    // Variable Declaration
    protected $_storeManager;
    protected $_printingMethodsFactory;
    protected $_colorsMethodFactrory;
    protected $_areasizeMethodFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Biztech\PrintingMethods\Model\Mysql4\Printingmethod\CollectionFactory $printingMethodsFactory,
        \Biztech\PrintingMethods\Model\ResourceModel\Colors\CollectionFactory $colorsMethodFactrory,
        \Biztech\PrintingMethods\Model\Mysql4\Areasize\CollectionFactory $areasizeMethodFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_printingMethodsFactory = $printingMethodsFactory;
        $this->_colorsMethodFactrory = $colorsMethodFactrory;
        $this->_areasizeMethodFactory = $areasizeMethodFactory;
        parent::__construct($context);
    }


    public function execute() {
        try {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $productId = $data['productId'];
            $storeid = $this->_storeManager->getStore()->getId();
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            //Result array
            $result =  array();
            $result['printingMethods'] = array();
            $printingmethodattr = $product->getResource()->getAttributeRawValue($product->getId(), 'printingmethodattr', $storeid);

            if (is_array($printingmethodattr)) {
                $printing_methods = $printingmethodattr;
            } else {
                $printing_methods = explode(",", $printingmethodattr);
            }
            $resultData =[];
            if ($product->getPrintingmethodattr()) {
                $collection = $this->_printingMethodsFactory->create();
                $resultData = $collection->addFieldToFilter('printing_id', ['in' => $printing_methods])
                                    ->addFieldToFilter('printingtype_ids', array('neq' => ""))
                                    ->addFieldToFilter('status', array('eq' => "1"))->getData();
                foreach ($resultData as $key => $value) {
                    $printingTypeIds = explode(",", $value['printingtype_ids']);
                    foreach ($printingTypeIds as $index => $printingTypeIdsValue) {
                        if($value['method_type'] == '1'){
                            $colorCounterCollection = $this->_colorsMethodFactrory->create();
                            $resultData[$key]['counterData'][] = $colorCounterCollection->addFieldToFilter('colors_id', ['eq' => $printingTypeIdsValue])->getFirstItem()->getData();
                        }
                        if($value['method_type'] == '2'){
                            $areasizeCollection = $this->_areasizeMethodFactory->create();
                            $resultData[$key]['counterData'][] = $areasizeCollection->addFieldToFilter('areasize_id', ['eq' => $printingTypeIdsValue])->getFirstItem()->getData();
                        }
                    }
                }
            }
            $result["printingMethods"] = $resultData;
            $this->getResponse()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Productdesigner.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $text = "getCartData: " . $e->getMessage();
            $logger->info($text);
            $this->getResponse()->setBody($e->getMessage());
        }
    }

}
