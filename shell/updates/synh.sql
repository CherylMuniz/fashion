
CREATE TABLE `diff_catalog_product_entity` (
  `sku` varchar(64) DEFAULT NULL COMMENT 'SKU',
  KEY `IDX_CATALOG_PRODUCT_ENTITY_SKU` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 /* real sku */
insert into diff_catalog_product_entity SELECT e.sku FROM catalog_product_entity e where e.sku in( SELECT fe.sku FROM fashione_magento3.catalog_product_entity fe );

/* real sku */
SELECT count(e.sku) FROM catalog_product_entity e inner join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku;
insert into diff_catalog_product_entity SELECT e.sku FROM catalog_product_entity e inner join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku;
insert into diff_catalog_product_entity (sku) values ('lens-fullyrimmed'),('lens-rimless'),('lens-specialty'),('lens-standard'),('lens-oakley');
 /* to delete */
select count(e.sku) from catalog_product_entity e left join diff_catalog_product_entity de on e.sku = de.sku where de.sku is null;
delete e.* from catalog_product_entity e left join diff_catalog_product_entity de on e.sku = de.sku where de.sku is null;




-- II ---
CREATE TABLE `new_sku` (
 `sku` varchar(64) DEFAULT NULL COMMENT 'SKU',
 KEY `IDX_CATALOG_PRODUCT_ENTITY_SKU` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into new_sku SELECT e1.sku FROM fashione_magento3.catalog_product_entity e1 left join diff_catalog_product_entity e2 on e1.sku=e2.sku where e2.sku is null;
delete from new_sku where sku is null;



        -- differ two tables : ---
-- old (need delete)--
SELECT e.sku FROM catalog_product_entity e left join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku not in('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley');
-- new (need import)--
SELECT e.sku FROM fashione_magento3.catalog_product_entity e left join catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku is not null;

-- delete diff! ---
delete e.* from catalog_product_entity e left join fashione_magento3.catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku not in('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley');

-- check products ---
select count(*) from catalog_product_entity;
select count(*) from fashione_magento3.catalog_product_entity;



-- set visibility --
select * from eav_attribute where attribute_code ='visibility';
select count(*) from catalog_product_entity_int where attribute_id= and value=3;
update catalog_product_entity_int set value=4 where (attribute_id=200 and  value=3);


-- sinch catalog_product_entity with catalog_category_product;
select count(*) from fashione_magento3.catalog_product_entity e join fashione_magento3.catalog_category_product p on e.entity_id = p.product_id left join catalog_product_entity e1 on e.sku = e1.sku join catalog_category_product p1 on e1.entity_id = p1.product_id where e1.sku is null;


-- try update attributes ---
set @attr_id = (select attribute_id from fashione_magento3.eav_attribute where attribute_code='manufacturer');
set @cnt = (select count(*) from fashione_magento3.eav_attribute_option  where attribute_id=@attr_id);

set @manuf = (select attribute_id from eav_attribute where attribute_code='manufacturer');



-- import new values for attribute manufacturer --
set @value = (select v.value from fashione_magento3.eav_attribute_option_value v 
    join fashione_magento3.eav_attribute_option o on v.option_id= o.option_id and o.attribute_id=70 
    left join eav_attribute_option_value fv on v.value = fv.value where fv.value is null limit 1); 
insert into eav_attribute_option (attribute_id, sort_order) VALUES (163, 0);
insert into eav_attribute_option_value (option_id, store_id, value) VALUES (LAST_INSERT_ID(),0,@value);
-- end --

-- update products. set manufacturer where manufacturer option is null --
select count(*) from catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
where i1.attribute_id=70 and i.attribute_id=163 and i.value is null;

update catalog_product_entity_int i 
join catalog_product_entity e on i.entity_id=e.entity_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalog_product_entity_int i1 on e1.entity_id=i1.entity_id 
join fashione_magento3.eav_attribute_option o1 on i1.value = o1.option_id 
join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id 
join eav_attribute_option_value v on v1.value = v.value 
join eav_attribute_option o on v.option_id = o.option_id 
set i.value = o.option_id 
where i1.attribute_id=70 and i.attribute_id=163 and i.value is null;
-- end --


--- 1 product missing problem --
insert into temp (sku1,sku2) select e1.sku, e2.sku from catalog_product_entity e1 left join fashione_magento3.catalog_product_entity e2 on e1.sku = e2.sku;
CREATE TABLE `temp` (
  `sku1` varchar(64) DEFAULT NULL COMMENT 'SKU',
  `sku2` varchar(64) DEFAULT NULL COMMENT 'SKU',
  KEY `IDX_CATALOG_PRODUCT_ENTITY_SKU1` (`sku1`),
  KEY `IDX_CATALOG_PRODUCT_ENTITY_SKU2` (`sku2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
select count(*) from catalog_product_entity e join temp t on e.sku = t.sku1;
select count(t.sku1) from temp t join catalog_product_entity e on t.sku1 = e.sku;
-- end. (diff from text files) - duplicated sku OSC-474 --

-- catalog_category_product problem --
    -- check ccp on live --
select count(*) from fashione_magento3.catalog_category_product p join fashione_magento3.catalog_category_entity e on p.category_id = e.entity_id join fashione_magento3.catalog_product_entity e1 on p.product_id = e1.entity_id;
select count(*) from fashione_magento3.catalog_category_product p left join fashione_magento3.catalog_product_entity e1 on p.product_id = e1.entity_id where e1.entity_id is null;
--- ----
