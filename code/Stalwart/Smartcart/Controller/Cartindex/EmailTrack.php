<?php

namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Stalwart\Smartcart\Model\SmartcartFactory;

class EmailTrack extends Action
{
	/**
     * @var Session
     */
    protected $session;

	public function __construct(
        Context $context, 
        Session $customerSession, 
        SmartcartFactory $smartcartFactory
    )
    {
        $this->customerSession = $customerSession;
        $this->_smartcartFactory = $smartcartFactory;
 
        parent::__construct($context);
    }

    public function execute()
    {

    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        $toTrack = $this->getRequest()->getParam('totrack','');
        $toTrackData = base64_decode(urldecode($toTrack));
        $paramStrToArray = explode("&",$toTrackData);
       
        $paramArray = array();
        foreach ($paramStrToArray as $key => $value) {
            list($paramLabel, $paramValue) = explode("=",$value);
            $paramArray[$paramLabel] = $paramValue;
        }

        
		if(
            ($paramArray['mailid'] && !empty($paramArray['mailid'])) && 
            ($paramArray['smart_cart_id'] && !empty($paramArray['smart_cart_id'])) && 
            ($paramArray['cus_id'] && !empty($paramArray['cus_id']))
        ) {
			
			$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($paramArray['smart_cart_id']);
            if($smartCartObject && $smartCartObject->getId())
            {
                $allRecipientsJsonString = $smartCartObject->getRecepientData();
                if($allRecipientsJsonString && $this->isJson($allRecipientsJsonString))
                {
                    $allRecipients = json_decode($allRecipientsJsonString, true);
                    if(isset($allRecipients[$paramArray['mailid']]))
                    {
                        $allRecipients[$paramArray['mailid']]['mailopened'] = 1;
                        $smartCartObject->setRecepientData(json_encode($allRecipients));
                        $smartCartObject->save();
                    }
                }
            }
		}
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}