select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and backend_type='text';
select count(*) from catalog_product_entity_text where value is null;
select count(*) from catalog_product_entity_text t left join catalog_product_entity e on t.entity_id = e.entity_id where e.entity_id is null;
/*
+----------------------+
| attribute_code       |
+----------------------+
| custom_layout_update |
| description          |
| gift_amount          |
| meta_keyword         |
| recurring_profile    |
| short_description    |
+----------------------+

*/
DROP PROCEDURE IF EXISTS _text;
DELIMITER ||
CREATE PROCEDURE _text(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=4;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    update catalog_product_entity_text t 
    join catalog_product_entity e on t.entity_id = e.entity_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.catalog_product_entity_text t1 on t1.entity_id = e1.entity_id
    SET t.value = t1.value
    where t.attribute_id = @attr_id and t1.attribute_id = @live_attr_id;
END
||
DELIMITER ;

call _text('custom_layout_update');
call _text('description');
call _text('gift_amount');
call _text('meta_keyword');
call _text('recurring_profile');
call _text('short_description');