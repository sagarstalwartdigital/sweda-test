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

namespace Mageplaza\PromoStandards\Plugin;

/**
 * Class PortoTopmenu
 * @package Mageplaza\PromoStandards\Plugin
 */
class PortoTopmenu
{
    /**
     * @param \Smartwave\Megamenu\Block\Topmenu $topmenu
     * @param $html
     *
     * @return string
     */
    public function afterGetMegamenuHtml(\Smartwave\Megamenu\Block\Topmenu $topmenu, $html)
    {
        $html .= $topmenu->getLayout()
            ->createBlock('Mageplaza\PromoStandards\Block\Frontend')
            ->setTemplate('Mageplaza_PromoStandards::position/topmenuporto.phtml')
            ->toHtml();

        return $html;
    }
}
