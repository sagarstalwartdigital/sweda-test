<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Magemobcart\Block\Adminhtml\Notification;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $notificationCollection;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Biztech\Magemobcart\Model\ResourceModel\Notification\Collection $notificationCollection,
        array $data = []
    ) {
        $this->_notificationCollection = $notificationCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setId('inventorysystemGrid');
        $this->setDefaultSort('notification_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection
     * @return $this
     */
    protected function _prepareCollection()
    {
        try {
            $collection = $this->_notificationCollection;
            $this->setCollection($collection);
            parent::_prepareCollection();
            return $this;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Prepare columns
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'notification_id',
            [
            'header' => __('ID'),
            'index' => 'notification_id',
            'class' => 'entity_id',
            ]
        );
        $this->addColumn(
            'title',
            [
            'header' => __('Title'),
            'index' => 'title',
            'class' => 'entity_id'
                ]
        );
        $this->addColumn(
            'is_sent',
            [
            'header' => __('Send ?'),
            'class' => 'entity_id',
            'index'     => 'is_sent',
            'type'      => 'options',
            'options'   => array(
                1 => 'Yes',
                2 => 'No',
            )
                ]
        );
        $this->addColumn(
            'action',
            [
            'header' => __('Action'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => [
                [
                    'caption' => __('Edit'),
                    'url' => ['base' => '*/*/edit'],
                    'field' => 'id',
                ]
            ],
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'header_css_class' => 'col-action',
            'column_css_class' => 'col-action',
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('notification_id');
        $this->getMassactionBlock()->setFormFieldName('notification');

        /* on select of this massaction it will set the values from js */
        
        $this->getMassactionBlock()->addItem('remove', [
            'label' => __('Delete'),
            'url' => $this->getUrl('*/*/massdelete', ['_current' => true]),
            'confirm' => __('Are you sure want to remove selected slide/s?')
        ]);
        return $this;
    }
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'magemobcart/*/edit',
            ['id' => $row->getId()]
        );
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}