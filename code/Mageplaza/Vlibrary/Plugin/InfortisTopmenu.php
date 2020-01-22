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

namespace Mageplaza\Vlibrary\Plugin;

/**
 * Class InfortisTopmenu
 * @package Mageplaza\Vlibrary\Plugin
 */
class InfortisTopmenu
{
    /**
     * @param \Infortis\UltraMegamenu\Block\Navigation $topmenu
     * @param $html
     *
     * @return string
     */
    public function afterRenderCategoriesMenuHtml(\Infortis\UltraMegamenu\Block\Navigation $topmenu, $html)
    {
        $html .= $topmenu->getLayout()
            ->createBlock('Mageplaza\Vlibrary\Block\Frontend')
            ->setTemplate('Mageplaza_Vlibrary::position/topmenuinfortis.phtml')
            ->toHtml();

        return $html;
    }
}
