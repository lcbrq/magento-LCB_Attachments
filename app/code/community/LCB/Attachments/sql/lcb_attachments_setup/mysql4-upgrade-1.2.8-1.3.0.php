<?php

/**
 * Add attachment visibility feature
 */

$installer = $this;
$installer->startSetup();
$installer->getConnection()
        ->addColumn($installer->getTable('lcb_attachments'), 'visibility_groups', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            'nullable' => true,
            'default' => null,
            'comment' => 'attachment visibility groups'
        ));

$installer->endSetup();
