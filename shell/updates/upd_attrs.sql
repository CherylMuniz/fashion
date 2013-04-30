set @attribute='manufacturer';
set @attr_id = (select attribute_id from eav_attribute where attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where attribute_code=@attribute);

-- update products (catalog_product_entity_int) --
select count(*) from catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
where i1.attribute_id=@live_attr_id and i.attribute_id=@attr_id and i.value is null;

update catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
set i.value = o.option_id 
where i1.attribute_id=@live_attr_id and i.attribute_id=@attr_id and i.value is null;


------------------------------------------------------------------------
set @attribute='frame_gender';
set @demo_attr_id = (select attribute_id from eav_attribute where attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where attribute_code=@attribute);

-- update products (catalog_product_entity_varchar) --
select count(*) from catalog_product_entity_varchar i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_varchar i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
where i1.attribute_id=128 and i.attribute_id=147 and i.value is null;

update catalog_product_entity_varchar i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
set i.value = o.option_id 
where i1.attribute_id=@live_attr_id and i.attribute_id=@demo_attr_id and i.value is null;