<?php
/**
 * Magento Mad Capsule Media DPD Extension
 * http://www.madcapsule.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2009 Mad Capsule Media (http://www.madcapsule.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     James Mikkelson <james@madcapsule.co.uk>
*/

$installer = $this;
 
$installer->startSetup();
 
$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('madcap_dpd_consignments')};
CREATE TABLE {$this->getTable('madcap_dpd_consignments')} (
  `consignment` varchar(30) default NULL,
  `shipment_id` int(11) default NULL,
  `sent` timestamp NULL default CURRENT_TIMESTAMP,
  `returned` datetime default NULL,
  `order_id` int(11) default NULL,
  `service` varchar(25) default NULL,
  `service_id` int(11) default NULL,
  `weight` int(11) default NULL,
  `email` varchar(250) default NULL,
  `transfer_id` int(11) NOT NULL auto_increment,
  `status` int(11) default NULL,
  PRIMARY KEY  (`transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
    ");
 
$installer->endSetup();
