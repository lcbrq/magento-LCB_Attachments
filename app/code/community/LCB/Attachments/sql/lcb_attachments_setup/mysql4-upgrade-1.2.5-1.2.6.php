<?php

$installer = $this;
$installer->startSetup();

$sql = <<<SQLTEXT

ALTER TABLE `{$this->getTable('lcb_attachments')}` CHANGE `is_active` `is_active` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1';
UPDATE `{$this->getTable('lcb_attachments')}` SET `is_active` = 1


SQLTEXT;

$installer->run($sql);
$installer->endSetup();
