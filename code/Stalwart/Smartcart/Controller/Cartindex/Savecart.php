<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProTypeModel;
use Magento\Framework\Controller\ResultFactory;

class Savecart extends \Magento\Framework\App\Action\Action
{
    protected $customerSession;
    protected $logger;
    protected $_messageManager;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    private $_configurableProTypeModel;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

	public function __construct(
	    \Magento\Customer\Model\Session $customerSession,
	    \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
	    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
	    \Stalwart\Smartcart\Model\SmartcartFactory $smartcartFactory,
	    \Magento\Framework\Message\ManagerInterface $messageManager,
	    ConfigurableProTypeModel $configurableProTypeModel,
	    \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Framework\App\Action\Context $context

    ) {
    	$this->customerSession = $customerSession;
    	$this->_configurableProTypeModel = $configurableProTypeModel;
    	$this->_smartcartFactory = $smartcartFactory;
    	$this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
    	$this->_messageManager = $messageManager;
    	$this->urlModel = $urlFactory->create();

        parent::__construct($context);
    } 

    public function execute()
    {

    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$jsonResult = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $isAjax = $this->getRequest()->getParam('isajax', false);
        $isProView = $this->getRequest()->getParam('onproview', false);
        if ($isAjax) {

			$currentsmartcartid = $this->getRequest()->getParam('currentsmartcart');

			if ($currentsmartcartid) {

				$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($currentsmartcartid);

				if($smartCartObject && $smartCartObject->getId() && ($smartCartObject->getCustomerId() == $this->customerSession->getCustomer()->getId())) {

					if (sizeof($smartCartObject->getData()) > 0) {

						$currentsmartcartoptions = $this->getRequest()->getParam('currentitemoption');

						$saveItemData = array();

						$saveItemData["smart_cart_id"] = $smartCartObject->getId();

							if ($currentsmartcartoptions) {

								parse_str($currentsmartcartoptions,$myt);
								
								$saveItemData["product_id"] = $myt['product'];

								if (isset($myt['super_attribute']) && !empty($myt['super_attribute'])) {

									$simpleProdcutIdAndOptions["options"] = $myt['super_attribute'];

									foreach ($simpleProdcutIdAndOptions as $attrOpt) {
										foreach ($attrOpt as $attributeId => $attributeValue) {
											$attributeValues[$attributeId] = $attributeValue;
										}
									}
									$product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->getById($myt['product']);
									$assPro = $this->_configurableProTypeModel->getProductByAttributes($attributeValues, $product);
									$assocateProId = $assPro->getEntityId();

									if ($assocateProId && !empty($assocateProId)) {
										$simpleProdcutIdAndOptions["simple_product_id"] = $assocateProId;
									}
									$saveItemData["options"] = json_encode($simpleProdcutIdAndOptions);
								}
							}

						$smartCartItemObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcartitem');
						$smartCartItemObject->setData($saveItemData);
						$smartCartItemObject->save();	
						$this->messageManager->addSuccess(__("Saved Successfully Item in Your Smart Cart."));


						$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($smartCartObject->getId());
						$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
						$smartCartObject->save();


						$smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();


						$miniCartPopupHtml = $resultPage->getLayout()
						->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
						->setTemplate('Stalwart_Smartcart::yoursmartcart.phtml')
						->toHtml();
						$jsonResult->setData(['logged'  => true,'smartCartItemCount' => $smartCartItemCount, 'miniCartPopupHtml' => $miniCartPopupHtml]);
					} else {
						
					}
				}
			} else {
				if ($isProView) {
					$popupHtml = $resultPage->getLayout()
                        ->createBlock('Stalwart\Smartcart\Block\ModalCart')
                        ->setOnProView(1)
                        ->setIsAddProductPopup(1)
                        ->setTemplate('Stalwart_Smartcart::modal_smartcart.phtml')
                        ->toHtml();
        			$jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
				} else {
					$popupHtml = $resultPage->getLayout()
                        ->createBlock('Stalwart\Smartcart\Block\ModalCart')
                        ->setIsAddProductPopup(1)
                        ->setTemplate('Stalwart_Smartcart::modal_smartcart.phtml')
                        ->toHtml();
        			$jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
				}
			}
    	}else {
			if ($this->customerSession->isLoggedIn()) {
				$postedData = $this->getRequest()->getPostValue();
				
				if(empty($postedData))
					$postedData = $this->getRequest()->getParams();



				if (isset($postedData['choosediffcart']) && !empty($postedData['choosediffcart'])) {
					if ($postedData['choosediffcart'] == "yes") {
						
						if(isset($postedData["smart_cart"]) && !empty($postedData["smart_cart"])) {						
							$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($postedData['smart_cart']);
						}
						$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
						$smartCartObject->save();
						$smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();

						
		                $miniCartPopupHtml = $resultPage->getLayout()
		                                ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
		                                ->setTemplate('Stalwart_Smartcart::yoursmartcart.phtml')
		                                ->toHtml();
						$jsonResult->setData(['logged'  => true,'smartCartItemCount' => $smartCartItemCount, 'miniCartPopupHtml' => $miniCartPopupHtml]);
					}
				} elseif (isset($postedData['choosediffcartquickview']) && !empty($postedData['choosediffcartquickview'])) {
					if ($postedData['choosediffcartquickview'] == "yes") {
						if(isset($postedData["smart_cart"]) && !empty($postedData["smart_cart"])) {
							$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($postedData['smart_cart']);
						}
						$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
						$smartCartObject->save();
						$smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();

						
		                $quickViewChangeCartHtml = $resultPage->getLayout()
		                                ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
		                                ->setTemplate('MGS_QuickView::product/view/changesmartcart.phtml')
		                                ->toHtml();
						$jsonResult->setData(['logged'  => true, 'smartCartItemCount' => $smartCartItemCount, 'quickviewchangecarthtml' => $quickViewChangeCartHtml]);
					}
				} else {
					$error = 0;
					if(isset($postedData["smart_cart"]) && !empty($postedData["smart_cart"]))
					{
						$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart');
						$smartCartObject->load($postedData["smart_cart"]);

						if($smartCartObject)
						{
							if($smartCartObject->getCustomerId() != $this->customerSession->getCustomerId())
								$error = 1;
						}else{
							$error = 1;
						}
					}else{
						$saveData = array();
						
						$saveData["customer_id"] = $this->customerSession->getCustomerId();
						$saveData["title"] = (isset($postedData["title"]) && !empty($postedData["title"])) ? $postedData["title"] : $error = 1;

						$recipientData = array();
						if((isset($postedData["recipientname"]) && !empty($postedData["recipientname"])) && (isset($postedData["recipientemail"]) && !empty($postedData["recipientemail"])))
						{
							if(count($postedData["recipientname"]) == count($postedData["recipientemail"]))
							{
								foreach($postedData["recipientemail"] as $key => $value)
								{
									if(!empty($value) && (isset($postedData["recipientname"][$key]) && !empty($postedData["recipientname"][$key])))
										$recipientData[$value]['name'] = $postedData["recipientname"][$key];
								}

							}else{
								$error = 1;
							}
						}
						
						$saveData["recepient_data"] = json_encode($recipientData);

						$saveData["event_name"] = (isset($postedData["event_name"]) && !empty($postedData["event_name"])) ? $postedData["event_name"] : $postedData["event_name"] = NULL;
						$saveData["event_date"] = (isset($postedData["event_date"]) && !empty($postedData["event_date"])) ? $postedData["event_date"] : $postedData["event_date"] = NULL;
						$saveData["description"] = (isset($postedData["description"]) && !empty($postedData["description"])) ? $postedData["description"] : $postedData["description"] = NULL;
					}
					
					if($error == 0)
					{
						if(isset($saveData) && !empty($saveData))
						{
							$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart');

							$smartCartObject->setData($saveData);
							$smartCartObject->save();
							$this->messageManager->addSuccess(__("You Successfully Created Your ".$smartCartObject->getTitle()." Smart Cart."));
						}

						$saveItemData = array();
						if (isset($postedData['smartcartitem']) && !empty($postedData['smartcartitem'])) {
							$saveItemData["smart_cart_id"] = $postedData['smartcartitem'];
						} else {
							$saveItemData["smart_cart_id"] = $smartCartObject->getId();
						}
						if (isset($postedData["productdata"]) && !empty($postedData["productdata"]) || isset($postedData["productid"]) && !empty($postedData["productid"])) {
							if (isset($postedData["productdata"]) && !empty($postedData["productdata"])) {
								parse_str($postedData["productdata"],$myt);

								$saveItemData["product_id"] = $myt['product'];
								if (isset($myt['super_attribute']) && !empty($myt['super_attribute'])) {
									
									$simpleProdcutIdAndOptions["options"] = $myt['super_attribute'];

									foreach ($simpleProdcutIdAndOptions as $attrOpt) {
										foreach ($attrOpt as $attributeId => $attributeValue) {
											$attributeValues[$attributeId] = $attributeValue;
										}
									}
									$product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->getById($myt['product']);
									$assPro = $this->_configurableProTypeModel->getProductByAttributes($attributeValues, $product);
									$assocateProId = $assPro->getEntityId();
				
									if ($assocateProId && !empty($assocateProId)) {
										$simpleProdcutIdAndOptions["simple_product_id"] = $assocateProId;
									}
									$saveItemData["options"] = json_encode($simpleProdcutIdAndOptions);
								}
							}elseif(isset($postedData["productid"]) && !empty($postedData["productid"])) {
								$productId = $saveItemData["product_id"] = str_replace("product-", "", $postedData['productid']);
								if($productId)
								{
									$product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->getById($productId);
									
									if($product->getTypeId() == "configurable")
									{
										$productAttributes = $product->getTypeInstance()->getConfigurableOptions($product);
										foreach ($productAttributes as $attributeid => $attributevalue) {
											foreach ($attributevalue as $attributeoptionskey => $attributeoptionsvalue) {
												$optionsValue = $attributeoptionsvalue['value_index'];
											}
											$productOptions[$attributeid] = $optionsValue;
										}
										$productOption = array('options' => $productOptions);

										foreach ($productOption as $attrOpt) {
											foreach ($attrOpt as $attributeId => $attributeValue) {
												$attributeValues[$attributeId] = $attributeValue;
											}
										}
										$assPro = $this->_configurableProTypeModel->getProductByAttributes($attributeValues, $product);
										$assocateProId = $assPro->getEntityId();
										
										if ($assocateProId && !empty($assocateProId)) {
											$productOption["simple_product_id"] = $assocateProId;
										}
										$saveItemData["options"] = json_encode($productOption);
									}
									
								}
							}
							$saveItemData["usercomments"] =  NULL;

							$smartCartItemObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcartitem');
							$smartCartItemObject->setData($saveItemData);
							$smartCartItemObject->save();	
							$this->messageManager->addSuccess(__("Saved Successfully Item in Your Smart Cart."));
							

							$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($smartCartObject->getId());
							$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
							$smartCartObject->save();

							if (isset($postedData['isproview']) && !empty($postedData['isproview'])) {
								$smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();

								
				                $miniCartPopupHtml = $resultPage->getLayout()
				                                ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
				                                ->setTemplate('Stalwart_Smartcart::yoursmartcart.phtml')
				                                ->toHtml();

				                $addtoSmartBtnHtml = $resultPage->getLayout()
				                                ->createBlock('Magento\Catalog\Block\Product\View')
				                                ->setTemplate('product/view/addtosmartcartbtn.phtml')
				                                ->toHtml();

								$changeSmartCartBtnHtml = $resultPage->getLayout()
				                                ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
				                                ->setTemplate('MGS_QuickView::product/view/changesmartcart.phtml')
				                                ->toHtml();				                                

								$jsonResult->setData(['logged'  => true,'smartCartItemCount' => $smartCartItemCount, 'miniCartPopupHtml' => $miniCartPopupHtml, 'addtoSmartBtnHtml' => $addtoSmartBtnHtml, 'changeSmartCartBtnHtml' => $changeSmartCartBtnHtml ]);
							} else {
								$smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();

								
				                $miniCartPopupHtml = $resultPage->getLayout()
				                                ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
				                                ->setTemplate('Stalwart_Smartcart::yoursmartcart.phtml')
				                                ->toHtml();
								$jsonResult->setData(['logged'  => true,'smartCartItemCount' => $smartCartItemCount, 'miniCartPopupHtml' => $miniCartPopupHtml]);
							}
						}

					}else{
						$redirectReferer = $this->_redirect->getRefererUrl();
			            if($redirectReferer)
			                $redirectReferer = $this->urlModel->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
			            else
			                $redirectReferer = $this->urlModel->getUrl('customer/account/login');
			            $jsonResult->setData(['logged'  => false, "redirectUrl" => $redirectReferer]);
					}
				}
			}else{
				$redirectReferer = $this->_redirect->getRefererUrl();
	            if($redirectReferer)
	                $redirectReferer = $this->urlModel->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
	            else
	                $redirectReferer = $this->urlModel->getUrl('customer/account/login');
	            $jsonResult->setData(['logged'  => false, "redirectUrl" => $redirectReferer]);
			}
		}
		return $jsonResult;
		exit;
    }
}	
