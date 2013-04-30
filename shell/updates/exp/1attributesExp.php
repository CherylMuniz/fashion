<?php
ini_set("memory_limit","-1");
require_once '/home/www/production/app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
echo date("\nY-d-m H:i:s\n")."Begin\n";
$type_id = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();

//remember, for catalog_product and other entyty_type is unique attribute_code.
$attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')->addSetInfo(true)->setEntityTypeFilter($type_id)->load()->getItems();                             //$attributesCollection = Mage::getModel('eav/entity_attribute')->getCollection()->addFilter('entity_type_id',$type_id)->getItems();

$collection = array();
foreach($attributesCollection as $attr){
    $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attr->getAttributeCode());
    try {
        $attribute->setData( 'options', $attribute->getSource()->getAllOptions(false) ); 
    }catch(Exception $e){}
    $collection[] = $attribute->getData();
}
 $content = serialize($collection);
 $filename = 'attributes.ser';
$fo = fopen($filename, "w");
fputs($fo, $content);
fclose($fo);
echo date("\nY-d-m H:i:s\n")."End\n";