<?php
# +++++++++++++++++ Parse product custom options ++++++++++++++++++++++++
# Attribute for price rules must have "Use for Promo Rule Conditions" option set to "Yes" 

ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app();
//Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Custom_Options_Parse{
    
    public $file = '../var/import/catalog_price_rules.ser';
    public $rules;
    
    public function __construct(){
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->rules = unserialize($content);
        echo "File loaded \n";
    }
    
    public function load(){
        foreach($this->rules as $object){
            $this->parse($object);
        }
    }
    
    public function parse($item){
        $catalogPriceRule = Mage::getModel('catalogrule/rule');
        $cond = unserialize( $item->getConditionsSerialized() );
        $this->conditions($cond);
        $item->setConditionsSerialized( serialize($cond) );
        $object = $item->getData();
         $data = array(
            'rule_id' => $object['rule_id'],
            'name' => $object['name'],
            'description' => $object['description'],
            'from_date' => $object['from_date'],
            'to_date' => $object['to_date'],
            'customer_group_ids' => $object['customer_group_ids'],
            'is_active' => $object['is_active'],
            'conditions_serialized' => $object['conditions_serialized'],
            'actions_serialized' => $object['actions_serialized'],
            'stop_rules_processing' => $object['stop_rules_processing'],
            'sort_order' => $object['sort_order'],
            'simple_action' => $object['simple_action'],
            'discount_amount' => $object['discount_amount'],
            'website_ids' => array(1),
            'customer_group_checked' => $object['customer_group_checked'],
         );
         
        $catalogPriceRule
                ->setName( $object['name'] )
                ->setDescription( $object['description'] )
                ->setFromDate( $object['from_date'] )
                ->setToDate( $object['to_date'] )
                ->setCustomerGroupIds( $object['customer_group_ids'] )
                ->setIsActive( $object['is_active'] )
                ->setConditionsSerialized( $object['conditions_serialized'] )
                ->setActionsSerialized( $object['actions_serialized'] )
                ->setStopRulesProcessing( $object['stop_rules_processing'] )
                ->setSortOrder( $object['sort_order'] )
                ->setSimpleAction( $object['simple_action'] )
                ->setDiscountAmount( $object['discount_amount'] )
                ->setWebsiteIds( array(1) )
                ->setCustomerGroupChecked($object['customer_group_checked'])
                ;
        try {
            $catalogPriceRule->save();
        } catch (Exception $e) {
                mage::D($e);
            return;
        }
    }
    
    public function conditions(&$cond){
        if($cond['type'] == 'catalogrule/rule_condition_combine'){
            foreach( $cond['conditions'] as &$c ){
                if(is_array($c)){
                    $this->conditions($c);
                }
            } unset($c); // need for accurate delete link!
        }else{
            switch( $cond['attribute'] ) {
                case 'attribute_set_id' : 
                    $model = Mage::getModel('eav/entity_attribute_set')->getCollection()->getItemByColumnValue('attribute_set_name', $cond['value']);
                    $cond['value'] = $model->getId();
                    break;
                case 'category_ids' : 
                    $categ_arr = explode(", ", $cond['value']);
                    foreach($categ_arr as &$c){
                        if( empty($c) ) continue;
                        $c = Mage::getModel('catalog/category')->loadByAttribute('name', $c)->getData('entity_id');
                    }unset($c);
                    $cond['value'] = implode(", ", $categ_arr);
                    break;
                default : 
                    $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $cond['attribute']); if(!$attribute) { return; }
                    $allOpts = $attribute->getSource()->getAllOptions(true, true);
                    foreach ($allOpts as $option){
                        if( $option['label'] == $cond['value']){
                            $cond['value'] = $option['value'];
                        }
                    }
            }
        }
    }
}
echo date("\nY-d-m H:i:s")." - catalog price rules import start\n";
$imp = new Custom_Options_Parse();
$imp->load();
echo date("\nY-d-m H:i:s")." - catalog price rules import completed\n";