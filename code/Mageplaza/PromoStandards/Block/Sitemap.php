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

namespace Mageplaza\PromoStandards\Block;

/**
 * Class Sitemap
 * @package Mageplaza\PromoStandards\Block
 */
class Sitemap extends Frontend
{
    /**
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('sitemap', [
                'label' => __('Site Map'),
                'title' => __('Site Map')
            ]);
        }
    }

    /**
     * @param bool $meta
     *
     * @return array
     */
    public function getPromoStandardsTitle($meta = false)
    {
        $promostandardsTitle = parent::getPromoStandardsTitle($meta);

        if ($meta) {
            $promostandardsTitle[] = __('Site Map');
        } else {
            $promostandardsTitle = __('Site Map');
        }

        return $promostandardsTitle;
    }
}
