<?php

$installer = $this;
$installer->startSetup();
$sql = <<<SQLTEXT
        
DROP TABLE IF EXISTS `{$this->getTable('lcb_attachments')}`;
DROP TABLE IF EXISTS `{$this->getTable('lcb_attachments_store')}`;
DROP TABLE IF EXISTS `{$this->getTable('lcb_attachments_products')}`;
DROP TABLE IF EXISTS `{$this->getTable('lcb_attachments_categories')}`;

CREATE TABLE `{$this->getTable('lcb_attachments')}` (
  `attachment_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `is_active` smallint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `category` smallint(6) NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('lcb_attachments_store')}` (
  `attachment_id` smallint(66) NOT NULL COMMENT 'Attachment ID',
  `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store ID',
  PRIMARY KEY (`attachment_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachment To Store Linkage Table';

ALTER TABLE `{$this->getTable('lcb_attachments_store')}` ADD INDEX ( `attachment_id` );
ALTER TABLE `{$this->getTable('lcb_attachments_store')}` ADD INDEX ( `store_id` );

ALTER TABLE `{$this->getTable('lcb_attachments_store')}` ADD FOREIGN KEY ( `attachment_id` ) REFERENCES `{$this->getTable('lcb_attachments')}` (
`attachment_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `{$this->getTable('lcb_attachments_store')}` ADD FOREIGN KEY ( `store_id` ) REFERENCES `{$this->getTable('core_store')}` (
`store_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

CREATE TABLE `{$this->getTable('lcb_attachments_products')}` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `attachment_id` smallint(6) NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `{$this->getTable('lcb_attachments_products')}` ADD INDEX(`attachment_id`);
ALTER TABLE `{$this->getTable('lcb_attachments_products')}` ADD FOREIGN KEY (`attachment_id`) REFERENCES `{$this->getTable('lcb_attachments')}`(`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `{$this->getTable('lcb_attachments_categories')}` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `{$this->getTable('lcb_attachments_categories')}` VALUES (1, 'General');

SQLTEXT;

$installer->run($sql);
$installer->endSetup();
