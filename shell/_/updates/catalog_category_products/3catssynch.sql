create table if not exists catalog_category_product_fm3 select * from catalog_category_product; 
delete from catalog_category_product_fm3;
insert into catalog_category_product_fm3 select * from fashione_magento3.catalog_category_product;

update catalog_category_product_fm3 p
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = p.product_id
join catalog_product_entity e on e1.sku = e.sku
set product_id = e.entity_id;

update catalog_category_product_fm3 p
join catalog_category_product_synch s on p.category_id = s.live_category_id
set p.category_id = s.category_id;

drop table if exists catalog_category_product2; 
create table catalog_category_product2 like catalog_category_product;
insert into catalog_category_product2 select * from catalog_category_product;

delete from catalog_category_product;
insert into catalog_category_product select * from catalog_category_product_fm3;