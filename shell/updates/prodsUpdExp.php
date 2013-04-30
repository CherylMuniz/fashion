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
        //$skus = array('BE1238-1','BE1238-2','BE1238-3','BE1238-4','BE1239-1','BE1239-2','BE1239-3','BE1239-4','BE1240-1','BE1240-2','BE1240-3','BE1240-4','BE1249-1','BE1249-2','BE1249-3','BE1249-4','BE2127-1','BE2127-2','BE2127-3','BE2127-4','BE2127-5','BE2127-6','BE2128-1','BE2128-2','BE2128-3','BE2128-4','BE2129-1','BE2129-2','BE2129-3','BE2129-4','BE2130-1','BE2130-2','BE2130-3','BE2130-4','BE3043-1','BE3043-2','BE3043-3','BE3063-1','BE3063-2','BE3063-3','BE3064-1','BE3064-2','BE3064-3','BE3064-4','BE3065-1','BE3065-2','BE3065-3','BE3065-4','Boss0362-2','CA5502-1','CA6197-7','CH3094-1','CH3094-2','CH3256-1','CH3256-2','CH3256-3','CH3256-4','CH3256-5','CH4189TQ-5','CH4189TQ-6','CH4189TQ-7','Demoiselle1-1','Demoiselle1-2','Demoiselle1-4','Demoiselle2-1','Demoiselle2-2','Demoiselle2-3','DG1239-5','DIORFROZEN-1','DIORFROZEN2-2','DIORFROZEN2-3','DL5041-1','GG1037-1','GG1037-2','GG1037-3','GG1037-4','GG1037-5','GG1038-1','GG1038-2','GG1038-3','GG1038-4','GG1039-1','GG1039-2','GG1039-3','GG1039-4','GG2228-1','MU01OS-1','MU01OS-2','MU01OS-3','MU01OS-4','MU01OS-5','MU06OS-1','MU06OS-2','MU06OS-3','MU06OS-4','MU06OS-5','MU10NS-10','MU10NS-11','MU10NS-12','MU10NS-9','OO4020-1','OO4020-2','OX3153-1','OX3153-2','OX3153-3','PO0009-5','PO0009-6','PO0009-7','PO2404V-4','PO2404V-5','PO2404V-6','PO3044V-1','PO3044V-2','PO3044V-3','PO3044V-4','PO3046S-1','PO3046S-2','PO3046S-3','PO3046S-4','PO3047S-1','PO3047S-2','PO3047S-3','PO3047S-4','RB3407-4','RB3447-4','RB3447-5','RB3447-6','RB3447-7','RB3447-8','RB3449-12','RB4186-1','RB4186-2','RB4186-3','RB4186-4','RB4186-5','RB4187-1','RB4187-2','RB4187-3','RB4187-4','RB4187-5','RB5289-1','RB5289-2','RB5289-3','RB5289-4','RB5289-5','TF4059-5','TH0303-6',); // 147
        //$skus = array('Boss0338/N/S-1','Boss0338/N/S-3','Boss0338/N/S-4','Boss0338/N/S-5','Boss0338N/S-2','CD3214-3','CD3214-4','CD3214S-2','CH3213-6','DD1246-2741','DD1246-2754','DG1245-2739','DG1245-2741','DG3128-2738','DG3163-501','DG3163-656','DG3167-2737','DG3167-2738','DG3167-2739','DG3167-2740','DG3167-2741','DG3168-2737','DG3168-2739','DG3168-2742','DG3168-2743','DL0012-5','DL0012-6','DL0013-5','DL0013-6','DL0040-1','DL0040-2','DL0040-3','DL0040-4','FF1010-5','GG1006-5','GG1006-6','GG1010-6','GG1012S-5','GG1012S-6','GG1013-9','GG1013/S-10','GG1013/S-12','GG1013S-11','GG1019-7','GG1020-7','MB0293-1','MB0293-2','MB0293-3','MB0427-1','MB0427-2','MB0427-3','MB0428-1','MB0428-2','MB0428-3','MB0429-1','MB0429-2','MB0429-3','MB0429-4','MB0430-1','MB0430-2','MJ421S-2','MJ421S-3','MJ421S-4','MJ421S-5','MJ421S/1','MU04LV-1AB1O1','MU04LV-7S01O1','MU04LV-KA41O1','MU04LV-KAM1O1','MU04LV-KAR1O1','MU04LV-PC21O1','MU04LV-PC31O1','MU04LV-PC41O1','MU04LV-PC51O1','MU10LV-2AU1O1','Mu10NS-13','OSC-1016/87','OSC-1282','OSC-1390','OSC-3010-24','OSC-504','OSC-C574','OSC-CD3752','OX8030-01','OX8030-02','OX8030-03','OX8030-04','OX8030-05','OX8030-06','OX8031-01','OX8031-02','OX8031-03','OX8031-04','OX8031-05','OX8031-06','PDG4161-1','PDG4161-2','PDG4161-3','PDG4161-4','PDG4161-5','PRB2132-24','RB1530-1','RB1530-2','RB1530-3','RB1530-4','RB1530-5','RB2132-6013','RB2132-601485','RB2132-81251','RB2132-944','RB2140-10923F','RB2140-112485','RB2140-32','RB2140-33','RB2140-34','RB2140-35','RB2140-36','RB2140-47','RB2140-65','RB2140-66','RB2140-67','RB2140-68','RB2140-69','RB2140-70','RB2140-71','RB2140-72','RB2140-73','RB2140-74','RB2140-75','RB2140-76','RB2140-77','RB2140-78','RB2140-79','RB2140-80','RB2140-81','RB2140-82','RB2140-83','RB2140-84','RB2140-957','RB2140-965','RB5184-5139','RB5184-5140','RB5184-5141','RB5184-5215','RB5277-5','RL6081-1','RL6081-2','RL6081-3','RL6081-4','RL6081-5','RL6085-1','RL6085-2','RL6085-3','RL6085-4','RL6085-5','RL6089-1','RL6089-2','RL6089-3','RL6089-4','RL6089-5','RL6090-1','RL6090-2','RL6090-3','RL6090-4','RL6090-5','RL6091-1','RL6091-2','RL6091-3','RL6091-4','RL6091-5','SC-010WJ','SC-1028G','SC-1262','SC-20YHD','SC-2X5HD','SC-5013C','SC-85113','SC-BG4JS','SC-C1083C','TF144-7','TF144-8','TF236-5','TF236-6','TF294-1','TF294-2','TF294-3','TF294-4','TF295-1','TF295-2','TF295-3','TF295-4','TF296-1','TF296-2','TF296-3','TF296-4','TF297-1','TF297-2','TF297-3','TF297-4','TF298-1','TF298-2','TF298-3','TF298-4','TF299-1','TF299-2','TF299-3','TF299-4','TF300-1','TF300-2','TF300-3','TF300-4','TF305-1','TF305-2','TF305-3','TF317-1','TF317-2','TF317-3','TF318-1','TF318-2','TF318-3','TF35-5','TF5142-5','TF5142-6',); // 223
        $skus = array('DL0042-1','DL0042-2','DL0042-3','DL0042-4','DL0043-1','DL0043-2','DL0043-3','DL0043-4','DL0044-1','DL0044-2','DL0044-3','DL0045-1','DL0045-2','DL0045-3','DL0046-1','DL0046-2','DL0046-3','DL0046-4','DL0047-1','DL0047-2','DL0047-3','DL0047-4','DL0048-1','DL0048-2','DL0048-3','DL0048-4','DL0049-1','DL0049-2','DL0049-3','DL0049-4','MMJ366/S-29AJJ','MMJ366/S-C40CC','MMJ366/S-C41JJ','MMJ366/S-C42D8','MMJ366/S-C43VK','MMJ426/N-AFT','MMJ426/N-AFW','MMJ426/N-M4P','MMJ476-01U','MMJ476-01V','MMJ513-7P1','MMJ513-7P2','MMJ513-807','MMJ513-KVX','MMJ514-7PN','MMJ514-7PR','MMJ514-807','MMJ514-HKN','MMJ514-KVX','MMJ536-MZ7','MMJ536-MZ8','PR24PV-1','PR24PV-2','PR24PV-3','PR24PV-4','PR24PV-5','SC-002/71','SC-004/6G','SC-008','SC-05715','SC-1BO3M1','SC-2931-9524','SC-2953-9558','SC-2990-9531','SC-3024-9531','SC-3026-18131','SC-3028-95S3','SC-3034-39987','SC-4057-02','SC-4059-05','SC-4060-04','SC-4166-601/58','SC-601/58','SC-601S','SC-6LBJJ','SC-824/51','SC-9002/71','SC-9002/87','SC-9013/73','SC-9039-12935J','SC-9095-01','SC-MU5Y1',); 


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
