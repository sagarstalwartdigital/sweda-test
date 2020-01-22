<?php
namespace Biztech\SocialMediaImport\Controller\Index;

header("Access-Control-Allow-Origin: *");

class downloadInstagram extends \Magento\Framework\App\Action\Action {

    protected $_storeManager;
    protected $_filesystem;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, 
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_storeManager = $storeManager;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute() {
        $reader = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $data = $this->getRequest()->getParams();
        $instaData = [];
        $instaImages = [];
        for ($i = 0; $i < count($data['data']); $i++) {
            $imageDataorig_url = $data['data'][$i]['images']['standard_resolution']['url'];
            $filename_array = explode("/", $imageDataorig_url);
            $path = $reader->getAbsolutePath() . '/productdesigner/socialMedia/';

            $actualfilename = explode("?",$filename_array[count($filename_array) - 1]);
            $fileName = $path . $actualfilename[0];
            $socialMediaURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'productdesigner/socialMedia/' . $actualfilename[0];
            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            if (!is_writable($path)) {
                $data = array(
                    'status' => false,
                    'msg' => 'Destination directory not writable.',
                );
                $this->getResponse()->setBody(json_encode($data));
            }
            copy($imageDataorig_url, $fileName);
            $instaImages[] = $socialMediaURL;
        }
        $instaData['status'] = true;
        $instaData['images'] = $instaImages;
        $this->getResponse()->setBody(json_encode($instaData));
    }

}
