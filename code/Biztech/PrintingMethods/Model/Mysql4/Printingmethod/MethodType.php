<?php

namespace Biztech\PrintingMethods\Model\Mysql4\Printingmethod;

use Magento\Framework\Option\ArrayInterface;

class MethodType implements ArrayInterface
{

    public function toOptionArray($select = false)
    {
    	$options = [];
    	if ($select === true) {
    		$options = [
    			'' => __('Please Select Type'),
	            1 => __('By Color Quantity'),
	            2 => __('By Area Range')
    		];
    	} else {
	        $options = [
	            1 => __('By Color Quantity'),
	            2 => __('By Area Range')
	        ];
    	}


        return $options;
    }


}
