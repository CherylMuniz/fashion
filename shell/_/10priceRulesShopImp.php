<?php
# +++++++++++++++++ Parse product custom options ++++++++++++++++++++++++
# Attribute for price rules must have "Use for Promo Rule Conditions" option set to "Yes" 

ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Shopping_Cart_Price_Rules{
    
    public $file = '../var/import/shopping_cart_price_rules.ser';
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
        $catalogPriceRule = Mage::getModel('salesrule/rule');
        $cond = unserialize( $item->getConditionsSerialized() );
        //mage::D($item); return; //die;
        $this->conditions($cond);
        $item->setConditionsSerialized( serialize($cond) );
        $object = $item->getData();
        //mage::d($object);
        $data = array(
            'rule_id' => $object['rule_id'],
            'name' => $object['name'],
            'description' => $object['description'],
            'from_date' => $object['from_date'],
            'to_date' => $object['to_date'],
            'uses_per_customer' => $object['uses_per_customer'],
            'customer_group_ids' => $object['customer_group_ids'],
            'is_active' => $object['is_active'],
            'conditions_serialized' => $object['conditions_serialized'],
            'actions_serialized' => $object['actions_serialized'],
            'stop_rules_processing' => $object['stop_rules_processing'],
            'is_advanced' => $object['is_advanced'],
            //'product_ids' => $object['product_ids'],
            'sort_order' => $object['sort_order'],
            'simple_action' => $object['simple_action'],
            'discount_amount' => $object['discount_amount'],
            'discount_qty' => $object['discount_qty'],
            'discount_step' => $object['discount_step'],
            'simple_free_shipping' => $object['simple_free_shipping'],
            'apply_to_shipping' => $object['apply_to_shipping'],
            'times_used' => $object['times_used'],
            'is_rss' => $object['is_rss'],
            'website_ids' => array(1),
            'coupon_type' => $object['coupon_type'],
            'coupon_code' => $object['coupon_code'],
            'uses_per_coupon' => $object['uses_per_coupon'],
         );
         
        $catalogPriceRule
                ->setName( $object['name'] )
                ->setDescription( $object['description'] )
                ->setFromDate( $object['from_date'] )
                ->setToDate( $object['to_date'] )
                ->setUsesPerCustomer( $object['uses_per_customer'] )
                ->setCustomerGroupIds( $object['customer_group_ids'] )
                ->setIsActive( $object['is_active'] )
                ->setConditionsSerialized( $object['conditions_serialized'] )
                ->setActionsSerialized( $object['actions_serialized'] )
                ->setStopRulesProcessing( $object['stop_rules_processing'] )
                ->setIsAdvanced( $object['is_advanced'] )
                //->setProductIds( $object['product_ids'] )
                ->setSortOrder( $object['sort_order'] )
                ->setSimpleAction( $object['simple_action'] )
                ->setDiscountAmount( $object['discount_amount'] )
                ->setDiscountQty ( $object['discount_qty'] )
                ->setDiscountStep ( $object['discount_step'] )
                ->setSimpleFreeShipping( $object['simple_free_shipping'] )
                ->setApplyToShipping( $object['apply_to_shipping'] )
                ->setTimesUsed( $object['times_used'] )
                ->setIsRss( $object['is_rss'] )
                ->setWebsiteIds( array(1) )
                ->setCouponType( $object['coupon_type'] )
                ->setCouponCode( $object['coupon_code'] )
                ->setUsesPerCoupon( $object['uses_per_coupon'] )
                ;
        try {
            $catalogPriceRule->save();
        } catch (Exception $e) {
                mage::D($e);
            return;
        }
    }
    
    public function conditions(&$cond){
        if($cond['type'] == 'salesrule/rule_condition_combine' || $cond['type'] === 'salesrule/rule_condition_product_found'){
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
         //mage::d($cond);
        }
    }
}
echo date("\nY-d-m H:i:s")." - shopping cart price rules import start\n";
$imp = new Shopping_Cart_Price_Rules();
$imp->load();
echo date("\nY-d-m H:i:s")." - shopping cart price rules import completed\n";