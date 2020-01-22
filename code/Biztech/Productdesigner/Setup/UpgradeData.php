<?php

namespace Biztech\Productdesigner\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;
   
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);               

		if (version_compare($context->getVersion(), '2.0.1', '<')) {
			$imageEffects = [];
            $imageEffects[] = [
                'effect_name' => 'Grayscale',
                'value' => 0,
                'label' => 'Grayscale',
                'is_filter' => 1
            ];
            $imageEffects[] = [
                'effect_name' => 'Brightness',
                'value' => 0.05,
                'label' => 'Brightness',
                'is_filter' => 0
            ];
            $imageEffects[] = [
                'effect_name' => 'Contrast',
                'value' => 0.5,
                'label' => 'Contrast',
                'is_filter' => 0
            ];
            $imageEffects[] = [
                'effect_name' => 'Sepia',
                'value' => 0,
                'label' => 'Sepia',
                'is_filter' => 1
            ];

            $imageEffects[] = [
                'effect_name' => 'Invert',
                'value' => 0,
                'label' => 'Invert',
                'is_filter' => 1
            ];

            $imageEffects[] = [
                'effect_name' => 'Noise',
                'value' => 700,
                'label' => 'Noise',
                'is_filter' => 0
            ];

            $imageEffects[] = [
                'effect_name' => 'Saturation',
                'value' => 0.7,
                'label' => 'Saturation',
                'is_filter' => 0
            ];

            $imageEffects[] = [
                'effect_name' => 'Vintage',
                'value' => 0,
                'label' => 'vintage',
                'is_filter' => 1
            ];

            $imageEffects[] = [
                'effect_name' => 'Brownie',
                'value' => 0,
                'label' => 'brownie',
                'is_filter' => 1
            ];

	        foreach ($imageEffects as $key => $value) {
                $setup->getConnection()->update(
                    $setup->getTable('productdesigner_imageeffects'),
                    ['is_filter' => $value['is_filter']],
                    ['effect_name = ?' => $value['effect_name']]
                );
	        }
            
        }
	}
}
