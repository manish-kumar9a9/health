<?php
/**
 * Copyright © 2015 Ipragmatech. All rights reserved.
 */

namespace Ipragmatech\Ipcheckout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'ipcheckout_ipotp'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ipcheckout_ipotp')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ipcheckout_ipotp'
        )
		->addColumn(
            'mobileno',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'mobileno'
        )
		->addColumn(
            'otp_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'otp_code'
        )
		->addColumn(
            'createdat',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'createdat'
        )
		->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'status'
        )
        ->setComment(
            'Ipragmatech Ipcheckout ipcheckout_ipotp'
        );

		$installer->getConnection()->createTable($table);

        //City Table
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ipcheckout_city')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ipcheckout_ipotp'
        )
		->addColumn(
            'city_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'city_name'
        )
		->addColumn(
            'city_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'city_code'
        )
        ->setComment(
            'Ipragmatech Ipcheckout ipcheckout_city'
        );

		$installer->getConnection()->createTable($table);

        //Sales Order
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Customer Max Id',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_dob',
            [
                'type' => 'datetime',
                'comment' => 'Birthday',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_city_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max City ID',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_city_name',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max City Name',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_schedule',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max Schedule',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'max_gender',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max Customer Gender',
            ]
        );

        //Quote Order
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Customer Max Id',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_dob',
            [
                'type' => 'datetime',
                'comment' => 'Birthday',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_city_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max City ID',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_city_name',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max City Name',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_schedule',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max Schedule',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'max_gender',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max Customer Gender',
            ]
        );

        //Sales Grid
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Customer Max Id',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_dob',
            [
                'type' => 'datetime',
                'comment' => 'Birthday',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_city_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max City ID',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_city_name',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max City Name',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_schedule',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Max Schedule',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'max_gender',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Max Customer Gender',
            ]
        );


        $installer->endSetup();

    }
}
