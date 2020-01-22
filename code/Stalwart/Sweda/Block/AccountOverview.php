<?php
 
namespace Stalwart\Sweda\Block;


use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement; 
use Magento\Customer\Api\AccountManagementInterface;
 
class AccountOverview extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Newsletter\Model\Subscriber
     */
    protected $subscription;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        \Stalwart\Sweda\Model\OrderFactory $orderfactory,
        \Stalwart\Smartcart\Model\SmartcartFactory $smartcartfactory,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->subscriberFactory = $subscriberFactory;
        $this->customerRepository = $customerRepository;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->orderfactory = $orderfactory;
        $this->smartcartfactory = $smartcartfactory;
        parent::__construct($context, $data);
    }

    /**
     * Return the Customer given the customer Id stored in the session.
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerRepository->getById($this->customerSession->getCustomerId());
    }

    /**
     * Retrieve the Url for editing the customer's account.
     *
     * @return string
     */
    public function getAccountUrl()
    {
        return $this->_urlBuilder->getUrl('customer/account/edit', ['_secure' => true]);
    }

    /**
     * Retrieve the Url for customer addresses.
     *
     * @return string
     */
    public function getAddressesUrl()
    {
        return $this->_urlBuilder->getUrl('customer/address/index', ['_secure' => true]);
    }

    /**
     * Retrieve the Url for editing the specified address.
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @return string
     */
    public function getAddressEditUrl($address)
    {
        return $this->_urlBuilder->getUrl(
            'customer/address/edit',
            ['_secure' => true, 'id' => $address->getId()]
        );
    }

    /**
     * Retrieve the Url for customer orders.
     *
     * @return string
     */
    public function getOrdersUrl()
    {
        return $this->_urlBuilder->getUrl('customer/order/index', ['_secure' => true]);
    }

    /**
     * Retrieve the Url for customer reviews.
     *
     * @return string
     */
    public function getReviewsUrl()
    {
        return $this->_urlBuilder->getUrl('review/customer/index', ['_secure' => true]);
    }

    /**
     * Retrieve the Url for managing customer wishlist.
     *
     * @return string
     */
    public function getWishlistUrl()
    {
        return $this->_urlBuilder->getUrl('customer/wishlist/index', ['_secure' => true]);
    }

    /**
     * Retrieve the subscription object (i.e. the subscriber).
     *
     * @return \Magento\Newsletter\Model\Subscriber
     */
    public function getSubscriptionObject()
    {
        if ($this->subscription === null) {
            $this->subscription =
                $this->_createSubscriber()->loadByCustomerId($this->customerSession->getCustomerId());
        }

        return $this->subscription;
    }

    /**
     * Retrieve the Url for managing newsletter subscriptions.
     *
     * @return string
     */
    public function getManageNewsletterUrl()
    {
        return $this->getUrl('newsletter/manage');
    }

    /**
     * Retrieve subscription text, either subscribed or not.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getSubscriptionText()
    {
        if ($this->getSubscriptionObject()->isSubscribed()) {
            return __('You are subscribed to our newsletter.');
        }

        return __('You aren\'t subscribed to our newsletter.');
    }

    /**
     * Retrieve the customer's primary addresses (i.e. default billing and shipping).
     *
     * @return \Magento\Customer\Api\Data\AddressInterface[]|bool
     */
    public function getPrimaryAddresses()
    {
        $addresses = [];
        $customerId = $this->getCustomer()->getId();

        if ($defaultBilling = $this->customerAccountManagement->getDefaultBillingAddress($customerId)) {
            $addresses[] = $defaultBilling;
        }

        if ($defaultShipping = $this->customerAccountManagement->getDefaultShippingAddress($customerId)) {
            if ($defaultBilling) {
                if ($defaultBilling->getId() != $defaultShipping->getId()) {
                    $addresses[] = $defaultShipping;
                }
            } else {
                $addresses[] = $defaultShipping;
            }
        }

        return empty($addresses) ? false : $addresses;
    }

    /**
     * Get back Url in account dashboard.
     *
     * This method is copy/pasted in:
     * \Magento\Wishlist\Block\Customer\Wishlist  - Because of strange inheritance
     * \Magento\Customer\Block\Address\Book - Because of secure Url
     *
     * @return string
     */
    public function getBackUrl()
    {
        // the RefererUrl must be set in appropriate controller
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/');
    }

    /**
     * Create an instance of a subscriber.
     *
     * @return \Magento\Newsletter\Model\Subscriber
     */
    protected function _createSubscriber()
    {
        return $this->subscriberFactory->create();
    }

    public function getSwedaOrders() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $this->orderfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getCustomerId());
        $collection->setOrder('ordered_date','DESC');
        $collection->setPageSize('2');
        
        return $collection;
    }

    public function getSmartCartFactory() {
        $sortingParams = $this->getSortingParams();
        $collection = $this->smartcartfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId());
        $collection->setOrder('updated_at','DESC');
        $collection->setPageSize('10');

        if(!empty($sortingParams))
            $collection->setOrder($sortingParams["sortby"],$sortingParams["dir"]);

        return $collection;
    }

    public function getSortingParams()
    {
        $doSort = false;
        $sortBy = ($this->getRequest()->getParam('sort-by'))? $this->getRequest()->getParam('sort-by') : 'title';
        if($sortBy)
        {
            $sortingAllowedFields = array("title","event_name","event_date","created_at");
            $dir = ($this->getRequest()->getParam('dir'))? strtoupper($this->getRequest()->getParam('dir')) : 'ASC';
            if(in_array($sortBy,$sortingAllowedFields))
            {
                if(!in_array($dir, array("ASC","DESC")))
                {
                    $dir = "ASC";
                }
                $doSort = true;
            }

        }
        if($doSort)
            return array("sortby"=>$sortBy, "dir"=>$dir);   

        return array();
    }

    public function getSortUrl($fieldName = "")
    {
        if($fieldName)
        {
            $sortingParams = $this->getSortingParams();
                $dir = $sortingParams["dir"];
                if($sortingParams["sortby"] == $fieldName)
                {
                    if($dir == "ASC")
                        $dir = "DESC";
                    else
                        $dir = "ASC";
                }
                return $this->getUrl("customer/account/index",array("_query" => array("sort-by"=>$fieldName,"dir"=>$dir)));
        }
        return $this->getUrl("customer/account/index");
    }

    /**
     * Retrieve form data
     *
     * @return array
     */
    protected function getFormData()
    {
        $data = $this->getData('form_data');
        if ($data === null) {
            $formData = $this->customerSession->getCustomerFormData(true);
            $data = [];
            if ($formData) {
                $data['data'] = $formData;
                $data['customer_data'] = 1;
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }

    /**
     * Restore entity data from session. Entity and form code must be defined for the form.
     *
     * @param \Magento\Customer\Model\Metadata\Form $form
     * @param null $scope
     * @return \Magento\Customer\Block\Form\Register
     */
    public function restoreSessionData(\Magento\Customer\Model\Metadata\Form $form, $scope = null)
    {
        $formData = $this->getFormData();
        if (isset($formData['customer_data']) && $formData['customer_data']) {
            $request = $form->prepareRequest($formData['data']);
            $data = $form->extractData($request, $scope, false);
            $form->restoreData($data);
        }

        return $this;
    }

    /**
     * Return whether the form should be opened in an expanded mode showing the change password fields
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getChangePassword()
    {
        return $this->customerSession->getChangePassword();
    }

    /**
     * Get minimum password length
     *
     * @return string
     * @since 100.1.0
     */
    public function getMinimumPasswordLength()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Get minimum password length
     *
     * @return string
     * @since 100.1.0
     */
    public function getRequiredCharacterClassesNumber()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }

    public function getIsBtob()
    {
        return $this->getCustomer()->getCustomAttribute('is_btob')->getValue();
    }
}
