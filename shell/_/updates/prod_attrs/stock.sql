-- cataloginventory_stock_item 
-- FOREIGN KEY (`product_id`) REFERENCES `catalog_product_entity` (`entity_id`) 
-- FOREIGN KEY (`stock_id`) REFERENCES `cataloginventory_stock` (`stock_id`) 

-- #delete from cataloginventory_stock; insert into cataloginventory_stock select * from fashione_magento3.cataloginventory_stock;

drop table if exists cataloginventory_stock_item2;
create table cataloginventory_stock_item2 select * from cataloginventory_stock_item;

update cataloginventory_stock_item i 
join cataloginventory_stock s on i.stock_id = s.stock_id
join catalog_product_entity e on i.product_id = e.entity_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.cataloginventory_stock_item i1 on i1.product_id = e1.entity_id
join fashione_magento3.cataloginventory_stock s1 on s1.stock_id = i1.stock_id
SET 
i.qty = i1.qty,  
i.is_in_stock = i1.is_in_stock, 
i.max_sale_qty = i1.max_sale_qty;


    /*select * from cataloginventory_stock_item i 
    join cataloginventory_stock s on i.stock_id = s.stock_id
    join catalog_product_entity e on i.product_id = e.entity_id
    join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
    join fashione_magento3.cataloginventory_stock_item i1 on i1.product_id = e1.entity_id
    join fashione_magento3.cataloginventory_stock s1 on s1.stock_id = i1.stock_id limit 1\G*/


-- cataloginventory_stock_status --
drop table if exists cataloginventory_stock_status2;
create table cataloginventory_stock_status2 select * from cataloginventory_stock_status;

update cataloginventory_stock_status ss 
join cataloginventory_stock s on ss.stock_id = s.stock_id
join catalog_product_entity e on ss.product_id = e.entity_id
join fashione_magento3.catalog_product_entity e1 on e1.sku = e.sku
join fashione_magento3.cataloginventory_stock_status ss1 on ss1.product_id = e1.entity_id
join fashione_magento3.cataloginventory_stock s1 on s1.stock_id = ss1.stock_id
SET ss.stock_status = ss1.stock_status;