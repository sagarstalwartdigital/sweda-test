<?php
 
namespace Stalwart\Sweda\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class ProductPricesRegular extends Template
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
      * @var \Magento\Framework\Registry
      */
    protected $_registry;

    protected $request;

    /**
     * ModalOverlay constructor.
     *
     * @param BlockRepositoryInterface $blockRepository
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Stalwart\Productmanager\Model\SwedaproductpriceFactory $productPriceRegularFactory,
        \Stalwart\Productmanager\Model\SwedaproductimprintmethodsFactory $swedaProductImprintMethodsFactory,
        \Stalwart\Productmanager\Model\SwedaproductimprintmethodchargesFactory $swedaProductImprintMethodChargesFactory,
        \Stalwart\Productmanager\Model\SwedaproductmasterimprintmethodsFactory $swedaProductMasterImprintMethodsFactory,
        \Stalwart\Productmanager\Model\SwedaproductmasterimprintchargesFactory $swedaProductMasterImprintChargesFactory,
        \Stalwart\Productmanager\Model\SwedaproductpricedataFactory $swedaProductPriceDataFactory,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->_registry = $registry;
        $this->productPriceRegularFactory = $productPriceRegularFactory;
        $this->swedaProductImprintMethodsFactory = $swedaProductImprintMethodsFactory;
        $this->swedaProductImprintMethodChargesFactory = $swedaProductImprintMethodChargesFactory;
        $this->swedaProductMasterImprintMethodsFactory = $swedaProductMasterImprintMethodsFactory;
        $this->swedaProductMasterImprintChargesFactory = $swedaProductMasterImprintChargesFactory;
        $this->swedaProductPriceDataFactory = $swedaProductPriceDataFactory;

        parent::__construct($context, $data);
    }
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getCurrentCategory()
    {        
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentProduct()
    {        
        if($this->getBlockProductId())
            return $this->getBlockProductId();
        elseif ($this->request->getParam('id')) {
            $proId = $this->request->getParam('id');
            return $proId;
        } else {
            $proId = $this->_registry->registry('current_product');
            return $proId;
        }
    }

    public function getProductRegularPriceTypes() {
        return $this->productPriceRegularFactory->create()->getCollection()->addFieldToFilter('product_id',$this->getCurrentProduct())->getFirstItem();
    }

    public function getProductRegularPrices() {
        return $this->swedaProductPriceDataFactory->create()->getCollection()->addFieldToFilter('swedaproduct_price_id',$this->getProductRegularPriceTypes()->getId());
    }

    public function getProductImprintPosition() {
        $collection = $this->swedaProductImprintMethodsFactory->create()->getCollection()->addFieldToFilter('product_id',$this->getCurrentProduct()->getId());
        $collection->getSelect()->join(
            ['sip' => 'swedaproduct_imprintmethods_positions'],
            'main_table.id= sip.imprint_method',
            [
                "imprint_postion_label" => "imprint_position_label",
                "imprint_postion_area" => "imprint_position_area",
            ]
        )->join(
            ['smip' => 'swedaproduct_master_imprintpositions'],
            'sip.imprint_position_label = smip.id',
            array("imprint_postion_name" => "name")
        );
        return $collection;
    }
    public function getProductImprintMethod() {
      $collection = $this->swedaProductImprintMethodsFactory->create()->getCollection()->addFieldToFilter('product_id',$this->getCurrentProduct());
      $collection->getSelect()->join(
            ['smi' => 'swedaproduct_master_imprintmethods'],
            'main_table.imprint_label_id = smi.id',
            array("imprint_method_name" => "name")
        )->join(
            ['sic' => 'swedaproduct_imprintmethod_charges'],
            'main_table.id= sic.imprint_method_id',
            ['*']
        )->join(
            ['smic' => 'swedaproduct_master_imprintcharges'],
            'sic.charge_id= smic.id',
            array("imprint_charge_name" => "name", "display_type" => "display_type")
        );/*->join(
            ['sip' => 'swedaproduct_imprintmethods_positions'],
            'main_table.id= sip.imprint_method',
            [
                "imprint_postion_label" => "imprint_position_label",
                "imprint_postion_area" => "imprint_position_area",
            ]
        )->join(
            ['smip' => 'swedaproduct_master_imprintpositions'],
            'sip.imprint_position_label = smip.id',
            array("imprint_postion_name" => "name")
        );*/
      return $collection;
    }
    
}
