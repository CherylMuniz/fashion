-- all _int --
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and backend_type='int';

-- without options (empty set) --
select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and frontend_input not in('select','multiselect','boolean') and backend_type='int';
select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and frontend_input is null and backend_type='int';
/*
+----------------------------+
| attribute_code             |
+----------------------------+
| links_exist                |
| links_purchased_separately |
| old_id                     |
| price_type                 |
| shipment_type              |
| sku_type                   |
| weight_type                |
+----------------------------+
*/

select count(*) from catalog_product_entity_int where value is null;  select count(*) from fashione_magento3.catalog_product_entity_int where value is null;
select count(*) from catalog_product_entity_int t left join catalog_product_entity e on t.entity_id = e.entity_id where e.entity_id is null;



DROP PROCEDURE IF EXISTS _int;
DELIMITER ||
CREATE PROCEDURE _int(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=4;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    update catalog_product_entity_int t 
    join catalog_product_entity e on t.entity_id = e.entity_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.catalog_product_entity_int t1 on t1.entity_id = e1.entity_id
    SET t.value = t1.value
    where t.attribute_id = @attr_id and t1.attribute_id = @live_attr_id;
END
||
DELIMITER ;

call _int('custom_layout_update');
call _int('description');
call _int('gift_amount');
call _int('meta_keyword');
call _int('recurring_profile');
call _int('short_description');


-- select --
select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and attribute_code not in('status','visibility') and frontend_input in('select') and backend_type='int';
/*
+-----------------------+
| attribute_code        |
+-----------------------+
| color                 |
| enable_googlecheckout |
| extra_product         |
| frame_size            |
| frame_type            |
| gender                |
| is_imported           |
| is_recurring          |
| manufacturer          |
| price_view            |
| stock_clearance       |
| tax_class_id          |
+-----------------------+

*/

DROP PROCEDURE IF EXISTS _intopt;
DELIMITER ||
CREATE PROCEDURE _intopt(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=4;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    update catalog_product_entity_int i 
    join catalog_product_entity e on i.entity_id=e.entity_id 
    join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
    join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id and i1.attribute_id=@live_attr_id
    join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id and o1.attribute_id=@live_attr_id
    join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id
    join eav_attribute_option_value v on v1.value = v.value 
    join eav_attribute_option o on v.option_id = o.option_id and o.attribute_id=@attr_id
    set i.value = o.option_id 
    where i1.attribute_id=@live_attr_id and i.attribute_id=@attr_id;
    
END
||
DELIMITER ;

call _intopt('color');
call _intopt('enable_googlecheckout');
call _intopt('extra_product');
call _intopt('frame_size');
call _intopt('frame_type');
call _intopt('gender');
call _intopt('is_imported');
call _intopt('is_recurring');
call _intopt('manufacturer');
call _intopt('price_view');
call _intopt('stock_clearance');
-- call _intopt('tax_class_id'); //set manually, so as it is special(?) and depend on tax_class.