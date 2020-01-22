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

namespace Mageplaza\PromoStandards\Block\MonthlyArchive;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\PromoStandards\Block\Frontend;
use Mageplaza\PromoStandards\Helper\Data;
use Mageplaza\PromoStandards\Helper\Data as DataHelper;
use Mageplaza\PromoStandards\Model\CommentFactory;
use Mageplaza\PromoStandards\Model\LikeFactory;

/**
 * Class Widget
 * @package Mageplaza\PromoStandards\Block\MonthlyArchive
 */
class Widget extends Frontend
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $dateTime;

    /**
     * @var array
     */
    protected $_postDate;

    /**
     * Widget constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Mageplaza\PromoStandards\Model\CommentFactory $commentFactory
     * @param \Mageplaza\PromoStandards\Model\LikeFactory $likeFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Mageplaza\PromoStandards\Helper\Data $helperData
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        Context $context,
        FilterProvider $filterProvider,
        CommentFactory $commentFactory,
        LikeFactory $likeFactory,
        CustomerRepositoryInterface $customerRepository,
        DataHelper $helperData,
        DateTime $dateTime,
        array $data = []
    ) {
        $this->dateTime = $dateTime;

        parent::__construct(
            $context,
            $filterProvider,
            $commentFactory,
            $likeFactory,
            $customerRepository,
            $helperData,
            $data
        );
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        return $this->helperData->getPromoStandardsConfig('monthly_archive/enable_monthly');
    }

    /**
     * @return array
     */
    public function getDateArrayCount()
    {
        return array_values(array_count_values($this->getDateArray()));
    }

    /**
     * @return array
     */
    public function getDateArrayUnique()
    {
        return array_values(array_unique($this->getDateArray()));
    }

    /**
     * get array of posts's date formatted
     * @return array
     */
    public function getDateArray()
    {
        $dateArray = [];
        foreach ($this->getPostDate() as $postDate) {
            $dateArray[] = date("F Y", $this->dateTime->timestamp($postDate));
        }

        return $dateArray;
    }

    /**
     * @return array
     */
    protected function getPostDate()
    {
        if (!$this->_postDate) {
            $posts = $this->helperData->getPostList();
            $postDates = [];
            if ($posts->getSize()) {
                foreach ($posts as $post) {
                    $postDates[] = $post->getPublishDate();
                }
            }
            $this->_postDate = $postDates;
        }

        return $this->_postDate;
    }

    /**
     * @return int|mixed
     */
    public function getDateCount()
    {
        $limit = $this->helperData->getPromoStandardsConfig('monthly_archive/number_records') ?: 5;
        $dateArrayCount = $this->getDateArrayCount();
        $count = count($dateArrayCount);
        $result = ($count < $limit) ? $count : $limit;

        return $result;
    }

    /**
     * @param $month
     *
     * @return string
     */
    public function getMonthlyUrl($month)
    {
        return $this->helperData->getPromoStandardsUrl($month, Data::TYPE_MONTHLY);
    }

    /**
     * @return array
     */
    public function getDateLabel()
    {
        $postDates = $this->getPostDate();
        $postDatesLabel = [];
        if (sizeof($postDates)) {
            foreach ($postDates as $date) {
                $postDatesLabel[] = $this->helperData->getDateFormat($date, true);
            }
        }
        $result = array_values(array_unique($postDatesLabel));

        return $result;
    }
}
