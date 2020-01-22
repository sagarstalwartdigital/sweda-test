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

use Magento\Framework\App\ObjectManager;
use Mageplaza\Vlibrary\Block\Adminhtml\Category\Tree;
use Mageplaza\Vlibrary\Block\Frontend;
use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Widget
 * @package Mageplaza\Vlibrary\Block\Category
 */
class Widget extends Frontend
{
    /**
     * @return array|string
     */
    public function getTree()
    {
        $tree = ObjectManager::getInstance()->create(Tree::class);
        $tree = $tree->getTree(null, $this->store->getStore()->getId());

        return $tree;
    }

    /**
     * @param $tree
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getCategoryTreeHtml($tree)
    {
        if (!$tree) {
            return __('No Categories.');
        }

        $html = '';
        foreach ($tree as $value) {
    
            if (!$value) {
                continue;
            }
            if ($value['enabled']) {
                $level = count(explode('/', ($value['path'])));
                $hasChild = isset($value['children']) && $level < 4;
                $html .= '<div class="video-categories-text">';
                $html .= '<ul class="block-content menu-categories category-level' . $level . '" style="margin-bottom:0px;margin-top:8px;">';
                $html .= '<li class="category-item">';
                $html .= $hasChild ? '<i class="fa fa-plus-square-o mp-vlibrary-expand-tree-' . $level . '"></i>' : '';
                $html .= '<a class="list-categories" href="' . $this->getCategoryUrl($value['url']).'?ci='.$value['id'] . '">';
                $html .= ucfirst($value['text']) . '</a>';
                $html .= $hasChild ? $this->getCategoryTreeHtml($value['children']) : '';
                $html .= '</li>';
                $html .= '</ul>';
                $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * @param $category
     *
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->helperData->getVlibraryUrl($category, Data::TYPE_CATEGORY);
    }
}
