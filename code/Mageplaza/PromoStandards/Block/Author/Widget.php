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

namespace Mageplaza\PromoStandards\Block\Author;

use Mageplaza\PromoStandards\Block\Frontend;
use Mageplaza\PromoStandards\Helper\Data;

/**
 * Class Widget
 * @package Mageplaza\PromoStandards\Block\Author
 */
class Widget extends Frontend
{
    /**
     * @return mixed
     */
    public function getCurrentAuthor()
    {
        $authorId = $this->getRequest()->getParam('id');
        if ($authorId) {
            $author = $this->helperData->getObjectByParam($authorId, null, Data::TYPE_AUTHOR);
            if ($author && $author->getId()) {
                return $author;
            }
        }

        return null;
    }
}