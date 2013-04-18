<?php
ini_set("memory_limit","1000M");
//require_once "app/Mage.php";
umask(0);
//Mage::app();

class Oberig_Import {
    
    public $db;
    public $CSVfile;
    public $CSVCategoryFile;
    public $oldDB = 'fashione_staging2';
    public function __construct(){
        $this->db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->CSVfile = Mage::getBaseDir().'/var/import/export_all_products.csv';
        $this->CSVCategoryFile = Mage::getBaseDir().'/var/import/Categories.csv';
    }
    
    public function loadCsv(){
        $this->query("DROP TABLE IF EXISTS `oberig_products`");
        $this->query("CREATE TABLE IF NOT EXISTS `oberig_products` (
            `store` varchar(255) DEFAULT NULL,
            `websites` varchar(255) DEFAULT NULL,
            `attribute_set` varchar(255) DEFAULT NULL,
            `type` varchar(255) DEFAULT NULL,
            `category_ids` varchar(255) DEFAULT NULL,
            `sku` varchar(255) DEFAULT NULL,
            `has_options` varchar(255) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `meta_title` varchar(255) DEFAULT NULL,
            `meta_description` varchar(255) DEFAULT NULL,
            `image` varchar(255) DEFAULT NULL,
            `small_image` varchar(255) DEFAULT NULL,
            `thumbnail` varchar(255) DEFAULT NULL,
            `url_key` varchar(255) DEFAULT NULL,
            `url_path` varchar(255) DEFAULT NULL,
            `options_container` varchar(255) DEFAULT NULL,
            `extra_title` varchar(255) DEFAULT NULL,
            `frame_gender` varchar(255) DEFAULT NULL,
            `frame_shape` varchar(255) DEFAULT NULL,
            `price` varchar(255) DEFAULT NULL, 
            `cost` varchar(255) DEFAULT NULL,
            `weight` varchar(255) DEFAULT NULL,
            `manufacturer` varchar(255) DEFAULT NULL,
            `status` varchar(255) DEFAULT NULL,
            `tax_class_id` varchar(255) DEFAULT NULL,
            `visibility` varchar(255) DEFAULT NULL,
            `enable_googlecheckout` varchar(255) DEFAULT NULL,
            `is_imported` varchar(255) DEFAULT NULL,
            `frame_type` varchar(255) DEFAULT NULL,
            `sell_by_phone_only` varchar(255) DEFAULT NULL,
            `description` varchar(255) DEFAULT NULL,
            `short_description` varchar(255) DEFAULT NULL,
            `meta_keyword` varchar(255) DEFAULT NULL,
            `special_from_date` varchar(255) DEFAULT NULL,
            `qty` varchar(255) DEFAULT NULL,
            `min_qty` varchar(255) DEFAULT NULL,
            `use_config_min_qty` varchar(255) DEFAULT NULL,
            `is_qty_decimal` varchar(255) DEFAULT NULL,
            `backorders`varchar(255) DEFAULT NULL,
            `use_config_backorders`varchar(255) DEFAULT NULL,
            `min_sale_qty` varchar(255) DEFAULT NULL,
            `use_config_min_sale_qty` varchar(255) DEFAULT NULL,
            `max_sale_qty` varchar(255) DEFAULT NULL,
            `use_config_max_sale_qty` varchar(255) DEFAULT NULL,
            `is_in_stock` varchar(255) DEFAULT NULL,
            `low_stock_date` varchar(255) DEFAULT NULL,
            `notify_stock_qty` varchar(255) DEFAULT NULL,
            `use_config_notify_stock_qty` varchar(255) DEFAULT NULL,
            `manage_stock` varchar(255) DEFAULT NULL,
            `use_config_manage_stock` varchar(255) DEFAULT NULL,
            `stock_status_changed_automatically` varchar(255) DEFAULT NULL,
            `use_config_qty_increments` varchar(255) DEFAULT NULL,
            `qty_increments` varchar(255) DEFAULT NULL,
            `use_config_enable_qty_increments` varchar(255) DEFAULT NULL,
            `enable_qty_increments` varchar(255) DEFAULT NULL,
            `product_name` varchar(255) DEFAULT NULL,
            `store_id` varchar(255) DEFAULT NULL,
            `product_type_id` varchar(255) DEFAULT NULL,
            `product_status_changed` varchar(255) DEFAULT NULL,
            `product_changed_websites` varchar(255) DEFAULT NULL,
            `custom_design` varchar(255) DEFAULT NULL,
            `page_layout` varchar(255) DEFAULT NULL,
            `image_label` varchar(255) DEFAULT NULL,
            `small_image_label` varchar(255) DEFAULT NULL,
            `thumbnail_label` varchar(255) DEFAULT NULL,
            `gift_message_available` varchar(255) DEFAULT NULL,
            `framecolour` varchar(255) DEFAULT NULL,
            `special_price` varchar(255) DEFAULT NULL,
            `is_recurring` varchar(255) DEFAULT NULL,
            `frame_size` varchar(255) DEFAULT NULL,
            `custom_layout_update` varchar(255) DEFAULT NULL,
            `special_to_date` varchar(255) DEFAULT NULL,
            `news_from_date` varchar(255) DEFAULT NULL,
            `news_to_date` varchar(255) DEFAULT NULL,
            `custom_design_from` varchar(255) DEFAULT NULL,
            `custom_design_to` varchar(255) DEFAULT NULL,
            `stock_clearance` varchar(255) DEFAULT NULL,
            `lenstint` varchar(255) DEFAULT NULL,
            `framesizetest` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        
        try{
        $this->query("LOAD DATA INFILE '".$this->CSVfile."' 
                               INTO TABLE `oberig_products` 
                               FIELDS TERMINATED BY ',' 
                               ENCLOSED BY '\"'
                               LINES TERMINATED BY '\n' IGNORE 1 LINES 
        ");
        }catch(Exception $e){
            var_dump($e);
        }
    }
    
    public function query($query){
        $connect =  Mage::getSingleton('core/resource')->getConnection('core_write');
        echo $query."\n\n";
        return $connect->query($query);
    }
    
    public function addProduct(){
                
        echo date("\nY-d-m H:i:s\n");
        //$this->query("TRUNCATE TABLE `cataloginventory_stock_status_idx`");
    $processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
    $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_MANUAL));
    $processes->walk('save');

        $connection = Mage::getModel('core/resource')->getConnection('core_read');
        //$product = Mage::getModel('catalog/product');
        $product = new Mage_Catalog_Model_Product();

        // get data array
        $select = $connection->select()->from('oberig_products');
        $result = $connection->query($select)->fetchAll(); //(PDO::FETCH_ASSOC);  //mage::d($row); //echo $select->__toString();//die;//mage::D($result);

        //category mapping 
        $result2 = array();
             foreach($result as &$row){
                 if( empty($row['category_ids']) ) { $row['category_ids'] = array(); $result2[] = $row; continue; }
                 $cats = explode(',', $row['category_ids'] );
                 foreach($cats as &$cat){
                     $select = $connection->select()->from('oberig_category_mapping', 'new_cat_id')->where('old_cat_id=?', $cat);
                     $res = $connection->query($select)->fetch();
                     $cat = $res['new_cat_id'];
                }
                $row['category_ids'] = $cats;
                $result2[] = $row;
             }
        $result = $result2;

        foreach($result as $row){
        //mage::d($row['category_ids']);
            $product = new Mage_Catalog_Model_Product();
            // Build the product
            $product->setSku($row['sku']);
            $product->setAttributeSetId('4');# 9 is for default
            $product->setTypeId('simple');
            $product->setStatus(1);
            $product->setWebsiteIDs(array(1));
            $product->setCategoryIds($row['category_ids']); 
            $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
            $product->setPrice($row['price']);
            $product->setData('store', $row['store']);
                            //$product->setData('websites', $row['websites']);
            $product->setData('attribute_set', $row['attribute_set']);
            $product->setData('type', $row['type']);
            $product->setData('sku', $row['sku']);
            $product->setData('has_options', $row['has_options']);
            $product->setData('name', $row['name']);
            $product->setData('meta_title', $row['meta_title']);
            $product->setData('meta_description', $row['meta_description']);
            $product->setData('url_key', $row['url_key']);
            $product->setData('url_path', $row['url_path']);
            $product->setData('options_container', $row['options_container']);
            $product->setData('extra_title', $row['extra_title']);
            $product->setData('frame_gender', $row['frame_gender']);
            $product->setData('frame_shape', $row['frame_shape']);
            $product->setData('cost', $row['cost']);
            $product->setData('weight', $row['weight']);
            $product->setData('manufacturer', $row['manufacturer']);
                            //$product->setData('status', $row['status']);
            $product->setData('tax_class_id', $row['tax_class_id']);
                            //$product->setData('visibility', $row['visibility']);
            $product->setData('enable_googlecheckout', $row['enable_googlecheckout']);
            $product->setData('is_imported', $row['is_imported']);
            $product->setData('frame_type', $row['frame_type']);
                            //$product->setData('sell_by_phone_only', $row['sell_by_phone_only']);
            $product->setData('description', $row['description']);
            $product->setData('short_description', $row['short_description']);
            $product->setData('meta_keyword', $row['meta_keyword']);
            $product->setData('special_from_date', $row['special_from_date']);
            $product->setData('min_qty', $row['min_qty']);
            $product->setData('use_config_min_qty', $row['use_config_min_qty']);
            $product->setData('is_qty_decimal', $row['is_qty_decimal']);
            $product->setData('backorders', $row['backorders']);
            $product->setData('use_config_backorders', $row['use_config_backorders']);
            $product->setData('min_sale_qty', $row['min_sale_qty']);
            $product->setData('use_config_min_sale_qty', $row['use_config_min_sale_qty']);
            $product->setData('max_sale_qty', $row['max_sale_qty']);
            $product->setData('use_config_max_sale_qty', $row['use_config_max_sale_qty']);
            $product->setData('low_stock_date', $row['low_stock_date']);
            $product->setData('notify_stock_qty', $row['notify_stock_qty']);
            $product->setData('use_config_notify_stock_qty', $row['use_config_notify_stock_qty']);
            $product->setData('manage_stock', $row['manage_stock']);
            $product->setData('use_config_manage_stock', $row['use_config_manage_stock']);
            $product->setData('stock_status_changed_automatically', $row['stock_status_changed_automatically']);
            $product->setData('use_config_qty_increments', $row['use_config_qty_increments']);
            $product->setData('qty_increments', $row['qty_increments']);
            $product->setData('use_config_enable_qty_increments', $row['use_config_enable_qty_increments']);
            $product->setData('enable_qty_increments', $row['enable_qty_increments']);
            $product->setData('product_name', $row['product_name']);
            $product->setData('store_id', $row['store_id']);
            $product->setData('product_type_id', $row['product_type_id']);
            $product->setData('product_status_changed', $row['product_status_changed']);
            $product->setData('product_changed_websites', $row['product_changed_websites']);
            $product->setData('custom_design', $row['custom_design']);
            $product->setData('page_layout', $row['page_layout']);
            $product->setData('image_label', $row['image_label']);
            $product->setData('small_image_label', $row['small_image_label']);
            $product->setData('thumbnail_label', $row['thumbnail_label']);
            $product->setData('gift_message_available', $row['gift_message_available']);
            $product->setData('framecolour', $row['framecolour']);
            $product->setData('special_price', $row['special_price']);
            $product->setData('is_recurring', $row['is_recurring']);
            $product->setData('frame_size', $row['frame_size']);
            $product->setData('custom_layout_update', $row['custom_layout_update']);
            $product->setData('special_to_date', $row['special_to_date']);
            $product->setData('news_from_date', $row['news_from_date']);
            $product->setData('news_to_date', $row['news_to_date']);
            $product->setData('custom_design_from', $row['custom_design_from']);
            $product->setData('custom_design_to', $row['custom_design_to']);
            $product->setData('stock_clearance', $row['stock_clearance']);
            $product->setData('lenstint', $row['lenstint']);
            $product->setData('framesizetest', $row['framesizetest']);
            
            
            $product->setStockData(array(
                'is_in_stock' => $row['is_in_stock'],
                'qty' => $row['qty'])
            );
            $product->setIsMassupdate(false);
            try {
                $product->save();
                //Mage::getResourceSingleton('catalog/product_indexer_price')->reindexProductIds(array($productId)); 
                //Mage::register('current_product', $product);
                //$product = null;
            }
            catch (Exception $ex) {
                echo 'test\n';
                echo $ex->getMessage();
            //Handle the error
            }
        }
    $processes->walk('reindexAll');
    $processes->walk('setMode', array(Mage_Index_Model_Process::MODE_REAL_TIME));
    $processes->walk('save');
    $indexProcess = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_price');
    if ($indexProcess) {
    $indexProcess->reindexAll();
      } 

        echo date("\nY-d-m H:i:s\n");
        
    }
    
    public function loadCategories(){
        $categoryNameId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_category','name');
        $this->query("DROP TABLE IF EXISTS `oberig_categories`");
        $this->query("CREATE TABLE IF NOT EXISTS `oberig_categories` (
            `store` varchar(255) DEFAULT NULL,
            `categories` varchar(255) DEFAULT NULL,
            `cat_id` int(11),
            `is_active` int(11),
            `meta_title` varchar(255) DEFAULT NULL,
            `meta_keywords` varchar(255) DEFAULT NULL,
            `meta_description` varchar(255) DEFAULT NULL,
            `include_in_menu` int(11),
            `is_anchor` int(11),
            `description` varchar(255) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL
            )ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->query("LOAD DATA INFILE '".$this->CSVCategoryFile."' 
                               INTO TABLE `oberig_categories` 
                               FIELDS TERMINATED BY ',' 
                               ENCLOSED BY '\"'
                               LINES TERMINATED BY '\n' IGNORE 1 LINES 
        ");
        
        
        $this->query("DROP TABLE IF EXISTS `oberig_category_mapping`");
        $this->query("CREATE TABLE IF NOT EXISTS `oberig_category_mapping` (
                `old_cat_id` int(11),
                `new_cat_id` int(11),
                `name` varchar(255) DEFAULT NULL
                )ENGINE=InnoDB DEFAULT CHARSET=utf8");
                
         $this->query("
            INSERT INTO oberig_category_mapping (old_cat_id, name) SELECT cat_id, name FROM `oberig_categories`; 
         ");
         $this->query("
             UPDATE oberig_category_mapping ocm 
             INNER JOIN catalog_category_entity_varchar cev 
                ON ocm.name = cev.value
             SET ocm.new_cat_id = cev.entity_id
             WHERE cev.attribute_id={$categoryNameId} and cev.store_id=0;
         ");
    }
    
    public function importImages(){
        $imgId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','image');
        $imgSmallId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','small_image');
        $media_gallery_id = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','media_gallery');
        $old_imgId = 74;
        $old_imgSmallId = 75;
        $old_media_gallery_id = 77;
        
        $this->query("DELETE FROM catalog_product_entity_varchar WHERE attribute_id = {$imgId} ");
        $this->query("INSERT INTO catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) 
        SELECT 4, {$imgId}, 0, cpe.entity_id, op.image FROM oberig_products op
        INNER JOIN catalog_product_entity cpe 
            ON cpe.sku = op.sku 
        WHERE op.store_id=0
        ");
        
        $this->query("DELETE FROM catalog_product_entity_varchar WHERE attribute_id = {$imgSmallId} ");
        $this->query("INSERT INTO catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) 
        SELECT 4, {$imgSmallId}, 0, cpe.entity_id, op.small_image FROM oberig_products op
        INNER JOIN catalog_product_entity cpe 
            ON cpe.sku = op.sku 
        WHERE op.store_id=0
        ");
        
        $this->query("DELETE FROM catalog_product_entity_media_gallery WHERE attribute_id = {$media_gallery_id} ");
        $this->query("INSERT INTO catalog_product_entity_media_gallery (attribute_id, entity_id, value) 
        SELECT {$media_gallery_id}, cpe.entity_id, cpm_old.value FROM {$this->oldDB}.catalog_product_entity_media_gallery cpm_old
        INNER JOIN {$this->oldDB}.catalog_product_entity cpe_old
            ON cpe_old.entity_id = cpm_old.entity_id
        INNER JOIN catalog_product_entity cpe 
            ON cpe.sku = cpe_old.sku 
        WHERE cpm_old.attribute_id = {$old_media_gallery_id}
        ");
    }
}

$imp = new Oberig_Import();
$imp->loadCsv();
//$imp->loadCategories();
$imp->addProduct();
$imp->importImages();
//set attribute sell_by_phone_only  
