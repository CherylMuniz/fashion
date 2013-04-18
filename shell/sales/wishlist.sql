-- wishlist
-- wishlist_item
-- wishlist_item_option

delete from wishlist; 
insert into wishlist select * from fashione_magento3.wishlist;

insert into wishlist_item (
wishlist_item_id,
wishlist_id,
product_id,
store_id,
added_at,
description,
qty
)
select 
t1.wishlist_item_id,
t1.wishlist_id,
e.entity_id,
t1.store_id,
t1.added_at,
t1.description,
t1.qty
from  fashione_magento3.wishlist_item t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;

insert into wishlist_item_option (
option_id,
wishlist_item_id,
product_id,
code,
value
)
select 
t1.option_id,
t1.wishlist_item_id,
e.entity_id,
t1.code,
t1.value
from  fashione_magento3.wishlist_item_option t1
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = t1.product_id
join catalog_product_entity e on e.sku = e1.sku;
