<?php

/* ++++++++++++++++ rotate 360 rename folders from id to sku ++++++++++
 */
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '/home/www/demo/app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
die;
$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

$select = $connection->query("
SELECT e.entity_id, e.sku FROM fashione_magento3.catalog_product_entity e
INNER JOIN fashione_magento3.catalog_product_img360 i ON 
e.entity_id = i.product_id
");
$result = $select->fetchAll();
//mage::d(count($result)); die;
$i=0;
foreach($result as $res){
    try{
        rename('/home/www/demo/media/catalog/img360/'.$res['entity_id'], '/home/www/demo/media/catalog/img360/'.$res['sku']);
        ++$i;
    }catch( Exception $e ){
        echo $e->getMessage();
    }
}
echo "\nrenamed folders - ".$i."\n";


/*
INSERT INTO catalog_product_img360 (
product_id,
path,
title
)
SELECT e.entity_id, i.path, i.title FROM catalog_product_entity e
JOIN fashione_magento3.catalog_product_entity e1
ON e.sku = e1.sku
JOIN fashione_magento3.catalog_product_img360 i ON 
e1.entity_id = i.product_id
*/