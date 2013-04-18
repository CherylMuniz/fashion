<?php

/* ++++++++++++++++ notes ++++++++++
 * Creating attributes for Lens products.
 */
 
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

class Lens_Attributes{
    
    public $typeId;
    
    public $general;
    public $thickness;
    public $coating;
    public $transitions;
    public $fully_rimmed;
    public $rimmless_supra;
    public $designer_frames;
    public $prescription_sunglasses;
    public $wrapped_frame;
    public $oakley_prescription_sunglasses;
    public $lens_type;
    
    public $attributes = array(
            'lens_thickness',
            'lens_coating',
            'lens_transitions',
            'lens_fully_rimmed',
            'lens_rimmless_supra',
            'lens_designer_frames',
            'lens_prescription_sunglasses',
            'lens_wrapped_frame',
            'lens_oakley_prescription',
            'lens_type',
        );
    
    public $attributeSet;
    public $attributeGroup;
    
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

    
        $productTypes = 'simple';  //array('simple', 'grouped', 'configurable', 'virtual', 'bundle', 'downloadable', 'giftcard');
        $this->general = array(
                //'attribute_code'                => 'lens_coating',
                'attribute_model'               => '',
                'backend_model'                 => '',
                //'backend_type'                  => 'varchar',
                'backend_table'                 => '',
                'frontend_model'                => '',
                //'frontend_input'                => 'select',
                //'frontend_label'                => 'Lens Coating',
                'frontend_class'                => '',
                'source_model'                  => '',
                'is_required'                   => '',
                'is_user_defined'               => '1',
                'default_value'                 => '',
                'is_unique'                     => '',
                'note'                          => '',
                'frontend_input_renderer'       => '',
                'is_global'                     => '1',
                'is_visible'                    => '1',
                'is_searchable'                 => '0',
                'is_filterable'                 => '0',
                'is_comparable'                 => '0',
                'is_visible_on_front'           => '1',
                'is_html_allowed_on_front'      => '1',
                'is_used_for_price_rules'       => '0',
                'is_filterable_in_search'       => '0',
                'used_in_product_listing'       => '0',
                'used_for_sort_by'              => '0',
                'is_configurable'               => '0',
                'apply_to'                      => $productTypes,
                'is_visible_in_advanced_search' => '0',
                'position'                      => '0',
                'is_wysiwyg_enabled'            => '1',
                'is_used_for_promo_rules'       => '0',
                //'options'                       => array(),
        );
        $this->coating = array_merge($this->general, array(
                'attribute_code'                => 'lens_coating',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                'frontend_label'                => 'Lens Coating',
                //'source_model'                  => 'NULL',
                'options'                       => array(
                    'Anti-Skratch/Anti-Glare',
                    'Premium',
                    'Elite',
                    'Elite computer coatings',
                ),
        ));
        $this->thickness = array_merge($this->general, array(
                'attribute_code'                => 'lens_thickness',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                'frontend_label'                => 'Lens Thickness',
                //'source_model'                  => 'NULL',
                'options'                       => array(
                    '1.5',
                    '1.6',
                    '1.67',
                    '1.74',
                ),
        ));
        $this->transitions = array_merge($this->general, array(
                'attribute_code'                => 'lens_transitions',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source'                        => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Lens Transitions',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->fully_rimmed = array_merge($this->general, array(
                'attribute_code'                => 'lens_fully_rimmed',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Fully Rimmed',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->rimmless_supra = array_merge($this->general, array(
                'attribute_code'                => 'lens_rimmless_supra',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Rimless Supra',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->designer_frames = array_merge($this->general, array(
                'attribute_code'                => 'lens_designer_frames',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Designer Frames',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->prescription_sunglasses = array_merge($this->general, array(
                'attribute_code'                => 'lens_prescription_sunglasses',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Prescription sunglasses',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->wrapped_frame = array_merge($this->general, array(
                'attribute_code'                => 'lens_wrapped_frame',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Wrapped frame',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->oakley_prescription_sunglasses = array_merge($this->general, array(
                'attribute_code'                => 'lens_oakley_prescription',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'eav/entity_attribute_source_boolean',
                'frontend_label'                => 'Oakley Prescription sunglasses',
                'options'                       => array(
                    'Yes',
                    'No',
                ),
        ));
        $this->lens_type = array_merge($this->general, array(
                'attribute_code'                => 'lens_type',
                'backend_type'                  => 'varchar',
                'frontend_input'                => 'select',
                //'source_model'                  => 'NULL',
                'frontend_label'                => 'Lens Type',
                'options'                       => array(
                    'SV',
                ),
        ));
        
    }
    
    
    public function createAttribute($object){
            $attributeId = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $object['attribute_code']);
            if( $attributeId ) { Mage::getModel('eav/entity_attribute')->load($attributeId)->delete(); }
            $model = Mage::getModel('catalog/resource_eav_attribute');
            
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
                'source_model'                  => $object['source_model'],
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
            $model->addData($data);
            $model->setEntityTypeId($this->typeId);

            try { 
                $model->save(); echo $model->getAttributeCode()." ..........................ok. ID: " . $model->getId() . "\n"; 
                $model->setSourceModel($object['source_model']);    // TODO: check why need it, and how fix it for right set source_model
                $model->save();
            } catch(Exception $ex) { echo ("Error: " . $ex->getMessage() . "\n"); }
            
            //add attribute options 
            if ( empty($object['options']) ) { echo "Model ".$model->getAttributeCode()." saved ..........................ok. \n";  return; }
            
            $option['attribute_id'] = $model->getId();
            foreach($object['options'] as $opt){
                    $option['value']['option'][0] = $opt;
                    //mage::d($option); //die;
                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $setup->addAttributeOption($option);
            }
            echo "Model ".$model->getAttributeCode()." saved. options added ..........................ok. \n"; 
    }
    
    public function createAttributeSet(){
        Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', $this->attributeSet['name'])->getFirstItem()->delete();
        
        $model = Mage::getModel('eav/entity_attribute_set');
        $model->setEntityTypeId($this->typeId);
        $model->setAttributeSetName($this->attributeSet['name']);
        $model->setSortOrder($this->attributeSet['sort_order']);
        $model->validate();
        try{ $model->save(); echo "Created Attr Set Id: ".$model->getId()."\n"; }catch(Exception $ex){ echo "error\n"; }
        return $model->getId();
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
    
    public function assignAttribute($attrCode, $groupId, $setId){
        $attributeId = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $attrCode);
        if ( !$attributeId ) { echo "Attribute '{$attrCode}' not exists"; return; }
        $model = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId); 
        $model->setAttributeSetId($setId);
        $model->setAttributeGroupId($groupId);
        try { $model->save(); } catch(Exception $ex) { echo $ex->getMessage() . "\n"; }
    }
    
    public function load(){
        $this->createAttribute($this->coating);
        $this->createAttribute($this->thickness);
        $this->createAttribute($this->transitions);
        $this->createAttribute($this->fully_rimmed);
        $this->createAttribute($this->rimmless_supra);
        $this->createAttribute($this->designer_frames);
        $this->createAttribute($this->prescription_sunglasses);
        $this->createAttribute($this->wrapped_frame);
        $this->createAttribute($this->oakley_prescription_sunglasses);
        $this->createAttribute($this->lens_type);

        $setId = $this->createAttributeSetOnSkeletonSet();
        $groupId = $this->createAttributeGroup($setId);
        foreach($this->attributes as $attrName ){
            $this->assignAttribute($attrName, $groupId, $setId);
        }
    }
}
echo date("\nY-d-m H:i:s")." - LENS ATTRIBUTES START\n";
$l = new Lens_Attributes();
$l->load();
echo date("\nY-d-m H:i:s")." - LENS ATTRIBUTES END\n";
