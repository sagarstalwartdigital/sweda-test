<?php
namespace Stalwart\Bloglist\Block\Adminhtml\Bloglist;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Stalwart\Bloglist\Model\bloglistFactory
     */
    protected $_bloglistFactory;

    /**
     * @var \Stalwart\Bloglist\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Stalwart\Bloglist\Model\bloglistFactory $bloglistFactory
     * @param \Stalwart\Bloglist\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Stalwart\Bloglist\Model\BloglistFactory $BloglistFactory,
        \Stalwart\Bloglist\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_bloglistFactory = $BloglistFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('stalwartbloglist_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_bloglistFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'stalwartbloglist_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'stalwartbloglist_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		$this->addColumn(
			'bloglist_name',
			[
				'header' => __('Name'),
				'index' => 'bloglist_name',
			]
		);
		
		$this->addColumn(
			'bloglist_description',
			[
				'header' => __('Description'),
				'index' => 'bloglist_description',
			]
		);			
		
        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'stalwartbloglist_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
		

		
		   $this->addExportType($this->getUrl('bloglist/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('bloglist/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('stalwartbloglist_id');
        //$this->getMassactionBlock()->setTemplate('Stalwart_Bloglist::bloglist/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('bloglist');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('bloglist/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('bloglist/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );


        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('bloglist/*/index', ['_current' => true]);
    }

    /**
     * @param \Stalwart\Bloglist\Model\bloglist|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'bloglist/*/edit',
            ['stalwartbloglist_id' => $row->getId()]
        );
		
    }

	

}