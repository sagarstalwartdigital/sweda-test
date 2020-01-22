<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_PromoStandards
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\PromoStandards\Block\Adminhtml\Import\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Mageplaza\PromoStandards\Helper\Data as PromoStandardsHelper;
use Mageplaza\PromoStandards\Model\Config\Source\Import\Type;

/**
 * Class Import
 * @package Mageplaza\PromoStandards\Block\Adminhtml\Import\Edit
 */
class Import extends Template
{
    /**
     * @var PromoStandardsHelper
     */
    public $promostandardsHelper;

    /**
     * @var Type
     */
    public $importType;

    /**
     * Before constructor.
     *
     * @param Context $context
     * @param PromoStandardsHelper $promostandardsHelper
     * @param Type $importType
     * @param array $data
     */
    public function __construct(
        Context $context,
        PromoStandardsHelper $promostandardsHelper,
        Type $importType,
        array $data = []
    ) {
        $this->promostandardsHelper = $promostandardsHelper;
        $this->importType = $importType;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getTypeSelector()
    {
        $types = [];
        foreach ($this->importType->toOptionArray() as $item) {
            $types[] = $item['value'];
        }
        array_shift($types);

        return PromoStandardsHelper::jsonEncode($types);
    }

    /**
     * @param $priority
     * @param $message
     *
     * @return string
     */
    public function getMessagesHtml($priority, $message)
    {
        /** @var $messagesBlock \Magento\Framework\View\Element\Messages */
        $messagesBlock = $this->_layout->createBlock(\Magento\Framework\View\Element\Messages::class);
        $messagesBlock->{$priority}(__($message));

        return $messagesBlock->toHtml();
    }

    /**
     * @return string
     */
    public function getImportButtonHtml()
    {
        $importUrl = $this->getUrl('mageplaza_promostandards/import/import');
        $html = '&nbsp;&nbsp;<button id="word-press-import" href="' . $importUrl . '" class="" type="button" onclick="mpPromoStandardsImport.importAction();"><span><span><span>Import</span></span></span></button>';

        return $html;
    }
}
