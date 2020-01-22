<?php

namespace Biztech\ImportData\Cron;
use Magento\Catalog\Model\Product;

class Importproduct {

	protected $_logger;
	protected $_designCollectionFactory;
	protected $_filesystem;
	protected $_store;
	protected $orderModel;
	protected $virtualImagesFactory;
	protected $_designAreaPrintingMethod;
	protected $productRepository;
	protected $sideFactory;
	protected $product;
	protected $colorcodeFactory;
	protected $_dir;
	protected $_mediaGallery;
	protected $_importProduct;
	protected $selectionAreaFactory;
	protected $infoHelper;
	protected $printingmethodFactory;

	public function __construct(\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\App\Config\ScopeConfigInterface $store,
		\Biztech\ImportData\Model\VirtualImagesFactory $virtualImages,
		\Biztech\ImportData\Model\DesignAreaPrintingMethodFactory $designAreaPrintingMethod,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Biztech\Productdesigner\Model\SideFactory $sideFactory,
		\Biztech\ImportData\Model\ColorcodeFactory $ColorcodeFactory,
		\Magento\Framework\Filesystem\DirectoryList $dir,
		\Biztech\ImportData\Model\MediaGalleryFactory $mediaGallery,
		\Biztech\ImportData\Model\ImportproductFactory $importProduct,
		\Biztech\Productdesigner\Model\SelectionareaFactory $selectionAreaFactory,
		\Biztech\Productdesigner\Helper\Info $infoHelper,
		\Biztech\PrintingMethods\Model\PrintingmethodFactory $PrintingmethodFactory,
		\Magento\Catalog\Model\ProductFactory $product

	) {
		$this->_logger = $logger;
		$this->_filesystem = $filesystem;
		$this->_store = $store;
		$this->virtualImagesFactory = $virtualImages;
		$this->_designAreaPrintingMethod = $designAreaPrintingMethod;
		$this->productRepository = $productRepository;
		$this->sideFactory = $sideFactory;
		$this->product = $product;
		$this->colorcodeFactory = $ColorcodeFactory;
		$this->_dir = $dir;
		$this->_mediaGallery = $mediaGallery;
		$this->_importProduct = $importProduct;
		$this->selectionAreaFactory = $selectionAreaFactory;
		$this->infoHelper = $infoHelper;
		$this->printingmethodFactory = $PrintingmethodFactory;
	}

	public function execute() {

		$dir = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
		$productImages = $this->virtualImagesFactory->create();
		$masterProductPath = $dir . 'productdesigner/importData/VirtualProductMaster.json';
		$strJsonFileContents = file_get_contents($masterProductPath);
		$products = json_decode($strJsonFileContents, TRUE);
		foreach ($products as $mainProduct) {
			$masterId = $mainProduct['_id']['$oid'];
			$sku = $mainProduct['sku'];
			$product = $this->loadProductBySku($sku);
			if ($product) {
				$productImage = $productImages->getCollection()->addFieldToFilter('virtual_product_master_id', array('eq' => $masterId))->getFirstItem();

				//Import product images
				if ($product && !empty($productImage->getData()) && $product->getTypeId() == 'configurable') {
					$importedProduct = $this->_importProduct->create()->getCollection()->addFieldToFilter('productid', array('eq' => $product->getId()));

					if ($importedProduct->count() == 0) {
						$status = $this->importImages($product, $productImage->getVirtualImages(), $masterId);
						$this->setProductStatus($product->getId(), $status);
					}
				} else {
					// echo $product->getId() . '---' . $product->getTypeId() . '--' . $masterId . "<br>";
				}
			} else {
				// echo "ELSE:" . $sku . '<br>';
			}
		}
	}

	public function importImages($product, $productImages, $masterId) {

		//create side if side not already exits
		$this->createSide($productImages);

		//getAssociated product
		$productImages = (array) json_decode($productImages);
		$mainSideColorIds = (array) $productImages['main'];
		$mainSideColorIds = array_keys($mainSideColorIds);

		$_configChild = $product->getTypeInstance()->getUsedProducts($product);
		$configImages = array();

		foreach ($_configChild as $child) {

			//get color custom code in colorcode tabel
			$colorCodeId = $this->getColorCodeId($child, $product);
			//upload image one by one product. check all side in same product like (1 == red)
			if ($colorCodeId != '') {
				foreach ($productImages as $side => $sideImages) {
					if ($side != 'main' && !empty($sideImages)) {
						//set multiple design area

						$sideImages = (array) $sideImages;
						foreach ($sideImages as $sideImage) {

							$sideImage = (array) $sideImage;
							foreach ($sideImage as $code => $image) {
								if ($code == $colorCodeId && in_array($colorCodeId, $mainSideColorIds)) {
									$findImage = $this->getImageFromFolder($image);
									if ($findImage) {
										$this->uploadImageInProduct($findImage, $child, $side,'childProduct',$masterId,'');
										//store image for configure product
										if (!array_key_exists($side, $configImages)) {
											$configImages[$child->getId()][$side][] = $findImage;
										}

									}
								}
							}
							break;
						}

					}
				}
			}
		}
		//upload image in main configure product
		$status = 0;
		if (!empty($configImages)) {
			$configImagesKey = key($configImages);
			$configImages = current($configImages);
			/*if(count($configImages) > 1){
				$configImages = current($configImages);
			}
			*/
			//$configImages = array_unique($configImages);
			// $alreadyImportImage = array(); // add multiplae design area in same image
			foreach ($configImages as $sideConfig => $sideImages) {
				$alreadyImportImage = array(); 
				$status = 1;
				$sideImages = array_unique($sideImages);
				foreach ($sideImages as $key => $sideImage) {
					if (!in_array($sideImage, $alreadyImportImage)) {
						$alreadyImportImage[] = $sideImage;
						$this->uploadImageInProduct($sideImage, $product, $sideConfig, 'mainProduct', $masterId, $configImagesKey);
						//echo "hello0";exit;
					}
				}

			}
		}
		return $status;
	}

	public function getColorCodeId($child, $product) {
		$getChildId[] = $child->getColor();
		$optionId = $child->getColor();
		$label = '';
		$colorCodeId = '';
		$attr = $product->getResource()->getAttribute('color');
		if ($attr->usesSource()) {
			$label = $attr->getSource()->getOptionText($optionId);
		}

		if ($label != '') {
			//get color code custom id
			$colorCode = $this->colorcodeFactory->create()->getCollection()->addFieldToFilter('color_name', array('eq' => $label));
			if ($colorCode->count() != 0) {
				$colorCodeId = $colorCode->getFirstItem()->getColorCode();
			}
		}
		return $colorCodeId;

	}
	public function createSide($productImages) {

		$getAllSide = (array) json_decode($productImages);
		$getAllSide = array_keys($getAllSide);

		foreach ($getAllSide as $side) {
			$design = $this->sideFactory->create();
			$designCollection = $this->sideFactory->create()->getCollection()
				->addFieldToFilter('imageside_title', array('eq', $side));
			//check side allready exit or not
			if ($designCollection->count() == 0) {
				$design->setImagesideTitle($side);
				$design->setStatus(1);
				$design->save();
			}
		}

	}
	public function getImageFromFolder($image) {
		$image = explode('/', $image);
		$image = end($image);
		$dir = $this->_dir->getPath('media') . '/';
		$imageDir = $dir . 'productdesigner/importData/Sweda_Virtual_Images/';
		$imagePath = $this->rsearch($imageDir, $image);

		if ($imagePath) {
			return $imagePath;
		} else {
			return false;
		}
	}

	function rsearch($folder, $pattern) {
		$iti = new \RecursiveDirectoryIterator($folder);
		foreach (new \RecursiveIteratorIterator($iti) as $file) {
			if (strpos($file, $pattern) !== false) {
				return $file->getPathname();
			}
		}
		return false;
	}

	function uploadImageInProduct($image, $product, $side, $type, $masterId, $firstProduct) {
		//set product Image with hidden
		$product = $this->product->create()->load($product->getId());

		$product->setStoreId(0)->addImageToMediaGallery(
			$image,
			null,
			false,
			true
		);
		$product->save();
		

		//get last image id
		// $images = $product->getMediaGalleryEntries();
		$lastImageId = $this->getlastImageId($product);

		//getSide id based on the side name
		$sideExit = $this->getSideIdByName($side);
		//set side

		if ($lastImageId != '') {
			//setDesignArea
			if ($type == 'mainProduct') {
				$this->setDesignArea($product, $lastImageId, $sideExit, $masterId, $side, $image, $firstProduct);
			}

			$lastImageData = $this->_mediaGallery->create()->load($lastImageId);
			$lastImageData->setImageSide($sideExit);
			$lastImageData->save();
		}

	}
	public function setDesignArea($product, $lastImageId, $side, $masterId, $sideName, $image, $firstProduct) {
		$designAreaDatas = $this->_designAreaPrintingMethod->create()->getCollection()
			->addFieldToFilter('virtual_product_master_id', array('eq', $masterId))->addFieldToFilter('location', array('eq', $sideName));
		foreach ($designAreaDatas as $designAreaData) {
			if ($designAreaData) {
				//set product id for printing method select in product level
				$designAreaData->setProductId($product->getId());
				$designAreaData->save();
				$this->setPrintingMethod($product, $designAreaData->getImprintMethod());

				$designArea = unserialize($designAreaData->getImprintParam());
				$leftTop = isset($designArea['product_template_left_top']) ? $designArea['product_template_left_top'] : '0,0';
				$leftTop = explode(',', $leftTop);
				$left = isset($leftTop[0]) ? $leftTop[0] : 0;
				$top = isset($leftTop[1]) ? $leftTop[1] : 0;

				$widthHeight = isset($designArea['product_imprint_image_size']) ? $designArea['product_imprint_image_size'] : '0,0';
				$widthHeight = explode('X', $widthHeight);
				$width = isset($widthHeight[0]) ? $widthHeight[0] : 0;
				$height = isset($widthHeight[1]) ? $widthHeight[1] : 0;

				$selectionArea = $this->selectionAreaFactory->create();

				list($iWidth, $iHeight) = getimagesize($image);
				$iWidth = 500;
				$iHeight = 500;
				list($resize_width, $resize_height) = $this->infoHelper->calculateResizeWidthHeight($image);

				$designWidth = ($resize_width * $width) / $iWidth;
				$designHeight = ($resize_height * $height) / $iHeight;
				$x1 = ($resize_width * $left) / $iWidth;
				$y1 = ($resize_height * $top) / $iHeight;
				$x2 = $x1 + $designWidth;
				$y2 = $y1 + $designHeight;

				$selection_area_arr = [
					'width' => $designWidth,
					'height' => $designHeight,
					'x1' => $x1,
					'y1' => $y1,
					'x2' => $x2,
					'y2' => $y2,
					'image_id' => $lastImageId,
				];
				$selectionArea->setImageId($lastImageId);
				$selectionArea->setSelectionArea(json_encode($selection_area_arr));
				$selectionArea->setProductId($product->getId());
				$selectionArea->setMaskingImageId(0);
				$selectionArea->setImagesideId($side);
				$selectionArea->save();
				$this->setDesignerSetting($product, $firstProduct);
			}
		}

	}
	public function setPrintingMethod($product, $printingMethod) {
		$printingMehods = $this->printingmethodFactory->create()->getCollection();
		$storeMethodIds = array();
		$methods = (array) json_decode($printingMethod);
		foreach ($methods as $method => $status) {
			foreach ($printingMehods as $printingMehod) {
				if ($printingMehod->getPrintingName() == $method && $status == '1') {
					$storeMethodIds[] = $printingMehod->getPrintingId();
				}
			}
		}
		if (!empty($storeMethodIds)) {
			$storeMethodIds = implode(',', $storeMethodIds);
			$product->setPrintingmethodattr($storeMethodIds);
			$product->save();
		}

	}
	public function setDesignerSetting($product, $firstProduct) {

		$product->setEnableProductDesigner(1);
		$product->setDefaultAssociatedProduct($firstProduct);
		$product->save();
	}
	public function getlastImageId($product) {
		$images = $product->getMediaGalleryEntries();
		$lastImageId = '';
		foreach ($images as $child) {
			$lastImageId = $child->getId();
		}
		return $lastImageId;
	}
	public function getSideIdByName($side) {
		$designCollection = $this->sideFactory->create()->getCollection()
			->addFieldToFilter('imageside_title', array('eq', $side));
		//check side allready exit or not
		if ($designCollection->count() != 0) {
			$designCollection = $designCollection->getFirstItem();
			return $designCollection->getImagesideId();
		}
		return false;
	}
	public function setProductStatus($productId, $status) {
		$importProduct = $this->_importProduct->create();
		$importProduct->setProductid($productId);
		$importProduct->setStatus($status);
		$importProduct->save();
	}
	public function loadProductBySku($sku) {
		try {
			return $this->productRepository->get($sku);
		} catch (\Exception $e) {
			return false;
		}
	}
}
