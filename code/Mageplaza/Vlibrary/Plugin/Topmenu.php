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

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\TreeFactory;
use Mageplaza\Vlibrary\Helper\Data;

/**
 * Class Topmenu
 * @package Mageplaza\Vlibrary\Plugin
 */
class Topmenu
{
    /**
     * @var \Mageplaza\Vlibrary\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Data\TreeFactory
     */
    protected $treeFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * Topmenu constructor.
     *
     * @param \Mageplaza\Vlibrary\Helper\Data $helper
     * @param \Magento\Framework\Data\TreeFactory $treeFactory
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        Data $helper,
        TreeFactory $treeFactory,
        RequestInterface $request
    ) {
        $this->helper = $helper;
        $this->treeFactory = $treeFactory;
        $this->request = $request;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     *
     * @return array
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if ($this->helper->isEnabled() && $this->helper->getVlibraryConfig('general/toplinks')) {
            $subject->getMenu()
                ->addChild(
                    new Node(
                        $this->getMenuAsArray(),
                        'id',
                        $this->treeFactory->create()
                    )
                );
        }

        return [$outermostClass, $childrenWrapClass, $limit];
    }

    /**
     * @return array
     */
    private function getMenuAsArray()
    {
        $identifier = trim($this->request->getPathInfo(), '/');
        $routePath = explode('/', $identifier);
        $routeSize = sizeof($routePath);

        return [
            'name'       => $this->helper->getVlibraryConfig('general/name') ?: __('Vlibrary'),
            'id'         => 'mpvlibrary-node',
            'url'        => $this->helper->getVlibraryUrl(''),
            'has_active' => ($identifier == 'mpvlibrary/post/index'),
            'is_active'  => ('mpvlibrary' == array_shift($routePath)) && ($routeSize == 3)
        ];
    }
}
