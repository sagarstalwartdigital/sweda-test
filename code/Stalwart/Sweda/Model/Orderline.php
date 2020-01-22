<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\OrderlineInterface;
use Stalwart\Sweda\Model\ResourceModel;

class Orderline extends AbstractModel implements OrderlineInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Orderline::class);
    }

    public function getSku()
    {
        return $this->_getData(self::SKU);
    }

    public function setSku()
    {
        return $this->setData(self::SKU);
    }

    public function getSkuDescription()
    {
        return $this->_getData(self::SKU_DESCRIPTION);
    }

    public function setSkuDescription($data)
    {
        return $this->setData(self::SKU_DESCRIPTION);
    }

    public function getOrderQuantityUom()
    {
        return $this->_getData(self::ORDER_QUANTITY_UOM);
    }

    public function setOrderQuantityUom($data)
    {
        return $this->setData(self::ORDER_QUANTITY_UOM);
    }

    public function getOrderedQuantity()
    {
        return $this->_getData(self::ORDERED_QUANTITY);
    }

    public function setOrderedQuantity($data)
    {
        return $this->setData(self::ORDERED_QUANTITY);
    }

    public function getShippedQuantity()
    {
        return $this->_getData(self::SHIPPED_QUANTITY);
    }

    public function setShippedQuantity($data)
    {
        return $this->setData(self::SHIPPED_QUANTITY);
    }

    public function getUnitSellingPrice()
    {
        return $this->_getData(self::UNIT_SELLING_PRICE);
    }

    public function setUnitSellingPrice($data)
    {
        return $this->setData(self::UNIT_SELLING_PRICE);
    }

    public function getScheduleShipDate()
    {
        return $this->_getData(self::SCHEDULE_SHIP_DATE);
    }

    public function setScheduleShipDate($data)
    {
        return $this->setData(self::SCHEDULE_SHIP_DATE);
    }

    public function getLineStatus()
    {
        return $this->_getData(self::LINE_STATUS);
    }

    public function setLineStatus($data)
    {
        return $this->setData(self::LINE_STATUS);
    }

    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    public function setCreatedAt($data)
    {
        return $this->setData(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->_getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($data)
    {
        return $this->setData(self::UPDATED_AT);
    }
}