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

namespace Mageplaza\PromoStandards\Block\Category;

use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\PromoStandards\Block\Category
 */
class Listpost extends \Mageplaza\PromoStandards\Block\Listpost
{
    /**
     * @var string
     */
    protected $_category;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\PromoStandards\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($category = $this->getPromoStandardsObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_CATEGORY, $category->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getPromoStandardsObject()
    {
        if (!$this->_category) {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $category = $this->helperData->getObjectByParam($id, null, Data::TYPE_CATEGORY);
                if ($category && $category->getId()) {
                    $this->_category = $category;
                }
            }
        }

        return $this->_category;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $category = $this->getPromoStandardsObject();
            if ($category) {
                $breadcrumbs->addCrumb($category->getUrlKey(), [
                    'label' => __('Category'),
                    'title' => __('Category')
                ]);
            }
        }
    }

    /**
     * @param bool $meta
     *
     * @return array
     */
    public function getPromoStandardsTitle($meta = false)
    {
        $promostandardsTitle = parent::getPromoStandardsTitle($meta);
        $category = $this->getPromoStandardsObject();
        if (!$category) {
            return $promostandardsTitle;
        }

        if ($meta) {
            if ($category->getMetaTitle()) {
                array_push($promostandardsTitle, $category->getMetaTitle());
            } else {
                array_push($promostandardsTitle, ucfirst($category->getName()));
            }

            return $promostandardsTitle;
        }

        return ucfirst($category->getName());
    }
}
