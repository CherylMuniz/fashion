select a.attribute_code, a.source_model, a1.attribute_code,  a1.source_model from eav_attribute a
join mage.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id
where a.source_model = a1.source_model; 

select a.attribute_code, a.source_model, a1.attribute_code,  a1.source_model from eav_attribute a join mage.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id where a.source_model <> a1.source_model;


-- empty --
select a.attribute_code, a.source_model, a1.attribute_code,  a1.source_model from eav_attribute a join mage.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id where a.source_model is null or  a1.source_model is null;
select a.attribute_code, a.source_model, a1.attribute_code,  a1.source_model from eav_attribute a join mage.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id where a.source_model='' or  a1.source_model='';


select *,count(*) as cnt from catalog_product_entity group by sku having cnt > 1;

-- options 'One size only' title --
select e.sku from catalog_product_option_type_title t 
join catalog_product_option_type_value v on t.option_type_id = v.option_type_id 
join catalog_product_option o on o.option_id = v.option_id 
join catalog_product_entity e on e.entity_id = o.product_id
where t.title='One size only';


-- old (need delete)--
SELECT e.sku FROM catalog_product_entity e left join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku not in('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley');
-- new (need import)--
SELECT e.sku FROM fashione_magento3.catalog_product_entity e left join catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku is not null;

-- delete diff! ---
delete e.* from catalog_product_entity e left join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku not in('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley');

-- check products ---
select count(*) from catalog_product_entity;
select count(*) from fashione_magento3.catalog_product_entity;

-- sore, website --
select * from fashione_magento3.core_store; select * from core_store;
select * from fashione_magento3.core_website; select * from core_website;


-- customers --
show tables like '%customer%';
select email,count(*) from fashione_magento3.customer_entity group by group_id;
select * from fashione_magento3.customer_entity group by attribute_set_id;
select * from fashione_magento3.customer_entity group by entity_type_id;
select count(*) from fashione_magento3.customer_entity;
select count(*) from customer_entity;


update core_config_data set value='http://www.fashioneyewear.co.uk/' where path in('web/unsecure/base_url', 'web/secure/base_url');