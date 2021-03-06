 -- sales_flat_creditmemo                
 -- sales_flat_creditmemo_comment        
 -- sales_flat_creditmemo_grid           
 -- sales_flat_creditmemo_item  

delete from sales_flat_creditmemo;

-- alter table sales_flat_creditmemo add column `cybersource_token` varchar(255) DEFAULT NULL;
insert into sales_flat_creditmemo (
entity_id,
store_id,
adjustment_positive,
base_shipping_tax_amount,
store_to_order_rate,
base_discount_amount,
base_to_order_rate,
grand_total,
base_adjustment_negative,
base_subtotal_incl_tax,
shipping_amount,
subtotal_incl_tax,
adjustment_negative,
base_shipping_amount,
store_to_base_rate,
base_to_global_rate,
base_adjustment,
base_subtotal,
discount_amount,
subtotal,
adjustment,
base_grand_total,
base_adjustment_positive,
base_tax_amount,
shipping_tax_amount,
tax_amount,
order_id,
email_sent,
creditmemo_status,
state,
shipping_address_id,
billing_address_id,
invoice_id,
store_currency_code,
order_currency_code,
base_currency_code,
global_currency_code,
transaction_id,
increment_id,
created_at,
updated_at,
hidden_tax_amount,
base_hidden_tax_amount,
shipping_hidden_tax_amount,
base_shipping_hidden_tax_amnt,
shipping_incl_tax,
base_shipping_incl_tax,
cybersource_token
) select 
t1.entity_id,
t1.store_id,
t1.adjustment_positive,
t1.base_shipping_tax_amount,
t1.store_to_order_rate,
t1.base_discount_amount,
t1.base_to_order_rate,
t1.grand_total,
t1.base_adjustment_negative,
t1.base_subtotal_incl_tax,
t1.shipping_amount,
t1.subtotal_incl_tax,
t1.adjustment_negative,
t1.base_shipping_amount,
t1.store_to_base_rate,
t1.base_to_global_rate,
t1.base_adjustment,
t1.base_subtotal,
t1.discount_amount,
t1.subtotal,
t1.adjustment,
t1.base_grand_total,
t1.base_adjustment_positive,
t1.base_tax_amount,
t1.shipping_tax_amount,
t1.tax_amount,
t1.order_id,
t1.email_sent,
t1.creditmemo_status,
t1.state,
t1.shipping_address_id,
t1.billing_address_id,
t1.invoice_id,
t1.store_currency_code,
t1.order_currency_code,
t1.base_currency_code,
t1.global_currency_code,
t1.transaction_id,
t1.increment_id,
t1.created_at,
t1.updated_at,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.shipping_hidden_tax_amount,
t1.base_shipping_hidden_tax_amount,
t1.shipping_incl_tax,
t1.base_shipping_incl_tax,
null
from fashione_magento3.sales_flat_creditmemo t1;

delete from sales_flat_creditmemo_comment; insert into sales_flat_creditmemo_comment select * from fashione_magento3.sales_flat_creditmemo_comment;
delete from sales_flat_creditmemo_grid; insert into sales_flat_creditmemo_grid select * from fashione_magento3.sales_flat_creditmemo_grid;


delete from sales_flat_creditmemo_item;
insert into sales_flat_creditmemo_item (

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
weee_tax_disposition,
weee_tax_row_disposition,
base_weee_tax_disposition,
base_weee_tax_row_disposition,
weee_tax_applied,
base_weee_tax_applied_amount,
base_weee_tax_applied_row_amnt,
weee_tax_applied_amount,
weee_tax_applied_row_amount

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
t1.product_id,
t1.order_item_id,
t1.additional_data,
t1.description,
t1.sku,
t1.name,
t1.hidden_tax_amount,
t1.base_hidden_tax_amount,
t1.weee_tax_disposition,
t1.weee_tax_row_disposition,
t1.base_weee_tax_disposition,
t1.base_weee_tax_row_disposition,
t1.weee_tax_applied,
t1.base_weee_tax_applied_amount,
t1.base_weee_tax_applied_row_amount,
t1.weee_tax_applied_amount,
t1.weee_tax_applied_row_amount

from fashione_magento3.sales_flat_creditmemo_item t1;