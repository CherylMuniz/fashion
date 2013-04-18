<?php
ini_set("memory_limit","2000M");
require '../app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
echo date("\nY-d-m H:i:s")." - update frame size start\n";

//$product_id = 941;
$type = Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO; 

$collection = Mage::getModel('catalog/product')->getCollection()->getAllIds();

$count = sizeof($collection); $i=0;
foreach($collection as $product_id){
    $product = Mage::getModel('catalog/product')->load($product_id);
    if( in_array($product->getSku(), array('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley')) ){ continue; }
    foreach ($product->getOptions() as $o) {
        $p = $o->getValues();
        if( $o->getTitle() == 'Please select your frame size' && $o->getType() != 'radio' ){
           $values = array();
            foreach($p as $v){
                $data = $v->getData();
                foreach ($data as $dk => $dv){
                    $values[$v->getId()][$dk]= $dv;
                }
                $o->setType($type);
                $o->setSku('frame_size');
                $o->setValues($values);

                try{ $o->save(); } catch (Exception $e){
                    echo $e->getMessage()."\n";
                }
            }
        }
        //mage::d($o->getData());
    }
    ++$i; echo ' ' . floor($i*100/$count) . '%';
}
echo date("\nY-d-m H:i:s")." - update frame size end\n";