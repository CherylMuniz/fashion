<?php
# +++++++++++++++++ Parse product custom options ++++++++++++++++++++++++
# Attribute for price rules must have "Use for Promo Rule Conditions" option set to "Yes" 

ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app();
//Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Category_Export{
    
    public $file = '../var/import/categories.ser';
    public $objects;
    
    public function __construct(){
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        echo "File loaded \n";
    }
    
    public function load(){
        foreach($this->objects as $object){
            mage::D($object);
            die;
            //$this->parse($object);
        }
    }
    
    
    public function createCategory(){
        $catId = $this->getCategoryIdByName('Contact Lenses');
        if ($catId) return $catId;
        
        $parentId = Mage::app()->getStore()->getRootCategoryId();
        $category = new Mage_Catalog_Model_Category();
        $category->setName('Contact Lenses');
        $category->setUrlKey('contact-lenses');
        $category->setIsActive(1);
        $category->setDisplayMode('PRODUCTS');
        $category->setIsAnchor(0);
         
        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        $category->setPath($parentCategory->getPath());               
         
        $category->save();
        //unset($category);
        return $category->getId();
    }
    
    public function getCategoryIdByName($name){
        $parentId = $catId = null;
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->setStoreId('0')
            ->addAttributeToSelect('name'); //->addAttributeToSelect('is_active');
        foreach ($collection as $cat) {
            if ($cat->getName() == $name) {
                $catId = $cat->getId();
                break;
            }
        }
        return $catId;
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
$imp = new Category_Export();
$imp->load();
echo date("\nY-d-m H:i:s")." - catalog price rules import completed\n";
