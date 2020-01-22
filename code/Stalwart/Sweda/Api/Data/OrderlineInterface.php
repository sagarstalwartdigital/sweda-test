<?php


namespace Stalwart\Sweda\Api\Data;

interface OrderlineInterface
{

    const ID = 'id';
    const SKU = 'sku';
    const SKU_DESCRIPTION = 'sku_description';
    const ORDER_QUANTITY_UOM = 'order_quantity_uom';
    const ORDERED_QUANTITY = 'ordered_quantity';
    const SHIPPED_QUANTITY = 'shipped_quantity';
    const UNIT_SELLING_PRICE = 'unit_selling_price';
    const SCHEDULE_SHIP_DATE = 'schedule_ship_date';
    const LINE_STATUS = 'line_status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    // Id
    public function getId();
    public function setId($data);

    // Sku
    public function getSku();
    public function setSku();

    // Sku Description
    public function getSkuDescription();
    public function setSkuDescription($data);


    // Order Quantity Uom
    public function getOrderQuantityUom();
    public function setOrderQuantityUom($data);


    // Ordered Quantity
    public function getOrderedQuantity();
    public function setOrderedQuantity($data);


    // Shipped Quantity
    public function getShippedQuantity();
    public function setShippedQuantity($data);


    // Unit Selling Price
    public function getUnitSellingPrice();
    public function setUnitSellingPrice($data);


    // Schedule Ship Date
    public function getScheduleShipDate();
    public function setScheduleShipDate($data);


    // Line Status
    public function getLineStatus();
    public function setLineStatus($data);


    // Created At
    public function getCreatedAt();
    public function setCreatedAt($data);


    // Updated At
    public function getUpdatedAt();
    public function setUpdatedAt($data);

}