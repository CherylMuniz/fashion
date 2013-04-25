<?php
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '/home/www/demo/app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

class Related_Lens{
    
    const FULLY_RIMMED = 'lens-fullyrimmed';
    const RIMLESS_AND_SUPRA = 'lens-rimless';
    const STANDARD = 'lens-standard';
    const SPECIALTY = 'lens-specialty';
    const OAKLEY = 'lens-oakley';
    public $oakleyCategoryId = 92;  //Prescription Sunglasses -> Oakley
    
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
    public function setRelatedProduct($prodId, $relatedId){
        $product = Mage::getModel('catalog/product')->load($prodId);
        if( in_array($relatedId, $product->getRelatedProductIds() ) ){
            return null;
        } 
        $param = array(); 
        $param[$relatedId] = array('position' => 0);

        $product->setRelatedLinkData($param);
        $product->save();
        return $product->getSku();
    }
    
    public function setFullyRimmed(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products ". __METHOD__ ."\n"; 
        $relatedId = null;
        $relatedId =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', self::FULLY_RIMMED)->getId();
        
        $optId1 = Mage::getSingleton('eav/config')->getAttribute('catalog_product',  'frame_type' )->getSource()->getOptionId('Metal');
        $optId2 = Mage::getSingleton('eav/config')->getAttribute('catalog_product',  'frame_type' )->getSource()->getOptionId('Plastic');

        $idsFrameTypeItems = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('frame_type', array($optId1,$optId2) )->load()->getItems();

        $idsFrameType=null;
        foreach($idsFrameTypeItems as $item){
            $idsFrameType[] = $item->getId();
            //mage::d($product->getId());  if ( $product->getId() > 10 ) break;
        }
        $_category = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Designer Frames')->getFirstItem();
        //$_category = Mage::getModel('catalog/category')->loadByAttribute('name', 'Clearance');
        $subs = $_category->getAllChildren(true);
        $idsFromCat = array();
        foreach($subs as $cat_id) {
            $category = new Mage_Catalog_Model_Category();
            $category->load($cat_id);
            $collection = $category->getProductCollection();
            foreach ($collection as $product) {
                $idsFromCat[] = $product->getId();
            }
        }
        $productIds = array_diff( $idsFromCat, array_diff($idsFromCat, $idsFrameType) );
        $i=0; $count = count($productIds);
        foreach($productIds as $id){
            $sku = $this->setRelatedProduct($id, $relatedId);
            if( $sku ){ echo $sku."\n"; }
            //mage::d($product->getId());  if ( $product->getId() > 10 ) break;
            ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
        echo date("\nY-d-m H:i:s")." - Related products added". __METHOD__ ."\n";
        //$this->_afterQuery();
    }
    public function rimlessAndSupra(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products ". __METHOD__ ."\n"; 
        $relatedId = null;
        $relatedId =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', self::RIMLESS_AND_SUPRA)->getId();
        
        $optId1 = Mage::getSingleton('eav/config')->getAttribute('catalog_product',  'frame_type' )->getSource()->getOptionId('Half Rim (Supra)');
        $optId2 = Mage::getSingleton('eav/config')->getAttribute('catalog_product',  'frame_type' )->getSource()->getOptionId('Rimless');
        
        $itemsFrameType = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('frame_type', array($optId1,$optId2) )->load()->getItems();

        $idsFrameType=null;
        foreach($itemsFrameType as $item){
            $idsFrameType[] = $item->getId();
            //mage::d($product->getId());  if ( $product->getId() > 10 ) break;
        }
        $_category = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Designer Frames')->getFirstItem();
        //$_category = Mage::getModel('catalog/category')->loadByAttribute('name', 'Clearance');
        $subs = $_category->getAllChildren(true);
        $idsFromCat = array();
        foreach($subs as $cat_id) {
            $category = new Mage_Catalog_Model_Category();
            $category->load($cat_id);
            $collection = $category->getProductCollection();
            foreach ($collection as $product) {
                $idsFromCat[] = $product->getId();
            }
        }
        $productIds = array_diff( $idsFromCat, array_diff($idsFromCat, $idsFrameType) );
        echo "count Rimless and Supra = ".count($productIds)." samples: ".current($productIds).", " .next($productIds). ", " .next($productIds). "\n"; 
        foreach($productIds as $prodId){
                $sku = $this->setRelatedProduct($prodId, $relatedId);
                if( $sku ){ echo $sku."\n"; }
        }
        echo date("\nY-d-m H:i:s")." - Related products added". __METHOD__ ."\n";
        //$this->_afterQuery();
    }
    
    public function standard(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products ". __METHOD__ ."\n"; 
        $relatedId = null;
        $relatedId =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', self::STANDARD)->getId();

        $_category = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Prescription Sunglasses')->getFirstItem();
        //#$_category = Mage::getModel('catalog/category')->loadByAttribute('name', 'Clearance');
        //#$prescription_sunglasses_id = Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', 'Prescription Sunglasses')->getFirstItem()->getId();
        $subs = $_category->getAllChildren(true);
        $idsFromCat = array();
        foreach($subs as $cat_id) {
            $category = new Mage_Catalog_Model_Category();
            $category->load($cat_id);
            $collection = $category->getProductCollection();
            foreach ($collection as $product) {
                //#if( $product->getAttributeSetId() === $prescription_sunglasses_id ){ var_dump($prescription_sunglasses_id); } 
                $idsFromCat[] = $product->getId();
            }
        }
        
        // +++++ select Oakley Ids  +++++ //
        $productsOakley = Mage::getModel('catalog/category')->load( $this->oakleyCategoryId )->getProductCollection();
        $oakleyIds = array();
        foreach($productsOakley as $product){
            $oakleyIds[] = $product->getEntityId();
        }
        
        // +++++ select Special Sunglasses +++++ //
        $selectSpecial = Mage::getModel('catalog/product')->getCollection()->getSelect()->reset( Zend_Db_Select::COLUMNS )->joinInner( array('ps' => 'prescription_sunglasses'), 'e.sku = ps.sku', 'e.entity_id'); 
        $productsSpecial = $selectSpecial->query()->fetchAll();
        $specialIds = array();
        foreach($productsSpecial as $product){
            $specialIds[] = $product['entity_id'];
        }
        
        $productIds = array_diff( $idsFromCat, $oakleyIds, $specialIds );
        echo "count Sunglasses Standard = ".count($productIds)." samples: ".current($productIds).", " .next($productIds). ", " .next($productIds). "\n"; 
        $i=0; $count = count($productIds);
        foreach($productIds as $prodId){
                $sku = $this->setRelatedProduct($prodId, $relatedId);
                if( $sku ){ echo $sku."\n"; }
                ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
        echo date("\nY-d-m H:i:s")." - Related products added". __METHOD__ ."\n";
        //$this->_afterQuery();
    }
    public function specialty(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products ". __METHOD__ ."\n"; 
        $relatedId = null;
        $relatedId =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', self::SPECIALTY)->getId();

        $select = Mage::getModel('catalog/product')->getCollection()->getSelect()->reset( Zend_Db_Select::COLUMNS )->joinInner( array('ps' => 'prescription_sunglasses'), 'e.sku = ps.sku', 'e.entity_id'); 
        $productIds = $select->query()->fetchAll();
        echo "count Sunglasses Special = ".count($productIds)." samples: ".$productIds[0]['entity_id'].", " .$productIds[1]['entity_id']. ", " .$productIds[2]['entity_id']. "\n"; 
        $i=0; $count = count($productIds);
        foreach($productIds as $prodId){
            $sku = $this->setRelatedProduct($prodId['entity_id'], $relatedId);
            if( $sku ){ echo $sku."\n"; }
            ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
        echo date("\nY-d-m H:i:s")." - Related products added". __METHOD__ ."\n";
        //$this->_afterQuery();
    }
    public function oakley(){
                //$this->_beforeQuery();
        echo date("\nY-d-m H:i:s")." - Adding related products ". __METHOD__ ."\n"; 
        $relatedId = null;
        $relatedId =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', self::OAKLEY)->getId();
        
        //#$setId = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', 'Oakley')->getFirstItem()->getId(); // firstItem becouse of filter ($this->typeId) and filter ('attribute_set_name') design single attribute set.
        //#$products = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('attribute_set_id', $setId )->load()->getItems();
        
        $products = Mage::getModel('catalog/category')->load( $this->oakleyCategoryId )->getProductCollection();
        $count = count($products); 
        echo "count Oakley products = {$count}\n"; 
        $i=0; 
        foreach($products as $product){
            $sku = $this->setRelatedProduct($product->getEntityId(), $relatedId);
            if( $sku ){ echo $sku."\n"; }
            ++$i; echo ' ' . floor($i*100/$count) . '%';
        }
        echo date("\nY-d-m H:i:s")." - Related products added\n";
        //$this->_afterQuery();
    }
}
$r = new Related_Lens();
$r->setFullyRimmed();
$r->rimlessAndSupra();
$r->standard();
$r->specialty();
$r->oakley();
