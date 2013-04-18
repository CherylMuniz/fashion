-- _varchar without options --
select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and frontend_input not in('select','multiselect','boolean') and backend_type='varchar';
/*
+------------------------+
| attribute_code         |
+------------------------+
| custom_designer_frames |
| extra_title            |
| gallery                |
| image                  |
| image_label            |
| media_gallery          |
| meta_description       |
| meta_title             |
| name                   |
| small_image            |
| small_image_label      |
| thumbnail              |
| thumbnail_label        |
| url_key                |
+------------------------+
*/
DROP PROCEDURE IF EXISTS _varchar;
DELIMITER ||
CREATE PROCEDURE _varchar(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=4;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    update catalog_product_entity_varchar v 
    join catalog_product_entity e on v.entity_id = e.entity_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.catalog_product_entity_varchar v1 on v1.entity_id = e1.entity_id
    SET v.value = v1.value
    where v.attribute_id = @attr_id and v1.attribute_id = @live_attr_id;
END
||
DELIMITER ;

call _varchar('custom_designer_frames');
call _varchar('extra_title');
call _varchar('gallery');
call _varchar('image');
call _varchar('image_label');
call _varchar('media_gallery');
call _varchar('meta_description');
call _varchar('meta_title');
call _varchar('name');
call _varchar('small_image');
call _varchar('small_image_label');
call _varchar('thumbnail');
call _varchar('thumbnail_label');
call _varchar('url_key');

-- check catalog_product_entity_varchar --
select *,count(*) from catalog_product_entity_varchar where value is null group by attribute_id;