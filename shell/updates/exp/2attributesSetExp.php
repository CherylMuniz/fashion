<?php
ini_set("memory_limit","-1");
require_once '/home/www/production/app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
echo date("\nY-d-m H:i:s\n")."Begin\n";

$type_id = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
$items = Mage::getSingleton('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($type_id)->getItems();  //$z = Mage::getSingleton('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() )->getItems();
$attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')->addSetInfo(true)->setEntityTypeFilter($type_id)->load()->getItems();                             //$attributesCollection = Mage::getModel('eav/entity_attribute')->getCollection()->addFilter('entity_type_id',$type_id)->getItems();

$collection = $attributesData = array();
foreach($attributesCollection as $attribute){ 
    $attribute->load();
    if( !$attribute->getSourceModel() ) { continue; }
    $attribute->setData( 'options', $attribute->getSource()->getAllOptions(false) );
    $attributesData[] = $attribute->getData();
}
foreach($items as $item){
    $groups = Mage::getModel('eav/entity_attribute_group')->getCollection()->setAttributeSetFilter($item->getId())->getItems();
    $groupData = array();
    foreach($groups as $group){
        $group->load();
        $attrInGroup = array();
        foreach($attributesCollection as $attribute){
            if( $attribute->isInGroup( $item->getId(), $group->getId() ) ){
                $attrInGroup[] = $attribute->getData();                 //$attrInGroup[] = $attribute->getAttributeCode();
            }
        }
        $group->setData( 'attributes', $attrInGroup );
        $groupData[] = $group->getData();
    }
    $item->setData('groups', $groupData);
    $collection[] = $item->getData();
}
 $content = serialize($collection);
 $filename = 'attributesFull.ser';
$fo = fopen($filename, "w");
fputs($fo, $content);
fclose($fo);
echo date("\nY-d-m H:i:s\n")."End\n";