<?php
                                    # +++++++++++++++++ Synch categories products ++++++++++++++++++++++++
ini_set("memory_limit","-1");
require_once "/home/www/production/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class SynchCats{
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
    
    public function catalog_category_product_synch(){
        $connection = Mage::getModel('core/resource')->getConnection('core_write');
        $arr = $this->allCatsPath();
        foreach( $arr as $k => $v){
            $query = "UPDATE fashion.catalog_category_product_synch SET live_category_id={$connection->quote($k)} WHERE value={$connection->quote($v)}";
            try{
                $connection->exec($query);
            }catch(Exception $e){
                echo $query."\n";
                echo $e->getMessage();
            }
        }
    }
}
$s = new SynchCats();
$s->catalog_category_product_synch();