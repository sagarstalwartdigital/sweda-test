<?php
 
namespace Stalwart\Sweda\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class ProductImprintMethod extends Template
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
      * @var \Magento\Framework\Registry
      */
    protected $_registry;

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
        \Magento\Framework\Registry $registry,
        \Stalwart\Sweda\Model\ProductImprintMethodFactory $productImprintMethodFactory,
        \Stalwart\Productmanager\Model\SwedaproductimprintmethodsFactory $swedaProductImprintMethodsFactory,
        \Stalwart\Productmanager\Model\SwedaproductshippingFactory $productShippingInfoFactory,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->_registry = $registry;
        $this->productImprintMethodFactory = $productImprintMethodFactory;
        $this->swedaProductImprintMethodsFactory = $swedaProductImprintMethodsFactory;
        $this->productShippingInfoFactory = $productShippingInfoFactory;

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
        return $this->_registry->registry('current_product');
    }
    public function getProductImprintPosition() {
        $collection = $this->swedaProductImprintMethodsFactory->create()->getCollection();
        $collection->addFieldToFilter('product_id',$this->getCurrentProduct()->getId());
        $collection->addFieldToSelect('id','imprint_method_id');
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
    public function getProductImprintMethods() {
        return $this->swedaProductImprintMethodsFactory->create()->getCollection()->addFieldToFilter('product_id',$this->getCurrentProduct()->getId());
    }

    public function getProductShippingDetails() {
        return $this->productShippingInfoFactory->create()->getCollection()->addFieldToFilter('pid',$this->getCurrentProduct()->getId());
    }
    
}
