set @attribute='price';
set @entity_type_id=4;
set @attr_id = (select attribute_id from eav_attribute where entity_type_id=4 and attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=4 and attribute_code=@attribute);

select * from catalog_product_entity_decimal d 
join catalog_product_entity e on d.entity_id = e.entity_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_product_entity_decimal d1 on d1.entity_id = e1.entity_id
where d.value <> d1.value and d.attribute_id=@attr_id and d1.attribute_id=@live_attr_id
limit 1\G

drop table if exists catalog_product_entity_decimal2; create table catalog_product_entity_decimal2 select * from catalog_product_entity_decimal;

update catalog_product_entity_decimal d 
join catalog_product_entity e on d.entity_id = e.entity_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_product_entity_decimal d1 on d1.entity_id = e1.entity_id
set d.value = d1.value
where d.attribute_id=@attr_id and d1.attribute_id=@live_attr_id;


-- check price indexes ( should be identical ) --
select * from catalog_product_index_price p
join catalog_product_entity e on p.entity_id = e.entity_id and p.customer_group_id = 0

join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join catalog_product_index_price p1 on p1.entity_id = e1.entity_id and p1.customer_group_id = 0
limit 1\G


update catalog_product_index_price p
join catalog_product_entity e on p.entity_id = e.entity_id and p.customer_group_id = 0
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join catalog_product_index_price p1 on p1.entity_id = e1.entity_id and p1.customer_group_id = 0
set 
p.price = p1.price,
p.final_price = p1.final_price,
p.final_price = p1.final_price,
p.min_price = p1.min_price,
p.max_price = p1.max_price;




----------------------- clearance: 246 live, 199 demo
SELECT count(*) FROM `catalog_product_entity` AS `e`
INNER JOIN `catalog_category_product_index` AS `cat_index` 
ON cat_index.product_id=e.entity_id 
AND cat_index.store_id=1 
AND cat_index.visibility IN(2, 4) 
AND cat_index.category_id='199' 
AND cat_index.is_parent=1

INNER JOIN `catalog_product_index_price` AS `price_index` 
ON price_index.entity_id = e.entity_id 
AND price_index.website_id = '1' 
AND price_index.customer_group_id = 0;