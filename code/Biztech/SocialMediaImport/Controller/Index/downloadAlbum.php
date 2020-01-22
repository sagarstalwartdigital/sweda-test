<?php

namespace Biztech\SocialMediaImport\Controller\Index;

header("Access-Control-Allow-Origin: *");

class downloadAlbum extends \Magento\Framework\App\Action\Action {

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
        $data = json_decode(file_get_contents('php://input'), TRUE);
        // $data = $this->getRequest()->getParams();
        $picture_data = $data['data'];
        $fbData = [];
        $instaImages = [];
        if(isset($data['data']) && count($picture_data['data']) > 0){
            for ($i = 0; $i < count($picture_data['data']); $i++) {
                $imageDataorig_url = $picture_data['data'][$i]['picture'];
                
                $filename_array = explode("/", $imageDataorig_url);
                 $filename_array = explode("?", $filename_array[count($filename_array) - 1]);
                $path = $reader->getAbsolutePath() . '/productdesigner/socialMedia/';
                $socialMediaURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'productdesigner/socialMedia/' . $filename_array[0];
                $fileName = $path . $filename_array[0];
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
            $fbData['status'] = true;
            $fbData['images'] = $instaImages;
            $this->getResponse()->setBody(json_encode($fbData));
        }else{
            $fbData['status'] = true;
            $fbData['images'] = [];
            $this->getResponse()->setBody(json_encode($fbData));
        }
    }

}
