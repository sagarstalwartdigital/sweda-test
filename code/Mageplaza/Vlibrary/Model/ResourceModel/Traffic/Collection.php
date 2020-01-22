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

namespace Mageplaza\Vlibrary\Model\ResourceModel\Traffic;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Mageplaza\Vlibrary\Model\ResourceModel\Traffic
 */
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\Vlibrary\Model\Traffic', 'Mageplaza\Vlibrary\Model\ResourceModel\Traffic');
    }
}