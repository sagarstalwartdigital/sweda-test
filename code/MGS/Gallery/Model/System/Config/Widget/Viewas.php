<?php

namespace MGS\Gallery\Model\System\Config\Widget;

class Viewas implements \Magento\Framework\Option\ArrayInterface
{
    protected $postModel;

    public function __construct(\MGS\Gallery\Model\Post $postModel)
    {
        $this->postModel = $postModel;
    }

    public function toOptionArray()
    {
        $options = array(
            array(
                'label' => __('Default'),
                'value' => 'default'
            ),
            array(
                'label' => __('Owl Carousel'),
                'value' => 'owl_carousel'
            )
        );
        return $options;
    }
}
