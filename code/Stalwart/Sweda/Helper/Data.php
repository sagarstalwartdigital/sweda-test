<?php

namespace Stalwart\Sweda\Helper;

use Stalwart\Sweda\Model\OrderFactory;
use Stalwart\Sweda\Model\InvoiceFactory;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Setup\Exception;
use Stalwart\Sweda\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Stalwart\Sweda\Model\ResourceModel\Invoice\CollectionFactory as InvoiceCollectionFactory;
use Stalwart\Sweda\Model\ImportJobFactory;
use \Psr\Log\LoggerInterface;
use Stalwart\Sweda\Model\ResourceModel\ImportJob\CollectionFactory as ImportJobCollectionFactory;
use Stalwart\Sweda\Model\ResourceModel\Order as OrderResourceModel;
use Stalwart\Sweda\Model\ResourceModel\Invoice as InvoiceResourceModel;

class Data extends AbstractHelper
{
    private $ftp_server = "77.104.150.230";
    private $ftp_username = "sgtest@riopixel.com";
    private $ftp_password = "sgtest@12345";
    private $customerXmlPath = "/test";

    private $orderResourceModel;
    private $orderFactory;
    private $orderCollectionFactory;

    private $invoiceResourceModel;
    private $invoiceFactory;
    private $invoiceCollectionFactory;
    
    private $importJobFactory;
    private $logger;
    private $importJobCollectionFactory;

    protected $connection;
    protected $resource;

    public function __construct(Context $context,
                                OrderFactory $orderFactory,
                                OrderCollectionFactory $orderCollectionFactory,
                                InvoiceFactory $invoiceFactory,
                                InvoiceCollectionFactory $invoiceCollectionFactory,
                                ImportJobFactory $importJobFactory,
                                ImportJobCollectionFactory $importJobCollectionFactory,
                                OrderResourceModel $orderResourceModel,
                                InvoiceResourceModel $invoiceResourceModel,
                                \Magento\Framework\App\ResourceConnection $resource,
                                LoggerInterface $logger)
    {
        $this->orderResourceModel = $orderResourceModel;
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;

        $this->invoiceResourceModel = $invoiceResourceModel;
        $this->invoiceFactory = $invoiceFactory;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;

        $this->importJobFactory = $importJobFactory;
        $this->importJobCollectionFactory = $importJobCollectionFactory;
        $this->logger = $logger;

        $this->connection = $resource->getConnection();
        $this->resource = $resource;

        parent::__construct($context);
    }

    public function strStartsWith($haystack, $needle, $caseSensitive = true)
    {
        //Get the length of the needle.
        $length = strlen($needle);
        //Get the start of the haystack.
        $startOfHaystack = substr($haystack, 0, $length);
        //If we want our check to be case sensitive.
        if ($caseSensitive) {
            //Strict comparison.
            if ($startOfHaystack === $needle) {
                return true;
            }
        } else {
            //Case insensitive.
            //If the needle and the start of the haystack are the same.
            if (strcasecmp($startOfHaystack, $needle) == 0) {
                return true;
            }
        }
        //No matches. Return FALSE.
        return false;
    }

    public function GetOrderFilesFTP()
    {
        $fileList = [];

        try {
            $conn_id = ftp_connect($this->ftp_server);
            if (!$conn_id) {
                return $fileList;
            }

            $login_result = ftp_login($conn_id, $this->ftp_username, $this->ftp_password);
            if (!$login_result) {
                return $fileList;
            }

            ftp_pasv($conn_id, true);

            $files = ftp_mlsd($conn_id, ".");
            ftp_chdir($conn_id, $this->customerXmlPath);
            $files = ftp_mlsd($conn_id, ".");

            foreach ($files as $file) {

                $file_parts = pathinfo($file['name']);

                if ($this->strStartsWith($file['name'], 'order', false) && $file_parts['extension'] == 'xml') {
                    array_push($fileList, $file['name']);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }


        return $fileList;
    }

    public function GetNewOrderFiles($ftpOrderFiles)
    {
        $newFiles = [];

        try {
            if (count($ftpOrderFiles)) {

                $query = $this->importJobCollectionFactory->create();

                $dbOrderFiles = $query->
                addFieldToSelect(array('file_name'))
                    ->addFieldToFilter('file_name', array('in' => $ftpOrderFiles))
                    ->load()->toArray();

                $existingFileNames = array_column($dbOrderFiles['items'], 'file_name');

                foreach ($ftpOrderFiles as $ftpFile) {
                    if (!in_array($ftpFile, $existingFileNames, true)) {
                        array_push($newFiles, $ftpFile);
                    }
                }

                return $newFiles;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $newFiles;
    }

    public function GetOrderFileContent($ftpFileName)
    {
        $file_handle = null;
        $conn_id = null;

        try {
            $conn_id = ftp_connect($this->ftp_server);
            if (!$conn_id) {
                return null;
            }

            $login_result = ftp_login($conn_id, $this->ftp_username, $this->ftp_password);
            if (!$login_result) {
                return null;
            }

            ftp_pasv($conn_id, true);
            ftp_chdir($conn_id, $this->customerXmlPath);

            $file_handle = fopen('php://temp', 'r+');
            ftp_fget($conn_id, $file_handle, $ftpFileName, FTP_BINARY, 0);
            $fstats = fstat($file_handle);

            fseek($file_handle, 0);
            $contents = fread($file_handle, $fstats['size']);

            fclose($file_handle);
            ftp_close($conn_id);

            return $contents;

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return null;
    }

    public function InsertUpdateOrderfile($fileContent)
    {
        try {

            $orders = simplexml_load_string($fileContent);

            if (count($orders) > 0) {

                $elements = array();

                foreach ($orders as $order) {
                    array_push($elements, (int)$order->ORDER_NUMBER);
                }

                $query = $this->orderCollectionFactory->create();
                $updates = $query->addFieldToSelect(array('order_number'))
                    ->addFieldToFilter('order_number', array('in' => $elements))
                    ->load()->toArray();

                $updates = array_column($updates['items'], 'order_number');
                $inserts = [];

                /*if (count($updates)) {
                    foreach ($elements as $item) {
                        if (!in_array($item, $updates, false)) {
                            array_push($inserts, (int)$item);
                        }
                    }
                } else {
                    $inserts = $elements;
                }*/
                $inserts = $elements;
                

                if (count($inserts)) {

                    echo "Total update : " . count($inserts);

                    $all_files = [];
                    $c = 1;
                    foreach ($orders as $order) {
                        $c++;

                        //continue;
                        $model = [
                            "header_id" => (int)$order->HEADER_ID,
                            "order_number" => (int)$order->ORDER_NUMBER,
                            "customer_id" => (int)$order->CUSTOMER_ID,
                            "ordered_date" => date_format(date_create($order->ORDERED_DATE), "Y-m-d H:i:s"),
                            "booked_date" => date_format(date_create($order->BOOKED_DATE), "Y-m-d H:i:s"),
                            "customer_po_number" => (string)$order->CUST_PO_NUMBER,
                            "flow_status_code" => (string)$order->FLOW_STATUS_CODE,
                            "shipment_priority_code" => (string)$order->SHIPMENT_PRIORITY_CODE,
                            "ship_method_meaning" => (string)$order->SHIP_METHOD_MEANING,
                            "meaning" => (string)$order->MEANING,
                            "freight_account" => (string)$order->FRIEGHT_ACCOUNT,
                            "amount" => (float)$order->Amount,
                            "billing_customer_name" => (string)$order->BILL_TO_CUSTOMER,
                            "billing_address1" => (string)$order->BILL_TO_ADDRESS1,
                            "billing_address2" => (string)$order->BILL_TO_ADDRESS2,
                            "billing_city_state" => (string)$order->BILL_TO_CITY_STATE,
                            "billing_country" => (string)$order->BILL_TO_COUNTRY,
                            "created_at" => gmdate("Y-m-d H:i:s"),
                            "updated_at" => gmdate("Y-m-d H:i:s")
                        ];

                        array_push($all_files, $model);
                    }
                    if (count($all_files)) {
                        $tableName = $this->resource->getTableName("sweda_orders");
                        $result = $this->connection->insertOnDuplicate($tableName, $all_files);
                    }
                }

                if (count($updates)) {
                    echo "Total update : " . count($updates);
                }

            }
        } catch (\Exception $e) {
            var_dump($e);
            $this->logger->error($e->getMessage());
        }
    }



    public function GetInvoiceFilesFTP()
    {
        $fileList = [];

        try {
            $conn_id = ftp_connect($this->ftp_server);
            if (!$conn_id) {
                return $fileList;
            }

            $login_result = ftp_login($conn_id, $this->ftp_username, $this->ftp_password);
            if (!$login_result) {
                return $fileList;
            }

            ftp_pasv($conn_id, true);

            $files = ftp_mlsd($conn_id, ".");
            ftp_chdir($conn_id, $this->customerXmlPath);
            $files = ftp_mlsd($conn_id, ".");

            foreach ($files as $file) {

                $file_parts = pathinfo($file['name']);

                if ($this->strStartsWith($file['name'], 'invoice', false) && $file_parts['extension'] == 'xml') {
                    array_push($fileList, $file['name']);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }


        return $fileList;
    }

    public function GetNewInvoiceFiles($ftpInvoiceFiles)
    {
        $newFiles = [];

        try {
            if (count($ftpInvoiceFiles)) {

                $query = $this->importJobCollectionFactory->create();

                $dbInvoiceFiles = $query->
                addFieldToSelect(array('file_name'))
                    ->addFieldToFilter('file_name', array('in' => $ftpInvoiceFiles))
                    ->load()->toArray();

                $existingFileNames = array_column($dbInvoiceFiles['items'], 'file_name');

                foreach ($ftpInvoiceFiles as $ftpFile) {
                    if (!in_array($ftpFile, $existingFileNames, true)) {
                        array_push($newFiles, $ftpFile);
                    }
                }

                return $newFiles;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $newFiles;
    }

    public function GetInvoiceFileContent($ftpFileName)
    {
        $file_handle = null;
        $conn_id = null;

        try {
            $conn_id = ftp_connect($this->ftp_server);
            if (!$conn_id) {
                return null;
            }

            $login_result = ftp_login($conn_id, $this->ftp_username, $this->ftp_password);
            if (!$login_result) {
                return null;
            }

            ftp_pasv($conn_id, true);
            ftp_chdir($conn_id, $this->customerXmlPath);

            $file_handle = fopen('php://temp', 'r+');
            ftp_fget($conn_id, $file_handle, $ftpFileName, FTP_BINARY, 0);
            $fstats = fstat($file_handle);

            fseek($file_handle, 0);
            $contents = fread($file_handle, $fstats['size']);

            fclose($file_handle);
            ftp_close($conn_id);

            return $contents;

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return null;
    }

    public function InsertUpdateInvoicefile($fileContent)
    {
        try {

            $invoices = simplexml_load_string($fileContent);

            if (count($invoices) > 0) {

                $elements = array();

                foreach ($invoices as $invoice) {
                    array_push($elements, (int)$invoice->TRX_NUMBER);
                }

                $query = $this->invoiceCollectionFactory->create();
                $updates = $query->addFieldToSelect(array('invoice_number'))
                    ->addFieldToFilter('invoice_number', array('in' => $elements))
                    ->load()->toArray();

                $updates = array_column($updates['items'], 'invoice_number');
                $inserts = [];

                /*if (count($updates)) {
                    foreach ($elements as $item) {
                        if (!in_array($item, $updates, false)) {
                            array_push($inserts, (int)$item);
                        }
                    }
                } else {
                    $inserts = $elements;
                }*/
                $inserts = $elements;
                

                if (count($inserts)) {

                    echo "Total update : " . count($inserts);

                    $all_files = [];
                    foreach ($invoices as $invoice) {
                        $totalAmount = 0;
                        foreach($invoice->INVOICE_LINES->INVOICE_LINES_ROW as $invoiceLine)
                        {
                            $totalAmount = $totalAmount+ ((float)$invoiceLine->TOTAL);
                        }
                        
                        
                        //continue;
                        $model = [
                            "invoice_number" => (int)$invoice->TRX_NUMBER,
                            "order_number" => (int)$invoice->ORDER_NUMBER,
                            "customer_id" => (int)$invoice->CUSTOMER_ID,
                            "trx_date" => date_format(date_create($invoice->TRX_DATE), "Y-m-d H:i:s"),
                            "purchase_order" => (string)$invoice->PURCHASE_ORDER,
                            "waybill_number" => (string)$invoice->WAYBILL_NUMBER,
                            "terms" => (string)$invoice->TERMS,
                            "ship_actual_date" => date_format(date_create($invoice->SHIP_DATE_ACTUAL), "Y-m-d H:i:s"),
                            "due_date" => date_format(date_create($invoice->DUE_DATE), "Y-m-d H:i:s"),
                            "ship_via" => (string)$invoice->SHIP_VIA,
                            "total_amount" => $totalAmount,
                            "billing_customer_name" => (string)$invoice->BILL_TO_CUSTOMER,
                            "billing_address1" => (string)$invoice->BILL_TO_ADDRESS1,
                            "billing_address2" => (string)$invoice->BILL_TO_ADDRESS2,
                            "billing_city_state" => (string)$invoice->BILL_TO_CITY_STATE,
                            "billing_country" => (string)$invoice->BILL_TO_COUNTRY,
                            "created_at" => gmdate("Y-m-d H:i:s"),
                            "updated_at" => gmdate("Y-m-d H:i:s")
                        ];

                        array_push($all_files, $model);
                    }
                    if (count($all_files)) {
                        $tableName = $this->resource->getTableName("sweda_invoice");
                        $result = $this->connection->insertOnDuplicate($tableName, $all_files);
                    }
                }

                if (count($updates)) {
                    echo "Total update : " . count($updates);
                }

            }
        } catch (\Exception $e) {
            var_dump($e);
            $this->logger->error($e->getMessage());
        }
    }
}