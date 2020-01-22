<?php

namespace Biztech\PrintingMethods\Model\Entity\Attribute\Source;

class Printingmethodattr extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource {

    protected $request;
    protected $_options = [];

    public function __construct(
    \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    public function getAllOptions() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $printmethods = $objectManager->create('Biztech\PrintingMethods\Model\Mysql4\Printingmethod\Collection')->addFieldToFilter('status', 1);
        foreach ($printmethods as $printmethod) {
            $methodid = $printmethod->getPrintingId();
            $methodname = $printmethod->getPrintingName();
            $methoddata[] = array('label' => "$methodname", 'value' => "$methodid");
            $this->_options[] = array('label' => "$methodname", 'value' => "$methodid");
        }
        $options = $this->_options;
        return $options;
    }

    public function getOptionArray() {
        $_options = array();
        foreach ($this->getAllOptions() as $option) {
            $_options[$option['value']] = $option['label'];
        }
        return $_options;
    }
    public function getOptionText($value) {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

}
