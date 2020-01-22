<?php

namespace Stalwart\Sweda\Controller\Orderline;

use Magento\Framework\App\Action\Action;
use Stalwart\Sweda\Api\Data\OrderlineInterface;
use Stalwart\Sweda\Model\OrderlineFactory;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Filesystem\DirectoryList;
use \Stalwart\Sweda\Helper\Data;
use Stalwart\Sweda\Api\OrderlineRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Controller\Result\Raw as RawResult;
use Magento\Framework\Controller\ResultFactory;

class Orderline extends Action
{
    private $storeManager;
    private $directoryList;
    private $swedaHelper;
    private $orderlineRepository;
    protected $searchCriteriaBuilder;
    private $filterBuilder;
    private $OrderlineFactory;


    private $pageResultFactory;

    public function __construct(Context $context,
                                OrderlineFactory $OrderlineFactory,
                                StoreManagerInterface $storeManager,
                                DirectoryList $directoryList,
                                OrderlineFactory $orderlineFactory,
                                OrderlineRepositoryInterface $orderlineRepository,
                                Data $swedaHelper,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                FilterBuilder $filterBuilder,
                                ResultFactory $pageResultFactory,
                                array $data = [])
    {
        $this->OrderlineFactory = $OrderlineFactory;
        $this->orderlineRepository = $orderlineRepository;
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->swedaHelper = $swedaHelper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->pageResultFactory = $pageResultFactory;
        parent::__construct($context);
    }

    public function execute()
    {

            $orders = simplexml_load_file("http://swedausa.local/media/xml_files/customer/ORDERS_20181003_161951.xml") or die("Failed to load");
            $totalOrders = count($orders);
            echo "<h1>Total counts:" . $totalOrders . "</h1>";

            echo "<table BORDER='1'>";


            foreach ($orders as $order) {

                $model = $this->_objectManager->create('Stalwart\Sweda\Model\Orderline');

               // echo '<pre>'; print_r($order); echo '</pre>';

                $model->setData([
                    "id" => '',
                    "sku" => '1111',
                    "sku_description" => 'sku description',
                    "order_quantity_uom" => '50',
                    "ordered_quantity" => '20',
                    "shipped_quantity" => '60',
                    "unit_selling_price" => '500',
                    "schedule_ship_date" => 'Jun 7, 2019 2:34:43 PM',
                    "line_status" => 'Line Status',
                    "created_at" => 'Jun 7, 2019 2:34:43 PM',
                    "updated_at" => 'Jun 7, 2019 2:34:43 PM'
                ]);

                // $model->setData();

                $saveData = $model->save();

                if ($saveData) {
                    //$this->messageManager->addSuccess(__('Insert Record Successfully !'));
                    echo $order->HEADER_ID . ' = Insert Record Successfully !';
                }
            }    

          echo "</table>";

    }

    private function createRawResultPage(string $content): RawResult
    {
        $page = $this->pageResultFactory->create(ResultFactory::TYPE_RAW);
        $page->setHeader('Content-Type', 'text/plain');
        $page->setContents($content);
        return $page;
    }
}