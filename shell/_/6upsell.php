<?php
ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Product_Upsell {
    
    public $file;
    public $typeId;
    public $objects;
    public $attrsWithOpts;
    
    public function __construct(){
        $this->file = Mage::getBaseDir().'/var/import/productsall.ser';
        
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        echo "File loaded \n";
    }
    
    public function load(){
        $count = sizeof($this->objects); $i=0;
        foreach($this->objects as $object){
            //mage::d($object); //die;
            $this->upsell($object);
            //++$i; echo ' ' . floor($i*100/$count) . '%';
        }
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
    public function upsell($data){
        if( empty($data['upsell']) ){ return; }
        $productId = Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $data['sku'])->getId();
        $product = Mage::getModel('catalog/product')->load($productId);
        
        $param = array(); 
        $upsell = $data['upsell'];
        foreach($upsell as $key => $value){
            $id = Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $key)->getId();
            $upsell[$id] = $value;
            unset($upsell[$key]);
        }
        $product->setUpSellLinkData($upsell);
        try{ $product->save(); }catch(Exception $e) { mage::D($e); }
    }

}
    echo date("\nY-d-m H:i:s")." - import start\n";
    $imp = new Product_Upsell();
    $imp->_beforeImport();
    $imp->load();
    $imp->_afterImport();
    echo date("\nY-d-m H:i:s")." - import completed\n";
    
    
    
    /*
    public function loadProductLinks(){
        $this->query("
            DROP TABLE IF EXISTS `oberig_catalog_product_link_mapping`;
            CREATE TABLE `oberig_catalog_product_link_mapping` (
                 `link_type_id` INT(11) NOT NULL,
                 `product_sku` VARCHAR(255) DEFAULT NULL,
                 `linked_product_sku` VARCHAR(255) DEFAULT NULL
            );

            INSERT INTO `oberig_catalog_product_link_mapping` 
            SELECT cpl.link_type_id, cpe.sku, cpe2.sku FROM fashione_magento3.catalog_product_link cpl
            INNER JOIN fashione_magento3.catalog_product_entity cpe
                ON cpe.entity_id = cpl.product_id
            INNER JOIN fashione_magento3.catalog_product_entity cpe2
                ON cpe2.entity_id = cpl.linked_product_id
        ");
    }
    public function productLinksMapping(){
        $this->query("
        INSERT INTO `catalog_product_link` (
            product_id,
            linked_product_id,
            link_type_id
        ) SELECT cpe.entity_id, cpe2.entity_id, ocplm.link_type_id FROM `oberig_catalog_product_link_mapping` ocplm 
        INNER JOIN `catalog_product_entity` cpe
            ON cpe.sku = ocplm.product_sku
        INNER JOIN `catalog_product_entity` cpe2
            ON cpe2.sku = ocplm.linked_product_sku
        ");
    }
    */
//select *,count(*) from catalog_product_link group by link_type_id;

/*
 * 
 * info: Mage_Catalog_Block_Product_List_Upsell (_prepareData)
 * bugs: FIX Mage_Catalog_Block_Product_List_Upsell->_prepareData() comment Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_itemCollection);
 * or set visibility=4 for fashion database - update catalog_category_product_index set visibility=4; 
 * OR FIX Mage_Catalog_Block_Product_List_Upsell->_prepareData() comment Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_itemCollection);
*/