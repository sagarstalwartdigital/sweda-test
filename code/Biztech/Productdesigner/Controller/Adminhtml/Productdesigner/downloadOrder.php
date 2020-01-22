<?php

/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\Productdesigner\Controller\Adminhtml\Productdesigner;

use \Spipu\Html2Pdf\Html2Pdf;

header("Access-Control-Allow-Origin: *");

class downloadOrder extends \Magento\Backend\App\Action {

    protected $_order;
    protected $_fileSystem;
    protected $designOrderFactory;
    protected $_mediaGallery;
    protected $_side;
    protected $_designimagesFactory;
    protected $_infoHelper;
    protected $_orderHelper;
    protected $_helper;
    protected $_storeManager;
    protected $_productLoader;
    protected $_designOrdersFactory;
    protected $_designsFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\Filesystem $fileSystem,
        \Biztech\Productdesigner\Model\Mysql4\DesignOrders\CollectionFactory $designOrderFactory,
        \Biztech\Productdesigner\Model\ResourceModel\MediaGallery\CollectionFactory $mediaGallery,
        \Biztech\Productdesigner\Model\Side $side,
        \Biztech\Productdesigner\Model\Mysql4\Designimages\CollectionFactory $designimagesFactory,
        \Biztech\Productdesigner\Helper\Info $infoHelper,
        \Biztech\Productdesigner\Helper\Order $orderHelper,
        \Biztech\Productdesigner\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Catalog\Model\ProductFactory $_productLoade,
        \Biztech\Productdesigner\Model\Mysql4\DesignOrders\CollectionFactory $designOrdersFactory,
        \Biztech\Productdesigner\Model\DesignsFactory $designsFactory
    ) {
        parent::__construct($context);
        $this->_order = $order;
        $this->_fileSystem = $fileSystem;
        $this->designOrderFactory = $designOrderFactory;
        $this->_mediaGallery = $mediaGallery;
        $this->_side = $side;
        $this->_designimagesFactory = $designimagesFactory;
        $this->_infoHelper = $infoHelper;
        $this->_orderHelper = $orderHelper;
        $this->_helper = $helper;
        $this->_productLoader = $_productLoade;
        $this->_designOrdersFactory = $designOrdersFactory; 
        $this->_designsFactory = $designsFactory;
        $this->_storeManager = $storeManager;
    }

    public function execute() {
        $params = $this->getRequest()->getParams();
        $order_id = $params['order_id'];

        $storeid = $this->_storeManager->getStore()->getId();
        $checkIsIntegration = $this->_helper->getConfig("integration/general/code", $storeid);

        if($checkIsIntegration){
            $designIds = array();
            $orders = $this->_designOrdersFactory->create()->addFieldToFilter("order_id",$order_id)->getData();
            foreach ($orders as $orderkey => $ordervalue) {
                if(!empty($ordervalue['design_id'])){
                   array_push($designIds, $ordervalue['design_id']);
                }
            }                

            $order_increment_id = $order_id;
            $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $order_dir = $reader->getAbsolutePath() . 'productdesigner/order';
            if (!file_exists($order_dir)) {
                mkdir($order_dir, 0777, true);
            }
            $zip_dir = $reader->getAbsolutePath() . 'productdesigner/order/' . $order_increment_id;
            if (!file_exists($zip_dir)) {
                mkdir($zip_dir, 0777, true);
            }

            $this->_orderHelper->generateApiCSV($zip_dir, null, $order_id, null, 'false');
            foreach ($orders as $item) {

                $designData = $this->_designsFactory->create()->load($item['design_id'])->getData();
                $product = $this->_productLoader->create()->load($designData['associated_product_id']);

                $itemID = !empty($item['design_id']) ? $item['design_id'] : "";
                $sku = $product->getSku();
                $item_path = $reader->getAbsolutePath() . 'productdesigner/order/' . $order_increment_id . '/' . $itemID;
                $zip_path = array();

                    if (!file_exists($item_path)) {
                        mkdir($item_path, 0777, true);
                    }
                    if (isset($item['design_id'])) {
                        $content = '';
                        $design_id = $item['design_id'];
                        $this->_orderHelper->getOriginalImages($item_path, $design_id);
                        $designImages = $this->_designimagesFactory->create()->addFieldToFilter('design_id', array('eq' => $design_id));
                        foreach ($designImages as $designImage) {
                            if ($designImage->getTemplateMediaId()) {
                                $side = $this->_infoHelper->getImageSideFromTemplateMedia($designImage->getTemplateMediaId(), true, $this->_mediaGallery, $this->_side);
                            } else {
                                $side = $this->_infoHelper->getImageSideFromTemplateMedia($designImage->getProductImageId(), false, $this->_mediaGallery, $this->_side);
                            }
                            $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                            if ($designImage->getDesignImageType() == 'base_high') {
                                $name = $designImage->getImagePath();
                                $pdfname = 'Base -' . $sku . '-' . $side;
                                $designImagesBaseDir = $reader->getAbsolutePath() . '/productdesigner/designs/' . $design_id . '/base/';
                                $baseArr[] = scandir($designImagesBaseDir, 1);
                                foreach ($baseArr as $files) {
                                    foreach ($files as $key => $value) {
                                        if ('/' . $value == $name) {
                                            $imagePreFix = ltrim(strstr($value, '.', true), "'\'");
                                            $pngName = $imagePreFix . '.png';
                                            $jpgName = $imagePreFix . '.jpg';
                                            $name1 = 'Base-' . $sku . '-' . $side;
                                            copy($designImagesBaseDir . $pngName, $item_path . '/' . $pngName);
                                            rename($item_path . '/' . $pngName, $item_path . '/' . $name1 . '.png');
                                            copy($designImagesBaseDir . $jpgName, $item_path . '/' . $jpgName);
                                            rename($item_path . '/' . $jpgName, $item_path . '/' . $name1 . '.jpg');
                                        }
                                    }
                                }
                                $this->_orderHelper->generatePDF($designImagesBaseDir, $name, $design_id, $side, $item_path, $order_increment_id, $pdfname);
                            }

                            if ($designImage->getDesignImageType() == 'orig_high') {
                                $name = $designImage->getImagePath();
                                $pdfname = 'Print -' . $sku . '-' . $side;
                                $designImagesOrigDir = $reader->getAbsolutePath() . 'productdesigner/designs/' . $design_id . '/orig/';
                                $origArr[] = scandir($designImagesOrigDir, 1);
                                foreach ($origArr as $files) {
                                    foreach ($files as $key => $value) {
                                        if ('/' . $value == $name) {
                                            $imagePreFix = ltrim(strstr($value, '.', true), "'\'");
                                            $pngName = $imagePreFix . '.png';
                                            $jpgName = $imagePreFix . '.jpg';
                                            $name1 = 'Print-' . $sku . '-' . $side;
                                            copy($designImagesOrigDir . $pngName, $item_path . '/' . $pngName);
                                            rename($item_path . '/' . $pngName, $item_path . '/' . $name1 . '.png');
                                            copy($designImagesOrigDir . $jpgName, $item_path . '/' . $jpgName);
                                            rename($item_path . '/' . $jpgName, $item_path . '/' . $name1 . '.jpg');
                                        }
                                    }
                                }
                                $this->_orderHelper->generatePDF($designImagesOrigDir, $name, $design_id, $side, $item_path, $order_increment_id, $pdfname);
                            }
                        }
                    }
            }
            $this->_orderHelper->createEntireFolderZip($order_dir, $order_increment_id, $zip_dir);

        }else{
            $order = $this->_order->load($order_id);
            $order_increment_id = $this->_order->load($order_id)->getIncrementId();
            $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $order_dir = $reader->getAbsolutePath() . 'productdesigner/order';
            if (!file_exists($order_dir)) {
                mkdir($order_dir, 0777, true);
            }
            $zip_dir = $reader->getAbsolutePath() . 'productdesigner/order/' . $order_increment_id;
            if (!file_exists($zip_dir)) {
                mkdir($zip_dir, 0777, true);
            }

            $this->_orderHelper->generateCSV($zip_dir, $order, $order_id, null, 'false');

            foreach ($order->getAllVisibleItems() as $item) {
                $itemID = $item->getQuoteItemId();
                $sku = $item->getSku();
                $item_path = $reader->getAbsolutePath() . 'productdesigner/order/' . $order_increment_id . '/' . $itemID;
                $zip_path = array();

                if ($item->getProductOptionByCode('additional_options')) {
                    if (!file_exists($item_path)) {
                        mkdir($item_path, 0777, true);
                    }
                    if (isset($item->getProductOptionByCode('additional_options')[0]['design_id'])) {
                        $content = '';
                        $design_id = ($item->getProductOptionByCode('additional_options')[0]['design_id']);
                        $this->_orderHelper->getOriginalImages($item_path, $design_id);
                        $designImages = $this->_designimagesFactory->create()->addFieldToFilter('design_id', array('eq' => $design_id));
                        foreach ($designImages as $designImage) {
                            if ($designImage->getTemplateMediaId()) {
                                $side = $this->_infoHelper->getImageSideFromTemplateMedia($designImage->getTemplateMediaId(), true, $this->_mediaGallery, $this->_side);
                            } else {
                                $side = $this->_infoHelper->getImageSideFromTemplateMedia($designImage->getProductImageId(), false, $this->_mediaGallery, $this->_side);
                            }
                            $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                            if ($designImage->getDesignImageType() == 'base_high') {
                                $name = $designImage->getImagePath();
                                $pdfname = 'Base -' . $sku . '-' . $side;
                                $designImagesBaseDir = $reader->getAbsolutePath() . '/productdesigner/designs/' . $design_id . '/base/';
                                $baseArr[] = scandir($designImagesBaseDir, 1);
                                foreach ($baseArr as $files) {
                                    foreach ($files as $key => $value) {
                                        if ('/' . $value == $name) {
                                            $imagePreFix = ltrim(strstr($value, '.', true), "'\'");
                                            $pngName = $imagePreFix . '.png';
                                            $jpgName = $imagePreFix . '.jpg';
                                            $name1 = 'Base-' . $sku . '-' . $side;
                                            copy($designImagesBaseDir . $pngName, $item_path . '/' . $pngName);
                                            rename($item_path . '/' . $pngName, $item_path . '/' . $name1 . '.png');
                                            copy($designImagesBaseDir . $jpgName, $item_path . '/' . $jpgName);
                                            rename($item_path . '/' . $jpgName, $item_path . '/' . $name1 . '.jpg');
                                        }
                                    }
                                }
                                $this->_orderHelper->generatePDF($designImagesBaseDir, $name, $design_id, $side, $item_path, $order_increment_id, $pdfname);
                            }

                            if ($designImage->getDesignImageType() == 'orig_high') {
                                $name = $designImage->getImagePath();
                                $pdfname = 'Print -' . $sku . '-' . $side;
                                $designImagesOrigDir = $reader->getAbsolutePath() . 'productdesigner/designs/' . $design_id . '/orig/';
                                $origArr[] = scandir($designImagesOrigDir, 1);
                                foreach ($origArr as $files) {
                                    foreach ($files as $key => $value) {
                                        if ('/' . $value == $name) {
                                            $imagePreFix = ltrim(strstr($value, '.', true), "'\'");
                                            $pngName = $imagePreFix . '.png';
                                            $jpgName = $imagePreFix . '.jpg';
                                            $name1 = 'Print-' . $sku . '-' . $side;
                                            copy($designImagesOrigDir . $pngName, $item_path . '/' . $pngName);
                                            rename($item_path . '/' . $pngName, $item_path . '/' . $name1 . '.png');
                                            copy($designImagesOrigDir . $jpgName, $item_path . '/' . $jpgName);
                                            rename($item_path . '/' . $jpgName, $item_path . '/' . $name1 . '.jpg');
                                        }
                                    }
                                }
                                $this->_orderHelper->generatePDF($designImagesOrigDir, $name, $design_id, $side, $item_path, $order_increment_id, $pdfname);
                            }
                        }
                    }
                }
            }
            $this->_orderHelper->createEntireFolderZip($order_dir, $order_increment_id, $zip_dir);
        }
    }
}
