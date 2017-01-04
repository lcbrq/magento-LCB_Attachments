<?php

$installer = $this;
$installer->startSetup();

$sql = <<<SQLTEXT

ALTER TABLE `{$this->getTable('lcb_attachments')}` ADD `image` VARCHAR(255) NULL COMMENT 'Preview image' AFTER `file`;
ALTER TABLE `{$this->getTable('lcb_attachments')}` ADD `caption` VARCHAR(255) NULL AFTER `image`;

SQLTEXT;

$installer->run($sql);
$installer->endSetup();
