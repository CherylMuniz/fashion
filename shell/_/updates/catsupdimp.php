<?php
# +++++++++++++++++ Import categories ++++++++++++++++++++++++

ini_set("memory_limit","-1");
require_once "/home/www/demo/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Category_Export{
    
    public $file = '/home/www/demo/shell/_/updates/categories.ser';
    public $objects;
    public $entityTypeId; 
    public $attributeSetId; 
    public $allCatsPath; 
    
    public function __construct(){
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        echo "File loaded \n";
        
        $this->entityTypeId = Mage::getModel('eav/entity_type')->loadByCode('catalog_category')->getEntityTypeId();
        $this->attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($this->entityTypeId, 'entity_type_id')->getAttributeSetId();
        $this->allCatsPath = $this->allCatsPath();
    }
    
    public function load(){
        $connection = Mage::getModel('core/resource')->getConnection('core_write');
        
        $count = sizeof($this->objects); $i=$j=0; 
        echo "Categries count: {$count}\n";
        foreach($this->objects as $object){
            var_dump($object->getData('fullpath') );
            if( $object->getData('fullpath') && $object->getData('fullpath') !== 'Root Catalog' && !(int)$this->getCategoryIdByPath($object->getData('fullpath')) ){
                $categoryId = $this->createCategory($object);
                echo "created category - {$categoryId}: {$object->getData('fullpath')}\n";
            }
            
            $catId = $this->getCategoryIdByPath($object['fullpath']);
            if( empty($catId) ){
                die("Not exists: {$object['fullpath']}\n");
            }
            /*
            $category = Mage::getModel('catalog/category')->load($catId);
            $catProdIds = $category->getProductCollection()->getAllIds();
            
            $assignProds = array();
            foreach($object['products'] as $sku){
                if(empty($sku)){ continue;}
                $prodId = Mage::getModel('catalog/product')->getIdBySku($sku); if(empty($prodId)){ continue; die("\nProduct {$sku} not exists!"); }
                if( !in_array($prodId, $catProdIds) ){
                    $product = Mage::getModel('catalog/product')->load($prodId);
                    $cats = $product->getCategoryIds();
                    array_push($cats, (string)$catId);
                    $product->setCategoryIds($cats);
                    try{ $product->save(); } catch (Exception $e) { echo $e->getMessage(); }
                    //$assignProds[$prodId] = 1;
                    ++$j;
                }
            }
            //#$category->setPostedProducts($assignProds);
            //#try{ $category->save(); } catch (Exception $e) { echo $e->getMessage(); }
            ++$i; echo ' ' . floor($i*100/$count) .'% ';
            //#$this->setPath($categoryId);
            */
            
        }
        //echo "\nAssigned {$j} products";
    }
    
    public function synchronizeProdsInCats(){
        $connection = Mage::getModel('core/resource')->getConnection('core_write');
        foreach($this->objects as $object){
            $catId = $this->getCategoryIdByPath($object['fullpath']);
            if( empty($catId) ){
                die("Not exists: {$object['fullpath']}");
            }
            $category = Mage::getModel('catalog/category')->load($catId);
            //mage::ms($category->getProductCollection());
            $ids = $category->getProductCollection()->getAllIds();
            $skus = null;
            //mage::ms(Mage::getModel('catalog/product'));
            //die;
            foreach($ids as $prodId){
                $arr = Mage::getModel('catalog/product')->getResource()->getProductsSku($prodId);
                $skus[$arr[0]['entity_id']] = $arr[0]['sku'];
                //$skus[] = Mage::getModel('catalog/product')->load($prodId)->getSku();
            }
            //var_dump($skus);
            
            $diff = array_diff( $skus, $object['products'] );
            $diff2 = array_diff( $object['products'], $skus ); // TODO: if sku: cd3197-8 and CD3197-8 - that is different for array_diff() but the same for mysql!
            if(!empty($diff2)){
                foreach($diff2 as $sku2){
                    $product_id = Mage::getModel('catalog/product')->getIdBySku($sku2);
                    $query = "insert into catalog_category_product (category_id,product_id,position) VALUES ({$catId},{$product_id},1)";
                    echo $query."\n"; continue;
                    try{
                        $connection->exec($query);
                    }catch(Exception $e){
                        echo $query."\n";
                        mage::D($sku2);
                        echo $e->getMessage()."\n";
                    }
                }
                //die;
            }
            
            $str = null;
            if(!empty($diff)){
                $str = implode(',', array_flip($diff));
            }
            if(!empty($str)){
                $query = "delete from catalog_category_product where product_id IN ({$str}) and category_id={$catId}";
                echo $query."\n"; continue;
                try{
                    $connection->exec($query);
                }catch(Exception $e){
                    var_dump($e->getMessage());
                }
            }
            //mage::d($object['products'] );
            //die;
            //$catProdIds = $category->getProductCollection()->getAllIds();
        }
    }

    public function createCategory($object){
        //mage::d( $this->path2Int($object['path']) );  return;
        $category = new Mage_Catalog_Model_Category();
        
        $category->setEntityTypeId( $this->entityTypeId );
        $category->setAttributeSetId( $this->attributeSetId );
        if ( !empty($object['custom_apply_to_products']) ) $category->setCustomApplyToProducts( $object['custom_apply_to_products'] ); 
        if ( !empty($object['custom_design']) ) $category->setCustomDesign( $object['custom_design'] ); 
        if ( !empty($object['custom_design_from']) ) $category->setCustomDesignFrom( $object['custom_design_from'] ); 
        if ( !empty($object['custom_design_to']) ) $category->setCustomDesignTo( $object['custom_design_to'] ); 
        if ( !empty($object['custom_layout_update']) ) $category->setCustomLayoutUpdate( $object['custom_layout_update'] ); 
        if ( !empty($object['custom_use_parent_settings']) ) $category->setCustomUseParentSettings( $object['custom_use_parent_settings'] ); 
        
        $category->setAvailablesortBy($object['available_sort_by']);
        $category->setParentId( $this->getCategoryIdByPath($object['path']) );
        
        $category->setCreatedAt($object['created_at']);
        $category->setUpdatedAt($object['updated_at']);
        
        $category->setPosition( (int)$object['position'] );
        $category->setChildrenCount($object['children_count']);
        $category->setDescription($object['description']);
        $category->setDisplayMode($object['display_mode']);
        $category->setDefaultSortBy($object['default_sort_by']);
        
        $category->setFilterPriceRange($object['filter_price_range']);
        $category->setImage($object['image']);
        $category->setIncludeInMenu($object['include_in_menu']);
        $category->setIsActive($object['is_active']);
        $category->setIsAnchor($object['is_anchor']);
        $category->setLandingPage($object['landing_page']);
        $category->setLevel($object['level']);
        
        $category->setMetaTitle($object['meta_title']);
        $category->setMetaKeywords($object['meta_keywords']);
        $category->setMetaDescription($object['meta_description']);
        $category->setName($object['name']);
        $category->setPageLayout( $object['page_layout'] ); 
        if ( !empty($object['path']) ) $category->setPath( $this->path2Int($object['path']) ); 
        //if ( !empty($object['path_in_store']) ) $category->setPathInStore($object['path_in_store']); 
        $category->setPosition( (int)$object['position'] ); 
        $category->setThumbnail( (int)$object['thumbnail'] ); 
        $category->setUrlKey($object['url_key']);
        $category->setUrlPath($object['url_path']);
        
        try {
             $category->save();
             
             $category->load(); // must do it, because of position set wrong for first saving. magento bug? 
             $category->setPosition( (int)$object['position'] );
             $category->save();
        } catch (Exception $e) {
                zend_debug::dump($e);
            return;
        }
        return $category->getId();
    }
    
    
    public function setPath($catId){
        $category = Mage::getModel('catalog/category')->setStoreId(0)->load($catId); 
            $pathArr = explode("/", $category->getPath() );
            foreach($pathArr as &$node){
                if( end($pathArr) == $node ) { break; }
                $node = $this->getCategoryIdByName($node);
            }unset($node);
            $path = implode("/", $pathArr );
            $category->setPath($path);
            try {
                 $category->save();
            } catch (Exception $e) {
                    zend_debug::dump($e);
                return;
            }
    }
    public function getCategoryIdByPath($namePath){
        //mage::D($this->allCatsPath); die;
        foreach($this->allCatsPath as $key => $val){
            if($namePath === $val){
                return $key;
            }
        }
        return $namePath;
    }
    public function path2Int($strPath){
        static $intPath = array(); static $i=0; //static $x = null;
        if(empty($strPath)) { 
            $ret = $intPath; $intPath = array(); $i=0; 
            return implode('/',array_reverse($ret)); 
        }else{
            $intPath[$i++] = $this->getCategoryIdByPath($strPath); 
            //mage::D($intPath);
            $arrPath = explode("/", $strPath );
            array_pop($arrPath);
            //mage::D($arrPath);
            $strPath = implode('/', $arrPath);
            //mage::D($strPath);
            return $this->path2Int($strPath);
        }
    }
    public function allCatsPath(){
        $ids = Mage::getModel('catalog/category')->getCollection()->getAllIds();
        $arr = null;
        foreach($ids as $key => $id){
            $arr[$id] = $this->getCategoryNamePath($id);
        }
        return $arr;
    }
    public function getCategoryNamePath($catId){
        $category = Mage::getModel('catalog/category')->setStoreId(0)->load($catId); 
        $pathArr = explode("/", $category->getPath() );
        foreach($pathArr as &$node){
            $node = Mage::getModel('catalog/category')->load($node)->getName(); 
        }unset($node);
        $path = implode("/", $pathArr );
        return $path;
    }
    public function getCategoryIdByName($name){
        $parentId = $catId = null;
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->setStoreId('0')
            ->addAttributeToSelect('name'); 
        foreach ($collection as $cat) {
            if ($cat->getName() == $name) {
                $catId = $cat->getId();
                break;
            }
        }
        return $catId;
    }
    
    
    // create table and fill category_id and name-path 
    public function catalog_category_product_synch(){
        $connection = Mage::getModel('core/resource')->getConnection('core_write');
        $connection->exec("DROP TABLE catalog_category_product_synch");
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `catalog_category_product_synch` (
              `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Category ID demo site',
              `value` varchar(255) DEFAULT NULL COMMENT 'Value',
              `live_category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Category ID live site',
              UNIQUE KEY (`category_id`,`value`),
              UNIQUE KEY (`value`,`live_category_id`),
              UNIQUE KEY `UNQ_CAT_ID_VAL_LIVE_CAT_ID` (`category_id`,`value`,`live_category_id`),
              KEY `IDX_CATALOG_CATEGORY_PRODUCT_SYNCH_CATEGORY_ID` (`category_id`),
              KEY `IDX_CATALOG_CATEGORY_PRODUCT_SYNCH_VALUE` (`value`),
              KEY `IDX_CATALOG_CATEGORY_PRODUCT_SYNCH_LIVE_CATEGORY_ID` (`live_category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='For synchronize catalog_category_product'
        ");
        foreach($this->allCatsPath as $k => $v){
            $query = "INSERT INTO catalog_category_product_synch (category_id, value) VALUES ({$connection->quote($k)},{$connection->quote($v)})";
            try{
                $connection->exec($query);
            }catch(Exception $e){
                echo $query."\n";
                echo $e->getMessage();
            }
        }
    }
    
    public function assignProduct($categoryId, $object){
        $products = array();
        foreach($object['products'] as $sku){
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
            if( empty($product) || !$product->getId() ) { continue; }
            
            $products[$product->getId()] = 1;
            //Mage::getModel('Mage_Catalog_Model_Category_Api')->assignProduct($categoryId, $product->getId());
        }
        $category = Mage::getModel('catalog/category')->load($categoryId);
        $category->setPostedProducts($products);
        try{ $category->save(); } catch (Exception $e) { echo $e->getMessage(); }

        echo "\n{$object['name']} assigned\n";
    }
    
    public function _beforeImport(){
        //disable indexes
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_MANUAL));
        $processes->walk('save');
    }
    public function _afterImport(){
        //enable indexes
        $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_REAL_TIME));
        $processes->walk('save'); 
        
        //echo date("\nY-d-m H:i:s")." - reindex start\n";
        //passthru("php indexer.php reindexall");
        //echo date("\nY-d-m H:i:s")." - reindex finish\n";
    }
}
echo date("\nY-d-m H:i:s")." - catalog category import start\n";
$imp = new Category_Export();
//$imp->_beforeImport();
//$imp->synchronizeProdsInCats();
//$imp->catalog_category_product_synch();
$imp->load();
//$imp->_afterImport();
echo date("\nY-d-m H:i:s")." - catalog category import completed\n";


/*
-- need filled catalog_category_product_synch --
create table catalog_category_product_fm3 select * from catalog_category_product;  delete from catalog_category_product_fm3;
insert into catalog_category_product_fm3 select * from fashione_magento3.catalog_category_product;

update catalog_category_product_fm3 p
join fashione_magento3.catalog_product_entity e1 on e1.entity_id = p.product_id
join catalog_product_entity e on e1.sku = e.sku
set product_id = e.entity_id;

update catalog_category_product_fm3 p
join catalog_category_product_synch s on p.category_id = s.live_category_id
set p.category_id = s.category_id;

create table catalog_category_product2 like catalog_category_product;  insert into catalog_category_product2 select * from catalog_category_product;
delete from catalog_category_product;

insert into catalog_category_product select * from catalog_category_product_fm3;
-- end --

*/