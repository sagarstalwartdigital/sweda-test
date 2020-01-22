<?php
 
namespace Stalwart\Smartcart\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class SmartCartSend extends Template
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

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
        \Stalwart\Smartcart\Model\SmartcartFactory $smartcartfactory,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;
        $this->smartcartfactory = $smartcartfactory;

        parent::__construct($context, $data);
    }

    public function getSmartCartFront() {

        $smartcartid = $this->getSmartCartID();

        if ($smartcartid && !empty($smartcartid)) {
    		$collection = $this->smartcartfactory->create()->getCollection()
                            ->addFieldToFilter('customer_id',$this->customerSession->getCustomer()->getId())
                            ->addFieldToFilter('id',$smartcartid);
    	    return $collection->getFirstItem();
        }
    }
}
