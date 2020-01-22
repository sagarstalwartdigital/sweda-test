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

namespace Mageplaza\PromoStandards\Block\Topic;

use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\PromoStandards\Block\Topic
 */
class Listpost extends \Mageplaza\PromoStandards\Block\Listpost
{
    /**
     * @var \Mageplaza\PromoStandards\Model\TopicFactory
     */
    protected $_topic;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\PromoStandards\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($topic = $this->getPromoStandardsObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_TOPIC, $topic->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getPromoStandardsObject()
    {
        if (!$this->_topic) {
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                $topic = $this->helperData->getObjectByParam($id, null, Data::TYPE_TOPIC);
                if ($topic && $topic->getId()) {
                    $this->_topic = $topic;
                }
            }
        }

        return $this->_topic;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
        //     $topic = $this->getPromoStandardsObject();
        //     if ($topic) {
        //         $breadcrumbs->addCrumb($topic->getUrlKey(), [
        //             'label' => __('Topic'),
        //             'title' => __('Topic')
        //         ]);
        //     }
        // }
    }

    /**
     * @param bool $meta
     *
     * @return array
     */
    public function getPromoStandardsTitle($meta = false)
    {
        $promostandardsTitle = parent::getPromoStandardsTitle($meta);
        $topic = $this->getPromoStandardsObject();
        if (!$topic) {
            return $promostandardsTitle;
        }

        if ($meta) {
            if ($topic->getMetaTitle()) {
                array_push($promostandardsTitle, $topic->getMetaTitle());
            } else {
                array_push($promostandardsTitle, ucfirst($topic->getName()));
            }

            return $promostandardsTitle;
        }

        return ucfirst($topic->getName());
    }
}
