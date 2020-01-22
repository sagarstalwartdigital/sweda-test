<?php

namespace Biztech\ImportData\Cron;

class ImportVirtualImages {

	protected $_logger;
	protected $_designCollectionFactory;
	protected $_filesystem;
	protected $_store;
	protected $orderModel;
	protected $virtualImagesFactory;
	protected $designAreaPrintingMethodFactory;
	protected $colorcode;

	public function __construct(\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\App\Config\ScopeConfigInterface $store,
		\Biztech\ImportData\Model\VirtualImagesFactory $virtualImages,
		\Biztech\ImportData\Model\ColorcodeFactory $Colorcode,
		\Biztech\ImportData\Model\DesignAreaPrintingMethodFactory $designAreaPrintingMethod
	) {
		$this->_logger = $logger;
		$this->_filesystem = $filesystem;
		$this->_store = $store;
		$this->virtualImagesFactory = $virtualImages;
		$this->designAreaPrintingMethodFactory = $designAreaPrintingMethod;
		$this->colorcode = $Colorcode;
	}

	public function execute() {
		$this->_logger->info(__METHOD__);
		$dir = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();

		// virtual images
		$model = $this->virtualImagesFactory->create();
		$product_images_path = $dir . 'productdesigner/importData/VirtualProductImage/';
		$files = glob("$product_images_path/*.json");
		foreach ($files as $key => $file) {
			$strJsonFileContents = file_get_contents($file);
			// Convert to array
			$files_data = json_decode($strJsonFileContents, TRUE);
			$data = array();
			foreach ($files_data as $key => $value) {
				$virtualImages = json_encode($value['virtualImages']);
				$virtualProductMasterId = $value['virtualProductMasterId'];
				$data['virtual_product_master_id'] = $virtualProductMasterId;
				$data['virtual_images'] = $virtualImages;
				$model->setData($data);
				$model->save();
			}
		}

		// design area and printing method
		$printingMethodModel = $this->designAreaPrintingMethodFactory->create();
		$printing_method_path = $dir . 'productdesigner/importData/VirtualProductImprint/';
		$printingmethod_files = glob("$printing_method_path/*.json");
		foreach ($printingmethod_files as $key => $file) {
			$strJsonFileContents = file_get_contents($file);
			// Convert to array
			$files_data = json_decode($strJsonFileContents, TRUE);
			$data = array();
			foreach ($files_data as $key => $value) {
				$data['virtual_product_master_id'] = $value['virtualProductMasterId'];
				$data['imprint_param'] = $value['imprintParam'];
				$data['location'] = $value['location'];
				$data['default_imprint_method'] = $value['defaultImprintMethod'];
				$data['imprint_method'] = json_encode($value['imprintMethod']);
				$printingMethodModel->setData($data);
				$printingMethodModel->save();
			}
		}

	}
}
