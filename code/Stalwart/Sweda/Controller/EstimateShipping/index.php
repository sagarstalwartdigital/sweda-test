<?php

namespace Stalwart\Sweda\Controller\EstimateShipping;

use Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use FedEx\RateService\Request;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;


class Index extends Action
{
   
    protected $resultPageFactory;
    protected $resultJsonFactory;

    /**
    * @var Session
    */
    protected $session;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        JsonFactory $resultJsonFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $customerSession;
        $this->stockItem = $stockItem;
        parent::__construct($context);
    }

    public function execute()
    {

        $jsonResult = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $isEstimator = $this->getRequest()->getParam('estimator',false);

        $proId = $this->getRequest()->getParam('proid');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $JsonResponse = [];

        if ($isEstimator) {
            $rateRequest = new ComplexType\RateRequest();

            $shippingQty  = $this->getRequest()->getPost('qty');
            $shippingPostcode  = $this->getRequest()->getPost('postcode');
            $productSku  = $this->getRequest()->getPost('sku');
            $product_id  = $this->getRequest()->getPost('id');
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);

            $productShippingData = $objectManager->create('Stalwart\Productmanager\Model\Swedaproductshipping')->getCollection()->addFieldToFilter('pid',$product_id);

            foreach ($productShippingData as $productShippingInfo) {
                $carton_height = $productShippingInfo['carton_height'];
                $carton_length = $productShippingInfo['carton_length'];
                $carton_weight = $productShippingInfo['carton_weight'];
                $carton_width = $productShippingInfo['carton_width'];
                $fob_state = $productShippingInfo['state'];
                $fob_zip = $productShippingInfo['zip'];
            }
            
            $name = $product->getId();
            $sku = $product->getSku();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $UserKey = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('shipping_estimator/general/user_credential_key');
            $UserPassword = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('shipping_estimator/general/user_credential_password');
            $UserAccountNumber = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('shipping_estimator/general/user_account_number');
            $UserMeterNumber = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('shipping_estimator/general/user_meter_number');


            //authentication & client details
            $rateRequest->WebAuthenticationDetail->UserCredential->Key = $UserKey;
            $rateRequest->WebAuthenticationDetail->UserCredential->Password = $UserPassword;
            $rateRequest->ClientDetail->AccountNumber = $UserAccountNumber;
            $rateRequest->ClientDetail->MeterNumber = $UserMeterNumber;

            $rateRequest->TransactionDetail->CustomerTransactionId = 'testing rate service request';

            //version
            $rateRequest->Version->ServiceId = 'crs';
            $rateRequest->Version->Major = 24;
            $rateRequest->Version->Minor = 0;
            $rateRequest->Version->Intermediate = 0;

            $rateRequest->ReturnTransitAndCommit = true;

            //shipper
            $rateRequest->RequestedShipment->PreferredCurrency = 'USD';
            $rateRequest->RequestedShipment->Shipper->Address->StreetLines = ['10 Fed Ex Pkwy'];
            $rateRequest->RequestedShipment->Shipper->Address->City = 'City of Industry';
            $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'CA';
            $rateRequest->RequestedShipment->Shipper->Address->PostalCode = 91744;
            $rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'US';

            // //recipient
            // $rateRequest->RequestedShipment->Recipient->Address->StreetLines = ['13450 Farmcrest Ct'];
            // $rateRequest->RequestedShipment->Recipient->Address->City = 'Herndon';
            // $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = 'VA';
            $rateRequest->RequestedShipment->Recipient->Address->PostalCode = $shippingPostcode;
            $rateRequest->RequestedShipment->Recipient->Address->CountryCode = 'US';

            //shipping charges payment
            $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

            //rate request types
            $rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED, SimpleType\RateRequestType::_LIST];

            $rateRequest->RequestedShipment->PackageCount = 1;

            //create package line items
            $rateRequest->RequestedShipment->RequestedPackageLineItems = [new ComplexType\RequestedPackageLineItem()];


            //package 1
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Value = $carton_weight;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Units = SimpleType\WeightUnits::_LB;

            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Length = $carton_length;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Width = $carton_width;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Height = $carton_height;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Units = SimpleType\LinearUnits::_IN;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->GroupPackageCount = 1;


            $rateServiceRequest = new Request();
            // $rateServiceRequest->getSoapClient()->__setLocation(Request::PRODUCTION_URL); //use production URL

            $rateReply = $rateServiceRequest->getGetRatesReply($rateRequest); // send true as the 2nd argument to return the SoapClient's 

            $JsonResponse = [];
            $rates = [];
            if (!empty($rateReply->RateReplyDetails)) {
                foreach ($rateReply->RateReplyDetails as $rateReplyDetail) {
                    //echo $rateReplyDetail->ServiceType;
                    $firstEle = $rateReplyDetail->RatedShipmentDetails[0];
                    //echo $firstEle->ShipmentRateDetail->RateType;
                    //echo $firstEle->ShipmentRateDetail->TotalNetCharge->Amount;

                    $Service = $rateReplyDetail->ServiceType;
                    $Amount = $firstEle->ShipmentRateDetail->TotalNetCharge->Amount;

                    $rate['service'] = $Service;
                    $rate['amount'] = $Amount;

                    array_push($rates, $rate);
                }
            }
            $popupHtml = $resultPage->getLayout()
                                    ->createBlock('Stalwart\Sweda\Block\EstimateShipping')
                                    ->setTemplate('Stalwart_Sweda::product/view/shipping-rates.phtml')
                                    ->setRates($rates)
                                    ->setQty($shippingQty)
                                    ->setProId($product_id)
                                    ->toHtml();
        } else {
            $popupHtml = $resultPage->getLayout()
                                    ->createBlock('Stalwart\Sweda\Block\EstimateShipping')
                                    ->setTemplate('Stalwart_Sweda::product/view/shipping-estimate.phtml')
                                    ->setProductId($proId)
                                    ->toHtml();
        }

        $JsonResponse['popuphtml'] = $popupHtml;

        $JsonResponse['status']='success';

        

        $response = $jsonResult->setData($JsonResponse);
        return $response;
    }

}