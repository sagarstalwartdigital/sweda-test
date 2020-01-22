<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\OrderInterface;
use Stalwart\Sweda\Model\ResourceModel;

class Invoice extends AbstractModel implements OrderInterface
{

    protected function _construct()
    {
        $this->_init(ResourceModel\Invoice::class);
    }

    public function getOrderNumber()
    {
        return $this->_getData(self::ORDER_NUMBER);
    }

    public function setOrderNumber($data)
    {
        return $this->setData(self::ORDER_NUMBER, $data);
    }

    public function getHeaderId()
    {
        return $this->_getData(self::HEADER_ID);
    }

    public function setHeaderId($data)
    {
        return $this->setData(self::HEADER_ID, $data);
    }

    public function getOrderedDate()
    {
        return $this->_getData(self::ORDERED_DATE);
    }

    public function setOrderedDate($data)
    {
        return $this->setData(self::ORDERED_DATE, $data);
    }

    public function getBookedDate()
    {
        return $this->_getData(self::BOOKED_DATE);
    }

    public function setBookedDate($data)
    {
        return $this->setData(self::BOOKED_DATE, $data);
    }

    public function getCustomerPoNumber()
    {
        return $this->_getData(self::CUSTOMER_PO_NUMBER);
    }

    public function setCustomerPoNumber($data)
    {
        return $this->setData(self::CUSTOMER_PO_NUMBER, $data);
    }

    public function getFlowStatusCode()
    {
        return $this->_getData(self::FLOW_STATUS_CODE);
    }

    public function setFlowStatusCode($data)
    {
        return $this->setData(self::FLOW_STATUS_CODE, $data);
    }

    public function getShipmentPriorityCode()
    {
        return $this->_getData(self::SHIPMENT_PRIORITY_CODE);
    }

    public function setShipmentPriorityCode($data)
    {
        return $this->setData(self::SHIPMENT_PRIORITY_CODE, $data);
    }

    public function getShipMeanMethod()
    {
        return $this->_getData(self::SHIP_MEAN_METHOD);
    }

    public function setShipMeanMethod($data)
    {
        return $this->setData(self::SHIP_MEAN_METHOD, $data);
    }

    public function getMeaning()
    {
        return $this->_getData(self::MEANING);
    }

    public function setMeaning($data)
    {
        return $this->setData(self::MEANING, $data);
    }

    public function getFreightAccount()
    {
        return $this->_getData(self::FREIGHT_ACCOUNT);
    }

    public function setFreightAccount($data)
    {
        return $this->setData(self::FREIGHT_ACCOUNT, $data);
    }

    public function getAmount()
    {
        return $this->_getData(self::AMOUNT);
    }

    public function setAmount($data)
    {
        return $this->setData(self::AMOUNT, $data);
    }

    public function getBillingCustomerName()
    {
        return $this->_getData(self::BILLING_CUSTOMER_NAME);
    }

    public function setBillingCustomerName($data)
    {
        return $this->setData(self::BILLING_CUSTOMER_NAME, $data);
    }

    public function getBillingAddress1()
    {
        return $this->_getData(self::BILLING_ADDRESS1);
    }

    public function setBillingAddress1($data)
    {
        return $this->setData(self::BILLING_ADDRESS1, $data);
    }

    public function getBillingAddress2()
    {
        return $this->_getData(self::BILLING_ADDRESS2);
    }

    public function setBillingAddress2($data)
    {
        return $this->setData(self::BILLING_ADDRESS2, $data);
    }

    public function getShipmentCityState()
    {
        return $this->_getData(self::SHIPMENT_CITY_STATE);
    }

    public function setShipmentCityState($data)
    {
        return $this->setData(self::SHIPMENT_CITY_STATE, $data);
    }

    public function getShipmentCountry()
    {
        return $this->_getData(self::SHIPMENT_COUNTRY);
    }

    public function setShipmentCountry($data)
    {
        return $this->_getData(self::SHIPMENT_COUNTRY, $data);
    }

    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    public function setCreatedAt($data)
    {
        return $this->setData(self::CREATED_AT, $data);
    }

    public function getUpdatedAt()
    {
        return $this->_getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($data)
    {
        return $this->setData(self::UPDATED_AT, $data);
    }
}