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

namespace Mageplaza\Vlibrary\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Category
 * @package Mageplaza\Vlibrary\Block\Adminhtml
 */
class Category extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_category';
        $this->_blockGroup = 'Mageplaza_Vlibrary';
        $this->_headerText = __('Categories');
        $this->_addButtonLabel = __('Create New Vlibrary Category');

        parent::_construct();
    }
}
