<?php
namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod\Edit\Tab;

class Areasize extends \Magento\Backend\Block\Widget\Grid\Extended {

    protected $_productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Backend\Helper\Data $backendHelper, 
        \Biztech\PrintingMethods\Model\AreasizeFactory $productFactory, 
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct() {
        parent::_construct();
        $this->setId('areasizeGrid');
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
            $productIds = $this->_getSelectedAreasize();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('areasize_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('areasize_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _getSelectedAreasize() {
        $template_id = $this->getRequest()->getParam('id'); // Used in grid to return selected templates values.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $designs = $objectManager->create('Biztech\PrintingMethods\Model\Printingmethod')->load($template_id)->getPrintingtypeIds();
        return explode(',', $designs);
    }

    public function getSelectedAreasize() {

        $template_id = $this->getRequest()->getParam('id');
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
                        '*/*/byareasizegrid', ['_current' => true]
        );
    }

    protected function _prepareColumns() {

        $this->addColumn(
            'in_products', [
            'type' => 'checkbox',
            'html_name' => 'printing',
            'required' => true,
            'values' => $this->_getSelectedAreasize(),
            'align' => 'center',
            'index' => 'areasize_id',
            'header_css_class' => 'a-center'
                ]
        );

         $this->addColumn('position' ,[
            'header' => __('ID'),
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

        $this->addColumn('areasize_id', [
            'header' => __('Id'),
            'index' => 'areasize_id',
            'align' => 'center',
            'width' => '50px',
            ]);

        $this->addColumn('area_size', [
            'header' => __('Area Size'),
            'index' => 'area_size',
            'align' => 'right',
            'width' => '50px',
        ]);

        $this->addColumn('area_size_start', [
            'header' => __('Area Size Start'),
            'index' => 'area_size_start',
            'align' => 'right',
            'width' => '50px',
        ]);

         $this->addColumn('area_size_end', [
            'header' => __('Area Size End'),
            'index' => 'area_size_end',
            'align' => 'right',
            'width' => '50px',
        ]);

         $this->addColumn('area_price', [
            'header' => __('Area Size Price'),
            'index' => 'area_price',
            'align' => 'right',
            'width' => '50px',
        ]);
        return parent::_prepareColumns();
    }

}
