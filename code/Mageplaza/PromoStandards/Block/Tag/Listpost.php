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

namespace Mageplaza\PromoStandards\Block\Tag;

use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\PromoStandards\Block\Tag
 */
class Listpost extends \Mageplaza\PromoStandards\Block\Listpost
{
    /**
     * @var \Mageplaza\PromoStandards\Model\TagFactory
     */
    protected $_tag;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\PromoStandards\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($tag = $this->getPromoStandardsObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_TAG, $tag->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getPromoStandardsObject()
    {
        if (!$this->_tag) {
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                $tag = $this->helperData->getObjectByParam($id, null, Data::TYPE_TAG);
                if ($tag && $tag->getId()) {
                    $this->_tag = $tag;
                }
            }
        }

        return $this->_tag;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $tag = $this->getPromoStandardsObject();
            if ($tag) {
                $breadcrumbs->addCrumb($tag->getUrlKey(), [
                    'label' => __('Tag'),
                    'title' => __('Tag')
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
        $tag = $this->getPromoStandardsObject();
        if (!$tag) {
            return $promostandardsTitle;
        }

        if ($meta) {
            if ($tag->getMetaTitle()) {
                array_push($promostandardsTitle, $tag->getMetaTitle());
            } else {
                array_push($promostandardsTitle, ucfirst($tag->getName()));
            }

            return $promostandardsTitle;
        }

        return ucfirst($tag->getName());
    }
}
