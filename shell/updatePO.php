<?php
ini_set("memory_limit","2000M");
require '../app/Mage.php';
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

$product_id = 941;
$type = Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN;   //OPTION_TYPE_RADIO
/*
 *  const OPTION_GROUP_TEXT   = 'text';
    const OPTION_GROUP_FILE   = 'file';
    const OPTION_GROUP_SELECT = 'select';
    const OPTION_GROUP_DATE   = 'date';

    const OPTION_TYPE_FIELD     = 'field';
    const OPTION_TYPE_AREA      = 'area';
    const OPTION_TYPE_FILE      = 'file';
    const OPTION_TYPE_DROP_DOWN = 'drop_down';
    const OPTION_TYPE_RADIO     = 'radio';
    const OPTION_TYPE_CHECKBOX  = 'checkbox';
    const OPTION_TYPE_MULTIPLE  = 'multiple';
    const OPTION_TYPE_DATE      = 'date';
    const OPTION_TYPE_DATE_TIME = 'date_time';
    const OPTION_TYPE_TIME      = 'time';
 * 
 */

$product = Mage::getModel('catalog/product')->load($product_id);
foreach ($product->getOptions() as $o) {
    $p = $o->getValues();
    if( $o->getTitle() == 'Please select your frame size' ){
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
    mage::d($o->getData());
}
        
        
