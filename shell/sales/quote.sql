-- QUOTE
    -- sales_flat_quote
    -- sales_flat_quote_address
    -- sales_flat_quote_address_item
    -- sales_flat_quote_item
    -- sales_flat_quote_item_option
    -- sales_flat_quote_payment
    -- sales_flat_quote_shipping_rate

delete from sales_flat_quote;
insert into sales_flat_quote(
entity_id,
store_id,
created_at,
updated_at,
converted_at,
is_active,
is_virtual,
is_multi_shipping,
items_count,
items_qty,
orig_order_id,
store_to_base_rate,
store_to_quote_rate,
base_currency_code,
store_currency_code,
quote_currency_code,
grand_total,
base_grand_total,
checkout_method,
customer_id,
customer_tax_class_id,
customer_group_id,
customer_email,
customer_prefix,
customer_firstname,
customer_middlename,
customer_lastname,
customer_suffix,
customer_dob,
customer_note,
customer_note_notify,
customer_is_guest,
remote_ip,
applied_rule_ids,
reserved_order_id,
password_hash,
coupon_code,
global_currency_code,
base_to_global_rate,
base_to_quote_rate,
customer_taxvat,
customer_gender,
subtotal,
base_subtotal,
subtotal_with_discount,
base_subtotal_with_discount,
is_changed,
trigger_recollect,
ext_shipping_info,
gift_message_id,
is_persistent,
allow_alerts
) select 
t1.entity_id,
t1.store_id,
t1.created_at,
t1.updated_at,
t1.converted_at,
t1.is_active,
t1.is_virtual,
t1.is_multi_shipping,
t1.items_count,
t1.items_qty,
t1.orig_order_id,
t1.store_to_base_rate,
t1.store_to_quote_rate,
t1.base_currency_code,
t1.store_currency_code,
t1.quote_currency_code,
t1.grand_total,
t1.base_grand_total,
t1.checkout_method,
t1.customer_id,
t1.customer_tax_class_id,
t1.customer_group_id,
t1.customer_email,
t1.customer_prefix,
t1.customer_firstname,
t1.customer_middlename,
t1.customer_lastname,
t1.customer_suffix,
t1.customer_dob,
t1.customer_note,
t1.customer_note_notify,
t1.customer_is_guest,
t1.remote_ip,
t1.applied_rule_ids,
t1.reserved_order_id,
t1.password_hash,
t1.coupon_code,
t1.global_currency_code,
t1.base_to_global_rate,
t1.base_to_quote_rate,
t1.customer_taxvat,
t1.customer_gender,
t1.subtotal,
t1.base_subtotal,
t1.subtotal_with_discount,
t1.base_subtotal_with_discount,
t1.is_changed,
t1.trigger_recollect,
t1.ext_shipping_info,
t1.gift_message_id,
0,
t1.allow_alerts
from fashione_magento3.sales_flat_quote t1;

 -- sales_flat_quote_address --
delete from sales_flat_quote_address;
insert into sales_flat_quote_address(
address_id,
quote_id,
created_at,
updated_at,
customer_id,
save_in_address_book,
customer_address_id,
address_type,
email,
prefix,
firstname,
middlename,
lastname,
suffix,
company,
street,
city,
region,
region_id,
postcode,
country_id,
telephone,
fax,
same_as_billing,
free_shipping,
collect_shipping_rates,
shipping_method,
shipping_description,
weight,
subtotal,
base_subtotal,
subtotal_with_discount,
base_subtotal_with_discount,
tax_amount,
base_tax_amount,
shipping_amount,
base_shipping_amount,
shipping_tax_amount,
base_shipping_tax_amount,
discount_amount,
base_discount_amount,
grand_total,
base_grand_total,
customer_notes,
applied_taxes,
discount_description,
shipping_discount_amount,
base_shipping_discount_amount,
subtotal_incl_tax,
base_subtotal_total_incl_tax,
hidden_tax_amount,
base_hidden_tax_amount,
shipping_hidden_tax_amount,
base_shipping_hidden_tax_amnt,
shipping_incl_tax,
base_shipping_incl_tax,
vat_id,
vat_is_valid,
vat_request_id,
vat_request_date,
vat_request_success,
gift_message_id
) select 
t1.address_id,
t1.quote_id,
t1.created_at,
t1.updated_at,
t1.customer_id,
t1.save_in_address_book,
t1.customer_address_id,
t1.address_type,
t1.email,
t1.prefix,
t1.firstname,
t1.middlename,
t1.lastname,
t1.suffix,
t1.company,
t1.street,
t1.city,
t1.region,
t1.region_id,
t1.postcode,
t1.country_id,
t1.telephone,
t1.fax,
t1.same_as_billing,
t1.free_shipping,
t1.collect_shipping_rates,
t1.shipping_method,
t1.shipping_description,
t1.weight,
t1.subtotal,
t1.base_subtotal,
t1.subtotal_with_discount,
t1.base_subtotal_with_discount,
t1.tax_amount,
t1.base_tax_amount,
t1.shipping_amount,
t1.base_shipping_amount,
t1.shipping_tax_amount,
t1.base_shipping_tax_amount,
t1.discount_amount,
t1.base_discount_amount,
t1.grand_total,
t1.base_grand_total,
t1.customer_notes,
t1.applied_taxes,
t1.discount_description,
t1.shipping_discount_amount,
t1.base_shipping_discount_amount,
t1.subtotal_incl_tax,
t1.base_subtotal_total_incl_tax,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.shipping_hidden_tax_amount,
t1.base_shipping_hidden_tax_amount,
t1.shipping_incl_tax,
t1.base_shipping_incl_tax,
null,
null,
null,
null,
null,
t1.gift_message_id
from fashione_magento3.sales_flat_quote_address t1;

 -- sales_flat_quote_item --
 
 delete from sales_flat_quote_item;
insert into sales_flat_quote_item (

item_id,
quote_id,
created_at,
updated_at,
product_id,
store_id,
parent_item_id,
is_virtual,
sku,
name,
description,
applied_rule_ids,
additional_data,
free_shipping,
is_qty_decimal,
no_discount,
weight,
qty,
price,
base_price,
custom_price,
discount_percent,
discount_amount,
base_discount_amount,
tax_percent,
tax_amount,
base_tax_amount,
row_total,
base_row_total,
row_total_with_discount,
row_weight,
product_type,
base_tax_before_discount,
tax_before_discount,
original_custom_price,
redirect_url,
base_cost,
price_incl_tax,
base_price_incl_tax,
row_total_incl_tax,
base_row_total_incl_tax,
hidden_tax_amount,
base_hidden_tax_amount,
gift_message_id,
weee_tax_disposition,
weee_tax_row_disposition,
base_weee_tax_disposition,
base_weee_tax_row_disposition,
weee_tax_applied,
weee_tax_applied_amount,
weee_tax_applied_row_amount,
base_weee_tax_applied_amount,
base_weee_tax_applied_row_amnt

) select

t1.item_id,
t1.quote_id,
t1.created_at,
t1.updated_at,
e.entity_id,
t1.store_id,
t1.parent_item_id,
t1.is_virtual,
t1.sku,
t1.name,
t1.description,
t1.applied_rule_ids,
t1.additional_data,
t1.free_shipping,
t1.is_qty_decimal,
t1.no_discount,
t1.weight,
t1.qty,
t1.price,
t1.base_price,
t1.custom_price,
t1.discount_percent,
t1.discount_amount,
t1.base_discount_amount,
t1.tax_percent,
t1.tax_amount,
t1.base_tax_amount,
t1.row_total,
t1.base_row_total,
t1.row_total_with_discount,
t1.row_weight,
t1.product_type,
t1.base_tax_before_discount,
t1.tax_before_discount,
t1.original_custom_price,
t1.redirect_url,
t1.base_cost,
t1.price_incl_tax,
t1.base_price_incl_tax,
t1.row_total_incl_tax,
t1.base_row_total_incl_tax,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.gift_message_id,
t1.weee_tax_disposition,
t1.weee_tax_row_disposition,
t1.base_weee_tax_disposition,
t1.base_weee_tax_row_disposition,
t1.weee_tax_applied,
t1.weee_tax_applied_amount,
t1.weee_tax_applied_row_amount,
t1.base_weee_tax_applied_amount,
t1.base_weee_tax_applied_row_amount

from fashione_magento3.sales_flat_quote_item t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;

delete from sales_flat_quote_item_option;
insert into sales_flat_quote_item_option(
option_id,
item_id,
product_id,
code,
value
)select
t1.option_id,
t1.item_id,
e.entity_id,
t1.code,
t1.value
from fashione_magento3.sales_flat_quote_item_option t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;


-- sales_flat_quote_payment -- 
    -- alter table sales_flat_quote_payment add column `cybersource_token` varchar(255) DEFAULT '';
    -- alter table sales_flat_quote_payment add column `ideal_issuer_id` varchar(255) DEFAULT NULL;
    -- alter table sales_flat_quote_payment add column `ideal_issuer_list` text;
delete from sales_flat_quote_payment;
insert into sales_flat_quote_payment (

payment_id,
quote_id,
created_at,
updated_at,
method,
cc_type,
cc_number_enc,
cc_last4,
cc_cid_enc,
cc_owner,
cc_exp_month,
cc_exp_year,
cc_ss_owner,
cc_ss_start_month,
cc_ss_start_year,
po_number,
additional_data,
cc_ss_issue,
additional_information,
paypal_payer_id,
paypal_payer_status,
paypal_correlation_id,
sagepay_token_cc_id,
repeat_code,
cybersource_token,
ideal_issuer_id,
ideal_issuer_list

) select 

t1.payment_id,
t1.quote_id,
t1.created_at,
t1.updated_at,
t1.method,
t1.cc_type,
t1.cc_number_enc,
t1.cc_last4,
t1.cc_cid_enc,
t1.cc_owner,
t1.cc_exp_month,
t1.cc_exp_year,
t1.cc_ss_owner,
t1.cc_ss_start_month,
t1.cc_ss_start_year,
t1.po_number,
t1.additional_data,
t1.cc_ss_issue,
t1.additional_information,
t1.paypal_payer_id,
t1.paypal_payer_status,
t1.paypal_correlation_id,
t1.sagepay_token_cc_id,
t1.repeat_code,
t1.cybersource_token,
t1.ideal_issuer_id,
t1.ideal_issuer_list

from fashione_magento3.sales_flat_quote_payment t1;

delete from sales_flat_quote_shipping_rate;
insert into sales_flat_quote_shipping_rate select * from fashione_magento3.sales_flat_quote_shipping_rate;