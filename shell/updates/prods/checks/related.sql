set @attribute='status';
set @entity_type_id=4;
set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

-- count enabled (value=1) products without lens --
select count(e.sku) from catalog_product_entity e 
join catalog_product_entity_int i on e.entity_id = i.entity_id 
left join catalog_product_link l on e.entity_id = l.product_id 
where l.product_id is null and i.attribute_id=@attr_id and i.value=1; 

-- count all products without lens --
select count(e.sku) from catalog_product_entity e 
left join catalog_product_link l on e.entity_id = l.product_id 
where l.product_id is null; 

-- examples --
select e.sku from catalog_product_entity e 
left join catalog_product_link l on e.entity_id = l.product_id
where l.product_id is null limit 15;

-- examples --
select e.sku from catalog_product_entity e 
join catalog_product_entity_int i on e.entity_id = i.entity_id 
left join catalog_product_link l on e.entity_id = l.product_id 
where l.product_id is null and i.attribute_id=@attr_id and i.value=1 limit 10; 

