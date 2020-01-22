<?php
namespace Stalwart\Sweda\Controller\Downloadimages;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory; 

class DownloadAllImages extends Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $productId = $this->getRequest()->getParam('id');
        $_product = $_objectManager->get('Magento\Catalog\Model\Product')->load($productId); 

        $file_arr=array();

        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        foreach ($_product->getMediaGallery('images') as $image) {
            if(isset($image['file']) && !empty($image['file']))
                array_push($file_arr,$mediaUrl.'catalog/product'.$image['file']);
        }

        $usedProducts = $_product->getTypeInstance()->getUsedProducts($_product);

        foreach ($usedProducts as $child) {
            $_childproducts = $_objectManager->get('\Magento\Catalog\Model\Product')->load($child->getId());
            foreach ($_childproducts->getMediaGallery('images') as $image) {
                if(isset($image['file']) && !empty($image['file']))
                    array_push($file_arr,$mediaUrl.'catalog/product'.$image['file']);
            }
        }
        
        $filename = $_product->getSku().'_allimages.zip';
        $this->create_zip($filename,$file_arr);
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        unlink($filename);

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    public function create_zip($filename,$files = array())
    {
        $zip = new \ZipArchive;
        $res = $zip->open($filename, \ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($files as $file) {
                $download_file = file_get_contents($file);
                $zip->addFromString(basename($file), $download_file);
            }
            $zip->close();
        }
    }
}