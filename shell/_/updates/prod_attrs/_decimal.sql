select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and backend_type='decimal';
select count(*) from catalog_product_entity_decimal where value is null;
select count(*) from fashione_magento3.catalog_product_entity_decimal where value is null;

select count(*) from catalog_product_entity_decimal d join catalog_product_entity e on d.entity_id = e.entity_id where value is null and e.sku not in ('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley');
select count(*) from catalog_product_entity_decimal d left join catalog_product_entity e on d.entity_id = e.entity_id where e.entity_id is null;
/*
+----------------+
| attribute_code |
+----------------+
| cost           |
| minimal_price  |
| price          |
| special_price  |
| tier_price     |
| weight         |
+----------------+

*/
DROP PROCEDURE IF EXISTS _decimal;
DELIMITER ||
CREATE PROCEDURE _decimal(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=4;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    update catalog_product_entity_decimal d 
    join catalog_product_entity e on d.entity_id = e.entity_id and d.attribute_id = @attr_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.catalog_product_entity_decimal d1 on d1.entity_id = e1.entity_id and d1.attribute_id = @live_attr_id
    SET d.value = d1.value
    where d.attribute_id = @attr_id and d1.attribute_id = @live_attr_id;
    
    select * from catalog_product_entity_decimal d 
    join catalog_product_entity e on d.entity_id = e.entity_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.catalog_product_entity_decimal d1 on d1.entity_id = e1.entity_id
    where d.attribute_id = @attr_id and d1.attribute_id = @live_attr_id and d.value is null and d1.value is not null;
    
END
||
DELIMITER ;

call _decimal('cost');
call _decimal('minimal_price');
call _decimal('price');
call _decimal('special_price');
call _decimal('tier_price');
call _decimal('weight');

-- check price --
select d.price, d1.price, e.sku, e1.sku from catalog_product_index_price d 
join catalog_product_entity e on d.entity_id = e.entity_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
left join fashione_magento3.catalog_product_index_price d1 on d1.entity_id = e1.entity_id
where d.price is not null and d1.price is null;





set @attribute= 'price';
set @entity_type_id=4;
set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

select * from catalog_product_entity_decimal d 
join catalog_product_entity e on d.entity_id = e.entity_id and d.attribute_id = @attr_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_product_entity_decimal d1 on d1.entity_id = e1.entity_id and d1.attribute_id = @live_attr_id
where d.attribute_id = @attr_id and d1.attribute_id = @live_attr_id limit 1\G