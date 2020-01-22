<?php

namespace Biztech\Productdesigner\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Serialize\Serializer\Serialize;

class catalogProductLoadAfter implements ObserverInterface {

    protected $_request;
    protected $_eavAttributeModel;
    protected $designFactory;
    protected $attributeModel;
    protected $priceModel;
    protected $dataInterface;
    protected $_serialize;

    public function __construct(
    \Magento\Framework\App\Request\Http $request, \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttributeModel, \Biztech\Productdesigner\Model\DesignsFactory $designFactory, \Magento\Eav\Model\Entity\AttributeFactory $attributeModel, \Magento\Catalog\Model\Product\Type\Price $priceModel, \Magento\Framework\App\ProductMetadataInterface $dataInterface, Serialize $serialize
    ) {
        $this->_request = $request;
        $this->_eavAttributeModel = $eavAttributeModel;
        $this->designFactory = $designFactory;
        $this->attributeModel = $attributeModel;
        $this->priceModel = $priceModel;
        $this->dataInterface = $dataInterface;
        $this->_serialize = $serialize;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        $action = $this->_request->getFullActionName();
        if ($action == 'productdesigner_Cart_Save') {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            if (isset($data['customOptionFile'])) {
                foreach ($data['customOptionFile'] as $customOptionFile) {
                    $optionId = $customOptionFile['optionId'];
                    $fileName = $customOptionFile['fileName'];
                    $filePath = $customOptionFile['filePath'];
                    $optiontitle = $customOptionFile['optiontitle'];
                    $additionalOptions[] = array(
                        'code' => 'file-' . $optionId,
                        'label' => $optiontitle,
                        'value' => '<a href="' . $filePath . '" target="_blank">' . $fileName . '</a>',
                    );
                }
            }
            if (isset($data)) {
                $post = $this->_request->getPostValue();
                $designId = $post['designId'][0];
                $additionalOptions[] = array(
                    'code' => 'product_design',
                    'label' => __('Product Design'),
                    'design_id' => $designId,
                    'value' => __('Yes'),
                    'custom_view' => false,
                );
            }
            $optionData = array(
                'product_id' => $data['productId'],
                'code' => 'additional_options',
                'label' => 'Product Design',
                'value' => $this->serializeData($additionalOptions),
            );
            $designObj = $this->designFactory->create()->load($designId);

            // calc price
            $item = $observer->getQuoteItem();
            $item->addOption($optionData);
            $item = $observer->getEvent()->getData('quote_item');
            $product = $observer->getEvent()->getData('product');
            $prices = json_decode(base64_decode($designObj->getPrices()), true);
            $prices = $prices['objPrices'];

            $itemProId = $item->getProduct()->getId();
            if ($item->getProduct()->getTypeId() == 'configurable') {
                $attrs = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

                $configurable_attributes = array();
                foreach ($attrs as $attr) {
                    $configurable_attributes[] = $attr['attribute_code'];
                }

                $attrLen = count($configurable_attributes);
                $params = $item->getProduct()->getCustomOptions();
                $productTypeInstance = $product->getTypeInstance();
                $simpleproduct = '';
                $simpleCollection = $productTypeInstance->getUsedProductCollection($product)
                        ->addAttributeToSelect('*');

                for ($i = 0; $i < $attrLen; $i++) {
                    $designData = $params['attributes']->getData();
                    $designdata1 = $this->unserializeData($designData['value']);
                    $attrid = $this->attributeModel->create()->loadByCode('catalog_product', $configurable_attributes[$i])->getAttributeId();
                    $attr = $designdata1[$attrid];
                    $simpleCollection->addAttributeToFilter($configurable_attributes[$i], $attr);
                }

                foreach ($simpleCollection as $simple) {
                    $simpleproduct = $simple;
                    break;
                }
                $base_price = $this->priceModel->getBasePrice($simpleproduct, $item->getQty());
            } else {
                $base_price = $this->priceModel->getBasePrice($item->getProduct(), $item->getQty());
            }

            if (isset($data['additionalPrice']) && $data['additionalPrice'] > 0) {
                $prices += $data['additionalPrice'];
            }

            if (isset($data['customOptionPrice'])) {
                $prices += $data['customOptionPrice'];
            }
            $custom_price = $base_price + $prices;
            $custom_price = ($custom_price < 0) ? 0 : $custom_price;
            $item->setCustomPrice($custom_price);
            $item->setOriginalCustomPrice($custom_price);
            $item->getProduct()->setIsSuperMode(true);
        } elseif ($action == 'sales_order_reorder') {
            $item = $observer->getEvent()->getQuoteItem();
            $buyInfo = $item->getBuyRequest()->getData('options');

            if (isset($buyInfo['design']) && $buyInfo['design']) {
                $product_id = $item->getBuyRequest()->getData('product_id');
                $item = $observer->getEvent()->getData('quote_item');
                $product = $observer->getEvent()->getData('product');
                $additionalOptions = array();
                $additionalOptions[] = array(
                    'code' => 'product_design',
                    'label' => __('Product Design'),
                    'design_id' => $buyInfo['design'],
                    'value' => __('Yes'),
                    'custom_view' => false,
                );
                $item->addOption(
                        array(
                            'product_id' => $product_id,
                            'code' => 'additional_options',
                            'label' => 'Product Design',
                            'value' => $this->serializeData($additionalOptions),
                        )
                );
            }
        }
    }

    protected function serializeData($value) {
        $string = '';
        if (version_compare($this->dataInterface->getVersion(), '2.2.0', '>=')) {
            $string = json_encode($value);
        } else {
            // $string = serialize($value);
            $string = $this->_serialize->serialize($value);
        }
        return $string;
    }

    public function unserializeData($value) {
        $string = '';
        if (version_compare($this->dataInterface->getVersion(), '2.2.0', '>=')) {
            $string = json_decode($value, true);
        } else {
            $string = (isset($value) && $value) ? $this->_serialize->unserialize($value) : '';
            // $string = unserialize($value);
        }
        return $string;
    }

}
