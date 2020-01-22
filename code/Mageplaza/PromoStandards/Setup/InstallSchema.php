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

namespace Mageplaza\PromoStandards\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Mageplaza\PromoStandards\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('mageplaza_promostandards_post')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_post'))
                ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Post ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Post Name')
                ->addColumn('get_started_top', Table::TYPE_TEXT, 255, ['nullable => false'], 'get_started_top')
                ->addColumn('get_started_toplink', Table::TYPE_TEXT, 255, ['nullable => false'], 'get_started_toplink')
                ->addColumn('get_started_bottom', Table::TYPE_TEXT, 255, ['nullable => false'], 'get_started_bottom')
                ->addColumn('get_started_bottomlink', Table::TYPE_TEXT, 255, ['nullable => false'], 'get_started_bottomlink')
                ->addColumn('short_description', Table::TYPE_TEXT, '64k', [], 'Post Short Description')
                ->addColumn('post_content', Table::TYPE_TEXT, '64k', [], 'Post Content')
                ->addColumn('store_ids', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Store Id')
                ->addColumn('image', Table::TYPE_TEXT, 255, [], 'Post Image')
                ->addColumn('views', Table::TYPE_INTEGER, null, [], 'Post Views')
                ->addColumn('enabled', Table::TYPE_INTEGER, 1, [], 'Post Enabled')
                ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Post URL Key')
                ->addColumn('in_rss', Table::TYPE_INTEGER, 1, [], 'Post In RSS')
                ->addColumn('allow_comment', Table::TYPE_INTEGER, 1, [], 'Post Allow Comment')
                ->addColumn('meta_title', Table::TYPE_TEXT, 255, [], 'Post Meta Title')
                ->addColumn('meta_description', Table::TYPE_TEXT, '64k', [], 'Post Meta Description')
                ->addColumn('meta_keywords', Table::TYPE_TEXT, '64k', [], 'Post Meta Keywords')
                ->addColumn('meta_robots', Table::TYPE_INTEGER, null, [], 'Post Meta Robots')
                ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Post Updated At')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Post Created At')
                // Manage Faqs
                ->addColumn('question_1', Table::TYPE_TEXT, 255, [], 'Question 1')
                ->addColumn('answer_1', Table::TYPE_TEXT, '64k', [], 'Answer 1')
                ->addColumn('question_2', Table::TYPE_TEXT, 255, [], 'Question 2')
                ->addColumn('answer_2', Table::TYPE_TEXT, '64k', [], 'Answer 2')
                ->addColumn('question_3', Table::TYPE_TEXT, 255, [], 'Question 3')
                ->addColumn('answer_3', Table::TYPE_TEXT, '64k', [], 'Answer 3')
                ->addColumn('question_4', Table::TYPE_TEXT, 255, [], 'Question 4')
                ->addColumn('answer_4', Table::TYPE_TEXT, '64k', [], 'Answer 4')
                ->addColumn('question_5', Table::TYPE_TEXT, 255, [], 'Question 5')
                ->addColumn('answer_5', Table::TYPE_TEXT, '64k', [], 'Answer 5')
                ->addColumn('question_6', Table::TYPE_TEXT, 255, [], 'Question 6')
                ->addColumn('answer_6', Table::TYPE_TEXT, '64k', [], 'Answer 6')
                ->addColumn('question_7', Table::TYPE_TEXT, 255, [], 'Question 7')
                ->addColumn('answer_7', Table::TYPE_TEXT, '64k', [], 'Answer 7')
                ->addColumn('question_8', Table::TYPE_TEXT, 255, [], 'Question 8')
                ->addColumn('answer_8', Table::TYPE_TEXT, '64k', [], 'Answer 8')
                ->addColumn('question_9', Table::TYPE_TEXT, 255, [], 'Question 9')
                ->addColumn('answer_9', Table::TYPE_TEXT, '64k', [], 'Answer 9')
                ->addColumn('question_10', Table::TYPE_TEXT, 255, [], 'Question 10')
                ->addColumn('answer_10', Table::TYPE_TEXT, '64k', [], 'Answer 10')
                ->addColumn('question_11', Table::TYPE_TEXT, 255, [], 'Question 11')
                ->addColumn('answer_11', Table::TYPE_TEXT, '64k', [], 'Answer 11')
                ->addColumn('question_12', Table::TYPE_TEXT, 255, [], 'Question 12')
                ->addColumn('answer_12', Table::TYPE_TEXT, '64k', [], 'Answer 12')
                ->addColumn('question_13', Table::TYPE_TEXT, 255, [], 'Question 13')
                ->addColumn('answer_13', Table::TYPE_TEXT, '64k', [], 'Answer 13')
                ->addColumn('question_14', Table::TYPE_TEXT, 255, [], 'Question 14')
                ->addColumn('answer_14', Table::TYPE_TEXT, '64k', [], 'Answer 14')
                ->addColumn('question_15', Table::TYPE_TEXT, 255, [], 'Question 15')
                ->addColumn('answer_15', Table::TYPE_TEXT, '64k', [], 'Answer 15')
                ->setComment('Post Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_tag')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_tag'))
                ->addColumn('tag_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Tag ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Tag Name')
                ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Tag URL Key')
                ->addColumn('description', Table::TYPE_TEXT, '64k', [], 'Tag Description')
                ->addColumn('store_ids', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Store Id')
                ->addColumn('enabled', Table::TYPE_INTEGER, 1, [], 'Tag Enabled')
                ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Tag Updated At')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Tag Created At')
                ->setComment('Tag Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_topic')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_topic'))
                ->addColumn('topic_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Topic ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Topic Name')
                ->addColumn('description', Table::TYPE_TEXT, '64k', [], 'Topic Description')
                ->addColumn('store_ids', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Store Id')
                ->addColumn('enabled', Table::TYPE_INTEGER, 1, [], 'Topic Enabled')
                ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Topic URL Key')
                ->addColumn('meta_title', Table::TYPE_TEXT, 255, [], 'Topic Meta Title')
                ->addColumn('meta_description', Table::TYPE_TEXT, '64k', [], 'Topic Meta Description')
                ->addColumn('meta_keywords', Table::TYPE_TEXT, '64k', [], 'Topic Meta Keywords')
                ->addColumn('meta_robots', Table::TYPE_INTEGER, null, [], 'Topic Meta Robots')
                ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Topic Updated At')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Topic Created At')
                ->setComment('Topic Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_category')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_category'))
                ->addColumn('category_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Category ID')
                ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Category Name')
                ->addColumn('description', Table::TYPE_TEXT, '64k', [], 'Category Description')
                ->addColumn('store_ids', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Store Id')
                ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Category URL Key')
                ->addColumn('enabled', Table::TYPE_INTEGER, 1, [], 'Category Enabled')
                ->addColumn('meta_title', Table::TYPE_TEXT, 255, [], 'Category Meta Title')
                ->addColumn('meta_description', Table::TYPE_TEXT, '64k', [], 'Category Meta Description')
                ->addColumn('meta_keywords', Table::TYPE_TEXT, '64k', [], 'Category Meta Keywords')
                ->addColumn('meta_robots', Table::TYPE_INTEGER, null, [], 'Category Meta Robots')
                ->addColumn('parent_id', Table::TYPE_INTEGER, null, [], 'Category Parent Id')
                ->addColumn('path', Table::TYPE_TEXT, 255, [], 'Category Path')
                ->addColumn('position', Table::TYPE_INTEGER, null, [], 'Category Position')
                ->addColumn('level', Table::TYPE_INTEGER, null, [], 'Category Level')
                ->addColumn('children_count', Table::TYPE_INTEGER, null, [], 'Category Children Count')
                ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Category Updated At')
                ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Category Created At')
                ->setComment('Category Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_post_tag')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_post_tag'))
                ->addColumn('tag_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Tag ID')
                ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Post ID')
                ->addColumn('position', Table::TYPE_INTEGER, null, [
                    'nullable' => false,
                    'default'  => '0'
                ], 'Position')
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_tag', ['post_id']), ['post_id'])
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_tag', ['tag_id']), ['tag_id'])
                ->addForeignKey(
                    $installer->getFkName('mageplaza_promostandards_post_tag', 'post_id', 'mageplaza_promostandards_post', 'post_id'),
                    'post_id',
                    $installer->getTable('mageplaza_promostandards_post'),
                    'post_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('mageplaza_promostandards_post_tag', 'tag_id', 'mageplaza_promostandards_tag', 'tag_id'),
                    'tag_id',
                    $installer->getTable('mageplaza_promostandards_tag'),
                    'tag_id',
                    Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName(
                        'mageplaza_promostandards_post_tag',
                        ['post_id', 'tag_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['post_id', 'tag_id'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Post To Tag Link Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_post_topic')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_post_topic'))
                ->addColumn('topic_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Topic ID')
                ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Post ID')
                ->addColumn('position', Table::TYPE_INTEGER, null, ['nullable' => false, 'default' => '0'], 'Position')
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_topic', ['post_id']), ['post_id'])
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_topic', ['topic_id']), ['topic_id'])
                ->addForeignKey(
                    $installer->getFkName('mageplaza_promostandards_post_topic', 'post_id', 'mageplaza_promostandards_post', 'post_id'),
                    'post_id',
                    $installer->getTable('mageplaza_promostandards_post'),
                    'post_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('mageplaza_promostandards_post_topic', 'topic_id', 'mageplaza_promostandards_topic', 'topic_id'),
                    'topic_id',
                    $installer->getTable('mageplaza_promostandards_topic'),
                    'topic_id',
                    Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName(
                        'mageplaza_promostandards_post_topic',
                        ['post_id', 'topic_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['post_id', 'topic_id'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Post To Topic Link Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('mageplaza_promostandards_post_category')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_promostandards_post_category'))
                ->addColumn('category_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Category ID')
                ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    'primary'  => true,
                    'nullable' => false
                ], 'Post ID')
                ->addColumn('position', Table::TYPE_INTEGER, null, ['nullable' => false, 'default' => '0'], 'Position')
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_category', ['category_id']), ['category_id'])
                ->addIndex($installer->getIdxName('mageplaza_promostandards_post_category', ['post_id']), ['post_id'])
                ->addForeignKey(
                    $installer->getFkName(
                        'mageplaza_promostandards_post_category',
                        'category_id',
                        'mageplaza_promostandards_category',
                        'category_id'
                    ),
                    'category_id',
                    $installer->getTable('mageplaza_promostandards_category'),
                    'category_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('mageplaza_promostandards_post_category', 'post_id', 'mageplaza_promostandards_post', 'post_id'),
                    'post_id',
                    $installer->getTable('mageplaza_promostandards_post'),
                    'post_id',
                    Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName(
                        'mageplaza_promostandards_post_category',
                        ['category_id', 'post_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['category_id', 'post_id'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Category To Post Link Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
