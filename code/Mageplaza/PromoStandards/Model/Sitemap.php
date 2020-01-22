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

namespace Mageplaza\PromoStandards\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Mageplaza\PromoStandards\Helper\Data;
use Mageplaza\PromoStandards\Helper\Image;

/**
 * Class Sitemap
 * @package Mageplaza\PromoStandards\Model
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    /**
     * @var \Mageplaza\PromoStandards\Helper\Data
     */
    protected $promostandardsDataHelper;

    /**
     * @var \Mageplaza\PromoStandards\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var mixed
     */
    protected $router;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->promostandardsDataHelper = ObjectManager::getInstance()->get(Data::class);
        $this->imageHelper = ObjectManager::getInstance()->get(Image::class);
        $this->router = $this->promostandardsDataHelper->getPromoStandardsConfig('general/url_prefix');
    }

    /**
     * @return array
     */
    public function getPromoStandardsPostsSiteMapCollection()
    {
        $urlSuffix = $this->promostandardsDataHelper->getUrlSuffix();
        $postCollection = $this->promostandardsDataHelper->postFactory->create()->getCollection();
        $postSiteMapCollection = [];
        if (!$this->router) {
            $this->router = 'promostandards';
        }
        foreach ($postCollection as $item) {
            if (!is_null($item->getEnabled())) {
                $images = null;
                if ($item->getImage()) {
                    $imageFile = $this->imageHelper->getMediaPath($item->getImage(), Image::TEMPLATE_MEDIA_TYPE_POST);

                    $imagesCollection[] = new DataObject([
                        'url'     => $this->imageHelper->getMediaUrl($imageFile),
                        'caption' => null,
                    ]);
                    $images = new DataObject(['collection' => $imagesCollection]);
                }
                $postSiteMapCollection[$item->getId()] = new DataObject([
                    'id'         => $item->getId(),
                    'url'        => $this->router . '/post/' . $item->getUrlKey() . $urlSuffix,
                    'images'     => $images,
                    'updated_at' => $item->getUpdatedAt(),
                ]);
            }
        }

        return $postSiteMapCollection;
    }

    /**
     * @return array
     */
    public function getPromoStandardsCategoriesSiteMapCollection()
    {
        $urlSuffix = $this->promostandardsDataHelper->getUrlSuffix();
        $categoryCollection = $this->promostandardsDataHelper->categoryFactory->create()->getCollection();
        $categorySiteMapCollection = [];
        foreach ($categoryCollection as $item) {
            if (!is_null($item->getEnabled())) {
                $categorySiteMapCollection[$item->getId()] = new DataObject([
                    'id'         => $item->getId(),
                    'url'        => $this->router . '/category/' . $item->getUrlKey() . $urlSuffix,
                    'updated_at' => $item->getUpdatedAt(),
                ]);
            }
        }

        return $categorySiteMapCollection;
    }

    /**
     * @return array
     */
    public function getPromoStandardsTagsSiteMapCollection()
    {
        $urlSuffix = $this->promostandardsDataHelper->getUrlSuffix();
        $tagCollection = $this->promostandardsDataHelper->tagFactory->create()->getCollection();
        $tagSiteMapCollection = [];
        foreach ($tagCollection as $item) {
            if (!is_null($item->getEnabled())) {
                $tagSiteMapCollection[$item->getId()] = new DataObject([
                    'id'         => $item->getId(),
                    'url'        => $this->router . '/tag/' . $item->getUrlKey() . $urlSuffix,
                    'updated_at' => $item->getUpdatedAt(),
                ]);
            }
        }

        return $tagSiteMapCollection;
    }

    /**
     * @return array
     */
    public function getPromoStandardsTopicsSiteMapCollection()
    {
        $urlSuffix = $this->promostandardsDataHelper->getUrlSuffix();
        $topicCollection = $this->promostandardsDataHelper->topicFactory->create()->getCollection();
        $topicSiteMapCollection = [];
        foreach ($topicCollection as $item) {
            if (!is_null($item->getEnabled())) {
                $topicSiteMapCollection[$item->getId()] = new DataObject([
                    'id'         => $item->getId(),
                    'url'        => $this->router . '/topic/' . $item->getUrlKey() . $urlSuffix,
                    'updated_at' => $item->getUpdatedAt(),
                ]);
            }
        }

        return $topicSiteMapCollection;
    }

    /**
     * @inheritdoc
     */
    public function _initSitemapItems()
    {
        $this->_sitemapItems[] = new DataObject([
            'collection' => $this->getPromoStandardsPostsSiteMapCollection(),
        ]);
        $this->_sitemapItems[] = new DataObject([
            'collection' => $this->getPromoStandardsCategoriesSiteMapCollection(),
        ]);
        $this->_sitemapItems[] = new DataObject([
            'collection' => $this->getPromoStandardsTagsSiteMapCollection(),
        ]);
        $this->_sitemapItems[] = new DataObject([
            'collection' => $this->getPromoStandardsTopicsSiteMapCollection(),
        ]);

        parent::_initSitemapItems(); // TODO: Change the autogenerated stub
    }
}
