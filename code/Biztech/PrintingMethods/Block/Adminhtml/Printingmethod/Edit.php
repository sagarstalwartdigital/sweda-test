<?php
namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod;
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_printingmethod';
        $this->_blockGroup = 'Biztech_PrintingMethods';

        parent::_construct();

        
        $this->buttonList->update('save', 'label', __('Save Printing Method'));
        $this->buttonList->update('delete', 'label', __('Delete Printing Method'));
        $this->buttonList->remove('reset');
        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );
    }

   
    public function getHeaderText()
    {
        $item = $this->_coreRegistry->registry('current_biztech_productdesigner_printingmethod');
	
        if ($item->getId()) {
            return __("Edit Item '%1'", $this->escapeHtml($item->getName()));
        } else {
            return __('New Item');
        }
    }
}
