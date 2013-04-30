<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++ MOVE FOLDER MEDIA!!! ++++++++++++++++++++++++++++++++++//
// watch for process:   select count(*) from catalog_product_website
ini_set("memory_limit","-1");
require_once "/home/www/demo/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Product_Import {
    
    public $file;
    public $typeId;
    public $objects;
    public $conn;
    public $attrsWithOpts;
    public $allCatsPath;
    
    public function __construct(){
        $this->conn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->file = Mage::getBaseDir().'/shell/_/updates/prodsArr.ser';
        echo $this->file;
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $this->attrsWithOpts = $this->getAttributesWithOptions();
        
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->objects = unserialize($content);
        $this->allCatsPath = $this->allCatsPath();
        echo "File loaded \n";
    }
    
    public function load(){
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        $count = sizeof($this->objects); $i=0;
        //echo $count;
        foreach($this->objects as $object){
            //mage::d($object); 
            $this->addProduct($object);
            ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
    }
    public function getCategoryNamePath($catId){
        $category = Mage::getModel('catalog/category')->setStoreId(0)->load($catId); 
        $pathArr = explode("/", $category->getPath() );
        //mage::D($pathArr);
        foreach($pathArr as &$node){
            $node = Mage::getModel('catalog/category')->load($node)->getName(); 
        }unset($node);
        //mage::D($pathArr);
        $path = implode("/", $pathArr );
        //mage::D($path);
        return $path;
    }
    public function getCategoryIdByPath($namePath){
        foreach($this->allCatsPath as $key => $val){
            if($namePath === $val){
                return $key;
            }
        }
        return null;
    }
    
    #array( catId => 'Path/To/Category/In/Names' );
    public function allCatsPath(){
        $ids = Mage::getModel('catalog/category')->getCollection()->getAllIds();
        $arr = null;
        foreach($ids as $key => $id){
            $arr[$id] = $this->getCategoryNamePath($id);
        }
        return $arr;
    }
    public function addProduct($data){
        if ( Mage::getModel('catalog/product')->getIdBySku($data['sku']) ) { return; }
        $connection = Mage::getModel('core/resource')->getConnection('core_read');
        $product = new Mage_Catalog_Model_Product();
        
        $product->setAttributeSetId( Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFieldToFilter('attribute_set_name', $data['attribute_set_id'])->getFirstItem()->getAttributeSetId() );
        unset($data['attribute_set_id']);
        
        $product->setWebsiteIDs(array(1)); //// assign product to the default website $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
        
        $wrongPath = false;
        foreach( $data['categories'] as &$cat){
            $cat = $this->getCategoryIdByPath($cat);
            if( !$cat ) { $wrongPath = true; break; }
        }unset($cat);
        //mage::d( $data['categories'] ); return;
        if( !$wrongPath ){
            $product->setCategoryIds($data['categories']);
        }
        
        
        foreach($data as $key => $value){
            switch($key){
                case 'categories' :
                case 'is_in_stock' :
                case 'qty' :
                case 'media_gallery' :
                case 'related' :
                case 'upsell' :
                case 'crosssell' :
                case 'options' :
                    break;
                case 'visibility' :
                    switch($value){
                        case 'Not Visible Individually' : $val = 1; break;
                        case 'Catalog' : $val = 2; break;
                        case 'Search' : $val = 3; break;
                        case 'Catalog, Search' : $val = 4; break;
                        default: $val = ''; }
                    $product->setData($key, $val);
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
        $product->setStockData(array(
            'is_in_stock' => $data['is_in_stock'],
            'qty' => $data['qty'])
        );
        $product->setIsMassupdate(false);
        $product->setExcludeUrlRewrite(true);
        try {
            $product->save(); //Mage::getResourceSingleton('catalog/product_indexer_price')->reindexProductIds(array($productId)); 
            
            # ----------- set media gallery ---------------- #
                        // TODO: move folder Media need fix.
            $product = Mage::getModel('catalog/product')->load( $product->getId() );
            $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
                foreach( $data['media_gallery']['images'] as $img ){
                    $mediaGalleryData['images'][] = array(
                        'file'     => $img['file'],
                        'position' => $img['position'],
                        'label'    => $img['label'],
                        'disabled' => $img['disabled'],
                    );
                    $product->setData('media_gallery', $mediaGalleryData);
                    /*if (!is_null($mediaAttribute)) {
                        Mage::('catalog/product_attribute_backend_media')->setMediaAttribute($product, $mediaAttribute, $fileName);
                    }*/
                }
            $product->save();

            # ----------- set custom options ---------------- #
                        $options = array();
                        $sku = $data['sku'];
                        $i=0;
                        foreach ($data['options'] as $opt){
                            $options[$i][$sku] = array(
                                'title' => $opt['describe']['title'],
                                'type' => $opt['describe']['type'],
                                'is_require' => $opt['describe']['is_require'],
                                'sort_order' => $opt['describe']['sort_order'],
                                'values' => array()
                            );
                            if( !empty($opt['values']) ){
                                foreach ($opt['values'] as $val){
                                    $options[$i][$sku]['values'][] = array(
                                        'title' => $val['title'],
                                        'price' => $val['price'],
                                        'price_type' => ( empty($val['price_type']) ) ? 'fixed'  : $val['price_type'] ,
                                        'sku' => $val['sku'],
                                        'sort_order' => $val['sort_order']
                                    );
                                }
                            }
                            ++$i;
                        }
                        foreach($options as $option) {
                            foreach($option as $sku => $opt1) {
                                $product = Mage::getModel('catalog/product')->load( $product->getId() );
                                if(!$product->getOptionsReadonly()) {
                                    $product->setProductOptions(array($opt1));
                                    $product->setCanSaveCustomOptions(true);
                                    try{ $product->save(); }catch(Exception $e){ echo $e->getMessage(); }
                                }
                                Mage::getSingleton('catalog/product_option')->unsetOptions();
                            }
                        }
            # ----------- set upsell, crossel, related products not possible, first need load all products :-) ---------------- #

        }catch (Exception $ex) {
            mage::D($ex); 
            #mage::log($ex->getMessage(), null, 'prodsimp.log'); 
        }
    }
    
    //$code string, $value string
    public function optByCode($code, $value){
        return Mage::getSingleton('eav/config')->getAttribute('catalog_product',  $code )->getSource()->getOptionId($value);
    }
    
    public function getAttributesWithOptions(){
        $type_id = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type_id)->load()->getItems();
        $collection = array();
        foreach($attributesCollection as $attr){
            $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attr->getAttributeCode());
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
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++ MOVE FOLDER MEDIA!!! ++++++++++++++++++++++++++++++++++//
    //#passthru("mv /home/www/demo/media /home/www/demo/media00");
die;
    echo date("\nY-d-m H:i:s")." - import start\n";
    $imp = new Product_Import();
    //$imp->_beforeImport();
    $imp->load();
    //$imp->allCatsPath();
    //$imp->_afterImport();
    echo date("\nY-d-m H:i:s")." - import completed\n";