<?php

namespace Biztech\Productdesigner\Cron;

use \Spipu\Html2Pdf\Html2Pdf;

header("Access-Control-Allow-Origin: *");

class GenerateImages {

    protected $designOrderCollection;
    protected $designOrder;
    protected $design;
    protected $dir;
    protected $designImages;
    protected $product;
    protected $selectionareaCollection;
    protected $order;
    protected $_storeManager;
    protected $pdHelper;
    protected $infoHelper;
    protected $configurable;
    protected $designImagesOrigDir;
    protected $designImagesFactory;
    protected $_designCollection;
    protected $_fileSystem;
    protected $_scopeConfig;
    protected $_eventManager;
    protected $_objectFactory;

    const DPI = 150;
    const CANVASWIDTH = 540;

    public function __construct(
    \Biztech\Productdesigner\Model\Mysql4\DesignOrders\CollectionFactory $designOrderCollection, \Biztech\Productdesigner\Model\DesignOrdersFactory $designOrder, \Biztech\Productdesigner\Model\DesignsFactory $design, \Magento\Framework\Filesystem\DirectoryList $dir, \Biztech\Productdesigner\Model\Mysql4\Designimages\CollectionFactory $designImages, \Magento\Catalog\Model\ProductFactory $product, \Biztech\Productdesigner\Model\Mysql4\Selectionarea\CollectionFactory $selectionareaCollection, \Magento\Store\Model\StoreManagerInterface $storeManager, \Biztech\Productdesigner\Helper\Data $pdHelper, \Biztech\Productdesigner\Helper\Info $infoHelper, \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable, \Biztech\Productdesigner\Model\DesignimagesFactory $designImagesFactory, \Biztech\Productdesigner\Model\Mysql4\Designimages\Collection $designCollection, \Magento\Framework\Filesystem $fileSystem, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Event\Manager $manager, \Magento\Framework\DataObjectFactory $objectFactory
    ) {
        $this->designOrderCollection = $designOrderCollection;
        $this->designOrder = $designOrder;
        $this->design = $design;
        $this->dir = $dir;
        $this->designImages = $designImages;
        $this->product = $product;
        $this->selectionareaCollection = $selectionareaCollection;
        $this->_storeManager = $storeManager;
        $this->pdHelper = $pdHelper;
        $this->infoHelper = $infoHelper;
        $this->configurable = $configurable;
        $this->designImagesFactory = $designImagesFactory;
        $this->_designCollection = $designCollection;
        $this->_fileSystem = $fileSystem;
        $this->_scopeConfig = $scopeConfig;
        $this->_eventManager = $manager;
        $this->_objectFactory = $objectFactory;
    }

    public function execute() {
        $designId = '';
        try {
            $orderData = $this->designOrderCollection->create()
                    ->addFieldToFilter('status', array('eq' => 0))
                    ->getFirstItem();
            $order_row = $orderData->getData();
            if (count($order_row) != 0) {
                $designId = $order_row['design_id'];
                $orderId = $order_row['order_id'];
                $orderImagesId = $order_row['id'];
                $orderDesignModel = $this->designOrder->create()->load($orderImagesId);
                $orderDesignModel->setStatus('1'); // Processing
                // $orderDesignModel->save();
                $design = $this->design->create()->load($designId);
                $designImagesCollection = $this->designImages->create()
                        ->addFieldToFilter('design_id', $designId)
                        ->addFieldToFilter('design_image_type', array('in' => array('base_high', 'orig_high')));
                $designImagesCollection->walk('delete');
                try {
                    $output_result = $this->generateProductOutputImages($design, $orderId);
                } catch (\Exception $e) {
                    $output_result['status'] = 'fail';
                    $output_result['error'] = $e->getMessage();
                    $response = $this->infoHelper->throwException($e, self::class);
                    $this->getResponse()->setBody(json_encode($response));
                }

                $isImagesGenerated = 3; // Failure
                if (isset($output_result['status']) && $output_result['status'] == 'success') {
                    $isImagesGenerated = 2; // Success
                }
                $orderDesignModel->setStatus($isImagesGenerated);
                //$orderDesignModel->save();                
            }
        } catch (\Exception $e) {
            $response = $this->infoHelper->throwException($e, self::class);
            $this->getResponse()->setBody(json_encode($response));
        }
        return $this;
    }

    public function generateDesignPaths($design, $mediaPath) {
        $designId = $design->getId();
        $designImagesBaseDir = $mediaPath . '/productdesigner/designs/' . $designId . '/base/';
        $designImagesOrigDir = $mediaPath . '/productdesigner/designs/' . $designId . '/orig/';
        if (!file_exists($designImagesBaseDir)) {
            mkdir($designImagesBaseDir, 0777, true);
        }
        if (!file_exists($designImagesOrigDir)) {
            mkdir($designImagesOrigDir, 0777, true);
        }
        return array($designImagesBaseDir, $designImagesOrigDir);
    }

    public function getCanvasDataUrl($design, $mediaPath) {
        $large_image_file = $design->getCanvasDataurlFile();
        $dir = $mediaPath . '/productdesigner/canvasData/';
        $filename = $dir . $large_image_file;
        $filesize = 0;
        if (file_exists($filename)) {
            $filesize = filesize($filename);
        }
        $canvasData = '';
        if ($filesize > 0) {
            $readMyfile = fopen($filename, "r");
            $canvasData = fread($readMyfile, filesize($filename));
            fclose($readMyfile);
        }
        $canvasDataURL = json_decode(base64_decode($canvasData), true);
        $merged_large_images = array();
        foreach ($canvasDataURL as $key => $large_image) {
            $newkey = str_replace('@', '', $key);
            $newkey1 = strstr($newkey, "&", true);
            $newkey2 = str_replace('&', '', strstr($newkey, "&", false));
            $merged_large_images[$newkey1][$newkey2] = $large_image;
        }
        return $merged_large_images;
    }

    public function generateProductOutputImages($design, $orderId) {
        $designId = $design->getId();
        $mediaPath = $this->dir->getPath('media');
        $productId = $design->getProductId();
        $associatedProductId = $design->getAssociatedProductId();
        /**
         * It will generate the paths if not exist
         */
        list($designImagesBaseDir, $designImagesOrigDir) = $this->generateDesignPaths($design, $mediaPath);
        /**
         * It will fetch canvas data url by reading text file generated and saved in design table
         */
        $merged_large_images = $this->getCanvasDataUrl($design, $mediaPath);

        /**
         * It will fetch product image of products
         */
        $productData = $this->infoHelper->getProductTypeAndMediaImages($productId);
        $grouped_product_images = $productData['media_image'];
        $product_type = $productData['product_type'];
        /**
         * It will fetch all design areas of product
         */
        $selectionareas = $this->selectionareaCollection->create()
                        ->addFieldToFilter('product_id', array('in' => array($associatedProductId, $productId)))->getData();
        $dimensions = array();
        foreach ($selectionareas as $selectionarea) {
            $dimensions[$selectionarea['image_id']][$selectionarea['design_area_id']] = $selectionarea;
        }
        /**
         * Relative image id in case of configurable product
         */
        if ($product_type == 'configurable') {
            $relativeImageIds = json_decode($design->getRelativeImageId(), true);
            $relatedImageIdsArray = array();
            foreach ($relativeImageIds as $current => $desired) {
                $imageIdWithDesignAreasArray = explode("&", $current);
                $imageIds = $imageIdWithDesignAreasArray[0];
                $imageIdArray = explode('@', $imageIds);
                $imageId = $imageIdArray[1];
                $designAreaId = $imageIdWithDesignAreasArray[1];

                $desiredImageIdWithDesignAreasArray = explode("&", $desired);
                $desiredImageIds = $desiredImageIdWithDesignAreasArray[0];
                $desiredImageIdArray = explode('@', $desiredImageIds);
                $desiredImageId = $desiredImageIdArray[1];
                $desiredDesignAreaId = $desiredImageIdWithDesignAreasArray[1];

                $relatedImageIdsArray['image_ids'][$imageId] = $desiredImageId;
                $relatedImageIdsArray['designArea_ids'][$designAreaId] = $desiredDesignAreaId;
            }
            $parentImageIds = json_decode($design->getParentImageId(), true);
        }
        /**
         * Loop for all large images
         */
        $params = array();
        $params['designImagesBaseDir'] = $designImagesBaseDir;
        $params['associatedProductId'] = $associatedProductId;
        $params['designImagesOrigDir'] = $designImagesOrigDir;
        $params['design_id'] = $designId;
        $params['productId'] = $productId;
        $params['canvasRatio'] = \Biztech\Productdesigner\Helper\Info::CanvasRatio;
        $all_design_images = array();
        foreach ($merged_large_images as $productImageId => $large_image) {
            /**
             * Change key to updated
             */
            if ($product_type == 'configurable') {
                $image_id = $relatedImageIdsArray['image_ids'][$productImageId];
                $params['relatedImageIdsArray'] = $relatedImageIdsArray['designArea_ids'];
            } else {
                $image_id = $productImageId;
                $params['relatedImageIdsArray'] = array();
            }
            $prod_image_path = $grouped_product_images[$image_id]['path'];
            $params['prod_image_path'] = $prod_image_path;
            $params['large_image'] = $large_image;
            $params['image_id'] = $image_id;
            /**
             * If associated dimensions are not set then fetch from parent
             */
            if (!isset($dimensions[$image_id])) {
                $image_id = $parentImageIds[$productImageId];
            }
            $params['dimensions'] = $dimensions[$image_id];
            $all_design_images[] = $this->generateDesignImages($params);
            foreach ($all_design_images as $images) {
                foreach ($images as $key => $value) {
                    if ($key == 'base_high') {
                        $this->generatePDF($designImagesBaseDir, $value, $designId, $orderId);
                    } else {
                        $this->generatePDF($designImagesOrigDir, $value, $designId, $orderId);
                    }
                }
            }
            $this->downloadAllImagesPdf($all_design_images, $designId, $orderId);
            $data['image_id'][] = $image_id;
        }
        $data['designId'] = $designId;
        return $this->saveGeneratedImages($all_design_images, $data);
    }

    public function downloadAllImagesPdf($all_design_images, $designId, $orderId) {
        $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $dir = $reader->getAbsolutePath() . 'productdesigner/designs/' . $designId . '/';
        $content = '';
        foreach ($all_design_images as $designImage) {
            foreach ($designImage as $key => $value) {
                if ($key == 'base_high') {
                    $path = $dir . 'base' . $value;
                } else {
                    $path = $dir . 'orig' . $value;
                }


                $imgtype = getimagesize($path);
                $size = 'A4';
                $cnt_height = ($imgtype[1] * 550) / $imgtype[0];
                if ($cnt_height < 800) {
                    $size = 'A4';
                }
                if ($cnt_height > 800 && $cnt_height < 1150) {
                    $size = 'A3';
                } else if ($cnt_height > 1150 && $cnt_height < 1650) {
                    $size = 'A2';
                } else if ($cnt_height > 1650 && $cnt_height < 2350) {
                    $size = 'A1';
                }
                if ($imgtype[0] >= 550) {
                    $content .= "<page><div style='margin:0 auto; text-align:center; vertical-align:middle;'><img src='" . $path . "' width='550'></div><page_footer><div style='text-align:right'>Order ID # " . $orderId . "</div></page_footer></page>";
                } else {
                    $content .= "<page><div style='margin:0 auto; text-align:center; vertical-align:middle;'><img src='" . $path . "'></div><page_footer><div style='text-align:right'>Order ID # " . $orderId . "</div></page_footer></page>";
                }
            }
        }
        $name = $orderId . '_designs.pdf';
        $this->createPdf($content, $name, $size, $designId);
    }

    public function generateDesignImages($params) {
        /**
         * It will format large image data
         */
        $source = $this->processLargeImages($params['large_image'], $params['relatedImageIdsArray']);
        /**
         * Initialize variable from params
         */
        $designImagesBaseDir = $params['designImagesBaseDir'];
        $designImagesOrigDir = $params['designImagesOrigDir'];
        $allDimensions = $params['dimensions'];
        $productId = $params['productId'];
        $associatedProductId = $params['associatedProductId'];
        $canvasRatio = $params['canvasRatio'];
        $prod_image_path = $params['prod_image_path'];
        list($resize_width, $resize_height) = $this->infoHelper->calculateResizeWidthHeight($prod_image_path);
        /** Start Added By A.S. Custom Size Output * */
        $customObject = $this->_objectFactory->create();
        $customObject->setImage(array('imagepath' => $prod_image_path, 'product_id' => $associatedProductId, 'base_path' => $designImagesBaseDir, 'source' => $source, 'allDimensions' => $allDimensions));
        $this->_eventManager->dispatch('generated_images_custom_size', ['imagedata' => $customObject]);
        $prod_image_path = $customObject->getImage('imagepath') ?: $params['prod_image_path'];
        $source = $customObject->getImage('source');
        /** End Added By A.S. Custom Size Output * */
        list($iWidth, $iHeight, $imageType) = getimagesize($prod_image_path);
        /**
         * Check for canvas ratio
         */
        $enable_handles = (count($allDimensions) > 1) ? false : $this->infoHelper->IsEnableHandles($productId);
        /**
         * It will create source image with product image
         */
        list($base_image_name, $destination) = $this->infoHelper->createSourceImage($prod_image_path);

        /**
         * It will create source image with product image
         */
        list($origDestination, $orig_image_name) = $this->createBlankImage($iWidth, $iHeight);

        /**
         * Dimension loop to generate image with canvas ratio calculation
         */
        foreach ($allDimensions as $dimension) {
            $selection_area = json_decode($dimension['selection_area'], true);
            if ($enable_handles) {
                $clipx = $clipy = $canvasRatio;
                if ($selection_area['height'] + $canvasRatio > $resize_height) {
                    $clipy = $resize_height / $selection_area['height'];
                }
                if ($selection_area['width'] + $canvasRatio > $resize_width) {
                    $clipx = $resize_width / $selection_area['width'];
                }
            } else {
                $clipx = $clipy = 0;
            }
            $x1 = $selection_area['x1'] - (($selection_area['width'] + $clipx - $selection_area['width']) / 2);
            $y1 = $selection_area['y1'] - (($selection_area['height'] + $clipy - $selection_area['height']) / 2);
            $imageIdDesignAreaId = '@' . $dimension['image_id'] . '&' . $dimension['design_area_id'];
            $imageIdDesignAreaId = $dimension['design_area_id'];
            $newX1 = ($iWidth * $x1) / $resize_width;
            $newY1 = ($iHeight * $y1) / $resize_height;
            /**
             * Canvas data with product image
             */
            if (isset($source[$imageIdDesignAreaId])) {
                imagecopy($destination, $source[$imageIdDesignAreaId], $newX1, $newY1, 0, 0, imagesx($source[$imageIdDesignAreaId]), imagesy($source[$imageIdDesignAreaId]));
            }
            /**
             * Canvas data with blank image
             */
            if (isset($source[$imageIdDesignAreaId])) {
                imagecopy($origDestination, $source[$imageIdDesignAreaId], $newX1, $newY1, 0, 0, imagesx($source[$imageIdDesignAreaId]), imagesy($source[$imageIdDesignAreaId]));
            }
        }
        $imageType = image_type_to_mime_type($imageType);
        imagesavealpha($destination, true);
        switch ($imageType) {
            case 'image/jpeg':
                imagejpeg($destination, $designImagesBaseDir . $base_image_name, 100);
                /**
                 * It will convert image to PNG
                 */
                $this->pdHelper->convertImage('png', $designImagesBaseDir, $base_image_name);
                break;
            case 'image/png':
                imagepng($destination, $designImagesBaseDir . $base_image_name);
                /**
                 * It will convert image to JPG
                 */
                $this->pdHelper->convertImage('jpg', $designImagesBaseDir, $base_image_name);
                break;
        }
        imagedestroy($destination);
        imagesavealpha($origDestination, true);
        imagepng($origDestination, $designImagesOrigDir . $orig_image_name);
        imagedestroy($origDestination);
        /**
         * It will convert DES image to JPG
         */
        $this->pdHelper->convertImage('jpg', $designImagesOrigDir, $orig_image_name);
        $result = array("base_high" => '/' . $base_image_name, "orig_high" => '/' . $orig_image_name);
        return $result;
    }

    public function generatePDF($destination, $imageName, $designId, $orderId) {
        $destination = rtrim($destination, "/");
        $path = $destination . $imageName;
        $imgtype = getimagesize($path);
        $imgtype = getimagesize($path);
        switch ($imgtype['mime']) {
            case 'image/jpeg':
                $name = basename($imageName, '.jpg') . '.pdf';
                break;

            case 'image/png':
                $name = basename($imageName, '.png') . '.pdf';
                break;
            default:
                $name = basename($imageName, '.jpg') . '.pdf';
        }

        $size = 'A4';
        $cnt_height = ($imgtype[1] * 550) / $imgtype[0];
        if ($cnt_height < 800) {
            $size = 'A4';
        }
        if ($cnt_height > 800 && $cnt_height < 1150) {
            $size = 'A3';
        } else if ($cnt_height > 1150 && $cnt_height < 1650) {
            $size = 'A2';
        } else if ($cnt_height > 1650 && $cnt_height < 2350) {
            $size = 'A1';
        }
        if ($imgtype[0] >= 550) {
            $content = "<page><div style='margin:0 auto; text-align:center; vertical-align:middle;'><img src='" . $path . "' width='550'></div><page_footer><div style='text-align:right'>Order ID # " . $orderId . "</div></page_footer></page>";
        } else {
            $content = "<page><div style='margin:0 auto; text-align:center; vertical-align:middle;'><img src='" . $path . "'></div><page_footer><div style='text-align:right'>Order ID # " . $orderId . "</div></page_footer></page>";
        }
        $this->createPdf($content, $name, $size, $designId);
    }

    public function createPdf($content, $name, $size, $designId) {
       $html2pdf = new Html2Pdf('P', $size, 'en');
       $html2pdf->WriteHTML($content);
       $reader = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
       $extractPath = $reader->getAbsolutePath() . 'productdesigner/';
       $path = $extractPath . 'designs/' . $designId . '/pdf/';
       if (!file_exists($path)) {
           mkdir($path, 0777, true);
       }
       $html2pdf->Output($path . $name, 'F');
    }

    public function createBlankImage($iWidth, $iHeight) {
        $destination = imagecreatetruecolor($iWidth, $iHeight);
        imagesavealpha($destination, true);
        $imageColor = imagecolorallocatealpha($destination, 0, 0, 0, 127);
        imagefill($destination, 0, 0, $imageColor);
        $time = substr(md5(microtime()), rand(0, 26), 7);
        $orig_image_name = "des_" . $time . ".png";
        return array($destination, $orig_image_name);
    }

    public function processLargeImages($large_image, $relatedImageIdsArray) {
        foreach ($large_image as $key => $value) {
            if (count($relatedImageIdsArray) > 0) {
                $newKey = $relatedImageIdsArray[$key];
            } else {
                $newKey = $key;
            }
            $decodedLargeImage[$newKey] = base64_decode($value);
        }
        $source = array();
        foreach ($decodedLargeImage as $key => $value) {
            $source[$key] = imagecreatefromstring($value);
        }
        return $source;
    }

    public function saveGeneratedImages($all_design_images, $data) {
        $result = array();
        try {
            foreach ($all_design_images as $index => $design_images) {
                $image_id = $data['image_id'][$index];
                foreach ($design_images as $image_type => $design_image) {
                    $designImagesModel = $this->designImagesFactory->create();
                    $designImagesModel->setDesignId($data['designId'])
                            ->setDesignImageType($image_type)
                            ->setProductImageId($image_id)
                            ->setImagePath(str_replace('\\', '/', $design_image));
                    $designImagesModel->save();
                }
            }
            $eventParams = array("designedImages" => $all_design_images, "designId" => $data['designId'], "defaultDpiValue" => self::DPI);
            $this->_eventManager->dispatch('generated_images_saved_after', ['eventData' => $eventParams]);
            $result['status'] = 'success';
        } catch (\Exception $e) {
            $result['status'] = 'failure';
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

}
