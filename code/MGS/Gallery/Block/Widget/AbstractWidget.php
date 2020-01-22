<?php

namespace MGS\Gallery\Block\Widget;

class AbstractWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_galleryHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MGS\Gallery\Helper\Data $galleryHelper,
        array $data = []
    )
    {
        $this->_galleryHelper = $galleryHelper;
        parent::__construct($context, $data);
    }

    public function getConfig($key, $default = '')
    {
        if ($this->hasData($key)) {
            return $this->getData($key);
        }
        return $default;
    }
}
