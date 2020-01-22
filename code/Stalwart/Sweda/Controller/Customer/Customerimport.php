<?php

namespace Stalwart\Sweda\Controller\Customer;


class Customerimport extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    protected $customerRepository;

    protected $encryptor;

    protected $customerRegistry;


    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,

        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry
    ) {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;

        $this->customerRepository = $customerRepository;
        $this->encryptor          = $encryptor;
        $this->customerRegistry   = $customerRegistry;

        parent::__construct($context);
    }



    public function execute()
    {


        $customer = simplexml_load_file("http://swedausa.local/media/customerimport/customerimport.xml") or die("Failed to load");
        $totalCustomer = count($customer);

       

        echo "<h1>Total Customer counts:" . $totalCustomer . "</h1>";

        echo "<table BORDER='1'>";

        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();

        $storeId  = $this->storeManager->getStore()->getId();

        foreach ($customer as $customerdata) {

            $customer   = $this->customerFactory->create();

            $customer->setWebsiteId($websiteId);
            $customer->setStoreId($storeId);

            $customerSecure->setPasswordHash("admin@123");

            $customer->addData([
                // "entity_id" => $customerdata->ID,
                // "firstname" => $customerdata->Name,
                // "email" => $customerdata->Email,
                // "group_id" => $customerdata->Group,
                // "telephone" => $customerdata->Phone,
                // "postcode" => $customerdata->ZIP,
                // "country_id" => $customerdata->Country,
                // "region" => $customerdata->StateProvince,
                // "confirmation" => $customerdata->Confirmed_Email,
                // "created_at" => $customerdata->Account_Created_in,
                // "default_billing" => $customerdata->Billing_Address,
                // "default_shipping" => $customerdata->Shipping_Address,
                // "dob" => $customerdata->Date_of_Birth,
                // "taxvat" => $customerdata->Tax_VAT_Number,
                // "gender" => $customerdata->Gender,
                // "street" => $customerdata->Street_Address,
                // "city" => $customerdata->City,
                // "fax" => $customerdata->Fax,
                // "vat_id" => $customerdata->VAT_Number,
                // "company" => $customerdata->Company,
                // "lock_expires" => $customerdata->Account_Lock


                "firstname" => "ppp",
                "lastname" => "ppp Last Name",
                "email" => "ppp@gmail.com",
                "group_id" => "1",
                "telephone" => "9908809999",
                "postcode" => "365560",
                "country_id" => "US",
                "region" => "Michigan",
                "confirmation" =>"Confirmation Not Required",
                // "created_at" => "Default Store View",
                "default_billing" => "6146 Honey Bluff Parkway Calder Michigan 49628-7978",
                "default_shipping" => "6146 Honey Bluff Parkway Calder Michigan 49628-7978",
                "dob" => "1973-12-15",
                "taxvat" => "500",
                "gender" => "Female",
                "street" => "6146 Honey Bluff Parkway",
                "city" => "Rajula",
                "fax" => "123",
                "vat_id" => "555",
                "company" => "dev company",
                "billing_telephone" => "9999988888",
                "created_at" => gmdate("Y-m-d H:i:s"),
                "updated_at" => gmdate("Y-m-d H:i:s"),
                // "password_hash" => "10032586bc62852d2a7ed121da58d05f",
                "lock_expires" => ""

            ]);

            $saveData = $customer->save();

            if ($saveData) {
                //$this->messageManager->addSuccess(__('Insert Record Successfully !'));
                echo $customerdata->ID.' = Insert Record Successfully !';
            }

        }

        echo "</table>";

    }

}
