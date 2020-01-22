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

namespace Mageplaza\PromoStandards\Block\Sidebar;

use Mageplaza\PromoStandards\Block\Frontend;
use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Search
 * @package Mageplaza\PromoStandards\Block\Sidebar
 */
class Search extends Frontend
{
    /**
     * @return string
     */
    public function getSearchPromoStandardsData()
    {
        $result = [];
        $posts = $this->helperData->getPostList();
        $limitDesc = (int) $this->getSidebarConfig('search/description');
        if (!empty($posts)) {
            foreach ($posts as $item) {
                $shortDescription = ($item->getShortDescription() && $limitDesc > 0) ? $item->getShortDescription() : '';
                if (strlen($shortDescription) > $limitDesc) {
                    $shortDescription = mb_substr($shortDescription, 0, $limitDesc, 'UTF-8') . '...';
                }

                $result[] = [
                    'value' => $item->getName(),
                    'url'   => $item->getUrl(),
                    'image' => $this->resizeImage($item->getImage(), '100x'),
                    'desc'  => $shortDescription
                ];
            }
        }

        return Data::jsonEncode($result);
    }

    /**
     * get sidebar config
     *
     * @param $code
     * @param $storeId
     *
     * @return mixed
     */
    public function getSidebarConfig($code, $storeId = null)
    {
        return $this->helperData->getPromoStandardsConfig('sidebar/' . $code, $storeId);
    }
}
