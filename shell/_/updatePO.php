<?php
ini_set("memory_limit","2000M");
require '../app/Mage.php';
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

$product_id = 19763;
//$type = Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX;   //OPTION_TYPE_RADIO

$OPTION = 'is_require';
$VALUE = 0;

$product = Mage::getModel('catalog/product')->load($product_id);
//mage::D($product->getData() );
$names = array(
'SPH (Sphere) left',
'SPH (Sphere) right',
'',
'',
'',
);
foreach ($product->getOptions() as $o) {
    $p = $o->getValues();
    mage::D($o->getTitle());
    if( in_array($o->getTitle(), $names){
       $values = array();
        foreach($p as $v){
            $data = $v->getData();
            foreach ($data as $dk => $dv){
                $values[$v->getId()][$dk]= $dv;
            }
            //$o->setType($type);
            $o->setData($OPTION, $VALUE);
            $o->setValues($values);

            try{ $o->save(); } catch (Exception $e){
                echo $e->getMessage()."\n";
            }
            //mage::d($o->getValues());
        }
    }
}
