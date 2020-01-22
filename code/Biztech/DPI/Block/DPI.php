<?php
/**
 * @category   Biztech
 * @package    Biztech_DPI
 * @author     developer1.test@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Biztech\DPI\Block;

/**
 * DPI content block
 */
class DPI extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Biztech DPI Module'));
        
        return parent::_prepareLayout();
    }
}
