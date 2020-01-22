<?php

namespace Stalwart\Sweda\Block\Adminhtml\Order;

class Index extends \Magento\Backend\Block\Widget\Container
{
    public function __construct(\Magento\Backend\Block\Widget\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_headerText = __('Orders');
        $this->_addButtonLabel = __('Create New Order');
    }
}