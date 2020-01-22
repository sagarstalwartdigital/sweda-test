<?php
namespace Stalwart\Sweda\Controller\Downloadpropdf;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class DownloadPdf extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_downloader;

    /**
     * @var Magento\Framework\Filesystem\DirectoryList
     */
    protected $_directory;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory
    ) {
        $this->_downloader =  $fileFactory;
        $this->directory = $directory;
        parent::__construct($context);
    }

    public function execute()
    {

        $filePath = $this->getRequest()->getParam('fileName');
        $file = $this->directory->getPath("media")."/catalog/product/file/".$filePath;
        $filePathStrToArray = explode("/",$filePath);

        foreach ($filePathStrToArray as $filePathName) {
            $fileName = $filePathName;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
            
    }
}