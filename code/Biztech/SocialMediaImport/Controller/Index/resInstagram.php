<?php
namespace Biztech\SocialMediaImport\Controller\Index;
header("Access-Control-Allow-Origin: *");

class resInstagram extends \Magento\Framework\App\Action\Action {


	protected $_config;
	protected $_layout;
	protected $_storeManager;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $config,
		\Magento\Framework\View\LayoutInterface $layout,
		\Magento\Store\Model\StoreManagerInterface $storeManager
	) {
	    $this->_scopeConfig = $context;
	    parent::__construct($context);
	    $this->_config = $config;
	    $this->_layout = $layout;
	    $this->_storeManager = $storeManager;
	}

	public function execute() {
		$code = $this->getRequest()->getParam('code');
		$baseUrl = $this->_storeManager->getStore()->getBaseUrl();
		$instagramResCurlUrl = $this->_url->getUrl('productdesigner/index/resInstagram');
		$instaClientID = $this->_config->getValue('productdesigner/social_media_upload/instagram_clientid');
		$instaClientSecret = $this->_config->getValue('productdesigner/social_media_upload/instagram_clientsecret');
		$apiData = array(
			'client_id' => $instaClientID,
			'client_secret' => $instaClientSecret,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $instagramResCurlUrl,
			'code' => $code,
		);
		$apiHost = 'https://api.instagram.com/oauth/access_token';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiHost);
		curl_setopt($ch, CURLOPT_POST, count($apiData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$jsonData = curl_exec($ch);
		curl_close($ch);
		$accessPkg = (array) json_decode($jsonData);
		if(!empty($accessPkg['access_token'])){
			$accessToken = $accessPkg['access_token'];
			$user = (array) $accessPkg['user'];
			$resultPage = $this->_layout->createBlock('Biztech\SocialMediaImport\Block\SocialMediaImport');
			$data = $resultPage->setData(array("base_url"=>$baseUrl,"insta_code" => $accessToken, "user_id" => $user['id']))->setTemplate('socialmediaimport/social_media/instagram_child.phtml')->toHtml();
			$this->getResponse()->setBody($data);
			
		}
	}

}
