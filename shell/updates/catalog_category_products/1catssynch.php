<?php
# +++++++++++++++++ Import categories ++++++++++++++++++++++++

ini_set("memory_limit","-1");
require_once "/home/www/demo/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Category_Export{

    public $allCatsPath; 
    public $connection; 
    
    
    public function __construct(){
        $this->connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $this->allCatsPath = $this->allCatsPath();
        
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
    
    
    // create table and fill category_id and name-path 
    public function catalog_category_product_synch(){
        $connection = $this->connection;
        $connection->exec("DROP TABLE IF EXISTS catalog_category_product_synch");
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `catalog_category_product_synch` (
              `category_id` int(10) unsigned DEFAULT NULL COMMENT 'Category ID demo site',
              `value` varchar(255) DEFAULT NULL COMMENT 'Value',
              `live_category_id` int(10) unsigned DEFAULT NULL COMMENT 'Category ID live site',
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
    
    // !!!!!!after start catssynch2!!!!
    /*public function catalog_category_product(){
        $connection = $this->connection;
        $query = "create table catalog_category_product_fm3 select * from catalog_category_product;  delete from catalog_category_product_fm3;
                  insert into catalog_category_product_fm3 select * from fashione_magento3.catalog_category_product;";
        $this->exec($query);
        $query = "
            update catalog_category_product_fm3 p
            join fashione_magento3.catalog_product_entity e1 on e1.entity_id = p.product_id
            join catalog_product_entity e on e1.sku = e.sku
            set product_id = e.entity_id;

            update catalog_category_product_fm3 p
            join catalog_category_product_synch s on p.category_id = s.live_category_id
            set p.category_id = s.category_id;
        ";
        $this->exec($query);
        $query = "
            drop table if exists catalog_category_product2;
            create table catalog_category_product2 like catalog_category_product;
            insert into catalog_category_product2 select * from catalog_category_product;
        ";
        $this->exec($query);
        $query = "
            delete from catalog_category_product;
            insert into catalog_category_product select * from catalog_category_product_fm3;
        ";
        $this->exec($query);
    }*/
    public function exec($query){
        $connection = $this->connection;
        try{
            $connection->exec($query);
        }catch(Exception $e){
            echo $query."\n";
            echo $e->getMessage();
        }
    }
}
echo date("\nY-d-m H:i:s")." - catalog category import start\n";
$imp = new Category_Export();
$imp->catalog_category_product_synch();
echo date("\nY-d-m H:i:s")." - catalog category import completed\n";