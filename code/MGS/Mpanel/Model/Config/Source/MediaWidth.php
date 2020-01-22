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

class MediaWidth implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 0, 'label' => __('Use Dedault')],
			['value' => 3, 'label' => __('30%')],
			['value' => 4, 'label' => __('40%')],
			['value' => 5, 'label' => __('50%')],
			['value' => 6, 'label' => __('60%')],
			['value' => 7, 'label' => __('70%')],
		];
    }
}
