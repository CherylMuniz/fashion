drop table catalogsearch_fulltext_bkp;
rename table catalogsearch_fulltext to catalogsearch_fulltext_bkp;
create table catalogsearch_fulltext like catalogsearch_fulltext_bkp;
alter table catalogsearch_fulltext AUTO_INCREMENT=1;
INSERT INTO catalogsearch_fulltext (
    product_id,
    store_id,
    data_index
) SELECT 
    cpe.entity_id,
    1,
    mcf.data_index
FROM fashione_magento3.catalogsearch_fulltext AS mcf 
    INNER JOIN fashione_magento3.catalog_product_entity AS mcpe 
    ON mcf.product_id = mcpe.entity_id
    INNER JOIN catalog_product_entity AS cpe 
    ON mcpe.sku = cpe.sku
ON DUPLICATE KEY UPDATE 
    data_index = mcf.data_index