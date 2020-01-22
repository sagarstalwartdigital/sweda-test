<?php


namespace Stalwart\Sweda\Api\Data;

interface OrderInterface
{
    const ORDER_NUMBER = 'order_number';
    const HEADER_ID = 'header_id';
    const ORDERED_DATE = 'ordered_date';
    const BOOKED_DATE = 'booked_date';
    const CUSTOMER_PO_NUMBER = 'customer_po_number';
    const FLOW_STATUS_CODE = 'flow_status_code';
    const SHIPMENT_PRIORITY_CODE = 'shipment_priority_code';
    const SHIP_MEAN_METHOD = 'ship_mean_method';
    const MEANING = 'meaning';
    const FREIGHT_ACCOUNT = 'freight_account';
    const AMOUNT = 'amount';
    const BILLING_CUSTOMER_NAME = 'billing_customer_name';
    const BILLING_ADDRESS1 = 'billing_address1';
    const BILLING_ADDRESS2 = 'billing_address2';
    const SHIPMENT_CITY_STATE = 'shipment_city_state';
    const SHIPMENT_COUNTRY = 'shipment_country';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getOrderNumber();

    public function setOrderNumber($data);

    public function getHeaderId();

    public function setHeaderId($data);

    public function getOrderedDate();

    public function setOrderedDate($data);

    public function getBookedDate();

    public function setBookedDate($data);

    public function getCustomerPoNumber();

    public function setCustomerPoNumber($data);

    public function getFlowStatusCode();

    public function setFlowStatusCode($data);

    public function getShipmentPriorityCode();

    public function setShipmentPriorityCode($data);

    public function getShipMeanMethod();

    public function setShipMeanMethod($data);

    public function getFreightAccount();

    public function setFreightAccount($data);

    public function getAmount();

    public function setAmount($data);

    public function getBillingCustomerName();

    public function setBillingCustomerName($data);

    public function getBillingAddress1();

    public function setBillingAddress1($data);

    public function getBillingAddress2();

    public function setBillingAddress2($data);

    public function getShipmentCityState();

    public function setShipmentCityState($data);

    public function getShipmentCountry();

    public function setShipmentCountry($data);

    public function getCreatedAt();

    public function setCreatedAt($data);

    public function getUpdatedAt();

    public function setUpdatedAt($data);
}