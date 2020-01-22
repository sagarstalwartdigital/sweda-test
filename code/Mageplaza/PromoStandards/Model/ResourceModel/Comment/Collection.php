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

namespace Mageplaza\PromoStandards\Model\ResourceModel\Comment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Mageplaza\PromoStandards\Model\ResourceModel\Comment
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'comment_id';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\PromoStandards\Model\Comment', 'Mageplaza\PromoStandards\Model\ResourceModel\Comment');
    }
}
