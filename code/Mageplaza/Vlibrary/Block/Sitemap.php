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
 * @package     Mageplaza_Vlibrary
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Vlibrary\Block;

/**
 * Class Sitemap
 * @package Mageplaza\Vlibrary\Block
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
    public function getVlibraryTitle($meta = false)
    {
        $vlibraryTitle = parent::getVlibraryTitle($meta);

        if ($meta) {
            $vlibraryTitle[] = __('Site Map');
        } else {
            $vlibraryTitle = __('Site Map');
        }

        return $vlibraryTitle;
    }
}
