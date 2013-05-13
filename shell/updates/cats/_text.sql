set @entity_type_id=3;
-- select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=@entity_type_id and backend_type='text' and frontend_input='textarea';
-- select count(*) from catalog_category_entity_text where value is null;
-- select count(*) from catalog_category_entity_text t left join catalog_category_entity e on t.entity_id = e.entity_id where e.entity_id is null;
/*
custom_layout_update
description
meta_description
meta_keywords
*/
DROP PROCEDURE IF EXISTS cat_text;
DELIMITER ||
CREATE PROCEDURE cat_text(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=3;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into catalog_category_entity_text (
		entity_type_id,
		attribute_id,
		store_id,
		entity_id,
		value
    ) select 
		@entity_type_id,
		@attr_id,
		0,
		s.category_id,
		t1.value
    from fashione_magento3.catalog_category_entity_text t1
	join catalog_category_product_synch s on t1.entity_id = s.live_category_id 
		and t1.attribute_id = @live_attr_id 
		and t1.store_id = 0
		and s.value like '%Designer Eyewear%'
	on duplicate key update
	value = t1.value;
END
||
DELIMITER ;

call cat_text('custom_layout_update');
call cat_text('description');
call cat_text('meta_description');
call cat_text('meta_keywords');



-- **************************************** varchar ********************
-- select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=@entity_type_id and backend_type='varchar' and frontend_input='text';
/* 
meta_title
name
url_key
url_path 
*/
DROP PROCEDURE IF EXISTS cat_varchar;
DELIMITER ||
CREATE PROCEDURE cat_varchar(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=3;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into catalog_category_entity_varchar (
		entity_type_id,
		attribute_id,
		store_id,
		entity_id,
		value
    ) select 
		@entity_type_id,
		@attr_id,
		0,
		s.category_id,
		t1.value
    from fashione_magento3.catalog_category_entity_varchar t1
	join catalog_category_product_synch s on t1.entity_id = s.live_category_id 
		and t1.attribute_id = @live_attr_id 
		and t1.store_id = 0
		and s.value like '%Designer Eyewear%'
	on duplicate key update
	value = t1.value;
END
||
DELIMITER ;
call cat_varchar('meta_title');
call cat_varchar('name');
call cat_varchar('url_key');
call cat_varchar('url_path');



-- int --
-- select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=@entity_type_id and backend_type='int';
/*
custom_apply_to_products
custom_use_parent_settings
include_in_menu
is_active
is_anchor
landing_page
*/
DROP PROCEDURE IF EXISTS cat_int;
DELIMITER ||
CREATE PROCEDURE cat_int(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=3;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into catalog_category_entity_int (
		entity_type_id,
		attribute_id,
		store_id,
		entity_id,
		value
    ) select 
		@entity_type_id,
		@attr_id,
		0,
		s.category_id,
		t1.value
    from fashione_magento3.catalog_category_entity_int t1
	join catalog_category_product_synch s on t1.entity_id = s.live_category_id 
		and t1.attribute_id = @live_attr_id 
		and t1.store_id = 0
		and s.value like '%Designer Eyewear%'
	on duplicate key update
	value = t1.value;
END
||
DELIMITER ;
call cat_int('custom_use_parent_settings');