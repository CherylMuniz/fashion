 -- sales_flat_order 
 -- sales_flat_order_address 
 -- sales_flat_order_grid 
 -- sales_flat_order_item 
 -- sales_flat_order_payment 
 -- sales_flat_order_status_history 

delete from sales_flat_order;
insert into sales_flat_order (
    entity_id,
    state,
    status,
    coupon_code,
    protect_code,
    shipping_description,
    is_virtual,
    store_id,
    customer_id,
    base_discount_amount,
    base_discount_canceled,
    base_discount_invoiced,
    base_discount_refunded,
    base_grand_total,
    base_shipping_amount,
    base_shipping_canceled,
    base_shipping_invoiced,
    base_shipping_refunded,
    base_shipping_tax_amount,
    base_shipping_tax_refunded,
    base_subtotal,
    base_subtotal_canceled,
    base_subtotal_invoiced,
    base_subtotal_refunded,
    base_tax_amount,
    base_tax_canceled,
    base_tax_invoiced,
    base_tax_refunded,
    base_to_global_rate,
    base_to_order_rate,
    base_total_canceled,
    base_total_invoiced,
    base_total_invoiced_cost,
    base_total_offline_refunded,
    base_total_online_refunded,
    base_total_paid,
    base_total_qty_ordered,
    base_total_refunded,
    discount_amount,
    discount_canceled,
    discount_invoiced,
    discount_refunded,
    grand_total,
    shipping_amount,
    shipping_canceled,
    shipping_invoiced,
    shipping_refunded,
    shipping_tax_amount,
    shipping_tax_refunded,
    store_to_base_rate,
    store_to_order_rate,
    subtotal,
    subtotal_canceled,
    subtotal_invoiced,
    subtotal_refunded,
    tax_amount,
    tax_canceled,
    tax_invoiced,
    tax_refunded,
    total_canceled,
    total_invoiced,
    total_offline_refunded,
    total_online_refunded,
    total_paid,
    total_qty_ordered,
    total_refunded,
    can_ship_partially,
    can_ship_partially_item,
    customer_is_guest,
    customer_note_notify,
    billing_address_id,
    customer_group_id,
    edit_increment,
    email_sent,
    forced_shipment_with_invoice,
    payment_auth_expiration,
    quote_address_id,
    quote_id,
    shipping_address_id,
    adjustment_negative,
    adjustment_positive,
    base_adjustment_negative,
    base_adjustment_positive,
    base_shipping_discount_amount,
    base_subtotal_incl_tax,
    base_total_due,
    payment_authorization_amount,
    shipping_discount_amount,
    subtotal_incl_tax,
    total_due,
    weight,
    customer_dob,
    increment_id,
    applied_rule_ids,
    base_currency_code,
    customer_email,
    customer_firstname,
    customer_lastname,
    customer_middlename,
    customer_prefix,
    customer_suffix,
    customer_taxvat,
    discount_description,
    ext_customer_id,
    ext_order_id,
    global_currency_code,
    hold_before_state,
    hold_before_status,
    order_currency_code,
    original_increment_id,
    relation_child_id,
    relation_child_real_id,
    relation_parent_id,
    relation_parent_real_id,
    remote_ip,
    shipping_method,
    store_currency_code,
    store_name,
    x_forwarded_for,
    customer_note,
    created_at,
    updated_at,
    total_item_count,
    customer_gender,
    hidden_tax_amount,
    base_hidden_tax_amount,
    shipping_hidden_tax_amount,
    base_shipping_hidden_tax_amnt,
    hidden_tax_invoiced,
    base_hidden_tax_invoiced,
    hidden_tax_refunded,
    base_hidden_tax_refunded,
    shipping_incl_tax,
    base_shipping_incl_tax,
    coupon_rule_name,
    paypal_ipn_customer_notified,
    gift_message_id,
    offpaytype
) select 
    t1.entity_id,
    t1.state,
    t1.status,
    t1.coupon_code,
    t1.protect_code,
    t1.shipping_description,
    t1.is_virtual,
    t1.store_id,
    t1.customer_id,
    t1.base_discount_amount,
    t1.base_discount_canceled,
    t1.base_discount_invoiced,
    t1.base_discount_refunded,
    t1.base_grand_total,
    t1.base_shipping_amount,
    t1.base_shipping_canceled,
    t1.base_shipping_invoiced,
    t1.base_shipping_refunded,
    t1.base_shipping_tax_amount,
    t1.base_shipping_tax_refunded,
    t1.base_subtotal,
    t1.base_subtotal_canceled,
    t1.base_subtotal_invoiced,
    t1.base_subtotal_refunded,
    t1.base_tax_amount,
    t1.base_tax_canceled,
    t1.base_tax_invoiced,
    t1.base_tax_refunded,
    t1.base_to_global_rate,
    t1.base_to_order_rate,
    t1.base_total_canceled,
    t1.base_total_invoiced,
    t1.base_total_invoiced_cost,
    t1.base_total_offline_refunded,
    t1.base_total_online_refunded,
    t1.base_total_paid,
    t1.base_total_qty_ordered,
    t1.base_total_refunded,
    t1.discount_amount,
    t1.discount_canceled,
    t1.discount_invoiced,
    t1.discount_refunded,
    t1.grand_total,
    t1.shipping_amount,
    t1.shipping_canceled,
    t1.shipping_invoiced,
    t1.shipping_refunded,
    t1.shipping_tax_amount,
    t1.shipping_tax_refunded,
    t1.store_to_base_rate,
    t1.store_to_order_rate,
    t1.subtotal,
    t1.subtotal_canceled,
    t1.subtotal_invoiced,
    t1.subtotal_refunded,
    t1.tax_amount,
    t1.tax_canceled,
    t1.tax_invoiced,
    t1.tax_refunded,
    t1.total_canceled,
    t1.total_invoiced,
    t1.total_offline_refunded,
    t1.total_online_refunded,
    t1.total_paid,
    t1.total_qty_ordered,
    t1.total_refunded,
    t1.can_ship_partially,
    t1.can_ship_partially_item,
    t1.customer_is_guest,
    t1.customer_note_notify,
    t1.billing_address_id,
    t1.customer_group_id,
    t1.edit_increment,
    t1.email_sent,
    t1.forced_do_shipment_with_invoice,
    t1.payment_authorization_expiration,
    t1.quote_address_id,
    t1.quote_id,
    t1.shipping_address_id,
    t1.adjustment_negative,
    t1.adjustment_positive,
    t1.base_adjustment_negative,
    t1.base_adjustment_positive,
    t1.base_shipping_discount_amount,
    t1.base_subtotal_incl_tax,
    t1.base_total_due,
    t1.payment_authorization_amount,
    t1.shipping_discount_amount,
    t1.subtotal_incl_tax,
    t1.total_due,
    t1.weight,
    t1.customer_dob,
    t1.increment_id,
    t1.applied_rule_ids,
    t1.base_currency_code,
    t1.customer_email,
    t1.customer_firstname,
    t1.customer_lastname,
    t1.customer_middlename,
    t1.customer_prefix,
    t1.customer_suffix,
    t1.customer_taxvat,
    t1.discount_description,
    t1.ext_customer_id,
    t1.ext_order_id,
    t1.global_currency_code,
    t1.hold_before_state,
    t1.hold_before_status,
    t1.order_currency_code,
    t1.original_increment_id,
    t1.relation_child_id,
    t1.relation_child_real_id,
    t1.relation_parent_id,
    t1.relation_parent_real_id,
    t1.remote_ip,
    t1.shipping_method,
    t1.store_currency_code,
    t1.store_name,
    t1.x_forwarded_for,
    t1.customer_note,
    t1.created_at,
    t1.updated_at,
    t1.total_item_count,
    t1.customer_gender,
    t1.hidden_tax_amount,
    t1.base_hidden_tax_amount,
    t1.shipping_hidden_tax_amount,
    t1.base_shipping_hidden_tax_amount,
    t1.hidden_tax_invoiced,
    t1.base_hidden_tax_invoiced,
    t1.hidden_tax_refunded,
    t1.base_hidden_tax_refunded,
    t1.shipping_incl_tax,
    t1.base_shipping_incl_tax,
    null,
    t1.paypal_ipn_customer_notified,
    t1.gift_message_id,
    t1.offpaytype
from fashione_magento3.sales_flat_order t1;

delete from sales_flat_order_address; insert into sales_flat_order_address select *,null,null,null,null,null from fashione_magento3.sales_flat_order_address;
delete from sales_flat_order_grid;    insert into sales_flat_order_grid select * from fashione_magento3.sales_flat_order_grid;



-- items ---
delete from sales_flat_order_item;
insert into sales_flat_order_item (
item_id,
order_id,
parent_item_id,
quote_item_id,
store_id,
created_at,
updated_at,
product_id,
product_type,
product_options,
weight,
is_virtual,
sku,
name,
description,
applied_rule_ids,
additional_data,
free_shipping,
is_qty_decimal,
no_discount,
qty_backordered,
qty_canceled,
qty_invoiced,
qty_ordered,
qty_refunded,
qty_shipped,
base_cost,
price,
base_price,
original_price,
base_original_price,
tax_percent,
tax_amount,
base_tax_amount,
tax_invoiced,
base_tax_invoiced,
discount_percent,
discount_amount,
base_discount_amount,
discount_invoiced,
base_discount_invoiced,
amount_refunded,
base_amount_refunded,
row_total,
base_row_total,
row_invoiced,
base_row_invoiced,
row_weight,
base_tax_before_discount,
tax_before_discount,
ext_order_item_id,
locked_do_invoice,
locked_do_ship,
price_incl_tax,
base_price_incl_tax,
row_total_incl_tax,
base_row_total_incl_tax,
hidden_tax_amount,
base_hidden_tax_amount,
hidden_tax_invoiced,
base_hidden_tax_invoiced,
hidden_tax_refunded,
base_hidden_tax_refunded,
is_nominal,
tax_canceled,
hidden_tax_canceled,
tax_refunded,
base_tax_refunded,
discount_refunded,
base_discount_refunded,
gift_message_id,
gift_message_available,
base_weee_tax_applied_amount,
base_weee_tax_applied_row_amnt,
weee_tax_applied_amount,
weee_tax_applied_row_amount,
weee_tax_applied,
weee_tax_disposition,
weee_tax_row_disposition,
base_weee_tax_disposition,
base_weee_tax_row_disposition
) select 
t1.item_id,
t1.order_id,
t1.parent_item_id,
t1.quote_item_id,
t1.store_id,
t1.created_at,
t1.updated_at,
e.entity_id,
t1.product_type,
t1.product_options,
t1.weight,
t1.is_virtual,
t1.sku,
t1.name,
t1.description,
t1.applied_rule_ids,
t1.additional_data,
t1.free_shipping,
t1.is_qty_decimal,
t1.no_discount,
t1.qty_backordered,
t1.qty_canceled,
t1.qty_invoiced,
t1.qty_ordered,
t1.qty_refunded,
t1.qty_shipped,
t1.base_cost,
t1.price,
t1.base_price,
t1.original_price,
t1.base_original_price,
t1.tax_percent,
t1.tax_amount,
t1.base_tax_amount,
t1.tax_invoiced,
t1.base_tax_invoiced,
t1.discount_percent,
t1.discount_amount,
t1.base_discount_amount,
t1.discount_invoiced,
t1.base_discount_invoiced,
t1.amount_refunded,
t1.base_amount_refunded,
t1.row_total,
t1.base_row_total,
t1.row_invoiced,
t1.base_row_invoiced,
t1.row_weight,
t1.base_tax_before_discount,
t1.tax_before_discount,
t1.ext_order_item_id,
t1.locked_do_invoice,
t1.locked_do_ship,
t1.price_incl_tax,
t1.base_price_incl_tax,
t1.row_total_incl_tax,
t1.base_row_total_incl_tax,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.hidden_tax_invoiced,
t1.base_hidden_tax_invoiced,
t1.hidden_tax_refunded,
t1.base_hidden_tax_refunded,
t1.is_nominal,
t1.tax_canceled,
t1.hidden_tax_canceled,
t1.tax_refunded,
null,
null,
null,
t1.gift_message_id,
t1.gift_message_available,
t1.base_weee_tax_applied_amount,
t1.base_weee_tax_applied_row_amount,
t1.weee_tax_applied_amount,
t1.weee_tax_applied_row_amount,
t1.weee_tax_applied,
t1.weee_tax_disposition,
t1.weee_tax_row_disposition,
t1.base_weee_tax_disposition,
t1.base_weee_tax_row_disposition
from fashione_magento3.sales_flat_order_item t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;


-- payments --
-- alter table sales_flat_order_payment add column `ideal_transaction_checked` tinyint(1) unsigned DEFAULT NULL;
-- alter table sales_flat_order_payment add column `ideal_issuer_title` varchar(255) DEFAULT NULL;
-- alter table sales_flat_order_payment add column `ideal_issuer_id` varchar(255) DEFAULT NULL;
-- alter table sales_flat_order_payment add column `cybersource_token` varchar(255) DEFAULT NULL;
-- alter table sales_flat_order_payment add column `paybox_question_number` varchar(255) DEFAULT NULL;
-- alter table sales_flat_order_payment add column `flo2cash_account_id` varchar(255) DEFAULT NULL;
-- alter table sales_flat_order_payment add column `repeat_code` varchar(255) DEFAULT NULL;
delete from sales_flat_order_payment;
insert into sales_flat_order_payment (
entity_id,
parent_id,
base_shipping_captured,
shipping_captured,
amount_refunded,
base_amount_paid,
amount_canceled,
base_amount_authorized,
base_amount_paid_online,
base_amount_refunded_online,
base_shipping_amount,
shipping_amount,
amount_paid,
amount_authorized,
base_amount_ordered,
base_shipping_refunded,
shipping_refunded,
base_amount_refunded,
amount_ordered,
base_amount_canceled,
quote_payment_id,
additional_data,
cc_exp_month,
cc_ss_start_year,
echeck_bank_name,
method,
cc_debug_request_body,
cc_secure_verify,
protection_eligibility,
cc_approval,
cc_last4,
cc_status_description,
echeck_type,
cc_debug_response_serialized,
cc_ss_start_month,
echeck_account_type,
last_trans_id,
cc_cid_status,
cc_owner,
cc_type,
po_number,
cc_exp_year,
cc_status,
echeck_routing_number,
account_status,
anet_trans_method,
cc_debug_response_body,
cc_ss_issue,
echeck_account_name,
cc_avs_status,
cc_number_enc,
cc_trans_id,
paybox_request_number,
address_status,
additional_information,
repeat_code,
ideal_transaction_checked,
ideal_issuer_title,
cybersource_token,
paybox_question_number,
flo2cash_account_id,
ideal_issuer_id
)select 
t1.entity_id,
t1.parent_id,
t1.base_shipping_captured,
t1.shipping_captured,
t1.amount_refunded,
t1.base_amount_paid,
t1.amount_canceled,
t1.base_amount_authorized,
t1.base_amount_paid_online,
t1.base_amount_refunded_online,
t1.base_shipping_amount,
t1.shipping_amount,
t1.amount_paid,
t1.amount_authorized,
t1.base_amount_ordered,
t1.base_shipping_refunded,
t1.shipping_refunded,
t1.base_amount_refunded,
t1.amount_ordered,
t1.base_amount_canceled,
t1.quote_payment_id,
t1.additional_data,
t1.cc_exp_month,
t1.cc_ss_start_year,
t1.echeck_bank_name,
t1.method,
t1.cc_debug_request_body,
t1.cc_secure_verify,
t1.protection_eligibility,
t1.cc_approval,
t1.cc_last4,
t1.cc_status_description,
t1.echeck_type,
t1.cc_debug_response_serialized,
t1.cc_ss_start_month,
t1.echeck_account_type,
t1.last_trans_id,
t1.cc_cid_status,
t1.cc_owner,
t1.cc_type,
t1.po_number,
t1.cc_exp_year,
t1.cc_status,
t1.echeck_routing_number,
t1.account_status,
t1.anet_trans_method,
t1.cc_debug_response_body,
t1.cc_ss_issue,
t1.echeck_account_name,
t1.cc_avs_status,
t1.cc_number_enc,
t1.cc_trans_id,
t1.paybox_request_number,
t1.address_status,
t1.additional_information,
t1.repeat_code,
t1.ideal_transaction_checked,
t1.ideal_issuer_title,
t1.cybersource_token,
t1.paybox_question_number,
t1.flo2cash_account_id,
t1.ideal_issuer_id
from fashione_magento3.sales_flat_order_payment t1;

delete from sales_flat_order_status_history;
insert into sales_flat_order_status_history select *,null from fashione_magento3.sales_flat_order_status_history;

-- SHIPMENTS
     -- sales_flat_shipment 
     -- sales_flat_shipment_comment 
     -- sales_flat_shipment_grid 
     -- sales_flat_shipment_item 
     -- sales_flat_shipment_track 
     -- shipping_tablerate
delete from sales_flat_shipment;
insert into sales_flat_shipment select *,null,null from fashione_magento3.sales_flat_shipment;
delete from sales_flat_shipment_comment;
insert into sales_flat_shipment_comment select * from fashione_magento3.sales_flat_shipment_comment;
delete from sales_flat_shipment_grid;
insert into sales_flat_shipment_grid select * from fashione_magento3.sales_flat_shipment_grid;
delete from sales_flat_shipment_item;
insert into sales_flat_shipment_item select * from fashione_magento3.sales_flat_shipment_item;

delete from sales_flat_shipment_track;
insert into sales_flat_shipment_track (
    entity_id,
    parent_id,
    weight,
    qty,
    order_id,
    track_number,
    description,
    title,
    carrier_code,
    created_at,
    updated_at
)select 
    t1.entity_id,
    t1.parent_id,
    t1.weight,
    t1.qty,
    t1.order_id,
    t1.number,
    t1.description,
    t1.title,
    t1.carrier_code,
    t1.created_at,
    t1.updated_at
from fashione_magento3.sales_flat_shipment_track t1;
delete from shipping_tablerate; insert into shipping_tablerate select * from fashione_magento3.shipping_tablerate;


-- INVOICES  
    -- sales_flat_invoice
    -- sales_flat_invoice_comment
    -- sales_flat_invoice_grid
    -- sales_flat_invoice_item
    -- sales_invoiced_aggregated
    -- sales_invoiced_aggregated_order

        -- cybersource_token, base_shipping_hidden_tax_amount==base_shipping_hidden_tax_amnt
 -- alter table sales_flat_invoice add column `cybersource_token` varchar(255) DEFAULT NULL;
delete from sales_flat_invoice;
insert into sales_flat_invoice (
    entity_id,
    store_id,
    base_grand_total,
    shipping_tax_amount,
    tax_amount,
    base_tax_amount,
    store_to_order_rate,
    base_shipping_tax_amount,
    base_discount_amount,
    base_to_order_rate,
    grand_total,
    shipping_amount,
    subtotal_incl_tax,
    base_subtotal_incl_tax,
    store_to_base_rate,
    base_shipping_amount,
    total_qty,
    base_to_global_rate,
    subtotal,
    base_subtotal,
    discount_amount,
    billing_address_id,
    is_used_for_refund,
    order_id,
    email_sent,
    can_void_flag,
    state,
    shipping_address_id,
    store_currency_code,
    transaction_id,
    order_currency_code,
    base_currency_code,
    global_currency_code,
    increment_id,
    created_at,
    updated_at,
    hidden_tax_amount,
    base_hidden_tax_amount,
    shipping_hidden_tax_amount,
    base_shipping_hidden_tax_amnt,
    shipping_incl_tax,
    base_shipping_incl_tax,
    base_total_refunded,
    cybersource_token
)select 
    t1.entity_id,
    t1.store_id,
    t1.base_grand_total,
    t1.shipping_tax_amount,
    t1.tax_amount,
    t1.base_tax_amount,
    t1.store_to_order_rate,
    t1.base_shipping_tax_amount,
    t1.base_discount_amount,
    t1.base_to_order_rate,
    t1.grand_total,
    t1.shipping_amount,
    t1.subtotal_incl_tax,
    t1.base_subtotal_incl_tax,
    t1.store_to_base_rate,
    t1.base_shipping_amount,
    t1.total_qty,
    t1.base_to_global_rate,
    t1.subtotal,
    t1.base_subtotal,
    t1.discount_amount,
    t1.billing_address_id,
    t1.is_used_for_refund,
    t1.order_id,
    t1.email_sent,
    t1.can_void_flag,
    t1.state,
    t1.shipping_address_id,
    t1.store_currency_code,
    t1.transaction_id,
    t1.order_currency_code,
    t1.base_currency_code,
    t1.global_currency_code,
    t1.increment_id,
    t1.created_at,
    t1.updated_at,
    t1.hidden_tax_amount,
    t1.base_hidden_tax_amount,
    t1.shipping_hidden_tax_amount,
    t1.base_shipping_hidden_tax_amount,
    t1.shipping_incl_tax,
    t1.base_shipping_incl_tax,
    t1.base_total_refunded,
    t1.cybersource_token
from fashione_magento3.sales_flat_invoice t1;

delete from sales_flat_invoice_comment;
insert into sales_flat_invoice_comment select * from fashione_magento3.sales_flat_invoice_comment;

delete from sales_flat_invoice_grid;
insert into sales_flat_invoice_grid select * from fashione_magento3.sales_flat_invoice_grid;

delete from sales_flat_invoice_item;
insert into sales_flat_invoice_item (
entity_id,
parent_id,
base_price,
tax_amount,
base_row_total,
discount_amount,
row_total,
base_discount_amount,
price_incl_tax,
base_tax_amount,
base_price_incl_tax,
qty,
base_cost,
price,
base_row_total_incl_tax,
row_total_incl_tax,
product_id,
order_item_id,
additional_data,
description,
sku,
name,
hidden_tax_amount,
base_hidden_tax_amount,
base_weee_tax_applied_amount,
base_weee_tax_applied_row_amnt,
weee_tax_applied_amount,
weee_tax_applied_row_amount,
weee_tax_applied,
weee_tax_disposition,
weee_tax_row_disposition,
base_weee_tax_disposition,
base_weee_tax_row_disposition
) select 
t1.entity_id,
t1.parent_id,
t1.base_price,
t1.tax_amount,
t1.base_row_total,
t1.discount_amount,
t1.row_total,
t1.base_discount_amount,
t1.price_incl_tax,
t1.base_tax_amount,
t1.base_price_incl_tax,
t1.qty,
t1.base_cost,
t1.price,
t1.base_row_total_incl_tax,
t1.row_total_incl_tax,
e.entity_id,
t1.order_item_id,
t1.additional_data,
t1.description,
t1.sku,
t1.name,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.base_weee_tax_applied_amount,
t1.base_weee_tax_applied_row_amount,
t1.weee_tax_applied_amount,
t1.weee_tax_applied_row_amount,
t1.weee_tax_applied,
t1.weee_tax_disposition,
t1.weee_tax_row_disposition,
t1.base_weee_tax_disposition,
t1.base_weee_tax_row_disposition
from fashione_magento3.sales_flat_invoice_item t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;


delete from sales_invoiced_aggregated;
insert into sales_invoiced_aggregated select * from fashione_magento3.sales_invoiced_aggregated;
delete from sales_invoiced_aggregated_order;
insert into sales_invoiced_aggregated_order select * from fashione_magento3.sales_invoiced_aggregated_order;



-- sales order --
    -- sales_order_status
    -- sales_order_status_label
    -- sales_order_status_state
    -- sales_order_tax
    -- sales_order_tax_item //not exists in 1.5
delete from sales_order_status; insert into sales_order_status select * from fashione_magento3.sales_order_status;
insert into sales_order_status (status, label) VALUES ('refunded', 'Refunded');
insert into sales_order_status_label select * from fashione_magento3.sales_order_status_label;
insert into sales_order_status_state select * from fashione_magento3.sales_order_status_state;
delete from sales_order_tax; insert into sales_order_tax select * from fashione_magento3.sales_order_tax;


delete from sales_order_aggregated_created; insert into sales_order_aggregated_created select * from fashione_magento3.sales_order_aggregated_created;
-- fashione_magento3.sales_order_aggregated_updated  //not exists

delete from sales_payment_transaction; insert into sales_payment_transaction select * from fashione_magento3.sales_payment_transaction;

delete from sales_recurring_profile; insert into sales_recurring_profile select * from fashione_magento3.sales_recurring_profile;
delete from sales_recurring_profile_order; insert into sales_recurring_profile_order select * from fashione_magento3.sales_recurring_profile_order;

delete from sales_refunded_aggregated; insert into sales_refunded_aggregated select * from fashione_magento3.sales_refunded_aggregated;
delete from sales_refunded_aggregated_order; insert into sales_refunded_aggregated_order select * from fashione_magento3.sales_refunded_aggregated_order;

delete from sales_shipping_aggregated; insert into sales_shipping_aggregated select * from fashione_magento3.sales_shipping_aggregated;
delete from sales_shipping_aggregated_order; insert into sales_shipping_aggregated_order select * from fashione_magento3.sales_shipping_aggregated_order;


-- OTHER --

delete from sales_billing_agreement; insert into sales_billing_agreement select * from fashione_magento3.sales_billing_agreement; -- empty now
delete from sales_billing_agreement_order; insert into sales_billing_agreement_order select * from fashione_magento3.sales_billing_agreement_order; -- empty now

delete from sales_bestsellers_aggregated_daily; 
insert into sales_bestsellers_aggregated_daily (
id,
period,
store_id,
product_id,
product_name,
product_price,
qty_ordered,
rating_pos
)
select 
t1.id,
t1.period,
t1.store_id,
e.entity_id,
t1.product_name,
t1.product_price,
t1.qty_ordered,
t1.rating_pos
from  fashione_magento3.sales_bestsellers_aggregated_daily t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;

delete from sales_bestsellers_aggregated_monthly; 
insert into sales_bestsellers_aggregated_monthly (
id,
period,
store_id,
product_id,
product_name,
product_price,
qty_ordered,
rating_pos
)
select 
t1.id,
t1.period,
t1.store_id,
e.entity_id,
t1.product_name,
t1.product_price,
t1.qty_ordered,
t1.rating_pos
from  fashione_magento3.sales_bestsellers_aggregated_monthly t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;

delete from sales_bestsellers_aggregated_yearly; 
insert into sales_bestsellers_aggregated_yearly (
id,
period,
store_id,
product_id,
product_name,
product_price,
qty_ordered,
rating_pos
)
select 
t1.id,
t1.period,
t1.store_id,
e.entity_id,
t1.product_name,
t1.product_price,
t1.qty_ordered,
t1.rating_pos
from  fashione_magento3.sales_bestsellers_aggregated_yearly t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;
