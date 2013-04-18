-- salesrule --
-- salesrule_coupon --
-- salesrule_coupon_usage --
-- salesrule_customer --
-- salesrule_customer_group -- //no
-- salesrule_label --
-- salesrule_product_attribute --
-- salesrule_website -- //no

delete from salesrule;
insert into salesrule (
rule_id,
name,
description,
from_date,
to_date,
uses_per_customer,
is_active,
conditions_serialized,
actions_serialized,
stop_rules_processing,
is_advanced,
product_ids,
sort_order,
simple_action,
discount_amount,
discount_qty,
discount_step,
simple_free_shipping,
apply_to_shipping,
times_used,
is_rss,
coupon_type,
use_auto_generation,
uses_per_coupon
) select 
t1.rule_id,
t1.name,
t1.description,
t1.from_date,
t1.to_date,
t1.uses_per_customer,
t1.is_active,
t1.conditions_serialized,
t1.actions_serialized,
t1.stop_rules_processing,
t1.is_advanced,
t1.product_ids,
t1.sort_order,
t1.simple_action,
t1.discount_amount,
t1.discount_qty,
t1.discount_step,
t1.simple_free_shipping,
t1.apply_to_shipping,
t1.times_used,
t1.is_rss,
t1.coupon_type,
0,
0
from fashione_magento3.salesrule t1;


delete from salesrule_coupon;
insert into salesrule_coupon (
coupon_id,
rule_id,
code,
usage_limit,
usage_per_customer,
times_used,
expiration_date,
is_primary,
-- created_at,
type
) select 
t1.coupon_id,
t1.rule_id,
t1.code,
t1.usage_limit,
t1.usage_per_customer,
t1.times_used,
t1.expiration_date,
t1.is_primary,
-- t1.created_at,
0
from fashione_magento3.salesrule_coupon t1;

delete from salesrule_coupon_usage;
insert into salesrule_coupon_usage select * from fashione_magento3.salesrule_coupon_usage;

delete from salesrule_customer;
insert into salesrule_customer select * from fashione_magento3.salesrule_customer;

delete from salesrule_label;
insert into salesrule_label select * from fashione_magento3.salesrule_label;

delete from salesrule_product_attribute;
insert into salesrule_product_attribute (
rule_id,
website_id,
customer_group_id,
attribute_id
) select 
t1.rule_id,
t1.website_id,
t1.customer_group_id,
e.attribute_id
from fashione_magento3.salesrule_product_attribute t1
join fashione_magento3.eav_attribute e1 on e1.attribute_id = t1.attribute_id
join eav_attribute e on e.attribute_code = e1.attribute_code and e.entity_type_id = 4;
