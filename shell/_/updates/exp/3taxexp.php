<?
ini_set("memory_limit","4000M");
require_once '/home/www/production/app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
class Tax {
    public $json = null;
    public $filename = 'tax.ser';
    public function export(){
        $data = array();
        $items = Mage::getModel('tax/class')->getCollection()->load()->getItems();
        foreach($items as $item){
            $tmp = $item->getData(); unset($tmp['class_id']);
            $data[] = $tmp;
        }
        $this->json['tax_class'] = $data;
        
        $data = array();
        $items = Mage::getModel('tax/calculation')->getCollection()->load()->getItems();
        foreach($items as $item){
            $tmp = $item->getData(); unset($tmp['tax_calculation_id']);
            //mage::D(Mage::getModel('tax/calculation_rule')->getCollection()->addFieldToFilter('tax_calculation_rule_id', $tmp['tax_calculation_rule_id'])->getFirstItem()->getCode() ); die;
            $tmp['tax_calculation_rule_id'] =  Mage::getModel('tax/calculation_rule')->getCollection()->addFieldToFilter('tax_calculation_rule_id', $tmp['tax_calculation_rule_id'])->getFirstItem()->getCode();
            $tmp['tax_calculation_rate_id'] =  Mage::getModel('tax/calculation_rate')->getCollection()->addFieldToFilter('tax_calculation_rate_id', $tmp['tax_calculation_rate_id'])->getFirstItem()->getCode();
            $tmp['customer_tax_class_id'] =  Mage::getModel('tax/class')->getCollection()->addFieldToFilter('class_type', 'CUSTOMER')->addFieldToFilter('class_id', $tmp['customer_tax_class_id'])->getFirstItem()->getClassName();
            $tmp['product_tax_class_id'] =  Mage::getModel('tax/class')->getCollection()->addFieldToFilter('class_type', 'PRODUCT')->addFieldToFilter('class_id', $tmp['product_tax_class_id'])->getFirstItem()->getClassName();
            $data[] = $tmp;
        }
        $this->json['tax_calculation'] = $data;
        
        $data = array();
        $items = Mage::getModel('tax/calculation_rate')->getCollection()->load()->getItems();
        foreach($items as $item){
            $tmp = $item->getData(); unset($tmp['tax_calculation_rate_id']);
            $data[] = $tmp;
        }
        $this->json['tax_calculation_rate'] = $data;
        
        $data = array();
        $items = Mage::getModel('tax/calculation_rate_title')->getCollection()->load()->getItems();
        foreach($items as $item){
            $tmp = $item->getData(); unset($tmp['tax_calculation_rate_title_id']);
            $data[] = $tmp;
        }
        $this->json['tax_calculation_rate_title'] = $data;
        
        $data = array();
        $items = Mage::getModel('tax/calculation_rule')->getCollection()->load()->getItems();
        foreach($items as $item){
            $tmp = $item->getData(); unset($tmp['tax_calculation_rule_id']);
            $data[] = $tmp;
        }
        $this->json['tax_calculation_rule'] = $data;
        //mage::d($this->json);
        $this->toFile();
        
    }
    public function toFile(){
        echo date("\nY-d-m H:i:s\n")."Start write to file\n";
        $content = serialize($this->json);
        $fo = fopen("tax.ser", "w+");
        fputs($fo, $content);
        fclose($fo);
        echo date("\nY-d-m H:i:s\n")."Wrotten!\n";
        passthru("tar zc tax.ser > tax.ser.tgz");
        //passthru("tar zc {$this->filename} > {$this->filename}.tgz");
    }
}

$c = new Tax();
$c->export();