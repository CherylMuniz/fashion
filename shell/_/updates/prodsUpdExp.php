<?
ini_set("memory_limit","10000M");
require_once "/home/www/production/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

class Export_Products {
    
    public $json = null;
    const CAT_NAME = 'Bench';
    
    
    public function prodsCollection(){
        $collection = null;
        
        //$catId = Mage::getModel('catalog/category')->getCollection()->setStoreId('0')->addAttributeToSelect('name')->addAttributeToFilter('name', self::CAT_NAME)->getFirstItem()->getId();
        //$collection = Mage::getModel('catalog/category')->load($catId)->getProductCollection();
        //$collection = Mage::getModel('catalog/product')->getCollection()->load();
        
        //$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        //$result = $connection->query('select sku from new_sku');
        //foreach($result->fetchAll(PDO::FETCH_ASSOC) as $res){
            //$collection[] = Mage::getModel('catalog/product')->loadByAttribute('sku', $res['sku']);
        //}
        
        //$skus = array('BOSS0519-1','BOSS0519-2','BOSS0519-3','BOSS0519S-4','BOSS0519S-5','BOSS0520-1','BOSS0520-2','BOSS0520-3','BOSS0520-4','BOSS0520-5',);
        //$skus = array('BOSS0338-5','BOSS0338-6','BOSS0432-6','BOSS0432-7','BOSS0433-5','BOSS0433-6','BOSS0434-5','BOSS0434-6','BOSS0435-6','BOSS0439-5','BOSS0439-6','BOSS0474-1','BOSS0474-2','BOSS0474-3','BOSS0474-4','BOSS0521-1','BOSS0521-2','BOSS0521-3','BOSS0521-4','BOSS0521-5','BOSS0522-1','BOSS0522-2','BOSS0522-3','BOSS0522-4','BOSS0522-5','BOSS0522-6','BOSS0523-1','BOSS0523-2','BOSS0523-3','BOSS0523-4','BOSS0524-1','BOSS0524-2','BOSS0524-3','BOSS0524-4','BOSS0524-5','BOSS0525-1','BOSS0525-2','BOSS0525-3','BOSS0525-4','BOSS0526-1','BOSS0526-2','BOSS0526-3','BOSS0526-4','BOSS0527-1','BOSS0527-2','BOSS0527-3','BOSS0527-4','BOSS0528-1','BOSS0528-2','BOSS0528-3','BOSS0528-4','BOSS0529-1','BOSS0529-2','BOSS0529-3','BOSS0529-4','BOSS0530-1','BOSS0530-2','BOSS0530-3','BOSS0530-4','BOSS0531-1','BOSS0531-2','BOSS0531-3','BOSS0531-4','CARRERA5000-1','CARRERA5000-2','CARRERA5000-3','CARRERA5000-4','CARRERA5000-5','CARRERA5000-6','CD3254-1','CD3254-2','CD3254-3','CD3254-4','CD3255-1','CD3255-2','CD3255-3','CD3255-4','CD3256-1','CD3256-2','CD3256-3','CD3256-4','CD3258-1','CD3258-2','CD3258-3','CD3258-4','CD3259-1','CD3259-2','CD3259-3','CD3259-4','CD3260-1','CD3260-2','CD3260-3','CD3260-4','CD3769-1','CD3769-2','CD3769-3','CD3770-1','CD3770-2','CD3770-3','CD3770-4','CD3771-1','CD3771-2','CD3771-3','DIOREVER-4','DIOREVER-5','DIOREVER1-1','DIOREVER1-2','DIOREVER1-3','DIOREVER1-5','DIOREVER2-1','DIOREVER2-2','DIOREVER2-3','DIOREVER2-4','DIOREVER2-6','DIOREVER3-1','DIOREVER3-2','DIOREVER3-3','DIOREVER3-4','DIOREVER3-5','DIORFROZEN1-1','DIORFROZEN1-2','DIORFROZEN1-3','DIORFROZEN1-4','DIORFROZEN1-5','DIORFROZEN1-6','PM4052S-1','PM4052S-2','PM4052S-3','PM4052S-4','PM4052S-5','PM4057SQ-1','PM4057SQ-2','PM4057SQ-3','PM4059S-1','PM4059S-2','PM4059S-3','PM4059S-4','PM4059S-5','PM4060S-1','PM4060S-2','PM4060S-3','PM4060S-4','PM8081S-1','PM8081S-2','PM8081S-3','PM8081S-4','PM8126S-6','PM8141S-1','PM8141S-2','PM8141S-3','PM8141S-4','PM8141S-5','PM8152S-1','PM8152S-2','PM8152S-3','PM8152S-4','PM8152S-5','PM8153S-1','PM8153S-2','PM8157S-1','PM8157S-2','PM8157S-3','PM8158S-1','PM8158S-2','PM8158S-3','PM8158S-4','PM8158S-5','PM8162S-1','PM8162S-2','PM8162S-3','PM8162S-4','PM8162S-5','PM8162S-6','PM8162S-7','RB6049-1','RB6049-2','RB6049-3',);
        //$skus = array('CH3131-1','noSku1','noSku2','noSku3','PBV8092B-1','TH7643-1',);
        //$skus = array('OV5161-7');
        //$skus = array('OSC-474-1 ');
        //$skus = array('CD3207-3');
        //$skus = array('MU10NS-8','OV5195-1','OV5195-2','OV5195-3','OV5195-4','RB3025-25','TF1073-3',);
        $skus = array('BE1238-1','BE1238-2','BE1238-3','BE1238-4','BE1239-1','BE1239-2','BE1239-3','BE1239-4','BE1240-1','BE1240-2','BE1240-3','BE1240-4','BE1249-1','BE1249-2','BE1249-3','BE1249-4','BE2127-1','BE2127-2','BE2127-3','BE2127-4','BE2127-5','BE2127-6','BE2128-1','BE2128-2','BE2128-3','BE2128-4','BE2129-1','BE2129-2','BE2129-3','BE2129-4','BE2130-1','BE2130-2','BE2130-3','BE2130-4','BE3043-1','BE3043-2','BE3043-3','BE3063-1','BE3063-2','BE3063-3','BE3064-1','BE3064-2','BE3064-3','BE3064-4','BE3065-1','BE3065-2','BE3065-3','BE3065-4','Boss0362-2','CA5502-1','CA6197-7','CH3094-1','CH3094-2','CH3256-1','CH3256-2','CH3256-3','CH3256-4','CH3256-5','CH4189TQ-5','CH4189TQ-6','CH4189TQ-7','Demoiselle1-1','Demoiselle1-2','Demoiselle1-4','Demoiselle2-1','Demoiselle2-2','Demoiselle2-3','DG1239-5','DIORFROZEN-1','DIORFROZEN2-2','DIORFROZEN2-3','DL5041-1','GG1037-1','GG1037-2','GG1037-3','GG1037-4','GG1037-5','GG1038-1','GG1038-2','GG1038-3','GG1038-4','GG1039-1','GG1039-2','GG1039-3','GG1039-4','GG2228-1','MU01OS-1','MU01OS-2','MU01OS-3','MU01OS-4','MU01OS-5','MU06OS-1','MU06OS-2','MU06OS-3','MU06OS-4','MU06OS-5','MU10NS-10','MU10NS-11','MU10NS-12','MU10NS-9','OO4020-1','OO4020-2','OX3153-1','OX3153-2','OX3153-3','PO0009-5','PO0009-6','PO0009-7','PO2404V-4','PO2404V-5','PO2404V-6','PO3044V-1','PO3044V-2','PO3044V-3','PO3044V-4','PO3046S-1','PO3046S-2','PO3046S-3','PO3046S-4','PO3047S-1','PO3047S-2','PO3047S-3','PO3047S-4','RB3407-4','RB3447-4','RB3447-5','RB3447-6','RB3447-7','RB3447-8','RB3449-12','RB4186-1','RB4186-2','RB4186-3','RB4186-4','RB4186-5','RB4187-1','RB4187-2','RB4187-3','RB4187-4','RB4187-5','RB5289-1','RB5289-2','RB5289-3','RB5289-4','RB5289-5','TF4059-5','TH0303-6',); // 147


        foreach($skus as $s){
            $collection[] = Mage::getModel('catalog/product')->loadByAttribute('sku', $s);
        }
        return $collection;
    }
    
    
    public function export(){
        $collection = $this->prodsCollection();
        
        $count = count($collection);                                    //$limit = 1000; $iterations = ceil($count/$limit);
        $toJson = array(); $i=0; $j=0; 
        foreach ($collection as $product){
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
            // add categories name paths
            $categories = $product->getCategoryIds();
            foreach($categories as &$catId){
                $catId = $this->getCategoryNamePath($catId);
            }unset($catId);
            $data['categories'] = $categories;
            $toJson[$j] = $data;
            //mage::d($data); die;
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
    
    public function toFile($num){
        echo date("\nY-d-m H:i:s\n")."Start write to file\n";
        $content = serialize($this->json);
        $fo = fopen("{$num}.ser", "w+");
        fputs($fo, $content);
        fclose($fo);
        echo date("\nY-d-m H:i:s\n")."Wrotten!\n";
        passthru("tar zc {$num}.ser > {$num}.ser.tgz");
    }
    
}

echo date("\nY-d-m H:i:s\n")."Begin\n";

$exp = new Export_Products();
$exp->export();
$exp->toFile('prodsArr');

echo date("\nY-d-m H:i:s\n")."End\n";
