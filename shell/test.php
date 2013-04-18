<?
ini_set("memory_limit","-1");
//echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


    //$collection = Mage::getModel('catalog/category')->load(75)->getProductCollection();
    //$result = 0;
    //foreach ($collection as $product) {
        //$result[] = $product;
        //mage::D($product->getEntityId());
    //}

$_category = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Designer Frames')->getFirstItem();
$subs = $_category->getAllChildren(true);
$result = array();
foreach($subs as $cat_id) {
    $category = new Mage_Catalog_Model_Category();
    $category->load($cat_id);
    $collection = $category->getProductCollection();
    foreach ($collection as $product) {
        $result[] = $product->getId();
    }

}

var_dump($subs);
$collection = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', 'Designer Frames');

var_dump($collection->getSelect()->__toString());
//mage::ms($collection);
//mage::d(count($collection));
