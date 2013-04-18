<?php

/* ++++++++++++++++ notes ++++++++++
 * catalog_product_index_price - only through saving from adminpanel :(
 * and than 
 * catalog_category_product_index need restart reindex
 * 
 */
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$sku = null;
for(;;){
    if( !empty($sku) ) { break; }
    if( !empty($argv[1])){ $sku = $argv[1]; break; }
    echo "\nEnter SKU: "; 
    $sku = trim(fgets(STDIN));
}

$connection = Mage::getModel('core/resource')->getConnection('core_read');
$product = new Mage_Catalog_Model_Product();

$typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
$setId = Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($typeId)->addFilter('attribute_set_name', 'Default')->getFirstItem()->getId(); //$setId = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($typeId)->addFilter('attribute_set_name', 'Default')->getFirstItem()->getId(); 

// Build the product
$product->setSku($sku);
$product->setAttributeSetId($setId);# 9 is for default
$product->setTypeId('simple');
$product->setName('Lens 1');
$product->setCategoryIds(array(3)); # some cat id's,
$product->setWebsiteIDs(array(0,1)); //only array!!!!!  # Website id, 1 is default
$product->setStoreIDs(array(0,1));
$product->setDescription('Full description here');
$product->setShortDescription('Short description here');
$product->setPrice(39.99); # Set some price
$product->setWeight(4.0000);
$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
$product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
$product->setTaxClassId(0); # default tax class
$product->setStockData(array(
    'is_in_stock' => 1,
    'qty' => 99999
));

$product->setCreatedAt(strtotime('now'));

try {
    $product->save();
    echo "product created";
}
catch (Exception $ex) {
    zend_debug::dump($ex->getMessage());
}
echo date("\nY-d-m H:i:s\n");
?> 