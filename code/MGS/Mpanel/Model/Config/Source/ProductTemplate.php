<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace MGS\Mpanel\Model\Config\Source;

class ProductTemplate implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 1, 'label' => __('Background Expansion & Full Width')],
			['value' => 2, 'label' => __('Background Expansion')],
			['value' => 3, 'label' => __('Background Boxed')]
		];
    }
}
