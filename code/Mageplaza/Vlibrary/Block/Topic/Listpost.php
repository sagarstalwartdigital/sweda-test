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

namespace Mageplaza\Vlibrary\Block\Topic;

use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Listpost
 * @package Mageplaza\Vlibrary\Block\Topic
 */
class Listpost extends \Mageplaza\Vlibrary\Block\Listpost
{
    /**
     * @var \Mageplaza\Vlibrary\Model\TopicFactory
     */
    protected $_topic;

    /**
     * Override this function to apply collection for each type
     *
     * @return \Mageplaza\Vlibrary\Model\ResourceModel\Post\Collection
     */
    protected function getCollection()
    {
        if ($topic = $this->getVlibraryObject()) {
            return $this->helperData->getPostCollection(Data::TYPE_TOPIC, $topic->getId());
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function getVlibraryObject()
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

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $topic = $this->getVlibraryObject();
            if ($topic) {
                $breadcrumbs->addCrumb($topic->getUrlKey(), [
                    'label' => __('Topic'),
                    'title' => __('Topic')
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
        $topic = $this->getVlibraryObject();
        if (!$topic) {
            return $vlibraryTitle;
        }

        if ($meta) {
            if ($topic->getMetaTitle()) {
                array_push($vlibraryTitle, $topic->getMetaTitle());
            } else {
                array_push($vlibraryTitle, ucfirst($topic->getName()));
            }

            return $vlibraryTitle;
        }

        return ucfirst($topic->getName());
    }
}
