<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++ MOVE FOLDER MEDIA!!! ++++++++++++++++++++++++++++++++++//
// watch for process:   select count(*) from catalog_product_website
ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Customer_Import {
    
    public $file;
    public $typeId;
    public $objects;
    public $conn;
    public $attrsWithOpts;
    const FILENAME = 'customers.ser';
    const CUSTOMER_CODE = 'customer';
    const CUSTOMER_ADDRESS_CODE = 'customer_address';
    
    public function __construct(){
        $this->conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->file = Mage::getBaseDir().'/var/import/'.self::FILENAME;
        $this->typeId = Mage::getModel('eav/entity')->setType(self::CUSTOMER_CODE)->getTypeId();
        $this->attrsWithOpts = $this->getAttributesWithOptions(self::CUSTOMER_CODE);
        
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        //mage::D($this->objects); die;
        echo "File loaded \n";
    }
    
    public function load(){
        #Mage::getModel('customer/customer')->getCollection()->delete();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->query('ALTER TABLE `customer_entity` AUTO_INCREMENT=1'); 
        
        $count = sizeof($this->objects); $i=0;
        foreach($this->objects as $object){
            mage::d($object); 
            die;
            //$this->addCustomer($object);
            ++$i; echo floor($i*100/$count) . '% ';
        }
    }
    
    public function addCustomer($data){
        $connection = Mage::getModel('core/resource')->getConnection('core_read');
        $product = new Mage_Catalog_Model_Product();
        
        $product->setAttributeSetId( Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFieldToFilter('attribute_set_name', $data['attribute_set_id'])->getFirstItem()->getAttributeSetId() );
        unset($data['attribute_set_id']);
        
        $product->setWebsiteIDs(array(1)); //// assign product to the default website $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
        $product->setStoreIDs(array(1)); 
        foreach($data as $key => $value){
            switch($key){
                case 'store_id' :
                case 'website_id' :
                    break;
                case 'status' :
                    switch($value){
                        case 'Enabled' : $val = 1; break;
                        case 'Disabled' : $val = 2; break;
                        default: $val = ''; }
                    $product->setData($key, $val);
                    break;
                default :
                    ( in_array($key, $this->attrsWithOpts) ) ? $product->setData($key, $this->optByCode($key, $value)) : $product->setData($key, $value);
            }
            
        } //unset($value);

        $product->setIsMassupdate(false);
        $product->setExcludeUrlRewrite(true);
        try {
            $product->save(); //Mage::getResourceSingleton('catalog/product_indexer_price')->reindexProductIds(array($productId)); 
            
        }catch (Exception $ex) {
            mage::D($ex); 
            #mage::log($ex->getMessage(), null, 'prodsimp.log'); 
        }
    }
    
    //$code string, $value string
    public function optByCode($code, $value){
        return Mage::getSingleton('eav/config')->getAttribute(self::CUSTOMER_CODE,  $code )->getSource()->getOptionId($value);
    }
    
    public function getAttributesWithOptions($code){
        $type_id = Mage::getModel('eav/entity')->setType($code)->getTypeId();
        $attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type_id)->load()->getItems();
        $collection = array();
        foreach($attributesCollection as $attr){
            $attribute = Mage::getModel('eav/config')->getAttribute($code, $attr->getAttributeCode());
            try { $attribute->setData( 'options', $attribute->getSource()->getAllOptions(false) ); }catch(Exception $e){}

            //#if($attribute->getAttributeCode() == 'frame_shape'){ mage::D($attribute); }
            if( $attribute->getData('options') /* in_array($attribute->getFrontendInput(), array('select', 'multiselect')) */ ){
                $collection[] = $attribute->getAttributeCode();
            }
        } 
        return $collection;
    }
    public function _beforeImport(){
        //disable indexes
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_MANUAL));
        $processes->walk('save');
        
        //disable cahce
        $model = Mage::getModel('core/cache');
        $options = $model->canUse();
        foreach($options as $option=>$value) {
            $options[$option] = 0;
        }
        $model->saveOptions($options);
    }
    public function _afterImport(){
        //enable indexes
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_REAL_TIME));
        $processes->walk('save'); 
        
        echo date("\nY-d-m H:i:s")." - reindex start\n";
        passthru("php indexer.php reindexall");
        echo date("\nY-d-m H:i:s")." - reindex finish\n";
        
        //enable cache
        /*$model = Mage::getModel('core/cache');
        $options = $model->canUse();
        foreach($options as $option=>$value) {
            $options[$option] = 1;
        }
        $model->saveOptions($options);*/
    }
}
    echo date("\nY-d-m H:i:s")." - import start\n";
    $imp = new Customer_Import();
    //$imp->_beforeImport();
    $imp->load();
    //$imp->_afterImport();
    echo date("\nY-d-m H:i:s")." - import completed\n";