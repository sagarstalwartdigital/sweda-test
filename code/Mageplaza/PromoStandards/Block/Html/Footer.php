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

namespace Mageplaza\PromoStandards\Block\Html;

use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Footer
 * @package Mageplaza\PromoStandards\Block\Html
 */
class Footer extends Link
{
    /**
     * @var \Mageplaza\PromoStandards\Helper\Data
     */
    public $helper;

    /**
     * @var string
     */
    protected $_template = 'Mageplaza_PromoStandards::html\footer.phtml';

    /**
     * Footer constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Mageplaza\PromoStandards\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->helper->getPromoStandardsUrl('');
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if ($this->helper->getPromoStandardsConfig('general/name') == "") {
            return __("PromoStandards");
        }

        return $this->helper->getPromoStandardsConfig('general/name');
    }

    /**
     * @return string
     */
    public function getHtmlSiteMapUrl()
    {
        return $this->helper->getPromoStandardsUrl('sitemap');
    }
}
