select * from catalogsearch_fulltext f
join catalog_product_entity e on e.entity_id = f.product_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalogsearch_fulltext f1 on e1.entity_id=f1.product_id
limit 1\G 

-- need be equal --
select count(*) from fashione_magento3.catalogsearch_fulltext f;
select count(*) from fashione_magento3.catalogsearch_fulltext f join fashione_magento3.catalog_product_entity e on e.entity_id = f.product_id;

select count(*) from catalogsearch_fulltext f;
select count(*) from catalogsearch_fulltext f join catalog_product_entity e on e.entity_id = f.product_id;


-- delete diff --
select count(*) from catalogsearch_fulltext f left join catalog_product_entity e on f.product_id = e.entity_id where e.entity_id is null;
delete f.* from catalogsearch_fulltext f left join catalog_product_entity e on f.product_id = e.entity_id where e.entity_id is null;


-- not exists in search --
select f.product_id, e.entity_id, e.sku, count(*) from fashione_magento3.catalog_product_entity e left join fashione_magento3.catalogsearch_fulltext f on f.product_id = e.entity_id where f.product_id is null ;

-- update from live --
update catalogsearch_fulltext f 
join catalog_product_entity e on e.entity_id = f.product_id 
join fashione_magento3.catalog_product_entity e1 on e.sku=e1.sku 
join fashione_magento3.catalogsearch_fulltext f1 on e1.entity_id=f1.product_id
set f.data_index = f1.data_index;


-- !!! POSSIBLE PROBLEMS  !!! --
/*
 don't fill on `php indexer.php --reindex catalogseatch_fulltext` to `catalogseatch_fulltext` data_index some attrs: (name, description, etc..) 
  in the: Mage_CatalogSearch_Model_Resource_Fulltext => _getAttributeValue => $attribute->usesSource() [Mage_Eav_Model_Entity_Attribute_Abstract]
  should not have source_model filled for name, description, etc. See table eav_attribute
  
  be warning: var_dump($attribute->getSource()) change $attribute->usesSource() always to true!!! Although getSource() return "source empty object", if source_model not exists in eav_attribute table, but nevertheless is it the object, also usesSource() return true!!! (((
*/
-- !!!! eav_attribute need update with attention! source_model is different for 1.5 and 1.7 versions!!!
--update eav_attribute a
join fashione_magento3.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id
set a.source_model = a1.source_model;

select * from eav_attribute a
join fashione_magento3.eav_attribute a1 on a.attribute_code = a1.attribute_code and a.entity_type_id = a1.entity_type_id
limit 1\G