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

namespace Mageplaza\PromoStandards\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Post
 * @package Mageplaza\PromoStandards\Block\Adminhtml
 */
class Post extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Mageplaza_PromoStandards';
        $this->_headerText = __('Posts');
        $this->_headerText = __('Posts');
        $this->_addButtonLabel = __('Create New Promo Standards');

        parent::_construct();
    }
}
