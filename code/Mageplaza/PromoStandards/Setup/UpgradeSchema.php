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
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $connection = $installer->getConnection();

        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                $columns = [
                    'meta_title'       => [
                        'type'    => Table::TYPE_TEXT,
                        'length'  => 255,
                        'comment' => 'Post Meta Title',
                    ],
                    'meta_description' => [
                        'type'    => Table::TYPE_TEXT,
                        'length'  => '64k',
                        'comment' => 'Post Meta Description',
                    ],
                    'meta_keywords'    => [
                        'type'    => Table::TYPE_TEXT,
                        'length'  => '64k',
                        'comment' => 'Post Meta Keywords',
                    ],
                    'meta_robots'      => [
                        'type'    => Table::TYPE_INTEGER,
                        'length'  => '64k',
                        'comment' => 'Post Meta Robots',
                    ]
                ];

                $tagTable = $installer->getTable('mageplaza_promostandards_tag');
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tagTable, $name, $definition);
                }
            }

            if (!$installer->tableExists('mageplaza_promostandards_post_traffic')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_promostandards_post_traffic'))
                    ->addColumn('traffic_id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ], 'Traffic ID')
                    ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                        'unsigned' => true,
                        'nullable' => false
                    ], 'Post ID')
                    ->addColumn('numbers_view', Table::TYPE_TEXT, 255, [], 'Numbers View')
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_post_traffic', ['post_id']), ['post_id'])
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_post_traffic', ['traffic_id']), ['traffic_id'])
                    ->addForeignKey(
                        $installer->getFkName(
                            'mageplaza_promostandards_post_traffic',
                            'post_id',
                            'mageplaza_promostandards_post',
                            'post_id'
                        ),
                        'post_id',
                        $installer->getTable('mageplaza_promostandards_post'),
                        'post_id',
                        Table::ACTION_CASCADE
                    )
                    ->addIndex(
                        $installer->getIdxName(
                            'mageplaza_promostandards_post_traffic',
                            ['post_id', 'traffic_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['post_id', 'traffic_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )
                    ->setComment('Traffic Post Table');

                $installer->getConnection()->createTable($table);
            }
            if (!$installer->tableExists('mageplaza_promostandards_author')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_promostandards_author'))
                    ->addColumn('user_id', Table::TYPE_INTEGER, null, [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ], 'User ID')
                    ->addColumn('name', Table::TYPE_TEXT, 255, [], 'Display Name')
                    ->addColumn('url_key', Table::TYPE_TEXT, 255, [], 'Author URL Key')
                    ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Author Updated At')
                    ->addColumn(
                        'created_at',
                        Table::TYPE_TIMESTAMP,
                        null,
                        ['default' => Table::TIMESTAMP_INIT],
                        'Author Created At'
                    )
                    ->addForeignKey(
                        $installer->getFkName('mageplaza_promostandards_author', 'user_id', 'admin_user', 'user_id'),
                        'user_id',
                        $installer->getTable('admin_user'),
                        'user_id',
                        Table::ACTION_CASCADE
                    )
                    ->addIndex(
                        $installer->getIdxName(
                            'mageplaza_promostandards_author',
                            ['user_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['user_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )
                    ->setComment('Author Table');

                $installer->getConnection()->createTable($table);
            }

            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                $columns = [
                    'author_id'   => [
                        'type'     => Table::TYPE_INTEGER,
                        'comment'  => 'Author ID',
                        'unsigned' => true,
                    ],
                    'modifier_id' => [
                        'type'     => Table::TYPE_INTEGER,
                        'comment'  => 'Modifier ID',
                        'unsigned' => true,
                    ],
                ];

                $table = $installer->getTable('mageplaza_promostandards_post');
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($table, $name, $definition);
                }
            }
        }
        if (version_compare($context->getVersion(), '1.1.2', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_post'),
                    'meta_robots',
                    ['type' => Table::TYPE_TEXT]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_tag'),
                    'meta_robots',
                    ['type' => Table::TYPE_TEXT]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_category')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_category'),
                    'meta_robots',
                    ['type' => Table::TYPE_TEXT]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_topic'),
                    'meta_robots',
                    ['type' => Table::TYPE_TEXT]
                );
            }

            if (!$installer->tableExists('mageplaza_promostandards_comment')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_promostandards_comment'))
                    ->addColumn('comment_id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ], 'Comment ID')
                    ->addColumn(
                        'post_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false,],
                        'Post ID'
                    )
                    ->addColumn(
                        'entity_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false,],
                        'User Comment ID'
                    )
                    ->addColumn(
                        'has_reply',
                        Table::TYPE_SMALLINT,
                        2,
                        ['unsigned' => true, 'nullable' => false, 'default' => 0],
                        'Comment has reply'
                    )
                    ->addColumn(
                        'is_reply',
                        Table::TYPE_SMALLINT,
                        2,
                        ['unsigned' => true, 'nullable' => false, 'default' => 0],
                        'Is reply comment'
                    )
                    ->addColumn(
                        'reply_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => true, 'default' => 0],
                        'Reply ID'
                    )
                    ->addColumn('content', Table::TYPE_TEXT, 255, [], 'Comment content')
                    ->addColumn('created_at', Table::TYPE_TEXT, null, [], 'Comment Created At')
                    ->addColumn(
                        'status',
                        Table::TYPE_SMALLINT,
                        3,
                        ['unsigned' => true, 'nullable' => false, 'default' => 3],
                        'Status'
                    )
                    ->addColumn(
                        'store_ids',
                        Table::TYPE_TEXT,
                        null,
                        ['nullable' => false, 'unsigned' => true,],
                        'Store Id'
                    )
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_comment', ['comment_id']), ['comment_id'])
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_comment', ['entity_id']), ['entity_id'])
                    ->addForeignKey(
                        $installer->getFkName('mageplaza_promostandards_comment', 'entity_id', 'customer_entity', 'entity_id'),
                        'entity_id',
                        $installer->getTable('customer_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    )->addForeignKey(
                        $installer->getFkName('mageplaza_promostandards_comment', 'post_id', 'mageplaza_promostandards_post', 'post_id'),
                        'post_id',
                        $installer->getTable('mageplaza_promostandards_post'),
                        'post_id',
                        Table::ACTION_CASCADE
                    );

                $installer->getConnection()->createTable($table);
            }
            if (!$installer->tableExists('mageplaza_promostandards_comment_like')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_promostandards_comment_like'))
                    ->addColumn('like_id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ], 'Like ID')
                    ->addColumn(
                        'comment_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false,],
                        'Comment ID'
                    )
                    ->addColumn(
                        'entity_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false,],
                        'User Like ID'
                    )
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_comment_like', ['like_id']), ['like_id'])
                    ->addForeignKey(
                        $installer->getFkName(
                            'mageplaza_promostandards_comment_like',
                            'comment_id',
                            'mageplaza_promostandards_comment',
                            'comment_id'
                        ),
                        'comment_id',
                        $installer->getTable('mageplaza_promostandards_comment'),
                        'comment_id',
                        Table::ACTION_CASCADE
                    )->addForeignKey(
                        $installer->getFkName(
                            'mageplaza_promostandards_comment_like',
                            'entity_id',
                            'customer_entity',
                            'entity_id'
                        ),
                        'entity_id',
                        $installer->getTable('customer_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    );

                $installer->getConnection()->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '1.1.3', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_author')) {
                $columns = [
                    'image'             => [
                        'type'     => Table::TYPE_TEXT,
                        'length'   => 255,
                        'comment'  => 'Author Image',
                        'unsigned' => true,
                    ],
                    'short_description' => [
                        'type'     => Table::TYPE_TEXT,
                        'length'   => '64k',
                        'comment'  => 'Author Short Description',
                        'unsigned' => true,
                    ],
                    'facebook_link'     => [
                        'type'     => Table::TYPE_TEXT,
                        'length'   => 255,
                        'comment'  => 'Facebook Link',
                        'unsigned' => true,
                    ],
                    'twitter_link'      => [
                        'type'     => Table::TYPE_TEXT,
                        'length'   => 255,
                        'comment'  => 'Twitter Link',
                        'unsigned' => true,
                    ],
                ];

                $table = $installer->getTable('mageplaza_promostandards_author');
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($table, $name, $definition);
                }
            }
        }

        if (version_compare($context->getVersion(), '1.1.4', '<')) {
            if (!$installer->tableExists('mageplaza_promostandards_post_product')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_promostandards_post_product'))
                    ->addColumn('post_id', Table::TYPE_INTEGER, null, [
                        'unsigned' => true,
                        'primary'  => true,
                        'nullable' => false
                    ], 'Post ID')
                    ->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                        'unsigned' => true,
                        'primary'  => true,
                        'nullable' => false
                    ], 'Entity ID')
                    ->addColumn(
                        'position',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false, 'default' => '0'],
                        'Position'
                    )
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_post_product', ['post_id']), ['post_id'])
                    ->addIndex($installer->getIdxName('mageplaza_promostandards_post_product', ['entity_id']), ['entity_id'])
                    ->addForeignKey(
                        $installer->getFkName(
                            'mageplaza_promostandards_post_product',
                            'post_id',
                            'mageplaza_promostandards_post',
                            'post_id'
                        ),
                        'post_id',
                        $installer->getTable('mageplaza_promostandards_post'),
                        'post_id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'mageplaza_promostandards_post_product',
                            'entity_id',
                            'catalog_product_entity',
                            'entity_id'
                        ),
                        'entity_id',
                        $installer->getTable('catalog_product_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    )
                    ->addIndex(
                        $installer->getIdxName(
                            'mageplaza_promostandards_post_product',
                            ['post_id', 'entity_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['post_id', 'entity_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )
                    ->setComment('Post To Product Link Table');

                $installer->getConnection()->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '2.4.2', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                $connection->addColumn($installer->getTable('mageplaza_promostandards_post'), 'publish_date', [
                    'type'    => Table::TYPE_TIMESTAMP,
                    null,
                    'comment' => 'Post Publish Date',
                ]);
            }
        }

        if (version_compare($context->getVersion(), '2.4.3', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_post'),
                    'created_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_post'),
                    'updated_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_post'),
                    'publish_date',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_tag'),
                    'created_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_tag'),
                    'updated_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_category')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_category'),
                    'created_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_category')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_category'),
                    'updated_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_topic'),
                    'created_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_topic'),
                    'updated_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }
        }

        if (version_compare($context->getVersion(), '2.4.4', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_comment'),
                    'content',
                    ['type' => Table::TYPE_TEXT]
                );
            }
            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_comment'),
                    'created_at',
                    ['type' => Table::TYPE_DATETIME]
                );
            }

            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'status')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_comment'), 'status', [
                        'type'    => Table::TYPE_INTEGER,
                        3,
                        ['unsigned' => true, 'nullable' => false, 'default' => 3],
                        'comment' => 'status',
                    ]);
                }
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'store_ids')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_comment'), 'store_ids', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['nullable' => false, 'unsigned' => true,],
                        'comment' => 'Store Id',
                    ]);
                }
            }
        }

        if (version_compare($context->getVersion(), '2.4.5', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post_traffic')) {
                $connection->modifyColumn(
                    $installer->getTable('mageplaza_promostandards_post_traffic'),
                    'numbers_view',
                    ['type' => Table::TYPE_INTEGER]
                );
            }
        }

        if (version_compare($context->getVersion(), '2.4.6', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                $connection->dropForeignKey(
                    $installer->getTable('mageplaza_promostandards_comment'),
                    $installer->getFkName('mageplaza_promostandards_comment', 'entity_id', 'customer_entity', 'entity_id')
                );
            }
        }

        if (version_compare($context->getVersion(), '2.4.7', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'user_name')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_comment'), 'user_name', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'User Name',
                    ]);
                }
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'user_email')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_comment'), 'user_email', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'User Email',
                    ]);
                }
            }
        }

        if (version_compare($context->getVersion(), '2.4.8', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_post'), 'import_source')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_post'), 'import_source', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Import Source',
                    ]);
                }
            }

            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_tag'), 'import_source')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_tag'), 'import_source', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Import Source',
                    ]);
                }
            }

            if ($installer->tableExists('mageplaza_promostandards_category')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_category'), 'import_source')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_category'), 'import_source', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Import Source',
                    ]);
                }
            }

            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'import_source')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_comment'), 'import_source', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Import Source',
                    ]);
                }
            }

            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_topic'), 'import_source')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_topic'), 'import_source', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Import Source',
                    ]);
                }
            }
        }

        if (version_compare($context->getVersion(), '2.4.9', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_post'), 'created_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_post'),
                        'created_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_post'), 'updated_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_post'),
                        'updated_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_post'), 'publish_date')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_post'),
                        'publish_date',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
            }
            if ($installer->tableExists('mageplaza_promostandards_category')) {
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_category'), 'created_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_category'),
                        'created_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_category'), 'updated_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_category'),
                        'updated_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
            }
            if ($installer->tableExists('mageplaza_promostandards_tag')) {
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_tag'), 'created_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_tag'),
                        'created_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_tag'), 'updated_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_tag'),
                        'updated_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
            }
            if ($installer->tableExists('mageplaza_promostandards_topic')) {
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_topic'), 'created_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_topic'),
                        'created_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_topic'), 'updated_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_topic'),
                        'updated_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
            }
            if ($installer->tableExists('mageplaza_promostandards_comment')) {
                if ($connection->tableColumnExists($installer->getTable('mageplaza_promostandards_comment'), 'created_at')) {
                    $connection->modifyColumn(
                        $installer->getTable('mageplaza_promostandards_comment'),
                        'created_at',
                        ['type' => Table::TYPE_TIMESTAMP]
                    );
                }
            }
        }

        if (version_compare($context->getVersion(), '2.5.0', '<')) {
            if ($installer->tableExists('mageplaza_promostandards_post')) {
                if (!$connection->tableColumnExists($installer->getTable('mageplaza_promostandards_post'), 'layout')) {
                    $connection->addColumn($installer->getTable('mageplaza_promostandards_post'), 'layout', [
                        'type'    => Table::TYPE_TEXT,
                        null,
                        ['unsigned' => true, 'nullable' => true],
                        'comment' => 'Post Layout',
                    ]);
                }
            }
        }

        $installer->endSetup();
    }
}
