<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProTypeModel;
use Magento\Framework\Controller\ResultFactory;

class Duplicatecart extends \Magento\Framework\App\Action\Action
{
    protected $customerSession;
    protected $logger;
    protected $_messageManager;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    private $_configurableProTypeModel;

	public function __construct(
	    \Magento\Customer\Model\Session $customerSession,
	    \Stalwart\Smartcart\Model\SmartcartFactory $smartcartFactory,
	    \Magento\Framework\Message\ManagerInterface $messageManager,
	    ConfigurableProTypeModel $configurableProTypeModel,
	    \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Framework\App\Action\Context $context

    ) {
    	$this->customerSession = $customerSession;
    	$this->_configurableProTypeModel = $configurableProTypeModel;
    	$this->_smartcartFactory = $smartcartFactory;
    	$this->_messageManager = $messageManager;
    	$this->urlModel = $urlFactory->create();

        parent::__construct($context);
    } 

    public function execute()
    {
    	$isError = 1;
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		if ($this->customerSession->isLoggedIn())
		{
			$postedData = $this->getRequest()->getPostValue();
			if(empty($postedData))
				$postedData = $this->getRequest()->getParams();
			
			if(isset($postedData["id"]) && !empty($postedData["id"])) {
				$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($postedData['id']);
				if($smartCartObject)
				{
					if($smartCartObject->getCustomerId() == $this->customerSession->getCustomerId())
					{
						$dataToBeDuplicated = $smartCartObject->getData();
						$plainCurrentTitle = explode("(copy", $dataToBeDuplicated["title"]); 
						$plainCurrentTitle = $plainCurrentTitle[0];
						$collection = $this->_smartcartFactory->create()->getCollection()->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId());
						$alreadyCopyCounts = 0;
						foreach ($collection as $sc) {
							if(strpos($sc->getTitle(), trim($plainCurrentTitle)) !== false){
								$alreadyCopyCounts++;
							}
						}
						
						if(!empty($dataToBeDuplicated))
						{
							try{
	    						$isError = 0;
								unset($dataToBeDuplicated["id"]);
								unset($dataToBeDuplicated["created_at"]);
								unset($dataToBeDuplicated["updated_at"]);
								if($alreadyCopyCounts)
									$dataToBeDuplicated["title"] = $plainCurrentTitle." (copy-".$alreadyCopyCounts++.")";
								else
									$dataToBeDuplicated["title"] = $plainCurrentTitle." (copy)";

								$duplicateSmartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart');
								$duplicateSmartCartObject->setData($dataToBeDuplicated)->save();

								foreach($smartCartObject->getItems() as $smartCartItem)
								{
									$itemDataToBeDuplicated = $smartCartItem->getData();
									if(!empty($itemDataToBeDuplicated))
									{
										unset($itemDataToBeDuplicated["id"]);
										unset($itemDataToBeDuplicated["created_at"]);
										unset($itemDataToBeDuplicated["updated_at"]);
										$itemDataToBeDuplicated["smart_cart_id"] = $duplicateSmartCartObject->getId();
										$smartCartItemObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcartitem');
										$smartCartItemObject->setData($itemDataToBeDuplicated);
										$smartCartItemObject->save();
									}
								}
							}catch(Exception $e){
								$isError = 1;
							}
						}
					}
				}
			}
		}
		if($isError)
			$this->messageManager->addError(__("Something went wrong, Please try later."));
		else
			$this->messageManager->addSuccess(__("Duplicate smart cart created."));

		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setPath('smartcart/cartindex/smartcartfront/');
        return $resultRedirect;
		
    }
}	
