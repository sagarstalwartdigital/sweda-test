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

namespace Mageplaza\PromoStandards\Block\Adminhtml\Post\Edit\Tab;

use DateTimeZone;
use IntlDateFormatter;
use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Cms\Model\Page\Source\PageLayout as BasePageLayout;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Config\Model\Config\Source\Design\Robots;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\System\Store;
use Mageplaza\PromoStandards\Block\Adminhtml\Post\Edit\Tab\Renderer\Category;
use Mageplaza\PromoStandards\Block\Adminhtml\Post\Edit\Tab\Renderer\Tag;
use Mageplaza\PromoStandards\Block\Adminhtml\Post\Edit\Tab\Renderer\Topic;
use Mageplaza\PromoStandards\Helper\Image;

/**
 * Class Post
 * @package Mageplaza\PromoStandards\Block\Adminhtml\Post\Edit\Tab
 */
class Post extends Generic implements TabInterface
{
    /**
     * Wysiwyg config
     *
     * @var Config
     */
    public $wysiwygConfig;

    /**
     * Country options
     *
     * @var Yesno
     */
    public $booleanOptions;

    /**
     * @var Robots
     */
    public $metaRobotsOptions;

    /**
     * @var Store
     */
    public $systemStore;

    /**
     * @var Session
     */
    protected $authSession;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var Enabledisable
     */
    protected $enabledisable;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var BasePageLayout
     */
    protected $_layoutOptions;

    /**
     * Post constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Session $authSession
     * @param DateTime $dateTime
     * @param BasePageLayout $layoutOption
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Yesno $booleanOptions
     * @param Enabledisable $enableDisable
     * @param Robots $metaRobotsOptions
     * @param Store $systemStore
     * @param Image $imageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $authSession,
        DateTime $dateTime,
        BasePageLayout $layoutOption,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Yesno $booleanOptions,
        Enabledisable $enableDisable,
        Robots $metaRobotsOptions,
        Store $systemStore,
        Image $imageHelper,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->booleanOptions = $booleanOptions;
        $this->enabledisable = $enableDisable;
        $this->metaRobotsOptions = $metaRobotsOptions;
        $this->systemStore = $systemStore;
        $this->authSession = $authSession;
        $this->_date = $dateTime;
        $this->_layoutOptions = $layoutOption;
        $this->imageHelper = $imageHelper;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\PromoStandards\Model\Post $post */
        $post = $this->_coreRegistry->registry('mageplaza_promostandards_post');

        /** @var Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Post Information'),
            'class'  => 'fieldset-wide'
        ]);

        $fieldset->addField('author_id', 'hidden', ['name' => 'author_id']);

        $fieldset->addField('name', 'text', [
            'name'     => 'name',
            'label'    => __('Name'),
            'title'    => __('Name'),
            'required' => true
        ]);


        $fieldset->addField('enabled', 'select', [
            'name'   => 'enabled',
            'label'  => __('Status'),
            'title'  => __('Status'),
            'values' => $this->enabledisable->toOptionArray()
        ]);
        if (!$post->hasData('enabled')) {
            $post->setEnabled(1);
        }

    
        $fieldset->addField('post_content', 'editor', [
            'name'   => 'post_content',
            'label'  => __('Content'),
            'title'  => __('Content'),
            'config' => $this->wysiwygConfig->getConfig([
                'add_variables'  => false,
                'add_widgets'    => true,
                'add_directives' => true
            ])
        ]);

        $fieldset->addField('short_description', 'editor', [
            'name'   => 'short_description',
            'label'  => __('Faqs'),
            'title'  => __('Faqs'),
            'config' => $this->wysiwygConfig->getConfig([
                'add_variables'  => false,
                'add_widgets'    => true,
                'add_directives' => true
            ])

        ]);

        if ($this->_storeManager->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        } else {
            /** @var RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock(Element::class);
            $fieldset->addField('store_ids', 'multiselect', [
                'name'   => 'store_ids',
                'label'  => __('Store Views'),
                'title'  => __('Store Views'),
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);

            if (!$post->hasData('store_ids')) {
                $post->setStoreIds(0);
            }
        }

        $fieldset->addField('image', \Mageplaza\PromoStandards\Block\Adminhtml\Renderer\Image::class, [
            'name'  => 'image',
            'label' => __('Image'),
            'title' => __('Image'),
            'path'  => $this->imageHelper->getBaseMediaPath(Image::TEMPLATE_MEDIA_TYPE_POST)
        ]);

        // $fieldset->addField('categories_ids', Category::class, [
        //     'name'  => 'categories_ids',
        //     'label' => __('Categories'),
        //     'title' => __('Categories'),
        // ]);
        // if (!$post->getCategoriesIds()) {
        //     $post->setCategoriesIds($post->getCategoryIds());
        // }

        $fieldset->addField('topics_ids', Topic::class, [
            'name'  => 'topics_ids',
            'label' => __('Categories'),
            'title' => __('Categories'),
        ]);
        if (!$post->getTopicsIds()) {
            $post->setTopicsIds($post->getTopicIds());
        }

        // $fieldset->addField('tags_ids', Tag::class, [
        //     'name'  => 'tags_ids',
        //     'label' => __('Tags'),
        //     'title' => __('Tags'),
        // ]);
        // if (!$post->getTagsIds()) {
        //     $post->setTagsIds($post->getTagIds());
        // }

        // $fieldset->addField('in_rss', 'select', [
        //     'name'   => 'in_rss',
        //     'label'  => __('In RSS'),
        //     'title'  => __('In RSS'),
        //     'values' => $this->booleanOptions->toOptionArray(),
        // ]);
        // $fieldset->addField('allow_comment', 'select', [
        //     'name'   => 'allow_comment',
        //     'label'  => __('Allow Comment'),
        //     'title'  => __('Allow Comment'),
        //     'values' => $this->booleanOptions->toOptionArray(),
        // ]);

        $fieldset->addField('get_started_top', 'text', [
            'name'     => 'get_started_top',
            'label'    => __('Iframe src link'),
            'title'    => __('Iframe src link')
        ]);
        $fieldset->addField('get_started_toplink', 'text', [
            'name'     => 'get_started_toplink',
            'label'    => __('Get Started Top Link'),
            'title'    => __('Get Started Top Link')
        ]);

        $fieldset->addField('get_started_bottom', 'text', [
            'name'     => 'get_started_bottom',
            'label'    => __('Get Started Bottom Title'),
            'title'    => __('Get Started Bottom Title')
        ]);
        $fieldset->addField('get_started_bottomlink', 'text', [
            'name'     => 'get_started_bottomlink',
            'label'    => __('Get Started Bottom Link'),
            'title'    => __('Get Started Bottom Link')
        ]);
        
        $fieldset->addField('publish_date', 'date', [
            'name'        => 'publish_date',
            'label'       => __('Publish Date'),
            'title'       => __('Publish Date'),
            'date_format' => 'M/d/yyyy',
            'timezone'    => false,
            'value'       => $this->_date->date('m/d/Y')
        ]);

        /** get current time for public_time field */
        $currentTime = new \DateTime($this->_date->date(), new DateTimeZone('UTC'));
        $currentTime->setTimezone(new DateTimeZone($this->_localeDate->getConfigTimezone()));
        $time = $currentTime->format('H,i,s');

        $fieldset->addField('publish_time', 'time', [
            'name'     => 'publish_time',
            'label'    => __('Publish Time'),
            'title'    => __('Publish Time'),
            'format'   => $this->_localeDate->getTimeFormat(IntlDateFormatter::SHORT),
            'timezone' => false,
            'value'    => $time
        ]);

        $seoFieldset = $form->addFieldset('seo_fieldset', [
            'legend' => __('Search Engine Optimization'),
            'class'  => 'fieldset-wide'
        ]);
        $seoFieldset->addField('url_key', 'text', [
            'name'  => 'url_key',
            'label' => __('URL Key'),
            'title' => __('URL Key')
        ]);
        $seoFieldset->addField('meta_title', 'text', [
            'name'  => 'meta_title',
            'label' => __('Meta Title'),
            'title' => __('Meta Title')
        ]);
        $seoFieldset->addField('meta_description', 'textarea', [
            'name'  => 'meta_description',
            'label' => __('Meta Description'),
            'title' => __('Meta Description')
        ]);
        $seoFieldset->addField('meta_keywords', 'textarea', [
            'name'  => 'meta_keywords',
            'label' => __('Meta Keywords'),
            'title' => __('Meta Keywords')
        ]);
        $seoFieldset->addField('meta_robots', 'select', [
            'name'   => 'meta_robots',
            'label'  => __('Meta Robots'),
            'title'  => __('Meta Robots'),
            'values' => $this->metaRobotsOptions->toOptionArray()
        ]);

        $designFieldset = $form->addFieldset('design_fieldset', [
            'legend' => __('Design'),
            'class'  => 'fieldset-wide'
        ]);

        $designFieldset->addField('layout', 'select', [
            'name'   => 'layout',
            'label'  => __('Layout'),
            'title'  => __('Layout'),
            'values' => $this->_layoutOptions->toOptionArray()
        ]);

        if (!$post->getId()) {
            $post->addData([
                'allow_comment'    => 1,
                'meta_title'       => $this->_scopeConfig->getValue('promostandards/seo/meta_title'),
                'meta_description' => $this->_scopeConfig->getValue('promostandards/seo/meta_description'),
                'meta_keywords'    => $this->_scopeConfig->getValue('promostandards/seo/meta_keywords'),
                'meta_robots'      => $this->_scopeConfig->getValue('promostandards/seo/meta_robots'),
            ]);
        }

        // $seoFieldset = $form->addFieldset('fags_set', [
        //     'legend' => __('Manage Faqs'),
        //     'class'  => 'fieldset-wide'
        // ]);

        // // Question 1
        // $seoFieldset->addField('question_1', 'text', [
        //     'name'  => 'question_1',
        //     'label' => __('Question 1'),
        //     'title' => __('Question 1')
        // ]);
        // $seoFieldset->addField('answer_1', 'textarea', [
        //     'name'  => 'answer_1',
        //     'label' => __('Answer 1'),
        //     'title' => __('Answer 1')
        // ]);
        // // Question 2
        // $seoFieldset->addField('question_2', 'text', [
        //     'name'  => 'question_2',
        //     'label' => __('Question 2'),
        //     'title' => __('Question 2')
        // ]);
        // $seoFieldset->addField('answer_2', 'textarea', [
        //     'name'  => 'answer_2',
        //     'label' => __('Answer 2'),
        //     'title' => __('Answer 2')
        // ]);
        // // Question 3
        // $seoFieldset->addField('question_3', 'text', [
        //     'name'  => 'question_3',
        //     'label' => __('Question 3'),
        //     'title' => __('Question 3')
        // ]);
        // $seoFieldset->addField('answer_3', 'textarea', [
        //     'name'  => 'answer_3',
        //     'label' => __('Answer 3'),
        //     'title' => __('Answer 3')
        // ]);
        // // Question 4
        // $seoFieldset->addField('question_4', 'text', [
        //     'name'  => 'question_4',
        //     'label' => __('Question 4'),
        //     'title' => __('Question 4')
        // ]);
        // $seoFieldset->addField('answer_4', 'textarea', [
        //     'name'  => 'answer_4',
        //     'label' => __('Answer 4'),
        //     'title' => __('Answer 4')
        // ]);
        // // Question 5
        // $seoFieldset->addField('question_5', 'text', [
        //     'name'  => 'question_5',
        //     'label' => __('Question 5'),
        //     'title' => __('Question 5')
        // ]);
        // $seoFieldset->addField('answer_5', 'textarea', [
        //     'name'  => 'answer_5',
        //     'label' => __('Answer 5'),
        //     'title' => __('Answer 5')
        // ]);
        // // Question 6
        // $seoFieldset->addField('question_6', 'text', [
        //     'name'  => 'question_6',
        //     'label' => __('Question 6'),
        //     'title' => __('Question 6')
        // ]);
        // $seoFieldset->addField('answer_6', 'textarea', [
        //     'name'  => 'answer_6',
        //     'label' => __('Answer 6'),
        //     'title' => __('Answer 6')
        // ]);
        // // Question 7
        // $seoFieldset->addField('question_7', 'text', [
        //     'name'  => 'question_7',
        //     'label' => __('Question 7'),
        //     'title' => __('Question 7')
        // ]);
        // $seoFieldset->addField('answer_7', 'textarea', [
        //     'name'  => 'answer_7',
        //     'label' => __('Answer 7'),
        //     'title' => __('Answer 7')
        // ]);
        // // Question 8
        // $seoFieldset->addField('question_8', 'text', [
        //     'name'  => 'question_8',
        //     'label' => __('Question 8'),
        //     'title' => __('Question 8')
        // ]);
        // $seoFieldset->addField('answer_8', 'textarea', [
        //     'name'  => 'answer_8',
        //     'label' => __('Answer 8'),
        //     'title' => __('Answer 8')
        // ]);
        // // Question 9
        // $seoFieldset->addField('question_9', 'text', [
        //     'name'  => 'question_9',
        //     'label' => __('Question 9'),
        //     'title' => __('Question 9')
        // ]);
        // $seoFieldset->addField('answer_9', 'textarea', [
        //     'name'  => 'answer_9',
        //     'label' => __('Answer 9'),
        //     'title' => __('Answer 9')
        // ]);
        // // Question 10
        // $seoFieldset->addField('question_10', 'text', [
        //     'name'  => 'question_10',
        //     'label' => __('Question 10'),
        //     'title' => __('Question 10')
        // ]);
        // $seoFieldset->addField('answer_10', 'textarea', [
        //     'name'  => 'answer_10',
        //     'label' => __('Answer 10'),
        //     'title' => __('Answer 10')
        // ]);
        // // Question 11
        // $seoFieldset->addField('question_11', 'text', [
        //     'name'  => 'question_11',
        //     'label' => __('Question 11'),
        //     'title' => __('Question 11')
        // ]);
        // $seoFieldset->addField('answer_11', 'textarea', [
        //     'name'  => 'answer_11',
        //     'label' => __('Answer 11'),
        //     'title' => __('Answer 11')
        // ]);
        // // Question 12
        // $seoFieldset->addField('question_12', 'text', [
        //     'name'  => 'question_12',
        //     'label' => __('Question 12'),
        //     'title' => __('Question 12')
        // ]);
        // $seoFieldset->addField('answer_12', 'textarea', [
        //     'name'  => 'answer_12',
        //     'label' => __('Answer 12'),
        //     'title' => __('Answer 12')
        // ]);
        // // Question 13
        // $seoFieldset->addField('question_13', 'text', [
        //     'name'  => 'question_13',
        //     'label' => __('Question 13'),
        //     'title' => __('Question 13')
        // ]);
        // $seoFieldset->addField('answer_13', 'textarea', [
        //     'name'  => 'answer_13',
        //     'label' => __('Answer 13'),
        //     'title' => __('Answer 13')
        // ]);
        // // Question 14
        // $seoFieldset->addField('question_14', 'text', [
        //     'name'  => 'question_14',
        //     'label' => __('Question 14'),
        //     'title' => __('Question 14')
        // ]);
        // $seoFieldset->addField('answer_14', 'textarea', [
        //     'name'  => 'answer_14',
        //     'label' => __('Answer 14'),
        //     'title' => __('Answer 14')
        // ]);
        // // Question 15
        // $seoFieldset->addField('question_15', 'text', [
        //     'name'  => 'question_15',
        //     'label' => __('Question 15'),
        //     'title' => __('Question 15')
        // ]);
        // $seoFieldset->addField('answer_15', 'textarea', [
        //     'name'  => 'answer_15',
        //     'label' => __('Answer 15'),
        //     'title' => __('Answer 15')
        // ]);       

        /** Get the public_date from database */
        if ($post->getData('publish_date')) {
            $publicDateTime = new \DateTime($post->getData('publish_date'), new DateTimeZone('UTC'));
            $publicDateTime->setTimezone(new DateTimeZone($this->_localeDate->getConfigTimezone()));
            $publicDateTime = $publicDateTime->format('m/d/Y H:i:s');
            list($date, $time) = explode(' ', $publicDateTime);
            $time = str_replace(':', ',', $time);
            $post->setData('publish_date', $date);
            $post->setData('publish_time', $time);
        }

        $form->addValues($post->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Post');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
