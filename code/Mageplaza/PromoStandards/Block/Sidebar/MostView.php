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

/**
 * Class MostView
 * @package Mageplaza\PromoStandards\Block\Sidebar
 */
class MostView extends Frontend
{
    /**
     * @return \Mageplaza\PromoStandards\Model\ResourceModel\Post\Collection
     */
    public function getMostViewPosts()
    {
        $collection = $this->helperData->getPostList();
        $collection->getSelect()
            ->joinLeft(
                ['traffic' => $collection->getTable('mageplaza_promostandards_post_traffic')],
                'main_table.post_id=traffic.post_id',
                'numbers_view'
            )
            ->order('numbers_view DESC')
            ->limit((int) $this->helperData->getPromoStandardsConfig('sidebar/number_mostview_posts') ?: 4);

        return $collection;
    }

    /**
     * @return \Mageplaza\PromoStandards\Model\ResourceModel\Post\Collection
     */
    public function getRecentPost()
    {
        $collection = $this->helperData->getPostList();
        $collection->getSelect()
            ->limit((int) $this->helperData->getPromoStandardsConfig('sidebar/number_recent_posts') ?: 4);

        return $collection;
    }
}
