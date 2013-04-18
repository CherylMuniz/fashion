<?php

$installer = $this;

$newStatuses = array(
//	'pending' => 'Pending',
	'awaiting_frame' => 'Awaiting frame',
	'awaiting_frame_amended' => 'Awaiting frame (amended)',
	'awaiting_customer' => 'Awaiting Customer Reply',
	'frame_arrived' => 'Frame arrived',
	'awaiting_lenses' => 'Awaiting lenses',
	'lenses_arrived' => 'Lenses arrived',
//	'refunded' => 'Refunded',
	'review' => 'Review',
);

$states = array(
	'new',
	'pending_payment',
	'processing',
	'complete',
	'closed',
	'canceled',
	'holded',
	'payment_review',
);

$insertStatus = array();
foreach ($newStatuses as $code => $label) {
	$insertStatus[] = "('$code', '$label')";
}

$statuses = array_merge(array_keys($newStatuses), array('pending', 'canceled', 'refunded'));

$insertRelation = array();
foreach ($states as $state) {
	foreach ($statuses as $status) {
		$insertRelation[] = "('$status', '$state', 0)";
	}
}

$installer->startSetup();
$installer->run("INSERT IGNORE INTO {$installer->getTable('sales/order_status')} VALUES " . implode(',', $insertStatus));
$installer->run("INSERT IGNORE INTO {$installer->getTable('sales/order_status_state')} VALUES " . implode(',', $insertRelation));
$installer->endSetup();