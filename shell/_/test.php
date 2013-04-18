<?php
ini_set("memory_limit","2000M");
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app();


// Add three image sizes to media gallery
$mediaArray = array(
    'thumbnail'   => $putPathHere,
    'small_image' => $putPathHere,
    'image'       => $putPathHere,
);

// Remove unset images, add image to gallery if exists
$importDir = Mage::getBaseDir('media') . DS . 'import/';

foreach($mediaArray as $imageType => $fileName) {
    $filePath = $importDir.$fileName;
    if ( file_exists($filePath) ) {
        try {
            $product->addImageToMediaGallery($filePath, $imageType, false);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        echo "Product does not have an image or the path is incorrect. Path was: {$filePath}<br/>";
    }
}




die;

$model = Mage::getModel('eav/entity_attribute_set');
$id = $model->getCollection()->getItemByColumnValue('attribute_set_name', 'Lenses')->getId();
$model->load($id);
$model->delete();
die;

$p =Mage::getModel('catalog/product')->load(35);
//$p->setTypeId('bundle');
//$p->setRequiredOptions(0);
//$p->setHasOptions(1); $p->save(); die;
//$p->setHasOptions(0); $p->save(); die;
//$p->getOptions();
//$p->setPriceType(1);
//$p->save(); 
//die;
//mage::d($p->getOptions());
//$optBlock = new Mage_Catalog_Block_Product_View_Options;
$optBlock = Mage::getSingleton('core/layout')->createBlock('catalog/product_view_options');
//mage::d($optBlock );
$optBlock->addOptionRenderer('select', "catalog/product_view_options_type_select", "catalog/product/view/options/type/select.phtml");
$optBlock->setProduct($p);
$_options = Mage::helper('core')->decorateArray($optBlock->getOptions());
echo count($_options);
//die;
foreach($_options as $option){
    
        //$renderer = $optBlock->getOptionRender( $optBlock->getGroupOfOption($option->getType() ) );
        //$renderer['renderer'] = $optBlock->getLayout()->createBlock($renderer['block'])
                //->setTemplate($renderer['template']);
    //mage::d( $renderer['renderer']->setProduct($p)->setOption($option)->toHtml() );
    mage::d( $optBlock->getOptionHtml( $option ) );
    //mage::ms($option);
        //mage::d( (array) $option );
    //foreach($option as $opt){
    //}
    //break;
}
die;



$associatedProducts = $p->getTypeInstance(true)->getAssociatedProducts($p);
//mage::d($associatedProducts); 

    $products_links = Mage::getModel('catalog/product_link_api');
    try{
        mage::d($products_links->assign("grouped",4,11)); 
    }catch(Exception $e){ mage::d($e); }

        mage::d($products_links->items("grouped",4)); 


//mage::d($p->getData());
//mage::ms($p);
//echo $p->isSuper();

//echo $p->getTypeId()."\n";
//echo $p->setPriceType(1)."\n";
//echo $p->getPriceType()."\n";
//$p->setTypeId('grouped');
//$p->save();
//$p->setName('Dior BLACKTIE107 807 Black');
