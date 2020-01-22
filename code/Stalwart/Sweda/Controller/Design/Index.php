<?php

namespace Stalwart\Sweda\Controller\Design;

use Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Stalwart\Sweda\Model\OrderFactory;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;


use FedEx\RateService\Request;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;


class Index extends Action
{

    protected $productJob;
    protected $productFactory;

    protected $orderFactory;
    protected $orderRepository;

    protected $resultPageFactory;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    )
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $rateRequest = new ComplexType\RateRequest();
        $shippingQty  = $this->getRequest()->getPost('qty');
        $shippingPostcode  = $this->getRequest()->getPost('postcode');
        $shippingSku  = $this->getRequest()->getPost('sku');
        $shippingId  = $this->getRequest()->getPost('id');

        $product_id = $shippingId;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);

        $name = $product->getId();
        $sku = $product->getSku();
        $shipping_qty_in_carton = $product->getShippingQtyInCarton();
        $carton_height = $product->getCartonHeight();
        $carton_length = $product->getCartonLength();
        $carton_weight = $product->getCartonWeight();
        $carton_width = $product->getCartonWidth();
        $fob_country = $product->getResource()->getAttribute('fob_country')->getFrontend()->getValue($product);
        $carton_weight_unit = $product->getResource()->getAttribute('carton_weight_unit')->getFrontend()->getValue($product);
        $carton_unit = $product->getResource()->getAttribute('carton_unit')->getFrontend()->getValue($product);
        $packaging_type = $product->getResource()->getAttribute('packaging_type')->getFrontend()->getValue($product);
        $fob_state = $product->getFobState();
        $fob_zip = $product->getFobZip();

        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $result = [];
        $result['carton_weight'] = $carton_weight;
        $result['carton_length'] = $carton_length;
        $result['carton_width'] = $carton_width;
        $result['carton_height'] = $carton_height;
        $result['name'] = $name;
        $result['sku'] = $sku;
        $result['shipping_qty_in_carton'] = $shipping_qty_in_carton;
        $result['fob_country'] = $fob_country;
        $result['fob_state'] = $fob_state;
        $result['fob_zip'] = $fob_zip;
        $result['carton_weight_unit'] = $carton_weight_unit;
        $result['carton_unit'] = $carton_unit;
        $result['packaging_type'] = $packaging_type;


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
        $rateRequest->RequestedShipment->Shipper->Address->City = 'Memphis';
        $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'TN';
        $rateRequest->RequestedShipment->Shipper->Address->PostalCode = 38115;
        $rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'US';

        //recipient
        $rateRequest->RequestedShipment->Recipient->Address->StreetLines = ['13450 Farmcrest Ct'];
        $rateRequest->RequestedShipment->Recipient->Address->City = 'Herndon';
        $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = 'VA';
        $rateRequest->RequestedShipment->Recipient->Address->PostalCode = 20171;
        $rateRequest->RequestedShipment->Recipient->Address->CountryCode = 'US';

        //shipping charges payment
        $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

        //rate request types
        $rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED, SimpleType\RateRequestType::_LIST];

        $rateRequest->RequestedShipment->PackageCount = 2;

        //create package line items
        $rateRequest->RequestedShipment->RequestedPackageLineItems = [new ComplexType\RequestedPackageLineItem(), new ComplexType\RequestedPackageLineItem()];

        //package 1
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Value = 2;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Units = SimpleType\WeightUnits::_LB;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Length = 10;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Width = 10;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Height = 3;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Units = SimpleType\LinearUnits::_IN;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->GroupPackageCount = 1;

        //package 2
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Weight->Value = 5;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Weight->Units = SimpleType\WeightUnits::_LB;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Dimensions->Length = 20;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Dimensions->Width = 20;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Dimensions->Height = 10;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->Dimensions->Units = SimpleType\LinearUnits::_IN;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[1]->GroupPackageCount = 1;

        $rateServiceRequest = new Request();
        //$rateServiceRequest->getSoapClient()->__setLocation(Request::PRODUCTION_URL); //use production URL

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

        $JsonResponse['data']=$rates;
        $JsonResponse['status']='success';
        $response = $this->resultFactory
            ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
            ->setData($JsonResponse);
        return $response;

    }
}