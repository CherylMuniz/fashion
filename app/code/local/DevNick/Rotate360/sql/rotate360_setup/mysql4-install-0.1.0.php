<?php

$installer = $this;
 
$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('catalog_product_img360')};
    CREATE TABLE {$this->getTable('catalog_product_img360')} (
    `id` int(11) unsigned NOT NULL auto_increment,
    `product_id` int(11) unsigned NOT NULL,
    `path` varchar(255) NOT NULL default '',
    `title` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
 
$installer->endSetup();
