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

namespace Mageplaza\Vlibrary\Block\Tag;

use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\Vlibrary\Block\Tag
 */
class Listpost extends \Mageplaza\Vlibrary\Block\Listpost
{
    /**
     * @var \Mageplaza\Vlibrary\Model\TagFactory
     */
    protected $_tag;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\Vlibrary\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($tag = $this->getVlibraryObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_TAG, $tag->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getVlibraryObject()
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
            $tag = $this->getVlibraryObject();
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
    public function getVlibraryTitle($meta = false)
    {
        $vlibraryTitle = parent::getVlibraryTitle($meta);
        $tag = $this->getVlibraryObject();
        if (!$tag) {
            return $vlibraryTitle;
        }

        if ($meta) {
            if ($tag->getMetaTitle()) {
                array_push($vlibraryTitle, $tag->getMetaTitle());
            } else {
                array_push($vlibraryTitle, ucfirst($tag->getName()));
            }

            return $vlibraryTitle;
        }

        return ucfirst($tag->getName());
    }
}
