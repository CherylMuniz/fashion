set @attribute= 'image';
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
v1.value
from fashione_magento3.catalog_category_entity_varchar v1
join catalog_category_product_synch s on v1.entity_id = s.live_category_id and v1.attribute_id = @live_attr_id and v1.store_id = 0
on duplicate key update
value = v1.value;
