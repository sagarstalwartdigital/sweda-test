<?php


namespace Biztech\AdvancedFonts\Block\Adminhtml\Fonts\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{
    protected $fontsCollection;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Store\Model\System\Store $systemStore, array $data = [], \Biztech\Productdesigner\Model\Mysql4\Fonts\CollectionFactory $fontsCollection
    )
    {
        $this->_systemStore = $systemStore;
        $this->_coreRegistry = $registry;
        $this->fontsCollection = $fontsCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

  
    public function getTabLabel()
    {
        return __('Fonts Information');
    }

    
    public function getTabTitle()
    {
        return __('Fonts Information');
    }

    
    public function canShowTab()
    {
        return true;
    }

    
    public function isHidden()
    {
        return false;
    }

    
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_biztech_productdesigner_fonts');
        $id = $this->getRequest()->getParam('id');
        if($id){
            $model1 = $this->fontsCollection->create()
            ->addFieldToFilter('fonts_id', array('eq' => $id))->getData();
            $fontName = explode("/", $model1[0]['font_file']);
        }
        
        $form = $this->_formFactory->create();

        if ($id)
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __("Edit Font '%1'", $model->getFontLabel())]);
        else
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Add Font')]);
        if ($model->getId()) {
            $fieldset->addField('fonts_id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'font_label', 'text', ['name' => 'font_label', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );
        if ($id) {
            $fieldset->addField(
                'font_file', 'file', ['name' => 'font_file', 'label' => __('Font File'), 'title' => __('Font File'), 'required' => false,'note' => 'Accepted Files : TTF, OTF','after_element_html' => '<span class="hint"> '.$fontName[2].'</span>']
            );
        } else {
            $fieldset->addField(
                'font_file', 'file', ['name' => 'font_file', 'label' => __('Font File'), 'title' => __('Font File'), 'required' => true,'note' => 'Accepted Files : TTF, OTF']
            );
        }
        $fieldset->addField('font_image', 'image', array(
            'label' => __('Font Thumbnail'),
            'required' => false,
            'name' => 'font_image',
            'note' => 'Accepted Files : jpg, jpeg, gif, png'
        ));

        $eventElem = $fieldset->addField(
            'store_id', 'multiselect', [
                'name' => 'stores[]',
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );

        $fieldset->addField(
            'status', 'select', ['name' => 'status', 'label' => __('Status'), 'title' => __('status'), 'values' => array(
                array(
                    'value' => 1,
                    'label' => __('Enabled')
                ),
                array(
                    'value' => 2,
                    'label' => __('Disabled')
                ))
            ]
        );
        $fieldset->addField(
            'position', 'text', ['name' => 'position', 'label' => __('Sort Order'), 'title' => __('Sort Order'), 'required' => false]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
