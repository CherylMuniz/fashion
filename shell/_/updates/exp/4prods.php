<?
ini_set("memory_limit","10000M");
require_once '/home/www/production/app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Export_Products {
    
    public $json = null;
    public $filename = 'products.ser';
    
    public function export(){
        //$collection = Mage::getModel('catalog/product')->getCollection()->setPageSize(10)->load();
        $collection = Mage::getModel('catalog/product')->getCollection()->load();
        
        $count = count($collection);                                    //$limit = 1000; $iterations = ceil($count/$limit);
        $toJson = array(); $i=0; $j=0; 
        foreach ($collection as $product){
            /*
            if($i > $limit){
                $this->json = $toJson;
                $this->toFile($j);
                $toJson = array(); $i=0;
                echo 'Saved '. $j . ' products'.PHP_EOL;
            }
            */
            $product->load();

            //#$product->setStockItem('');                                 // set it to null. It's duplicated info. 
            
            $attributeSetName = Mage::getModel('eav/entity_attribute_set')->load( $product->getAttributeSetId() )->getAttributeSetName();
            $product->setAttributeSetId($attributeSetName);             // set it to null. It's dupliceted info. 
            
            $related = $this->getRelatedProducts($product);         if( !empty($related) ) { $product->setData('related', $related); }
            $upsell = $this->getUpSellProducts($product);           if( !empty($upsell) ) { $product->setData('upsell', $upsell); }
            $crosssell = $this->getCrossSellProducts($product);     if( !empty($crosssell) ) { $product->setData('crosssell', $crosssell); }
            
            $options = $this->prepareProductOptions( $product->getId() );
            $product->setData('options', $options['options']);
            
            $data = $product->getData();
            $data['qty'] = $product->getStockItem()->getQty();
            unset($data['entity_id']);
            unset($data['entity_type_id']);                             // delete. Is constant for all products
            unset($data['stock_item']);                                 // delete, as it is now empty

            $attrOptions = $this->getAttributesWithOptions();
            foreach($data as $key => &$val){
                if( in_array($key, $attrOptions) ){
                    $opts = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $key )->getSource()->getAllOptions();
                    foreach($opts as $op){
                        if($val == $op['value']){
                            $val = $op['label'];
                        }
                    }
                }
            }unset($val);
            $toJson[$j] = $data;
            //#if(count($toJson) > 29 ) break;
            $i++; $j++;
        }
        //#mage::D($toJson); die;
        $this->json = $toJson;
        return;
    }
    
    public function getAttributesWithOptions(){
        $type_id = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $attributesCollection = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type_id)->load()->getItems();
        $collection = array();
        foreach($attributesCollection as $attr){
            $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attr->getAttributeCode());
            try { $attribute->setData( 'options', $attribute->getSource()->getAllOptions(false) ); }catch(Exception $e){}

            if( $attribute->getData('options') /* in_array($attribute->getFrontendInput(), array('select', 'multiselect')) */ ){
                //$collection[] = $attribute->getData('options');
                $collection[] = $attribute->getAttributeCode();
            }
        } 
        //mage::D($collection);
        return $collection;
    }
    
    
    public function getRelatedProducts(Mage_Catalog_Model_Product $product){
            $collection = $product->getRelatedProductCollection()->getItems();
            $arrProducts = array();
            foreach($collection as $item){
                $arrProducts[ $item->getSku() ] = array('position' => $item->getPosition() );
            }
            //mage::d($arrProducts);
            return $arrProducts;
    }
    
    public function getUpSellProducts(Mage_Catalog_Model_Product $product){
            $collection = $product->getUpSellProductCollection()->getItems();
            $arrProducts = array();
            foreach($collection as $item){
                $arrProducts[ $item->getSku() ] = array('position' => $item->getPosition() );
            }
            //mage::d($arrProducts);
            return $arrProducts;
    }
    
    public function getCrossSellProducts(Mage_Catalog_Model_Product $product){
            $collection = $product->getCrossSellProductCollection()->getItems();
            $arrProducts = array();
            foreach($collection as $item){
                $arrProducts[ $item->getSku() ] = array('position' => $item->getPosition() );
            }
            //mage::d($arrProducts);
            return $arrProducts;
    }
    
    public function prepareProductOptions($productId){
        $product = Mage::getModel('catalog/product')->load($productId);
        $toJson = array();
        # $toJson = array('sku'=> $product->getSku());
        foreach ($product->getOptions() as $key => $o) {
            $optionData = $o->getData();
            unset($optionData['option_id']);
            unset($optionData['product_id']);
            
            $toJson['options'][$key] = array('describe' => $optionData);
            foreach($o->getValues() as $v){
              $valueData = $v->getData();
                unset($valueData['option_id']);
                unset($valueData['option_type_id']);
                $toJson['options'][$key]['values'][] = $valueData;
            }
        }
        return $toJson;
    }
    
    public function toFile($num){
        echo date("\nY-d-m H:i:s\n")."Start write to file\n";
        $content = serialize($this->json);
        $fo = fopen("products{$num}.ser", "w+");
        fputs($fo, $content);
        fclose($fo);
        echo date("\nY-d-m H:i:s\n")."Wrotten!\n";
        passthru("tar zc products{$num}.ser > products{$num}.ser.tgz");
        //passthru("tar zc {$this->filename} > {$this->filename}.tgz");
    }
    
}

echo date("\nY-d-m H:i:s\n")."Begin\n";

$exp = new Export_Products();
$exp->export();
$exp->toFile('all');

echo date("\nY-d-m H:i:s\n")."End\n";
