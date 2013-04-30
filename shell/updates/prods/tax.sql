show tables like 'tax%';

-- independent from nobody (no constraints) ---
tax_class                               --tax_calculation
tax_calculation_rate                    -- tax_calculation, tax_calculation_rate_title
tax_calculation_rule                    -- tax_calculation

-- constraints, but no hurt other tables. Need import with joins --
tax_calculation                         -- tax_class, tax_calculation_rate, tax_calculation_rule
tax_calculation_rate_title              -- tax_calculation_rate, core_store
tax_order_aggregated_created            -- core_store(store_id)
tax_order_aggregated_updated            -- core_store(store_id)

-- import ---
delete from tax_class;
ALTER TABLE `tax_class` AUTO_INCREMENT=1;
insert into tax_class (class_name, class_type) select c1.class_name, c1.class_type from fashione_magento3.tax_class c1;


delete from tax_calculation_rate;
ALTER TABLE `tax_calculation_rate` AUTO_INCREMENT=1;
insert into tax_calculation_rate (tax_country_id, tax_region_id, tax_postcode, code, rate, zip_is_range, zip_from, zip_to)
select r1.tax_country_id, r1.tax_region_id, r1.tax_postcode, r1.code, r1.rate, r1.zip_is_range, r1.zip_from, r1.zip_to from fashione_magento3.tax_calculation_rate r1;


delete from tax_calculation_rule;
ALTER TABLE `tax_calculation_rule` AUTO_INCREMENT=1;
insert into tax_calculation_rule (code, priority, position) select r1.code, r1.priority, r1.position from fashione_magento3.tax_calculation_rule r1;
-- end general tables --

-- tax_calculation --- 
delete from tax_calculation;
ALTER TABLE `tax_calculation` AUTO_INCREMENT=1;

select * from fashione_magento3.tax_calculation cn1
join fashione_magento3.tax_class c1 on cn1.customer_tax_class_id = c1.class_id 
join fashione_magento3.tax_class c2 on cn1.product_tax_class_id = c2.class_id
join fashione_magento3.tax_calculation_rate rt1 on cn1.tax_calculation_rate_id = rt1.tax_calculation_rate_id
join fashione_magento3.tax_calculation_rule ru1 on cn1.tax_calculation_rule_id = ru1.tax_calculation_rule_id

join tax_calculation_rule ru on ru1.code = ru.code 
join tax_calculation_rate rt on rt1.code = rt.code
join tax_class c on c.class_name = c2.class_name
join tax_class c0 on c0.class_name = c1.class_name
limit 1\G


insert into tax_calculation (tax_calculation_rate_id, tax_calculation_rule_id, customer_tax_class_id, product_tax_class_id)
select rt.tax_calculation_rate_id, ru.tax_calculation_rule_id, c0.class_id, c.class_id 
from fashione_magento3.tax_calculation cn1
join fashione_magento3.tax_class c1 on cn1.customer_tax_class_id = c1.class_id 
join fashione_magento3.tax_class c2 on cn1.product_tax_class_id = c2.class_id
join fashione_magento3.tax_calculation_rate rt1 on cn1.tax_calculation_rate_id = rt1.tax_calculation_rate_id
join fashione_magento3.tax_calculation_rule ru1 on cn1.tax_calculation_rule_id = ru1.tax_calculation_rule_id

join tax_calculation_rule ru on ru1.code = ru.code 
join tax_calculation_rate rt on rt1.code = rt.code
join tax_class c on c.class_name = c2.class_name
join tax_class c0 on c0.class_name = c1.class_name;
-- ohh shit...I do it! what a f*cking query! --
-- done --

-- tax_order_aggregated_created --
delete from tax_order_aggregated_created;
ALTER TABLE `tax_order_aggregated_created` AUTO_INCREMENT=1;
insert into `tax_order_aggregated_created` (period,store_id,code,order_status,percent,orders_count,tax_base_amount_sum)
select ac1.period, s.store_id, ac1.code, ac1.order_status, ac1.percent, ac1.orders_count, ac1.tax_base_amount_sum ac1
from fashione_magento3.tax_order_aggregated_created ac1 
join fashione_magento3.core_store s1 on ac1.store_id = s1.store_id
join core_store s on s1.code = s.code;
-- select *,count(*) from fashione_magento3.tax_order_aggregated_created group by store_id;

-- sales_order_tax --
delete from sales_order_tax;
insert into sales_order_tax select * from fashione_magento3.sales_order_tax; 
set @rownum:=0;  update sales_order_tax set tax_id = @rownum:=@rownum+1;
------------------------------------------------------------------------

--- customer_group must be set up manually --
update customer_group set tax_class_id=

-- !!! set tax_class_id for right prices !!! ---
    select * from eav_attribute where attribute_code='tax_class_id';
    -- see here what is numbers for Designer Glasses 10%, Designer sunglasses 20% , Prescription Sunglasses 10%
    select * from tax_class;
    -- than update `catalog_product_entity_int` not set from eav_here option_value!! WHY???? ---
    update catalog_product_entity_int set value=5 where value= and attribute_id=193;
    update catalog_product_entity_int set value=6 where value= and attribute_id=193;


set @attribute='tax_class_id';
set @entity_type_id=4;
set @attr_id = (select attribute_id from eav_attribute where entity_type_id=4 and attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=4 and attribute_code=@attribute);

update catalog_product_entity_int i
join catalog_product_entity e on i.entity_id = e.entity_id and i.attribute_id=@attr_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_product_entity_int i1 on i1.entity_id = e1.entity_id and i1.attribute_id=@live_attr_id
join fashione_magento3.tax_class c1 on i1.value = c1.class_id and  i1.attribute_id=@live_attr_id
join tax_class c on c1.class_name = c.class_name and c1.class_type = c.class_type
set i.value = c.class_id
where i.attribute_id=@attr_id and i1.attribute_id=@live_attr_id;

select *,count(*) from catalog_product_entity_int where attribute_id=@attr_id group by value;
select *,count(*) from fashione_magento3.catalog_product_entity_int where attribute_id=@live_attr_id group by value;