<?php

namespace Biztech\PrintingMethods\Ui\Component\Listing\Column;

class MethodTypeColumn extends \Magento\Ui\Component\Listing\Columns\Column
{
   
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource) {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if($item['method_type'] == '1'){
                    $item['method_type'] = 'By Color Quantity';
                }else{
                    $item['method_type'] = 'By Area Range';
                }
            }
        }
        return $dataSource;
    }
}