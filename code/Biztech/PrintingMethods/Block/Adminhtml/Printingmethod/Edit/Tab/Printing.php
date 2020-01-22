<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod\Edit\Tab;

class Printing extends \Magento\Backend\Block\Widget\Grid\Extended {

    protected $_productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Backend\Helper\Data $backendHelper, 
        \Biztech\PrintingMethods\Model\ColorsFactory $productFactory, 
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct() {
        parent::_construct();
        $this->setId('printingGrid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {

        $collection = $this->_productFactory->create()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedColors();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('colors_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('colors_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _getSelectedColors() {
        $template_id = $this->getRequest()->getParam('id'); // Used in grid to return selected templates values.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $designs = $objectManager->create('Biztech\PrintingMethods\Model\Printingmethod')->load($template_id)->getPrintingtypeIds();
        return explode(',', $designs);
    }

    public function getSelectedColors() {

        $template_id = $this->getRequest()->getParam('id');
        // if you save product id in your custom table 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $designs = $objectManager->create('Biztech\PrintingMethods\Model\Printingmethod')->load($template_id)->getPrintingtypeIds();
        $ids = explode(',', $designs);
        $custIds = array();

        foreach ($ids as $cust) {
            $custIds[$cust] = array('position' => $cust);
        }

        return $custIds;
    }

    public function getGridUrl() {
        return $this->_getData(
                        'grid_url'
                ) ? $this->_getData(
                        'grid_url'
                ) : $this->getUrl(
                        '*/*/bycolorquantitygrid', ['_current' => true]
        );
    }

    protected function _prepareColumns() {

        $this->addColumn('in_products', [
            'type' => 'checkbox',
            'html_name' => 'printing',
            'required' => true,
            'values' => $this->_getSelectedColors(),
            'align' => 'center',
            'index' => 'colors_id',
            'header_css_class' => 'a-center'
        ]);

         $this->addColumn('position' ,['header' => __('ID'),
            'name' => 'position',
            'width' => 60,
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'editable' => true,
            'edit_only' => true,
            'column_css_class' => 'no-display',
            'header_css_class' => 'no-display',
        ]);

        $this->addColumn('colors_id', [
            'header' => __('Id'),
            'index' => 'colors_id',
            'align' => 'center',
            'width' => '50px',
        ]);

         $this->addColumn('colors_counter', [
            'header' => __('Color Count'),
            'index' => 'colors_counter',
            'align' => 'right',
            'width' => '50px',
        ]);


         $this->addColumn('colors_price', [
            'header' => __('Color Price'),
            'index' => 'colors_price',
            'align' => 'right',
            'width' => '50px',
            
        ]);
        return parent::_prepareColumns();
    }

}
