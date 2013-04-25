select count(*) from catalog_category_entity;
select count(*) from fashione_magento3.catalog_category_entity;

-- check position  ---
select * from catalog_category_product_index i
join catalog_product_entity e on e.entity_id = i.product_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_category_product_index i1 on i1.product_id = e1.entity_id
limit 1\G
-- update don't run, it's index --
update catalog_category_product_index i
join catalog_product_entity e on e.entity_id = i.product_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.catalog_category_product_index i1 on i1.product_id = e1.entity_id
set i.position = i1.position;