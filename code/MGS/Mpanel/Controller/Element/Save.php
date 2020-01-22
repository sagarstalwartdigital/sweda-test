<?php
/**
 *
 * Copyright Â© 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MGS\Mpanel\Controller\Element;

use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_storeManager;

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    protected $_filesystem;

    protected $_file;

    protected $_cacheTypeList;
    protected $_cacheFrontendPool;

    /**
     * File Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    public function __construct(
        \Magento\Framework\App\Cache\TypeListInterface $typeListInterface,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\View\Element\Context $urlContext
    )
    {
        $this->_cacheTypeList = $typeListInterface;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->_urlBuilder = $urlContext->getUrlBuilder();
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_file = $file;
        parent::__construct($context);
    }

    public function getModel($model)
    {
        return $this->_objectManager->create($model);
    }

    function compareTools($object1, $object2)
    {
        return $object1->order > $object2->order;
    }

    public function execute()
    {
        if ($this->customerSession->getUsePanel() == 1) {
            $data = $this->getRequest()->getPostValue();

            switch ($data['type']) {
                /* Static content Block */
                case "static":
                    $this->removePanelImages('panel', $data);
                    $content = $data['content'];

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Text content block. Please wait to reload page.');
                    break;

                case "owl_banner":
                    $this->removePanelImages('slider', $data);

                    if (isset($data['setting']['html_slider']) && $data['setting']['html_slider'] != "") {
                        $dataInit = ['fullscreen', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'loop', 'fullheight', 'navtemple'];

                        $data = $this->reInitData($data, $dataInit);

                        $speed = '';
                        if ($data['setting']['speed']) {
                            $speed = $data['setting']['speed'];
                        }

                        $sliderHtml = htmlentities($data['setting']['html_slider']);

                        $dot = $data['setting']['pagination'];

                        $content = '{{block class="MGS\Mpanel\Block\Widget\OwlCarousel" fullscreen="' . $data['setting']['fullscreen'] . '" autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" offset="' . $data['setting']['offset'] . '" fullheight="' . $data['setting']['fullheight'] . '" pagination="' . $dot . '" loop="' . $data['setting']['loop'] . '" speed="' . $speed . '" html_slider="' . $sliderHtml . '" navtemple="' . $data['setting']['navtemple'] . '" effect="' . $data['setting']['effect'] . '" template="widget/owl_slider.phtml"}}';

                        $data['block_content'] = $content;
                        $result['message'] = 'success';
                        $sessionMessage = __('You saved the OWL Carousel Slider block. Please wait to reload page.');
                    } else {
                        $this->messageManager->addError(__('You have not add any images to slider.'));
                        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                        return $resultRedirect;
                    }
                    break;

                /* New Products Block */
                case "new_products":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore', 'numbercol', 'percol'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }
                    if ($data['setting']['use_tabs']) {
                        $template = 'products/new/category-tabs.phtml';
                    } else {
                        $template = 'products/new/' . $data['setting']['template'];
                    }

                    $content = '{{block class="MGS\Mpanel\Block\Products\NewProducts" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['template'] == 'list.phtml') {
                        $content .= ' numbercol="' . $data['setting']['numbercol'] . '" percol="' . $data['setting']['percol'] . '"';
                    }

                    if ($data['setting']['template'] == 'grid.phtml') {
                        $content .= ' perrow="' . $data['setting']['perrow'] . '"';
                    }

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the New Products block. Please wait to reload page.');
                    break;

                /* Attribute Products Block */
                case "attribute_products":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore', 'numbercol', 'percol'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }
                    if ($data['setting']['use_tabs']) {
                        $template = 'products/attribute/category-tabs.phtml';
                    } else {
                        $template = 'products/attribute/' . $data['setting']['template'];
                    }

                    $content = '{{block class="MGS\Mpanel\Block\Products\Attributes" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" attribute="' . $data['setting']['attribute'] . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['template'] == 'list.phtml') {
                        $content .= ' numbercol="' . $data['setting']['numbercol'] . '" percol="' . $data['setting']['percol'] . '"';
                    }

                    if ($data['setting']['template'] == 'grid.phtml') {
                        $content .= ' perrow="' . $data['setting']['perrow'] . '"';
                    }

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved Products by Attribute block. Please wait to reload page.');
                    break;

                /* Sale Products Block */
                case "sale":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore', 'numbercol', 'percol'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }
                    if ($data['setting']['use_tabs']) {
                        $template = 'products/sale/category-tabs.phtml';
                    } else {
                        $template = 'products/sale/' . $data['setting']['template'];
                    }

                    $content = '{{block class="MGS\Mpanel\Block\Products\Sale" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['template'] == 'list.phtml') {
                        $content .= ' numbercol="' . $data['setting']['numbercol'] . '" percol="' . $data['setting']['percol'] . '"';
                    }

                    if ($data['setting']['template'] == 'grid.phtml') {
                        $content .= ' perrow="' . $data['setting']['perrow'] . '"';
                    }

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Sale Products block. Please wait to reload page.');
                    break;

                /* Top Rate Products Block */
                case "rate":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }
                    if ($data['setting']['use_tabs']) {
                        $template = 'products/rate/category-tabs.phtml';
                    } else {
                        $template = 'products/rate/' . $data['setting']['template'];
                    }

                    $content = '{{block class="MGS\Mpanel\Block\Products\Rate" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['template'] == 'list.phtml') {
                        $content .= ' numbercol="' . $data['setting']['numbercol'] . '" percol="' . $data['setting']['percol'] . '"';
                    }

                    if ($data['setting']['template'] == 'grid.phtml') {
                        $content .= ' perrow="' . $data['setting']['perrow'] . '"';
                    }

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Top Rate Products block. Please wait to reload page.');
                    break;

                /* Category Products Block */
                case "category_products":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }
                    if ($data['setting']['use_tabs']) {
                        if ($data['setting']['template'] == 'masonry.phtml') {
                            $template = 'products/category_products/category-tabs-masonry.phtml';
                        } else {
                            $template = 'products/category_products/category-tabs.phtml';
                        }
                    } else {
                        $template = 'products/category_products/' . $data['setting']['template'];
                    }

                    $content = '{{block class="MGS\Mpanel\Block\Products\Category" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['template'] == 'list.phtml') {
                        $content .= ' numbercol="' . $data['setting']['numbercol'] . '" percol="' . $data['setting']['percol'] . '"';
                    }

                    if ($data['setting']['template'] == 'grid.phtml') {
                        $content .= ' perrow="' . $data['setting']['perrow'] . '"';
                    }
                    if ($data['setting']['template'] != 'masonry.phtml') {
                        if ($data['setting']['use_slider']) {
                            $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                        }
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Category Products block. Please wait to reload page.');
                    break;

                /* Deals Block */
                case "deals":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = '';
                    if (isset($data['setting']['category_id'])) {
                        $categories = implode(',', $data['setting']['category_id']);
                    }
                    if ($data['setting']['template'] == 'list.phtml') {
                        $data['setting']['use_tabs'] = 0;
                    }

                    $template = 'products/deals/' . $data['setting']['template'];

                    $content = '{{block class="MGS\Mpanel\Block\Products\Deals" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" perrow="' . $data['setting']['perrow'] . '" ratio="' . $data['setting']['ratio'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" template="' . $template . '"';

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Deals block. Please wait to reload page.');
                    break;

                /* Product Tabs Block */
                case "tabs":
                    if (isset($data['setting']['tabs']) && count($data['setting']['tabs']) > 0) {
                        $tabs = $data['setting']['tabs'];
                        $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'use_tabs', 'loadmore'];
                        $data = $this->reInitData($data, $dataInit);
                        $categories = '';
                        if (isset($data['setting']['category_id'])) {
                            $categories = implode(',', $data['setting']['category_id']);
                        }

                        usort($tabs, function ($item1, $item2) {
                            if ($item1['position'] == $item2['position']) return 0;
                            return $item1['position'] < $item2['position'] ? -1 : 1;
                        });

                        $tabType = $tabLabel = [];
                        foreach ($tabs as $tab) {
                            $tabType[] = $tab['value'];
                            $tabLabel[] = $tab['label'];
                        }
                        $tabs = implode(',', $tabType);
                        $labels = implode(',', $tabLabel);

                        $content = '{{block class="MGS\Mpanel\Block\Products\Tabs" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" perrow="' . $data['setting']['perrow'] . '" category_ids="' . $categories . '" use_slider="' . $data['setting']['use_slider'] . '" loadmore="' . $data['setting']['loadmore'] . '" ratio="' . $data['setting']['ratio'] . '" template="products/tabs/grid.phtml" tabs="' . $tabs . '"';

                        if ($data['setting']['use_slider']) {
                            $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                        }

                        $content .= 'labels="' . $labels . '"';

                        $content .= '}}';

                        $data['block_content'] = $content;
                        $result['message'] = 'success';
                        $sessionMessage = __('You saved the Product Tabs block. Please wait to reload page.');
                    } else {
                        $this->messageManager->addError(__('You have not add any tabs.'));
                        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                        return $resultRedirect;
                    }
                    break;

                /* Single Product Block */
                case "special_product":
                    $dataInit = ['product_name', 'product_price', 'product_rating', 'product_categories', 'product_description', 'product_addcart', 'landing_mode'];
                    $data = $this->reInitData($data, $dataInit);

                    $content = '{{block class="MGS\Mpanel\Block\Products\SpecialProduct" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" product_id="' . $data['setting']['product_id'] . '" ratio="' . $data['setting']['ratio'] . '" product_name="' . $data['setting']['product_name'] . '" product_price="' . $data['setting']['product_price'] . '" product_rating="' . $data['setting']['product_rating'] . '" product_categories="' . $data['setting']['product_categories'] . '" product_description="' . $data['setting']['product_description'] . '" product_addcart="' . $data['setting']['product_addcart'] . '" landing_mode="' . $data['setting']['landing_mode'] . '"';

                    if ($data['setting']['product_description'] && isset($data['setting']['characters_count']) && ($data['setting']['characters_count'] != '')) {
                        $content .= ' characters_count="' . $data['setting']['characters_count'] . '"';
                    }

                    $content .= ' template="products/single/default.phtml"}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Single Product block. Please wait to reload page.');
                    break;

                /* Megamenu Block */
                case "megamenu":
                    $menu = $data['setting']['menu'];
                    $menu = explode(':', $menu);
                    $menuType = 'vertical';
                    $menuBlock = '\Vertical';
                    if ($menu[1] == 1) {
                        $menuType = 'horizontal';
                        $menuBlock = '\Horizontal';
                    }
                    $content = '{{block class="MGS\Mmegamenu\Block' . $menuBlock . '" menu_id="' . $menu[0] . '" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" template="panel/' . $menuType . '.phtml"}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Megamenu block. Please wait to reload page.');
                    break;

                /* Brand Block */
                case "brands":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination'];
                    $data = $this->reInitData($data, $dataInit);
                    $content = '{{block class="MGS\Brand\Block\Widget\Home" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" perrow="' . $data['setting']['perrow'] . '" use_slider="' . $data['setting']['use_slider'] . '" brand_by="' . $data['setting']['brand_by'] . '" template="widget/home.phtml"';

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Brand block. Please wait to reload page.');
                    break;

                /* Promotion Banner Block */
                case "promo_banner":
                    if ((!isset($data['chooser'])) || ($data['chooser'] == 'new')) {
                        $result = $this->savePromoBanner($data['banner']);
                    } else {
                        $result['message'] = 'success';
                        $result['data'] = $data['banner']['choose_identifier'];
                    }
                    $data['block_content'] = '{{widget type="MGS\Promobanners\Block\Widget\Banner" banner_id="' . $result['data'] . '" template="banner.phtml"}}';
                    $data['setting']['banner_id'] = $result['data'];
                    unset($data['chooser'], $data['banner']);
                    $sessionMessage = __('You saved the Promotion Banner block. Please wait to reload page.');
                    break;

                /* Latest Posts Block */
                case "latest_posts":
                    $dataInit = ['number_row', 'show_short_content', 'view_as', 'show_thumbnail', 'autoplay', 'stop_auto', 'navigation', 'pagination'];
                    $data = $this->reInitData($data, $dataInit);
                    $cates = implode(',', $data['setting']['post_category']);
                    $content = '{{widget type="MGS\Blog\Block\Widget\Latest" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" post_category="' . $cates . '" number_of_posts="' . $data['setting']['number_of_posts'] . '" items="' . $data['setting']['items'] . '" show_thumbnail="' . $data['setting']['show_thumbnail'] . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" template="widget/' . $data['setting']['template'] . '"';

                    if ($data['setting']['show_short_content']) {
                        $content .= ' show_short_content="1" limit_characters_short_content="' . $data['setting']['limit_characters_short_content'] . '"';
                    }

                    if ($data['setting']['view_as']) {
                        $content .= ' view_as="owl_carousel" autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Latest Posts block. Please wait to reload page.');
                    break;

                /* Portfolio Block */
                case "portfolio":
                    $dataInit = ['show_categories', 'show_thumbnail', 'show_content', 'use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination'];
                    $data = $this->reInitData($data, $dataInit);
                    $categories = implode(',', $data['setting']['categories']);
                    $content = '{{block class="MGS\Portfolio\Block\Widget" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" category_ids="' . $categories . '" limit="' . $data['setting']['limit'] . '" perrow="' . $data['setting']['perrow'] . '" show_categories="' . $data['setting']['show_categories'] . '" show_content="' . $data['setting']['show_content'] . '" show_thumbnail="' . $data['setting']['show_thumbnail'] . '" use_slider="' . $data['setting']['use_slider'] . '" template="widget/' . $data['setting']['template'] . '"';

                    if ($data['setting']['show_content']) {
                        $content .= ' character_count="' . $data['setting']['character_count'] . '"';
                    }

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Portfolio block. Please wait to reload page.');
                    break;

                /* Testimonial Block */
                case "testimonial":
                    $dataInit = ['show_avatar', 'use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination'];
                    $data = $this->reInitData($data, $dataInit);
                    $content = '{{block class="MGS\Testimonial\Block\Testimonial" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" testimonials_count="' . $data['setting']['limit'] . '" perrow="' . $data['setting']['perrow'] . '" show_avatar="' . $data['setting']['show_avatar'] . '" use_slider="' . $data['setting']['use_slider'] . '" template="' . $data['setting']['template'] . '"';

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }

                    $content .= '}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Testimonial block. Please wait to reload page.');
                    break;

                /* Facebook Fan Box Block */
                case "facebook":
                    $dataInit = ['hide_cover', 'show_facepile', 'hide_call_to', 'small_header', 'fit_inside', 'show_posts'];
                    $data = $this->reInitData($data, $dataInit);
                    $tabs = implode(',', $data['setting']['facebook_tabs']);
                    $content = '{{block class="MGS\Social\Block\Panel\Widget" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" page_url="' . $data['setting']['page_url'] . '" width="' . $data['setting']['width'] . '" height="' . $data['setting']['height'] . '" facebook_tabs="' . $tabs . '" hide_cover="' . $data['setting']['hide_cover'] . '" show_facepile="' . $data['setting']['show_facepile'] . '" show_posts="' . $data['setting']['show_posts'] . '" small_header="' . $data['setting']['small_header'] . '" fit_inside="' . $data['setting']['fit_inside'] . '" template="widget/fanbox.phtml"}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the facebook Fanbox block. Please wait to reload page.');
                    break;

                /* Twitter Feed Block */
                case "twitter":
                    $dataInit = ['use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination'];
                    $data = $this->reInitData($data, $dataInit);

                    $content = '{{block class="MGS\Social\Block\Panel\Widget" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" use_slider="' . $data['setting']['use_slider'] . '" feed_count="' . $data['setting']['feed_count'] . '"';

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '"';
                    }

                    $content .= ' template="widget/twitter_feed.phtml"}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Twitter Feed block. Please wait to reload page.');
                    break;

                /* Instagram Block */
                case "instagram":
                    $dataInit = ['link', 'like', 'use_slider', 'autoplay', 'stop_auto', 'navigation', 'pagination', 'comment'];
                    $data = $this->reInitData($data, $dataInit);
                    $content = '{{block class="MGS\Social\Block\Panel\Widget" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" limit="' . $data['setting']['limit'] . '" resolution="' . $data['setting']['resolution'] . '" link="' . $data['setting']['link'] . '" perrow="' . $data['setting']['perrow'] . '" use_slider="' . $data['setting']['use_slider'] . '" like="' . $data['setting']['like'] . '" comment="' . $data['setting']['comment'] . '"';

                    if ($data['setting']['use_slider']) {
                        $content .= ' autoplay="' . $data['setting']['autoplay'] . '" stop_auto="' . $data['setting']['stop_auto'] . '" navigation="' . $data['setting']['navigation'] . '" pagination="' . $data['setting']['pagination'] . '" number_row="' . $data['setting']['number_row'] . '"';
                    }
                    $content .= ' template="widget/instagram.phtml"}}';

                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Instagram block. Please wait to reload page.');
                    break;
                /* Lookbook Block */
                case "lookbook":
                    $data['block_content'] = '{{widget type="MGS\Lookbook\Block\Widget\Lookbook" lookbook_id="' . $data['setting']['lookbook_id'] . '" template="MGS_Lookbook::widget/lookbook.phtml"}}';

                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Lookbook block. Please wait to reload page.');
                    break;

                /* Lookbook Block */
                case "lookbook_slider":
                    $data['block_content'] = '{{widget type="MGS\Lookbook\Block\Widget\Slider" slider_id="' . $data['setting']['slide_id'] . '" template="MGS_Lookbook::widget/slider.phtml"}}';

                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Lookbook Slider block. Please wait to reload page.');
                    break;
                /* Instagram Shop Block */
                case "snapppt":
                    $content = '{{block class="MGS\Social\Block\Panel\Widget" mgs_panel_title="' . $this->encodeHtml($data['setting']['title']) . '" mgs_panel_note="' . $this->encodeHtml($data['setting']['additional_content']) . '" template="widget/snapppt_shop.phtml"}}';

                    $data['block_content'] = $content;

                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Snapppt shop block. Please wait to reload page.');
                    break;
                /* Video Banner Block */
                case "video_banner":
                    $dataInit = ['autoplay', 'controls', 'loop', 'muted', 'preload'];
                    $data = $this->reInitData($data, $dataInit);

                    $htmltoString = "";
                    if ($data['setting']['html_content']) {
                        $htmltoString = htmlentities($data['setting']['html_content']);
                    }

                    $content = '{{block class="Magento\Framework\View\Element\Template" autoplay="' . $data['setting']['autoplay'] . '" controls="' . $data['setting']['controls'] . '" loop="' . $data['setting']['loop'] . '" poster_image="' . $data['setting']['poster_image'] . '" video_background="' . $data['setting']['video_background'] . '" muted="' . $data['setting']['muted'] . '" video_type="' . $data['setting']['video_type'] . '" video_url="' . $data['setting']['video_url'] . '" preload="' . $data['setting']['preload'] . '" height="' . $data['setting']['height'] . '" html_content="' . $htmltoString . '" template="MGS_Mpanel::widget/video_banner.phtml"}}';


                    $data['block_content'] = $content;

                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Video Banner block. Please wait to reload page.');
                    break;

                    
                case "start-project":
                    $this->removePanelImages('start-project', $data);
                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['title'] = $data['setting']['title'];
                    // $projectSettings['description'] = $data['setting']['description'];
                    $projectSettings['description'] = htmlentities($data['setting']['description']);
                    $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    $projectSettings['buttonLink'] = $data['setting']['buttonLink'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" start-project="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/start-project.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the project block settings. Please wait to reload page.');

                    break;

                case "imprint_project":
                    $this->removePanelImages('imprint_project', $data);
                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['title'] = $data['setting']['title'];
                    $projectSettings['description'] = $data['setting']['description'];
                    $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    $projectSettings['buttonLink'] = $data['setting']['buttonLink'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprint_project="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprint_project.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the project block settings. Please wait to reload page.');

                break;


                case "useful-tools":
                    $this->removePanelImages('useful-tools', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->toolOrder > $second->toolOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" useful-tools="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/useful-tools.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the useful tools. Please wait to reload page.');

                break;

                case "drinkware":
                    $this->removePanelImages('drinkware', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['description'] = $data['setting']['description'];
                    $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" drinkware="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/drinkware.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Drinkware. Please wait to reload page.');

                break;

                case "edit_page_drinkware":
                    $this->removePanelImages('edit_page_drinkware', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['description'] = $data['setting']['description'];
                    $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" edit_page_drinkware="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/edit_page_drinkware.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Drinkware. Please wait to reload page.');

                break;


                case "virtual_mock_up_useful_tools":
                    $this->removePanelImages('virtual_mock_up_useful_tools', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['description'] = $data['setting']['description'];
                    $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" virtual_mock_up_useful_tools="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/virtual_mock_up_useful_tools.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Drinkware. Please wait to reload page.');
                break;

                case "product_data_download":
                    $this->removePanelImages('product_data_download', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    // $toolSettings['description'] = $data['setting']['description'];
                    $projectSettings['description'] = htmlentities($data['setting']['description']);
                    $toolSettings['button_title'] = $data['setting']['button_title'];
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" product_data_download="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/product_data_download.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Product Data Download. Please wait to reload page.');

                break;

                case "pattern_gallery":
                    $this->removePanelImages('pattern_gallery', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    // $toolSettings['description'] = $data['setting']['description'];
                    // $projectSettings['description123'] = htmlentities($data['setting']['description123']);
                    // $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_title'] = htmlentities($data['setting']['button_title']);
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" pattern_gallery="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/pattern_gallery.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Pattern Gallery. Please wait to reload page.');

                break;

                case "pattern_gallery_landing_page":
                    $this->removePanelImages('pattern_gallery_landing_page', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    // $toolSettings['description'] = $data['setting']['description'];
                    // $projectSettings['description123'] = htmlentities($data['setting']['description123']);
                    // $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_title'] = htmlentities($data['setting']['button_title']);
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" pattern_gallery_landing_page="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/pattern_gallery_landing_page.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Pattern Gallery. Please wait to reload page.');

                break;


                case "document_library":
                    $this->removePanelImages('document_library', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    // $toolSettings['description'] = $data['setting']['description'];
                    // $projectSettings['description123'] = htmlentities($data['setting']['description123']);
                    // $toolSettings['button_title'] = $data['setting']['button_title'];
                    $toolSettings['button_title'] = htmlentities($data['setting']['button_title']);
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->drinkOrder > $second->drinkOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" document_library="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/document_library.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Document Library. Please wait to reload page.');

                break;

                case "customer_service":
                    $this->removePanelImages('customer_service', $data);
                    $data['col'] = 12;
                    $toolSettings = [];
                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['description'] = $data['setting']['description'];
                    // $toolSettings['button_title'] = $data['setting']['button_title'];
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];
                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->cuserOrder > $second->cuserOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }
                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" customer_service="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/customer_service.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Customer Service. Please wait to reload page.');
                break;

                case "yourtoolbox":
                    $this->removePanelImages('yourtoolbox', $data);
                    $data['col'] = 12;
                    $toolSettings = [];
                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['description'] = $data['setting']['description'];
                    // $toolSettings['button_title'] = $data['setting']['button_title'];
                    // $toolSettings['button_link'] = $data['setting']['button_link'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];
                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->yourtoolOrder > $second->yourtoolOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }
                    $jsonData = base64_encode(json_encode($toolSettings));
                    $content = '{{block class="Magento\Framework\View\Element\Template" yourtoolbox="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/yourtoolbox.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Customer Service. Please wait to reload page.');
                break;
                
                case "imprintview_faqs":
                    $this->removePanelImages('imprintview_faqs', $data);
                    $data['col'] = 12;
                    $imprintSettings = [];

                    $imprintSettings['title'] = $data['setting']['title'];
                    $imprintSettings['impfaq_seeall'] = $data['setting']['impfaq_seeall'];
                    $imprintSettings['impfaq_seealllink'] = $data['setting']['impfaq_seealllink'];
                    $imprintSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $imprintArray = json_decode($data['setting']['toolsJson']);
                        usort($imprintArray, function ($first, $second) {
                            return $first->toolOrder > $second->toolOrder;
                        });
                        $imprintSettings['toolsJson'] = json_encode($imprintArray);
                    }

                    $jsonData = base64_encode(json_encode($imprintSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprintview_faqs="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprintview_faqs.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the imprintlist view tag . Please wait to reload page.');

                break;

                case "imprintview_gallery":
                    $this->removePanelImages('imprintview_gallery', $data);
                    $data['col'] = 12;
                    $toolSettings = [];

                    $toolSettings['title'] = $data['setting']['title'];
                    $toolSettings['toolsJson'] = $data['setting']['toolsJson'];


                    if (isset($data['setting']['toolsJson'])) {
                        $toolArray = json_decode($data['setting']['toolsJson']);
                        usort($toolArray, function ($first, $second) {
                            return $first->toolOrder > $second->toolOrder;
                        });
                        $toolSettings['toolsJson'] = json_encode($toolArray);
                    }

                    $jsonData = base64_encode(json_encode($toolSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprintview_gallery="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprintview_gallery.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Gallery. Please wait to reload page.');

                break;


                case "imprint_list":
                    $this->removePanelImages('imprint_list', $data);
                    $data['col'] = 12;
                    $imprintSettings = [];

                    $imprintSettings['title'] = $data['setting']['title'];
                    $imprintSettings['imprintList'] = $data['setting']['imprintList'];


                    if (isset($data['setting']['imprintList'])) {
                        $imprintArray = json_decode($data['setting']['imprintList']);
                        usort($imprintArray, function ($first, $second) {
                            return $first->toolOrder > $second->toolOrder;
                        });
                        $imprintSettings['imprintList'] = json_encode($imprintArray);
                    }

                    $jsonData = base64_encode(json_encode($imprintSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprint_list="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprint_list.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the imprint list. Please wait to reload page.');

                break;

                case "imprintlist_view_tag":
                    $this->removePanelImages('imprintlist_view_tag', $data);
                    $data['col'] = 12;
                    $imprintSettings = [];

                    $imprintSettings['title'] = $data['setting']['title'];
                    $imprintSettings['imptag_shopproduct'] = $data['setting']['imptag_shopproduct'];
                    $imprintSettings['imptag_shopprolink'] = $data['setting']['imptag_shopprolink'];
                    $imprintSettings['imprintView'] = $data['setting']['imprintView'];


                    if (isset($data['setting']['imprintView'])) {
                        $imprintArray = json_decode($data['setting']['imprintView']);
                        usort($imprintArray, function ($first, $second) {
                            return $first->toolOrder > $second->toolOrder;
                        });
                        $imprintSettings['imprintView'] = json_encode($imprintArray);
                    }

                    $jsonData = base64_encode(json_encode($imprintSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprintlist_view_tag="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprintlist_view_tag.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the imprintlist view tag . Please wait to reload page.');

                break;



                case "imprint-methods":
                    $this->removePanelImages('imprint-methods', $data);
                    $data['col'] = 12;
                    $imprintSettings = [];

                    $imprintSettings['methodsJson'] = $data['setting']['methodsJson'];
                    $imprintSettings['title'] = $data['setting']['title'];
                    $imprintSettings['linkText'] = $data['setting']['linkText'];
                    $imprintSettings['linkUrl'] = $data['setting']['linkUrl'];
                    $imprintSettings['background_all'] = $data['setting']['background_all'];
                    $imprintSettings['background_two'] = $data['setting']['background_two'];

                    if (isset($data['setting']['methodsJson'])) {
                        $methodsArray = json_decode($data['setting']['methodsJson']);
                        usort($methodsArray, function ($first, $second) {
                            return $first->imprintOrder > $second->imprintOrder;
                        });
                        $imprintSettings['methodsJson'] = json_encode($methodsArray);
                    }


                    $jsonData = base64_encode(json_encode($imprintSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprint-methods="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprint-methods.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the imprint methods. Please wait to reload page.');
                break;

                case "ship-saver":
                    $this->removePanelImages('ship-saver', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['backgroundImage'] = $data['setting']['backgroundImage'];
                    $projectSettings['backgroundImagemo'] = $data['setting']['backgroundImagemo'];
                    $projectSettings['title'] = htmlentities($data['setting']['title']);
                    $projectSettings['description'] = htmlentities($data['setting']['description']);
                    $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    $projectSettings['buttonLink'] = $data['setting']['buttonLink'];
                    $projectSettings['imagespopup'] = $data['setting']['imagespopup'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" ship-saver="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/ship-saver.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the shipping saver solution. Please wait to reload page.');

                break;

                case "privacy-policy":
                    $this->removePanelImages('privacy-policy', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    // $projectSettings['backgroundImage'] = $data['setting']['backgroundImage'];
                    // $projectSettings['backgroundImagemo'] = $data['setting']['backgroundImagemo'];
                    $projectSettings['title'] = htmlentities($data['setting']['title']);
                    $projectSettings['description'] = htmlentities($data['setting']['description']);
                    // $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    // $projectSettings['buttonLink'] = $data['setting']['buttonLink'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" privacy-policy="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/privacy-policy.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Privacy Policy solution. Please wait to reload page.');

                break;

                case "apparel_terms":
                    $this->removePanelImages('apparel_terms', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    // $projectSettings['backgroundImage'] = $data['setting']['backgroundImage'];
                    // $projectSettings['backgroundImagemo'] = $data['setting']['backgroundImagemo'];
                    $projectSettings['title'] = htmlentities($data['setting']['title']);
                    $projectSettings['description'] = htmlentities($data['setting']['description']);
                    // $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    // $projectSettings['buttonLink'] = $data['setting']['buttonLink'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" apparel_terms="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/apparel_terms.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Apparel Terms. Please wait to reload page.');

                break;

                
                case "price_table":
                    $this->removePanelImages('price_table', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    //$projectSettings['title'] = htmlentities($data['setting']['title']);
                    $projectSettings['title'] = $data['setting']['title'];
                    $projectSettings['description'] = htmlentities($data['setting']['description']);

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" price_table="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/price_table.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Price Table solution. Please wait to reload page.');

                break;

                case "instagramsweda":
                    $this->removePanelImages('instagramsweda', $data);

                    $data['col'] = 12;
                    $projectSettings = [];
                    $projectSettings['title'] = $data['setting']['title'];
                    $projectSettings['description'] = htmlentities($data['setting']['description']);

                    $projectSettings['instagram_images_tags'] = $data['setting']['instagram_images_tags'];
                    $projectSettings['limit_images'] = $data['setting']['limit_images'];
                    
                    $projectSettings['instagramone'] = $data['setting']['instagramone'];
                    $projectSettings['instagramtwo'] = $data['setting']['instagramtwo'];
                    $projectSettings['instagramthree'] = $data['setting']['instagramthree'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" instagramsweda="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/instagramsweda.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Instagram solution. Please wait to reload page.');

                break;


                case "newsletter":
                    $this->removePanelImages('newsletter', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['backgroundImage'] = $data['setting']['backgroundImage'];
                    $projectSettings['backgroundImagemo'] = $data['setting']['backgroundImagemo'];
                    $projectSettings['parallaxleft'] = $data['setting']['parallaxleft'];
                    $projectSettings['parallaxright'] = $data['setting']['parallaxright'];
                    $projectSettings['title'] = $data['setting']['title'];
                    $projectSettings['description'] = $data['setting']['description'];
                    // $projectSettings['placeholder'] = $data['setting']['placeholder'];
                    $projectSettings['buttonText'] = $data['setting']['buttonText'];
                    // $projectSettings['buttonLink'] = $data['setting']['buttonLink'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" newsletter="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/newsletter.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the newsletter settings. Please wait to reload page.');

                break;


                case "aboutus":
                    $this->removePanelImages('aboutus', $data);    

                     $data['col'] = 12;
                     $projectSettings = [];

                     $projectSettings['title'] = $data['setting']['title'];
                     $projectSettings['backgroundImage'] = $data['setting']['backgroundImage'];
                     $projectSettings['backgroundImagemobile'] = $data['setting']['backgroundImagemobile'];
                     // $projectSettings['backgroundImagevideo'] = $data['setting']['backgroundImagevideo'];

                     $projectSettings['title_why_customers'] = $data['setting']['title_why_customers'];
                     $projectSettings['main_why_customers'] = $data['setting']['main_why_customers'];
                     $projectSettings['main_why_customersmobile'] = $data['setting']['main_why_customersmobile'];

                     $projectSettings['title_one'] = $data['setting']['title_one'];
                     //$projectSettings['desctiption_one'] = $data['setting']['desctiption_one'];
                     $projectSettings['desctiption_one'] = htmlentities($data['setting']['desctiption_one']);
                     $projectSettings['imgwhy_one'] = $data['setting']['imgwhy_one'];

                     $projectSettings['title_two'] = $data['setting']['title_two'];
                     // $projectSettings['desctiption_two'] = $data['setting']['desctiption_two'];
                     $projectSettings['desctiption_two'] = htmlentities($data['setting']['desctiption_two']);
                     $projectSettings['imgwhy_two'] = $data['setting']['imgwhy_two'];

                     $projectSettings['title_three'] = $data['setting']['title_three'];
                     $projectSettings['desctiption_three'] = htmlentities($data['setting']['desctiption_three']);
                     $projectSettings['imgwhy_three'] = $data['setting']['imgwhy_three'];


                     $projectSettings['title_story_1'] = $data['setting']['title_story_1'];
                     $projectSettings['desctiption_story_1'] = htmlentities($data['setting']['desctiption_story_1']);
                     // $projectSettings['desctiption_story_1'] = $data['setting']['desctiption_story_1'];
                     $projectSettings['imgsave_story_1'] = $data['setting']['imgsave_story_1'];

                     $projectSettings['title_story_2'] = $data['setting']['title_story_2'];
                     $projectSettings['desctiption_story_2'] = htmlentities($data['setting']['desctiption_story_2']);
                     // $projectSettings['desctiption_story_2'] = $data['setting']['desctiption_story_2'];
                     $projectSettings['imgsave_story_2'] = $data['setting']['imgsave_story_2'];
                     $projectSettings['imgsave_storybackground_2'] = $data['setting']['imgsave_storybackground_2'];


                     $projectSettings['title_spirit_2'] = $data['setting']['title_spirit_2'];
                     // $projectSettings['desctiption_spirit_2'] = $data['setting']['desctiption_spirit_2'];
                     $projectSettings['desctiption_spirit_2'] = htmlentities($data['setting']['desctiption_spirit_2']);
                     $projectSettings['imgsave_spirit_2'] = $data['setting']['imgsave_spirit_2'];
                     $projectSettings['imgsave_spirit_3'] = $data['setting']['imgsave_spirit_3'];


                     $jsonData = base64_encode(json_encode($projectSettings));

                     $content = '{{block class="Magento\Framework\View\Element\Template" aboutus="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/aboutus.phtml"}}';
                     $data['block_content'] = $content;
                     $result['message'] = 'success';
                     $sessionMessage = __('You saved the aboutus settings. Please wait to reload page.');

                 break;


                case "imprint_section_one":
                    $this->removePanelImages('imprint_section_one', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    $projectSettings['impdesctiption_one'] = $data['setting']['impdesctiption_one'];
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    $projectSettings['impbackgroundImagevideo'] = $data['setting']['impbackgroundImagevideo'];
                    $projectSettings['logobackgroundImage'] = $data['setting']['logobackgroundImage'];
                    $projectSettings['impbackgroundImagevideopriv'] = $data['setting']['impbackgroundImagevideopriv'];
                    
                    $projectSettings['imptitle_two'] = $data['setting']['imptitle_two'];
                    $projectSettings['impdesctiption_two'] = $data['setting']['impdesctiption_two'];


                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprint_section_one="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprint_section_one.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Imprint Method settings. Please wait to reload page.');

                break;

                case "video_library":
                    $this->removePanelImages('video_library', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    
                    $projectSettings['full_video_link'] = $data['setting']['full_video_link'];
                    $projectSettings['preview_video_link'] = $data['setting']['preview_video_link'];
                    //$projectSettings['impdesctiption_one'] = $data['setting']['impdesctiption_one'];
                    $projectSettings['impdesctiption_one'] = htmlentities($data['setting']['impdesctiption_one']);
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    $projectSettings['impbackgroundImagevideo'] = $data['setting']['impbackgroundImagevideo'];
                    $projectSettings['impbackgroundImagevideopriv'] = $data['setting']['impbackgroundImagevideopriv'];
                    $projectSettings['imptitle_two'] = $data['setting']['imptitle_two'];
                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" video_library="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/video_library.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the Video Library settings. Please wait to reload page.');

                break;

                case "pet_products_k9s_for_warriors":
                    $this->removePanelImages('pet_products_k9s_for_warriors', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    // $projectSettings['impdesctiption_one'] = $data['setting']['impdesctiption_one'];
                    $projectSettings['impdesctiption_one'] = htmlentities($data['setting']['impdesctiption_one']);
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    //$projectSettings['impbackgroundImagevideo'] = $data['setting']['impbackgroundImagevideo'];
                    $projectSettings['logobackgroundImage'] = $data['setting']['logobackgroundImage'];
                    // $projectSettings['impbackgroundImagevideopriv'] = $data['setting']['impbackgroundImagevideopriv'];
                    
                    // $projectSettings['imptitle_two'] = $data['setting']['imptitle_two'];
                    // $projectSettings['impdesctiption_two'] = $data['setting']['impdesctiption_two'];


                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" pet_products_k9s_for_warriors="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/pet_products_k9s_for_warriors.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the pet_products_k9s_for_warriors settings. Please wait to reload page.');

                break;

                case "basecamp_the_warrior_spirit_retreat":
                    $this->removePanelImages('basecamp_the_warrior_spirit_retreat', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    // $projectSettings['impdesctiption_one'] = $data['setting']['impdesctiption_one'];
                    $projectSettings['impdesctiption_one'] = htmlentities($data['setting']['impdesctiption_one']);
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    //$projectSettings['impbackgroundImagevideo'] = $data['setting']['impbackgroundImagevideo'];
                    // $projectSettings['logobackgroundImage'] = $data['setting']['logobackgroundImage'];
                    // $projectSettings['impbackgroundImagevideopriv'] = $data['setting']['impbackgroundImagevideopriv'];
                    
                    // $projectSettings['imptitle_two'] = $data['setting']['imptitle_two'];

                    $projectSettings['impdesctiption_two'] = htmlentities($data['setting']['impdesctiption_two']);
                    $projectSettings['video_iframe'] = htmlentities($data['setting']['video_iframe']);


                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" basecamp_the_warrior_spirit_retreat="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/basecamp_the_warrior_spirit_retreat.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the basecamp the warrior spirit retreat settings. Please wait to reload page.');

                break;


                case "virtual_mock_up_tool":
                    $this->removePanelImages('virtual_mock_up_tool', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    // $projectSettings['description_top'] = $data['setting']['description_top'];
                    $projectSettings['description_top'] = htmlentities($data['setting']['description_top']);
                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    $projectSettings['description_bottom'] = htmlentities($data['setting']['description_bottom']);
                    // $projectSettings['description_bottom'] = $data['setting']['description_bottom'];
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    $projectSettings['logobackgroundImage'] = $data['setting']['logobackgroundImage'];
                    $projectSettings['BackgroundImages'] = $data['setting']['BackgroundImages'];

                    $projectSettings['button_title'] = $data['setting']['button_title'];
                    $projectSettings['button_link'] = $data['setting']['button_link'];

                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" virtual_mock_up_tool="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/virtual_mock_up_tool.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the virtual mock up tool settings. Please wait to reload page.');

                break;

                case "imprintlist_view":
                    $this->removePanelImages('imprintlist_view', $data);

                    $data['col'] = 12;
                    $projectSettings = [];

                    $projectSettings['imptitle_one'] = $data['setting']['imptitle_one'];
                    $projectSettings['link_details_page'] = $data['setting']['link_details_page'];
                    // $projectSettings['impdesctiption_one'] = $data['setting']['impdesctiption_one'];
                    $projectSettings['impdesctiption_one'] = htmlentities($data['setting']['impdesctiption_one']);
                    $projectSettings['impbackgroundImage'] = $data['setting']['impbackgroundImage'];
                    $projectSettings['logobackgroundImage'] = $data['setting']['logobackgroundImage'];
                    $projectSettings['impbackgroundImagevideo'] = $data['setting']['impbackgroundImagevideo'];
                    $projectSettings['impbackgroundImagevideopriv'] = $data['setting']['impbackgroundImagevideopriv'];
                    $projectSettings['external_video_url'] = $data['setting']['external_video_url'];

                    $projectSettings['full_video_link'] = $data['setting']['full_video_link'];
                    $projectSettings['preview_video_link'] = $data['setting']['preview_video_link'];
                    //$projectSettings['video_linkone'] = $data['setting']['video_linkone'];
                    //$projectSettings['video_linktwo'] = $data['setting']['video_linktwo'];
                    //$projectSettings['video_linkthree'] = $data['setting']['video_linkthree'];
                    
                    
                    $projectSettings['imptitle_two'] = $data['setting']['imptitle_two'];
                    // $projectSettings['impdesctiption_two'] = $data['setting']['impdesctiption_two'];
                    $projectSettings['impdesctiption_two'] = htmlentities($data['setting']['impdesctiption_two']);


                    $jsonData = base64_encode(json_encode($projectSettings));

                    $content = '{{block class="Magento\Framework\View\Element\Template" imprintlist_view="' . $this->encodeHtml($jsonData) . '" template="MGS_Mpanel::widget/imprintlist_view.phtml"}}';
                    $data['block_content'] = $content;
                    $result['message'] = 'success';
                    $sessionMessage = __('You saved the imprintlist_view settings. Please wait to reload page.');

                break;        
            }

            $types = array('config', 'layout', 'block_html', 'collections', 'reflection', 'db_ddl', 'eav', 'config_integration', 'config_integration_api', 'full_page', 'translate', 'config_webservice');
            $types = array('block_html', 'collections', 'full_page');
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
            }
            foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                //$cacheFrontend->getBackend()->clean();
            }
            if ($result['message'] == 'success') {
                $this->saveBlockData($data, $sessionMessage);
            } else {
                return $this->getMessageHtml('danger', $result['message'], false);
            }
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }

    /* Create Promotion Banner */
    public function savePromoBanner($data)
    {
        if (isset($data['identifier'])) {

            if (!isset($data['banner_id'])) {
                $existBanners = $this->getModel('MGS\Promobanners\Model\Promobanners')
                    ->getCollection()
                    ->addFieldToFilter('identifier', $data['identifier']);

                if (count($existBanners) > 0) {
                    $result['message'] = __('Identifier already exist. Please use other identifier');
                    return $result;
                }
            }

            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'filename']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                } catch (\Exception $e) {
                    $result['message'] = $e->getMessage();
                    return $result;
                }
                $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('promobanners/');
                $uploader->save($path);
                $fileName = $uploader->getUploadedFileName();
                $data['filename'] = $fileName;
            }

            $model = $this->getModel('MGS\Promobanners\Model\Promobanners');
            $model->setData($data);

            if (isset($data['banner_id'])) {
                $id = $data['banner_id'];
                unset($data['banner_id']);
                $model->setId($id);
            }

            try {
                // save the data
                $model->save();

                $result['message'] = 'success';
                $result['data'] = $model->getIdentifier();
                return $result;
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
                return $result;
            }
        }
    }

    /* Save data to childs table */
    public function saveBlockData($data, $sessionMessage)
    {
        $model = $this->getModel('MGS\Mpanel\Model\Childs');
        $data['setting'] = json_encode($data['setting']);
        if ($data['type'] == 'separator') {
            $data['col'] = 12;
        }

        if (!isset($data['child_id'])) {
            $storeId = $this->_storeManager->getStore()->getId();
            $data['store_id'] = $storeId;
            $data['position'] = $this->getNewPositionOfChild($data['store_id'], $data['block_name']);
        }


        $model->setData($data);
        if (isset($data['child_id'])) {
            $id = $data['child_id'];
            unset($data['child_id']);
            $model->setId($id);
        }
        try {
            // save the data
            $model->save();
            return $this->getMessageHtml('success', $sessionMessage, true);
        } catch (\Exception $e) {
            return $this->getMessageHtml('danger', $e->getMessage(), false);
        }
    }

    /* Set value 0 for not exist data */
    public function reInitData($data, $dataInit)
    {
        foreach ($dataInit as $item) {
            if (!isset($data['setting'][$item])) {
                $data['setting'][$item] = 0;
            }
        }
        return $data;
    }

    /* Get position of new block for sort */
    public function getNewPositionOfChild($storeId, $blockName)
    {
        $child = $this->getModel('MGS\Mpanel\Model\Childs')
            ->getCollection()
            ->addFieldToFilter('store_id', $storeId)
            ->addFieldToFilter('block_name', $blockName)
            ->setOrder('position', 'DESC')
            ->getFirstItem();

        if ($child->getId()) {
            $position = (int)$child->getPosition() + 1;
        } else {
            $position = 1;
        }

        return $position;
    }

    /* Show message after save block */
    public function getMessageHtml($type, $message, $reload)
    {
        $html = '<style type="text/css">
			.container {
				padding: 0px 15px;
				margin-top:60px;
			}
			.page.messages .message {
				padding: 15px;
				font-family: "Lato",arial,tahoma;
				font-size: 14px;
			}
			.page.messages .message-success {
				background-color: #dff0d8;
			}
			.page.messages .message-danger {
				background-color: #f2dede;
			}
		</style>';
        $html .= '<main class="page-main container">
			<div class="page messages"><div data-placeholder="messages"></div><div>
				<div class="messages">
					<div class="message-' . $type . ' ' . $type . ' message" data-ui-id="message-' . $type . '">
						<div>' . $message . '</div>
					</div>
				</div>
			</div>
		</div></main>';

        if ($reload) {
            $html .= '<script type="text/javascript">window.parent.location.reload();</script>';
        }

        return $this->getResponse()->setBody($html);
    }

    public function removePanelImages($type, $data)
    {
        if (isset($data['remove']) && (count($data['remove']) > 0)) {
            foreach ($data['remove'] as $filename) {
                $filePath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('wysiwyg/' . $type . '/') . $filename;
                if ($this->_file->isExists($filePath)) {
                    $this->_file->deleteFile($filePath);
                }
            }
        }
    }

    public function encodeHtml($html)
    {
        $result = str_replace("<", "&lt;", $html);
        $result = str_replace(">", "&gt;", $result);
        $result = str_replace('"', '&#34;', $result);
        $result = str_replace("'", "&#39;", $result);
        return $result;
    }
}