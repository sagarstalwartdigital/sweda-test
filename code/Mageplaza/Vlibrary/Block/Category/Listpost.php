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

namespace Mageplaza\Vlibrary\Block\Category;

use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\Vlibrary\Block\Category
 */
class Listpost extends \Mageplaza\Vlibrary\Block\Listpost
{
    /**
     * @var string
     */
    protected $_category;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\Vlibrary\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($category = $this->getVlibraryObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_CATEGORY, $category->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getVlibraryObject()
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
            $category = $this->getVlibraryObject();
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
    public function getVlibraryTitle($meta = false)
    {
        $vlibraryTitle = parent::getVlibraryTitle($meta);
        $category = $this->getVlibraryObject();
        if (!$category) {
            return $vlibraryTitle;
        }

        if ($meta) {
            if ($category->getMetaTitle()) {
                array_push($vlibraryTitle, $category->getMetaTitle());
            } else {
                array_push($vlibraryTitle, ucfirst($category->getName()));
            }

            return $vlibraryTitle;
        }

        return ucfirst($category->getName());
    }
}
