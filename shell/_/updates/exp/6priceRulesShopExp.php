<?
ini_set("memory_limit","1000M");
require_once '/home/www/production/app/Mage.php';
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

echo date("\nY-d-m H:i:s\n")."Begin\n";
$ids = Mage::getModel('salesrule/rule')->getCollection()->getAllIds();

$collection = array();
foreach($ids as $id){
    $rule = Mage::getModel('salesrule/rule')->load($id);
    $cond = unserialize($rule->getConditionsSerialized());
    conditions($cond);
    //mage::d($cond);
    $rule->setConditionsSerialized(serialize($cond));
    $collection[] = $rule;
}
# set Attribute label instead of attribute id for export. 
function conditions(&$cond){
    if($cond['type'] === 'salesrule/rule_condition_combine' || $cond['type'] === 'salesrule/rule_condition_product_found'){
        foreach( $cond['conditions'] as &$c ){
            if(is_array($c)){
                conditions($c);
            }
        } unset($c); // need for accurate delete link!
    }else{
        switch( $cond['attribute'] ) {
            case 'attribute_set_id' : 
                $model = Mage::getModel('eav/entity_attribute_set')->load($cond['value']);
                $cond['value'] = $model->getAttributeSetName();
                break;
            case 'category_ids' : 
                $categ_arr = explode(", ", $cond['value']);
                foreach($categ_arr as &$c){
                    $c = Mage::getModel('catalog/category')->load($c)->getName();
                }unset($c);
                $cond['value'] = implode(", ", $categ_arr);
                break;
            default : 
                $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $cond['attribute']);
                 if(!$attribute) { return; }
                $allOpts = $attribute->getSource()->getAllOptions(true, true);
                foreach ($allOpts as $option){
                    if( $option['value'] == $cond['value']){
                        $cond['value'] = $option['label'];
                    }
                }
        }
         //mage::d($cond);
    }
}

$content = serialize($collection);
$filename = 'shopping_cart_price_rules.ser';
$fo = fopen($filename, "w");
fputs($fo, $content);
fclose($fo);
echo date("\nY-d-m H:i:s\n")."End\n";