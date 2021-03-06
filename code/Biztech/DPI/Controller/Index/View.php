<?php
/**
 * @category   Biztech
 * @package    Biztech_DPI
 * @author     developer1.test@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Biztech\DPI\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use Biztech\DPI\Block\DPIView;

class View extends \Magento\Framework\App\Action\Action
{
	protected $_dpiview;

	public function __construct(
        Context $context,
        DPIView $dpiview
    ) {
        $this->_dpiview = $dpiview;
        parent::__construct($context);
    }

	public function execute()
    {
    	if(!$this->_dpiview->getSingleData()){
    		throw new NotFoundException(__('Parameter is incorrect.'));
    	}
    	
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
