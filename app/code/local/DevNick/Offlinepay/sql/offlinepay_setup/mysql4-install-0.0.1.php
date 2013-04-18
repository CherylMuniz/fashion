<?php

$installer = $this;

$installer->run("
ALTER TABLE `sales_flat_order` ADD `offpaytype` INT(9) NOT NULL;
");

$installer->endSetup();