<?php
 
namespace Stalwart\Smartcart\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class AddMoreProducts extends Template
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
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->customerSession = $customerSession;;

        parent::__construct($context, $data);
    }
}
