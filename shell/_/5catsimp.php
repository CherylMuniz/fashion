<?php
# +++++++++++++++++ Import categories ++++++++++++++++++++++++

ini_set("memory_limit","-1");
require_once "../app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Category_Export{
    
    public $file = '../var/import/categories.ser';
    public $objects;
    public $entityTypeId; 
    public $attributeSetId; 
    
    public function __construct(){
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        echo "File loaded \n";
        
        $this->entityTypeId = Mage::getModel('eav/entity_type')->loadByCode('catalog_category')->getEntityTypeId();
        $this->attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($this->entityTypeId, 'entity_type_id')->getAttributeSetId();
    }
    
    public function load(){
        Mage::getModel('catalog/category')->getCollection()->delete();
        
        $connection = Mage::getModel('core/resource')->getConnection('core_write');
        $connection->query('ALTER TABLE `catalog_category_entity` AUTO_INCREMENT=1'); 
        
        //$count = sizeof($this->objects); $i=0;
        foreach($this->objects as $object){
            $categoryId = $this->createCategory($object);
            $this->assignProduct($categoryId, $object);
            //++$i; echo ' ' . $i*100/$count . '% completed. ';
        }
        $this->setPaths();
    }
    
    public function createCategory($object){
        $collection = Mage::getModel('catalog/category')->getCollection()->clear();
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
        $category->setParentId(0);
        
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
        if ( !empty($object['path']) ) $category->setPath($object['path']); 
        if ( !empty($object['path_in_store']) ) $category->setPathInStore($object['path_in_store']); 
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
    
    
    public function setPaths(){
        $collection = Mage::getModel('catalog/category')->getCollection(); 
        foreach($collection as $category){
            $category->load();
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
}
echo date("\nY-d-m H:i:s")." - catalog category import start\n";
$imp = new Category_Export();
$imp->load();
echo date("\nY-d-m H:i:s")." - catalog category import completed\n";



