<?php
ini_set("memory_limit","-1");
require_once '../app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

/**
 * Import product attributes and attribute sets. It must be import before import products
 */
 class Oberig_Import_Attributes{
    public $typeId;
    public $file = '../var/import/attributes.ser';
    public $content;
    
    public function __construct(){
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->content = unserialize($content);
        echo "File loaded \n";
    }
    
    public function load(){
        $attrCollection = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($this->typeId)->load();
        $this->clear($attrCollection); 
        foreach($this->content as $object){
            $this->parse($object);
        }
    }

    public function parse($object){
        $this->createAttribute($object);
    }
    public function clear($collection){
        foreach($collection as $c){
            $c->delete();
        }
    }
   
    
    public function createAttribute($object){
        //mage::d($object['source_model']); return;
            $attributeId = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $object['attribute_code']);
            $model = Mage::getModel('catalog/resource_eav_attribute');
            //$model = new Mage_Eav_Model_Entity_Setup();
            
            $data = array(
                'attribute_code'                => $object['attribute_code'],
                'attribute_model'               => $object['attribute_model'],
                'backend_model'                 => $object['backend_model'],
                'backend_type'                  => $object['backend_type'],
                'backend_table'                 => $object['backend_table'],
                'frontend_model'                => $object['frontend_model'],
                'frontend_input'                => $object['frontend_input'],
                'frontend_label'                => $object['frontend_label'],
                'frontend_class'                => $object['frontend_class'],
                //'source_model'                  => $object['source_model'],
                'is_required'                   => $object['is_required'],
                'is_user_defined'               => $object['is_user_defined'],
                'default_value'                 => $object['default_value'],
                'is_unique'                     => $object['is_unique'],
                'note'                          => $object['note'],
                'frontend_input_renderer'       => $object['frontend_input_renderer'],
                'is_global'                     => $object['is_global'],
                'is_visible'                    => $object['is_visible'],
                'is_searchable'                 => $object['is_searchable'],
                'is_filterable'                 => $object['is_filterable'],
                'is_comparable'                 => $object['is_comparable'],
                'is_visible_on_front'           => $object['is_visible_on_front'],
                'is_html_allowed_on_front'      => $object['is_html_allowed_on_front'],
                'is_used_for_price_rules'       => $object['is_used_for_price_rules'],
                'is_filterable_in_search'       => $object['is_filterable_in_search'],
                'used_in_product_listing'       => $object['used_in_product_listing'],
                'used_for_sort_by'              => $object['used_for_sort_by'],
                'is_configurable'               => $object['is_configurable'],
                'apply_to'                      => $object['apply_to'],
                'is_visible_in_advanced_search' => $object['is_visible_in_advanced_search'],
                'position'                      => $object['position'],
                'is_wysiwyg_enabled'            => $object['is_wysiwyg_enabled'],
                'is_used_for_promo_rules'       => $object['is_used_for_promo_rules'],
            );
            //$model->addAttribute($this->typeId, $object['attribute_code'], $data)
            $model->setSourceModel($object['source_model']);
            $model->setEntityTypeId($this->typeId);
            $model->addData($data);
            try { 
                    $model->save(); 
                    $model->setSourceModel($object['source_model']);    // TODO: check why need it, and how fix it for right set source_model
                    $model->save();
                    echo $model->getAttributeCode()." ..........................ok. ID: " . $model->getId() . "\n"; 
            } catch(Exception $ex) { echo ("Error: " . $ex->getMessage() . "\n"); }
            
            //add attribute options 
            if ( empty($object['options']) ) { echo "Model ".$model->getAttributeCode()." saved ..........................ok. \n";  return; }
            
            $option['attribute_id'] = $model->getId();
            foreach($object['options'] as $opt){
                    $option['value']['option'][0] = $opt['label'];
                    //mage::d($option); //die;
                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $setup->addAttributeOption($option);
            }
            echo "Model ".$model->getAttributeCode()." saved. options added ..........................ok. \n"; 
    }
}

echo date("\nY-d-m H:i:s\n")."Begin\n";
$im = new Oberig_Import_Attributes();
$im->load();
echo date("\nY-d-m H:i:s\n")."End\n";