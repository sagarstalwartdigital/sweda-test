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

namespace Mageplaza\Vlibrary\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Traffic
 * @package Mageplaza\Vlibrary\Model
 */
class Traffic extends AbstractModel
{
    /**
     * Define resource model
     */
    public function _construct()
    {
        $this->_init('Mageplaza\Vlibrary\Model\ResourceModel\Traffic');
    }
}
