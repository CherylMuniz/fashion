<?
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

//echo date("\nY-d-m H:i:s")." - set websiteIds start\n";
echo date("\nY-d-m H:i:s")." - set storeIds start\n";
$ids = Mage::getModel('catalog/product')->getCollection()->getAllIds();
$count = sizeof($collection); $i=0;


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


foreach($ids as $id){
    $product = Mage::getModel('catalog/product')->load($id);
    //$product->setWebsiteIDs(array(1));
    $product->setStoreIDs(array(0,1));
    
    $product->setIsMassupdate(false);
    $product->setExcludeUrlRewrite(true);
    
    try{ $product->save(); }catch(Exception $e){ echo $e->getMessage(); }
    ++$i; echo ' ' . $i;
}

        //enable indexes
        //$processes = Mage::getSingleton('index/indexer')->getProcessesCollection();
        //$processes->walk('setMode', array(Mage_Index_Model_Process::MODE_REAL_TIME));
        //$processes->walk('save'); 

//echo date("\nY-d-m H:i:s")." - set websiteIds end\n";
echo date("\nY-d-m H:i:s")." - set storeIds end\n";

//watch for process:   select count(*) from catalog_product_website