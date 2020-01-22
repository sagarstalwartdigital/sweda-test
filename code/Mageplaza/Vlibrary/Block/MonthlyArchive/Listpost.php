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

namespace Mageplaza\Vlibrary\Block\MonthlyArchive;

use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\Vlibrary\Block\MonthlyArchive
 */
class Listpost extends \Mageplaza\Vlibrary\Block\Listpost
{
    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\Vlibrary\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        return $this->helperData->getPostCollection(Data::TYPE_MONTHLY, $this->getMonthKey());
    }

    /**
     * @return mixed
     */
    protected function getMonthKey()
    {
        return $this->getRequest()->getParam('month_key');
    }

    /**
     * @return false|string
     */
    protected function getMonthLabel()
    {
        return $this->helperData->getDateFormat($this->getMonthKey() . '-10', true);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb($this->getMonthKey(), [
                'label' => __('Monthy Archive'),
                'title' => __('Monthy Archive')
            ]);
        }
    }

    /**
     * @param bool $meta
     *
     * @return array|false|string
     */
    public function getVlibraryTitle($meta = false)
    {
        $vlibraryTitle = parent::getVlibraryTitle($meta);

        if ($meta) {
            array_push($vlibraryTitle, $this->getMonthLabel());

            return $vlibraryTitle;
        }

        return $this->getMonthLabel();
    }
}
