set @attribute='status';
-- set @attribute='visibility';
set @entity_type_id=4;
set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);


select *,count(*) from catalog_product_entity_int where attribute_id=@attr_id group by value;
select *,count(*) from fashione_magento3.catalog_product_entity_int where attribute_id=@live_attr_id group by value;

-- check --
select * from catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
where i.attribute_id=@attr_id and i1.attribute_id=@live_attr_id and i.value <> i1.value
limit 1\G

-- update products (catalog_product_entity_int) --

drop table if exists catalog_product_entity_int2; create table catalog_product_entity_int2 select * from catalog_product_entity_int; 
update catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
set i.value = i1.value 
where i.attribute_id=@attr_id and i1.attribute_id=@live_attr_id;