<?php
ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
    
class Tax_Import {
    public $file;
    public $objects;
    public $conn;
    
    public function __construct(){
        $this->conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->file = Mage::getBaseDir().'/var/import/tax.ser';
        
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        //mage::D($this->objects); die;
        echo "File loaded \n";
    }
    
    public function tax_class(){
        $items = Mage::getModel('tax/class')->getCollection()->getItems();
        foreach($items as $item){ $item->delete(); } 
        $this->conn->query('ALTER TABLE `tax_class` AUTO_INCREMENT=1');
        foreach($this->objects['tax_class'] as $item){
            $model = new Mage_Tax_Model_Class();
            foreach( $item as $key => $value){
                $model->setData($key,$value);
            }
            try{
                $model->save();
            }catch( Exception $e ){
                mage::d($e->getMessage());
            }
        }
    }
    public function tax_calculation(){
        $items = Mage::getModel('tax/calculation')->getCollection()->getItems();
        foreach($items as $item){ $item->delete(); } 
        $this->conn->query('ALTER TABLE `tax_calculation` AUTO_INCREMENT=1');
        //mage::D($this->objects['tax_calculation']); die;
        foreach($this->objects['tax_calculation'] as $item){
                $item['tax_calculation_rule_id'] =  Mage::getModel('tax/calculation_rule')->getCollection()->addFieldToFilter('code', $item['tax_calculation_rule_id'])->getFirstItem()->getTaxCalculationRuleId();
                $item['tax_calculation_rate_id'] =  Mage::getModel('tax/calculation_rate')->getCollection()->addFieldToFilter('code', $item['tax_calculation_rate_id'])->getFirstItem()->getTaxCalculationRateId();
                $item['customer_tax_class_id'] =  Mage::getModel('tax/class')->getCollection()->addFieldToFilter('class_type', 'CUSTOMER')->addFieldToFilter('class_name', $item['customer_tax_class_id'])->getFirstItem()->getClassId();
                $item['product_tax_class_id'] =  Mage::getModel('tax/class')->getCollection()->addFieldToFilter('class_type', 'PRODUCT')->addFieldToFilter('class_name', $item['product_tax_class_id'])->getFirstItem()->getClassId();
                //mage::d($item); die;
            $model = new Mage_Tax_Model_Calculation();
            foreach( $item as $key => $value){
                $model->setData($key,$value);
            }
            try{
                $model->save();
            }catch( Exception $e ){
                mage::d($e->getMessage());
                //die;
            }
        }
        
    }
    
    public function tax_calculation_rate(){
        $items = Mage::getModel('tax/calculation_rate')->getCollection()->getItems();
        foreach($items as $item){ $item->delete(); }
        $this->conn->query('ALTER TABLE `tax_calculation_rate` AUTO_INCREMENT=1');
        foreach($this->objects['tax_calculation_rate'] as $item){
            $model = new Mage_Tax_Model_Calculation_Rate();
            foreach( $item as $key => $value){
                $model->setData($key,$value);
            }
            try{
                $model->save();
            }catch( Exception $e ){
                mage::d($e->getMessage());
            }
        }
    }
    
    public function tax_calculation_rule(){
        $items = Mage::getModel('tax/calculation_rule')->getCollection()->getItems();
        foreach($items as $item){ $item->delete(); } 
        $this->conn->query('ALTER TABLE `tax_calculation_rule` AUTO_INCREMENT=1');
        foreach($this->objects['tax_calculation_rule'] as $item){
            $model = new Mage_Tax_Model_Calculation_Rule();
            foreach( $item as $key => $value){
                $model->setData($key,$value);
            }
            try{
                $model->save();
            }catch( Exception $e ){
                mage::d($e->getMessage());
            }
        }
    }
    
}
$imp = new Tax_Import();
$imp->tax_class();
$imp->tax_calculation_rate();
$imp->tax_calculation_rule();
$imp->tax_calculation();
