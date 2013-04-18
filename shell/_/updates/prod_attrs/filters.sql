-- set filter order --
select c.attribute_id, e.attribute_code, c.position, c1.attribute_id, e1.attribute_code, c1.position from catalog_eav_attribute c 
join eav_attribute e on c.attribute_id = e.attribute_id
join fashione_magento3.eav_attribute e1 on e.attribute_code = e1.attribute_code
join fashione_magento3.catalog_eav_attribute c1 on e1.attribute_id = c1.attribute_id;

create catalog_eav_attribute2 select * from catalog_eav_attribute;
update catalog_eav_attribute c 
join eav_attribute e on c.attribute_id = e.attribute_id
join fashione_magento3.eav_attribute e1 on e.attribute_code = e1.attribute_code
join fashione_magento3.catalog_eav_attribute c1 on e1.attribute_id = c1.attribute_id
set c.position = c1.position;