<?php

/* ++++++++++++++++ notes ++++++++++
 * catalog_product_index_price - only through saving from adminpanel :(
 * and than 
 * catalog_category_product_index need restart reindex
 * 
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!              First need start lens attributes creating script           !!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 */
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

class Lenses{

    public $typeId;
    public $categoryName = 'Contact Lenses';
    public $attributeSetName = 'Lenses';
    public $attributeSet;
    public $attributeGroup;
    
    public $storeId = 1;
    public $keys = array(
                'sku',
                'name',
                'description',
                'short_description',
                'price',
                'weight',
                'qty',
            );

    public $data = array();
    public $values = array();
    
    public $option_header_keys = array('title','type','is_require','sort_order','values','sku',);
    
    public $header_lens_type = array('Lens Type','radio','1','0',array(),'lens_type',);
    public $header_lens_glasses_for = array('Lens Glasses For','radio','0','1',array(),'lens_glasses_for',);
    public $header_lens_varifocal_type = array('Varifocal Type','radio','0','2',array(),'lens_varifocal_type',);
    public $header_lens_thickness = array('Lens Thickness','radio','1','3',array(),'lens_thickness',);
    public $header_lens_tint = array('Lens Tint','radio','1','4',array(),'lens_tint',);
    public $header_lens_tint_depth = array('Lens Tint Depth','radio','1','5',array(),'lens_tint_depth',);
    public $header_lens_coating = array('Lens Coating','radio','1','6',array(),'lens_coating',);
    public $header_lens_color = array('Lens Color','radio','1','7',array(),'lens_color',);
    
    public $option_body_keys = array('title','price','price_type','sku','sort_order',);
    public $values_lens_type = array(
         array('Frame Only', '', 'fixed', 'frame_only', '0'),
         array('Single Vision', '0.00', 'fixed', 'single', '1'),
         array('Varifocal', '0.00', 'fixed', 'varifocal', '2'),
     );
     public $values_lens_glasses_for = array(
         array('Distance', '0.00', 'fixed', 'glassesfor_general', '0'),
         array('Reading', '0.00', 'fixed', 'glassesfor_reading', '1'),
     );
     public $values_lens_varifocal_type = array(
         array('Advanced', '99.00', 'fixed', 'varifocal_advanced', '0'),
         array('Premium', '149.00', 'fixed', 'varifocal_premium', '1'),
     );
    public $values_lens_thickness = array(
         array('1.59 - Standard', '95.00', 'fixed', 'thickness_standard', '0'),
         array('1.67 - Thinner', '125.00', 'fixed', 'thickness_thinner', '1'),
     );
     public $values_lens_tint = array(
         array('Full', '0.00', 'fixed', 'tint_full', '0'),
         array('Graduated', '5.00', 'fixed', 'tint_graduated', '1'),
         array('Match Original', '10.00', 'fixed', 'tint_original', '2'),
         array('Polarised', '50.00', 'fixed', 'tint_polarised', '3'),
     );
     
     public $values_lens_tint_depth = array(
         array('Light', '0.00', 'fixed', 'tint_depth_light', '0'),
         array('Medium', '0.00', 'fixed', 'tint_depth_medium', '1'),
         array('Standard', '0.00', 'fixed', 'tint_depth_standard', '2'),
         array('Dark', '0.00', 'fixed', 'tint_depth_dark', '3'),
     );
     public $values_lens_color = array(
         array('Brown', '0.00', 'fixed', 'color_brown', '0'),
         array('Grey', '0.00', 'fixed', 'color_grey', '1'),
         array('Green', '0.00', 'fixed', 'color_green', '2'),
     );
     public $values_lens_coating = array(
         array('Anti-scratch', '0.00', 'fixed', 'coating_anti', '0'),
         array('Elite Sun Coatings', '50.00', 'fixed', 'coating_elite', '2'),
     );

    // ++++++++++++++++++++++++ prescription options +++++++++++++++++++//
    public $header_sphere_left = array('SPH (Sphere) left','drop_down','1','10',array(),'sphere_left',);
    public $header_sphere_right = array('SPH (Sphere) right','drop_down','1','11',array(),'sphere_right',);
        public $header_cylinder_left = array('CYL (Cylinder) left','drop_down','0','12',array(),'cylinder_left',);
        public $header_cylinder_right = array('CYL (Cylinder) right','drop_down','0','13',array(),'cylinder_right',);
    public $header_axis_left = array('Axis left','drop_down','0','14',array(),'axis_left',);
    public $header_axis_right = array('Axis right','drop_down','0','15',array(),'axis_right',);
        public $header_nearadd_left = array('Near/Add left','drop_down','0','16',array(),'nearadd_left',);
        public $header_nearadd_right = array('Near/Add right','drop_down','0','17',array(),'nearadd_right',);
    public $header_pd = array('PD (Pupil Distance)','drop_down','0','18',array(),'pupil_distance',);
    public $header_lens_for_product = array('For product','field','1','19','0.00','fixed','lens_for_product','80',);
    public $header_prescription_notes = array('Additional notes for our opticians','area','0','21','0.00','fixed','prescription_notes','150');
    public $header_prescription_date = array('Prescription date:','date','0','20','0.00','fixed','prescription_date',);
    
    //left eye values
    public $values_sphere_left = array();
    public $values_cylinder_left = array();
    public $values_axis_left = array();
    public $values_nearadd_left = array();
    //right eye values
    public $values_sphere_right = array();
    public $values_cylinder_right = array();
    public $values_axis_right = array();
    public $values_nearadd_right = array();
    
    public $values_pd = array();
    public $values_lens_for_product = array();

    public function __construct(){
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $this->attributeSet = array(
            'name' => 'Lenses',
            'sort_order' => '10',
            'skeleton_set' => Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', 'Default')->getFirstItem()->getId(),
        );
        $this->attributeGroup = array(
            'name' => 'Lenses',
            'sort_order' => '10',
            'default_id' => '0',
        );
        
        $this->_prepareDara();
        
        $this->values = array(
        array(
            'sku'                               => 'lens-specialty',
            'name'                              => 'Sun Specialty',
            'description'                                       => 'optical lens',
            'short_description'                                 => 'optical lens',
            'price'                             => '0.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
                ),
     );
        
        $this->data = $this->values;
        //foreach($this->values as $val){
            //$this->data[] = array_combine($this->keys, $val);
        //}
    }
    public function _prepareDara(){
        // prepare sphere option
        $sphere_values = array();
        $j=0; 
        for( $i=8; $i <= 8, $i >= -8; $i=$i-0.25, $j++ ){
            $plus =  ( $i > 0 ) ? "+" : null;
            $sphere_values[] = array($plus.number_format($i, 2, '.', ''),'','fixed','',$j,);
        }
        $sphere_values[] = array('Infinity','','fixed','',$j++);
        #$sphere_values[] = array('~','','fixed','',$j++);
        $this->values_sphere_left = $this->values_sphere_right = $sphere_values;
        
        // prepare cylinder option
        $values_cylinder = array();
        $j=0; 
        for( $i=8; $i <= 6, $i >= -6; $i=$i-0.25, $j++ ){
            $plus =  ( $i > 0 ) ? "+" : null;
            $values_cylinder[] = ( abs($i) > 2 ) ? array($plus.number_format($i, 2, '.', ''),'15','fixed','',$j,) : array($plus.number_format($i, 2, '.', ''),'','fixed','',$j,);
        }
        //$values_cylinder[] = array('None','','fixed','',$j++);
        $values_cylinder[] = array('DS','','fixed','',$j++);
        $values_cylinder[] = array('Plano','','fixed','',$j++);
        $values_cylinder[] = array('Infinity','','fixed','',$j++);
        #$values_cylinder[] = array('~','','fixed','',$j++);
        $this->values_cylinder_left = $this->values_cylinder_right = $values_cylinder;
        
        // prepare axis option
        $values_axis = array();
        for( $i=0; $i <= 180; $i++){
            $values_axis[] = array($i,'','fixed','',$i);
        }
        $this->values_axis_left = $this->values_axis_right = $values_axis;
        
        // prepare near/add option
        $values_nearadd = array();
        $j=0; 
        for( $i=0; $i <= 4; $i=$i+0.25, $j++ ){
            $values_nearadd[] = array("+".number_format($i, 2, '.', ''),'','fixed','',$j);
        }
        $this->values_nearadd_left = $this->values_nearadd_right = $values_nearadd;
        
        // prepare pupil distance option
        $values_pd = array();
        for( $i=52; $i>=52, $i <= 75; $i++){
            $values_pd[] = ($i == 63) ? array($i.' (average)','','fixed','',$i) : array($i,'','fixed','',$i);
        }
        $this->values_pd = $values_pd;
    }
    
    public function prepare($header, $values, $mode = 'select'){
        $isvalues = true;
        switch($mode) {
            case 'text' : 
            case 'area' : 
                $this->option_header_keys = array('title','type','is_require','sort_order','price','price_type','sku','max_characters',);
                $isvalues = false;
                break;
            case 'date' :
                $this->option_header_keys = array('title','type','is_require','sort_order','price','price_type','sku',); 
                break;
            default :
                $this->option_header_keys = array('title','type','is_require','sort_order','values','sku',);
        }
        $sku_arr = null;
        foreach($this->values as $prod){
                $sku_arr[] = $prod['sku'];
        }
        foreach($sku_arr as $sku){
            $options = array();
            $options[$sku] = array_combine($this->option_header_keys, $header);
            if($isvalues){
                foreach($values as $val){
                    $options[$sku]['values'][] = array_combine($this->option_body_keys, $val);
                }
            }
             //mage::D($options); 
            foreach($options as $sku => $option) {
                $id = Mage::getModel('catalog/product')->getIdBySku($sku);
                $product = Mage::getModel('catalog/product')->load($id);
             
                if(!$product->getOptionsReadonly()) {
                    $product->setProductOptions(array($option));
                    $product->setCanSaveCustomOptions(true);
                    try{ $product->save(); }catch(Exception $e){ echo $e->getMessage(); }
                }
                Mage::getSingleton('catalog/product_option')->unsetOptions();
            }
        }
    }

    public function deleteOptions(){
        $sku_arr = null;
        foreach($this->values as $prod){
            $sku_arr[] = $prod['sku'];
        }
        foreach($sku_arr as $sku){
            $id = Mage::getModel('catalog/product')->getIdBySku($sku);
            $product = Mage::getModel('catalog/product')->load($id);
            $items = $product->getProductOptionsCollection()->getItems();
            foreach($items as $item){
                //mage::d($item->getData());
                $item->delete();
            }
        }
    }

    public function addProduct($data){
        $product = new Mage_Catalog_Model_Product();

        // Build the product
        $setId = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', $this->attributeSet['name'])->getFirstItem()->getId(); // firstItem becouse of filter ($this->typeId) and filter ('attribute_set_name') design single attribute set.
        
        $product->setSku($data['sku']);
        $product->setAttributeSetId( $setId );# 9 is for default
        $product->setTypeId('simple');
        $product->setName($data['name']);
        $product->setWebsiteIDs(array(1)); //only array!!!!!  # Website id, 1 is default
        $product->setStoreIDs(array(0,1));
        $product->setDescription($data['description']);
        $product->setShortDescription($data['short_description']);
        $product->setPrice($data['price']); # Set some price
        $product->setWeight($data['weight']);
        $product->setStatus( Mage_Catalog_Model_Product_Status::STATUS_ENABLED );
        $product->setVisibility( Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH );
        $product->setTaxClassId(0); # default tax class
        if( $catId = $this->getCategoryIdByName($this->categoryName) ){
            $product->setCategoryIds(array($catId)); # some cat id's,
        }
        $product->setStockData(array(
            'is_in_stock' => 1,
            'qty' => $data['qty']
        ));

        $product->setCreatedAt(strtotime('now'));

        try {
            $product->save();
            echo "product created. ID: {$product->getId()} \n";
        }
        catch (Exception $ex) {
            zend_debug::dump($ex->getMessage());
        }
    }
    
    public function addLenses(){
        //mage::d($this->data); die;
        foreach($this->data as $d){
            mage::d($d);
            $this->addProduct($d);
        }
    }
    
    public function createCategory(){
        $catId = $this->getCategoryIdByName($this->categoryName);
        if ($catId) return $catId;
        
        $parentId = Mage::app()->getStore( $this->storeId )->getRootCategoryId();

        $category = new Mage_Catalog_Model_Category();
        $category->setName($this->categoryName);
        $category->setUrlKey('contact-lenses');
        $category->setIsActive(1);
        $category->setDisplayMode('PRODUCTS');
        $category->setIsAnchor(0);
         
        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        $category->setPath($parentCategory->getPath());               
         
        $category->save();
        //unset($category);
        return $category->getId();
    }
    
    public function getCategoryIdByName($name){
        //return Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Designer Frames')->getFirstItem()->getId();
        $parentId = $catId = null;
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->setStoreId('0')
            ->addAttributeToSelect('name'); //->addAttributeToSelect('is_active'); //->addAttributeToFilter('name', $name)->getFirstItem()->getId();
        foreach ($collection as $cat) {
            if ($cat->getName() == $name) {
                $catId = $cat->getId();
                break;
            }
        }
        return $catId;
    }
    
    public function addProductsToCategory( $catId ){
        $sku_arr = null;
        foreach($this->values as $prod){
            $sku_arr[] = $prod['sku'];
        }
        foreach($sku_arr as $sku){
            $id = Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $sku)->getId();
            Mage::getModel('catalog/category_api')->assignProduct($catId, $id);
        }
    }
    
    public function setRelatedProducts(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products\n";
        $sku_arr = array();
        foreach($this->values as $lenses){
            $sku_arr[] = $lenses['sku'];
        }
        $reltedIds = null;
        foreach($sku_arr as $sku){
            $relatedIds[] =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $sku)->getId();
        }

        $select = Mage::getModel('catalog/product')->getCollection()->getSelect()->reset( Zend_Db_Select::COLUMNS )->joinInner( array('ps' => 'prescription_sunglasses'), 'e.sku = ps.sku', 'e.entity_id'); 
        $productIds = $select->query()->fetchAll();
        echo "count Sunglasses Special = ".count($productIds)." samples: ".$productIds[0]['entity_id'].", " .$productIds[1]['entity_id']. ", " .$productIds[2]['entity_id']. "\n"; 
        $i=0; $count = count($productIds);
        foreach($productIds as $prodId){
            $sku = $this->setRelatedProduct($prodId['entity_id'], $relatedIds);
            if( $sku ){ echo $sku."\n"; }
            ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
        echo date("\nY-d-m H:i:s")." - Related products added\n";
        //$this->_afterQuery();
    }
    
    public function setRelatedProduct($prodId, $relatedIds){
        if( is_integer($relatedIds) ) $relatedIds = array($relatedIds);
        $product = Mage::getModel('catalog/product')->load($prodId);
        if( in_array($relatedIds[0], $product->getRelatedProductIds() ) ){
            return null;
        } 
        $param = array(); $i=0;
        foreach($relatedIds as $relId){
            $param[$relId] = array('position' => $i++);
        }
        $product->setRelatedLinkData($param);
        $product->save();
        return $product->getSku();
    }
    
    
    
    public function addImages(){
        foreach($this->getLensIds() as $id){
            $product = Mage::getModel('catalog/product')->load($id);
            
            //delete old images
            //if ($product->getId()){
                //$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                //$items = $mediaApi->items($product->getId());
                //foreach($items as $item)
                    //$mediaApi->remove($product->getId(), $item['file']);
            //}
            
            $this->addImagesToProduct($product);
            unset($product);
        }
    }
    
    public function addImagesToProduct($product){
            // Add three image sizes to media gallery
            $putPathHere = 'thickness_standard.jpg';

            $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
            
            // Remove unset images, add image to gallery if exists
            $importDir = Mage::getBaseDir('media') . DS . 'import/';

            $filePath = $importDir.$putPathHere;
            if ( file_exists($filePath) ) {
                try {
                    $product->addImageToMediaGallery($filePath,  array ('image','small_image','thumbnail'), false, false);
                    $product->save();
                } catch (Exception $e) { echo $e->getMessage(); }
            } else { echo "Product does not have an image or the path is incorrect. Path was: {$filePath}<br/>"; }
    }
    public function getLensIds(){
        $items = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('sku', array('bifocal','varifocal', 'single_vision'));
        $arrIds = null;
        foreach($items as $item){
            $arrIds[] = $item->getId();
        }
        return $arrIds;
    }
    
    
    public function getAttributeOptionIdByName($attribute_code, $attribute_value_name){
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attribute_code);
        $array = array();
            foreach ($attribute->getSource()->getAllOptions(false) as $option){
                if($option['label'] == $attribute_value_name){
                    return $option['value'];
            }
        }
    }
    public function createAttributeSetOnSkeletonSet() //Mage_Adminhtml_Catalog_Product_SetController
    {
        Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', $this->attributeSet['name'])->getFirstItem()->delete();
        /* @var $model Mage_Eav_Model_Entity_Attribute_Set */
        $model  = Mage::getModel('eav/entity_attribute_set')
            ->setEntityTypeId($this->typeId);

        /** @var $helper Mage_Adminhtml_Helper_Data */
        $helper = Mage::helper('adminhtml');

        try {
            //filter html tags
            $name = $helper->stripTags($this->attributeSet['name']);
            $model->setAttributeSetName(trim($name));
            $model->validate();
            $model->save();
            $model->initFromSkeleton($this->attributeSet['skeleton_set']);
            $model->save();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $model->getId();
    }
    
    public function createAttributeGroup($setId){
        $modelGroup = Mage::getModel('eav/entity_attribute_group');
        $modelGroup->setAttributeGroupName( $this->attributeGroup['name'] );
        $modelGroup->setSortOrder( $this->attributeGroup['sort_order'] );
        $modelGroup->setDefaultId( $this->attributeGroup['default_id'] );
        $modelGroup->setAttributeSetId($setId);
        $modelGroup->setGroups(array($modelGroup));
        try{ $modelGroup->save(); echo "Set group. Id: {$modelGroup->getId()}\n"; }catch(Exception $ex){ echo $ex->getMessage()."\n"; }
        return $modelGroup->getId();
    }
    
    public function _beforeQuery(){
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
    public function _afterQuery(){
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
    public function changeData(){
        $id = Mage::getModel('catalog/product')->getIdBySku($this->data[0]['sku']);
        $product = Mage::getModel('catalog/product')->load($id);
        $product->setStockData(array(
            'is_in_stock' => 1,
            'qty' => 10000,
        ));
        $product->save();
    }
}
echo date("\nY-d-m H:i:s")." - LENS START\n";

#$attrId = Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() )->addFilter('attribute_set_name', 'Lenses')->getFirstItem()->getId();
#Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('attribute_set_id', $attrId)->delete();
$l = new Lenses();
$catId = $l->createCategory();
//$setId = $l->createAttributeSetOnSkeletonSet();
//$l->createAttributeGroup($setId);
//$l->addImages();
//$l->addLenses();
//$l->changeData(); die;
    //add options 
    //$l->deleteOptions();
    //$l->prepare($l->header_lens_type, $l->values_lens_type);
    //$l->prepare($l->header_lens_thickness, $l->values_lens_thickness);
    //$l->prepare($l->header_lens_glasses_for, $l->values_lens_glasses_for);
    //$l->prepare($l->header_lens_varifocal_type, $l->values_lens_varifocal_type);
    //$l->prepare($l->header_lens_tint, $l->values_lens_tint);
    //$l->prepare($l->header_lens_tint_depth, $l->values_lens_tint_depth);
    //$l->prepare($l->header_lens_coating, $l->values_lens_coating);
    //$l->prepare($l->header_lens_color, $l->values_lens_color);

    //add prescripted options 
    //$l->prepare($l->header_sphere_right, $l->values_sphere_right);
    //$l->prepare($l->header_cylinder_right, $l->values_cylinder_right);
    //$l->prepare($l->header_axis_right, $l->values_axis_right);
    //$l->prepare($l->header_nearadd_right, $l->values_nearadd_right);
    //$l->prepare($l->header_sphere_left, $l->values_sphere_left);
    //$l->prepare($l->header_cylinder_left, $l->values_cylinder_left);
    //$l->prepare($l->header_axis_left, $l->values_axis_left);
    //$l->prepare($l->header_nearadd_left, $l->values_nearadd_left);
    //$l->prepare($l->header_pd, $l->values_pd);
    //$l->prepare($l->header_lens_for_product, $l->values_lens_for_product, 'text');
    //$l->prepare($l->header_prescription_notes, $l->values_prescription_notes, 'area');

//$l->addProductsToCategory($catId);

$l->setRelatedProducts();