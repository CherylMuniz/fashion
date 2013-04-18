<?php
ini_set("memory_limit","-1");
require_once '../app/Mage.php';
umask(0);
$app = Mage::app('default');
echo date("\nY-d-m H:i:s\n");
/**
 * Import product options action
 */
 class Oberig_Import_Attributes{
    public $db;
    public $file;
    public $oldDB = 'fashione_staging2';
    public $typeId;
    
    public function __construct(){
        $this->db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->file = Mage::getBaseDir().'/var/import/attributes.txt';
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
    }
    public function query($query){
        $connect =  Mage::getSingleton('core/resource')->getConnection('core_write');
        echo $query."\n\n";
        return $connect->query($query);
    }
    

    public function attributeSetMapping(){
    // set attribute set mapping 
        $oldTypeId=4;
        $query = 
        "DROP TABLE IF EXISTS `oberig_attribute_set_mapping`;
        CREATE TABLE `oberig_attribute_set_mapping` (
            `attribute_set_name` varchar(255) DEFAULT NULL,
            `attribute_group_name` varchar(255) DEFAULT NULL,
            `attribute_code` varchar(255) DEFAULT NULL,
            `sort_order` smallint(6) NOT NULL DEFAULT '0' 
        );
        INSERT INTO `oberig_attribute_set_mapping` 
            SELECT attribute_set_name,attribute_group_name,attribute_code,eea.sort_order FROM {$this->oldDB}.`eav_attribute_set` eas
            INNER JOIN {$this->oldDB}.`eav_attribute_group` eag
                ON eas.attribute_set_id = eag. attribute_set_id
            INNER JOIN {$this->oldDB}.`eav_entity_attribute` eea
                ON eea.attribute_set_id = eag.attribute_set_id AND eea.attribute_group_id = eag.attribute_group_id
            INNER JOIN {$this->oldDB}.`eav_attribute` ea 
                ON ea.attribute_id = eea.attribute_id
            WHERE eas.entity_type_id = {$oldTypeId} AND ea.entity_type_id = {$oldTypeId}";
        $this->query($query);
    }
    
    public function createAttributeSet($setName){
        
        $model = Mage::getModel('eav/entity_attribute_set');
        $entityTypeID = Mage::getModel('catalog/product')->getResource()->getTypeId();
        $model->setEntityTypeId($entityTypeID);
        $model->setAttributeSetName($setName);
        $model->validate();
        try{ $model->save(); echo "Created Attr Set Id: ".$model->getId()."\n"; }catch(Exception $ex){ echo "error\n"; }
        return $model->getId();
    }

    public function createAttributeGroup($setId, $group_name){
        $modelGroup = Mage::getModel('eav/entity_attribute_group');
        $modelGroup->setAttributeGroupName( $group_name );
        $modelGroup->setAttributeSetId($setId);
        $modelGroup->setGroups(array($modelGroup));
        try{ $modelGroup->save(); echo "Set group. Id: {$modelGroup->getId()}\n"; }catch(Exception $ex){ echo "error set group \n"; }
    }
    
    public function attrSetsGroups(){
        
        //delete old attribute sets
        $this->query("DELETE FROM `eav_attribute_set` WHERE entity_type_id = {$this->typeId}");
        
        //create attr sets
        $connection = Mage::getModel('core/resource')->getConnection('core_read');
        $select = $connection->select()->from('oberig_attribute_set_mapping', 'attribute_set_name')->distinct(); //"SELECT DISTINCT attribute_set_name FROM oberig_attribute_set_mapping");
        //echo $select->__toString();
        $result = $connection->query($select)->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($result as $res){
            $setId = $this->createAttributeSet($res['attribute_set_name']);
            
            //create attr groups
            $select2 = $connection->select()->from('oberig_attribute_set_mapping', 'attribute_group_name')
            ->where('attribute_set_name=?', $res['attribute_set_name'])
            ->distinct(); 
            //echo $select2->__toString();
            $result2 = $connection->query($select2)->fetchAll(PDO::FETCH_ASSOC);
            //mage::d($result2);
            foreach($result2 as $res2){
                $this->createAttributeGroup($setId, $res2['attribute_group_name']);
            }
        }
    }
    
    public function loadAttributes(){
        $optionText = file_get_contents('../var/import/attributes.txt');
        $attrArray = unserialize($optionText);
        //mage::d($attrArray);
        //die;
        //$model = Mage::getModel('catalog/resource_eav_attribute')->load(143)->delete(); die;
        foreach($attrArray as $attr){
                try{ $attr->getSource()->getAllOptions(false); }catch(Exception $e){ echo "yes"; die; continue; }
                
                $model = Mage::getModel('catalog/resource_eav_attribute');
                //mage::ms($model); die;
                
                // if attribute_code already exists, renew it only.
                if( $attributeId = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $attr->getAttributeCode()) ){
                    $model = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId); #$model = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId)->delete();
                    
                    //delete old attribute options. TODO: apply API for it. 
                    $query = "DELETE ao FROM eav_attribute_option ao JOIN eav_attribute ea ON ao.attribute_id = ea.attribute_id AND ea.attribute_id ={$attributeId}";
                    $this->query($query);
                }
                $data = array(
                    'attribute_code'                => $attr->getAttributeCode(),
                    'attribute_model'               => $attr->getData('attribute_model'),
                    'backend_model'                 => $attr->getData('backend_model'),
                    'backend_type'                  => $attr->getData('backend_type'),
                    'backend_table'                 => $attr->getData('backend_table'),
                    'frontend_model'                => $attr->getData('frontend_model'),
                    'frontend_input'                => $attr->getData('frontend_input'),
                    'frontend_label'                => $attr->getData('frontend_label'),
                    'frontend_class'                => $attr->getData('frontend_class'),
                    'source_model'                  => $attr->getData('source_model'),
                    'is_required'                   => $attr->getData('is_required'),
                    'is_user_defined'               => $attr->getData('is_user_defined'),
                    'default_value'                 => $attr->getData('default_value'),
                    'is_unique'                     => $attr->getData('is_unique'),
                    'note'                          => $attr->getData('note'),
                    'frontend_input_renderer'       => $attr->getData('frontend_input_renderer'),
                    'is_global'                     => $attr->getData('is_global'),
                    'is_visible'                    => $attr->getData('is_visible'),
                    'is_searchable'                 => $attr->getData('is_searchable'),
                    'is_filterable'                 => $attr->getData('is_filterable'),
                    'is_comparable'                 => $attr->getData('is_comparable'),
                    'is_visible_on_front'           => $attr->getData('is_visible_on_front'),
                    'is_html_allowed_on_front'      => $attr->getData('is_html_allowed_on_front'),
                    'is_used_for_price_rules'       => $attr->getData('is_used_for_price_rules'),
                    'is_filterable_in_search'       => $attr->getData('is_filterable_in_search'),
                    'used_in_product_listing'       => $attr->getData('used_in_product_listing'),
                    'used_for_sort_by'              => $attr->getData('used_for_sort_by'),
                    'is_configurable'               => $attr->getData('is_configurable'),
                    'apply_to'                      => $attr->getData('apply_to'),
                    'is_visible_in_advanced_search' => $attr->getData('is_visible_in_advanced_search'),
                    'position'                      => $attr->getData('position'),
                    'is_wysiwyg_enabled'            => $attr->getData('is_wysiwyg_enabled'),
                    'is_used_for_promo_rules'       => $attr->getData('is_used_for_promo_rules'),

                );
                if( !$attributeId  ){
                    $model->addData($data);
                    $model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
                }else{
                    $model->setDataChanges($data);
                }
                
            // set groups and sets 
            $connection = Mage::getModel('core/resource')->getConnection('core_read');
            $select = $connection->select()->from(array('oasm' => 'oberig_attribute_set_mapping'), array('attribute_group_name', 'sort_order'))
                ->joinInner(array('eas'=>'eav_attribute_set'), 'oasm.attribute_set_name = eas.attribute_set_name  AND entity_type_id = 4', 'attribute_set_id')
                ->joinInner(array('eag'=>'eav_attribute_group'), 'eag.attribute_set_id = eas.attribute_set_id AND eag.attribute_group_name = oasm.attribute_group_name', 'attribute_group_id')
                ->where('attribute_code=?', $attr->getAttributeCode()); 
            echo $select->__toString()."\n";
            $result = $connection->query($select)->fetchAll();
            echo "-------------------------------------------------\n";
            mage::D($result);
            echo "-------------------------------------------------\n";
            foreach($result as $res){
                if($attributeId) { $model = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId); }
                $model->setAttributeSetId($res['attribute_set_id']);
                $model->setAttributeGroupId($res['attribute_group_id']);
                $model->setSortOrder($res['sort_order']);
                if($attributeId) { try { $model->save(); } catch(Exception $ex) { echo ("Error: " . $ex->getMessage() . "\n"); } }
            }
            //end set groups and sets 
                
            //if( $attr->getAttributeCode() == 'lenstint' )
            //{
                try { 
                    if(!$attributeId) { 
                        $model->save(); echo $attr->getAttributeCode()." ..........................ok. ID: " . $model->getId() . "\n"; 
                    }
                    $option['attribute_id'] = $model->getId();
                    $all_opts = $attr->getSource()->getAllOptions(false);
                } catch(Exception $ex) { echo ("Error: " . $ex->getMessage() . "\n"); }
                
                //add attribute options 
                foreach($all_opts as $opt){
                        $option['value']['option'][0] = $opt['label'];
                        mage::d($option); //die;
                        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                        $setup->addAttributeOption($option);
                }
                echo "Model ".$model->getAttributeCode()." saved. options added ..........................ok. \n"; 
                    //$model = Mage::getModel('catalog/resource_eav_attribute')->load($model->getId())->delete(); //die;
            //}
        }
        
        // set tax class options TODO: API
        $this->query("insert ignore into tax_class select * from {$this->oldDB}.tax_class");
        //die;
    }



}


$im = new Oberig_Import_Attributes();
$im->attributeSetMapping();
$im->attrSetsGroups();
$im->loadAttributes();



die;
    //drop table oberig_attribute_set_mapping;
    //create table `oberig_attribute_set_mapping` (
        //`attribute_set_name` varchar(255) DEFAULT NULL,
        //`attribute_group_name` varchar(255) DEFAULT NULL,
        //`attribute_code` varchar(255) DEFAULT NULL
    //);
    //INSERT INTO `oberig_attribute_set_mapping` 
        //SELECT attribute_set_name,attribute_group_name,attribute_code FROM fashione_staging2.`eav_attribute_set` eas
        //INNER JOIN fashione_staging2.`eav_attribute_group` eag
            //ON eas.attribute_set_id = eag. attribute_set_id
        //INNER JOIN fashione_staging2.`eav_entity_attribute` eea
            //ON eea.attribute_set_id = eag.attribute_set_id AND eea.attribute_group_id = eag.attribute_group_id
        //INNER JOIN fashione_staging2.`eav_attribute` ea 
            //ON ea.attribute_id = eea.attribute_id
        //WHERE eas.entity_type_id = 4 AND ea.entity_type_id = 4 limit 10; 

















$a = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() );
//$items = $a->getItems();
//mage::d($items);
    //foreach($items as $attribute){
        //$attribute->load();
        //mage::ms($attribute); die;
    //}
//die;
//mage::d($attrArray );
foreach($attrArray as $attribute){
    //$old = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'asdad'); 
    $old =  Mage::getModel('eav/entity_attribute');
    //mage::ms($old); 
    //mage::d($old->getIdByCode('catalog_product', 'cost')); 
    
    if( $old->getIdByCode('catalog_product', $attribute->getAttributeCode()) ){
        $oldAttr = $old->load( $attribute->getAttributeCode() );
        $oldAttr->setData( $attribute->getData() );
        try { $oldAttr->save(); echo "{$oldAttr->getAttributeCode()} - ok\n"; }catch(Exception $ex) { echo("{$oldAttr->getAttributeCode()} - no. Error1 update attribute : " . $ex->getMessage() . "\n"); }
        
    }else{
        //die;
        //$model = Mage::getModel('catalog/resource_eav_attribute');
        //mage::d($attribute->getData());
        $old->setData( $attribute->getData() );
        //$old = Mage::getModel('eav/entity_attribute_source_table') ;
        //$old->setAttribute( $attribute );
        try { $old->save(); echo "{$old->getAttributeCode()} - ok\n"; }catch(Exception $ex) { echo("{$old->getAttributeCode()} - no. Error2 update attribute : " . $ex->getMessage() . "\n"); }
    }
}
die;

echo date("\nY-d-m H:i:s\n")."End\n";
