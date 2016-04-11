<?php

$installer = $this;
$installer->startSetup();

$sql = <<<SQLTEXT

ALTER TABLE `{$this->getTable('lcb_attachments_categories')}` ADD `display` VARCHAR(255) NULL COMMENT 'Type of content display' AFTER `name`;
        
SQLTEXT;

$installer->run($sql);
$installer->endSetup();
