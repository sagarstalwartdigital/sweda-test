<?php
/**
 * @category   Biztech
 * @package    Biztech_DPI
 * @author     developer1.test@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Biztech\DPI\Model\ResourceModel;

class DPI extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('biztech_dpi', 'dpi_id');   //here "biztech_dpi" is table name and "dpi_id" is the primary key of custom table
    }
}