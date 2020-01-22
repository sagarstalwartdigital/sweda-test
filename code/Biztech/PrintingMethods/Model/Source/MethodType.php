<?php

namespace Biztech\PrintingMethods\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class MethodType extends AbstractSource implements SourceInterface, OptionSourceInterface
{
   
    const BY_COLOR_QUANTITY = 1;

    const BY_AREA_RANGE = 2;

    
    public static function getOptionArray()
    {
        return [self::BY_COLOR_QUANTITY => __('By Color Quantity'), self::BY_AREA_RANGE => __('By Area Range')];
    }
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}