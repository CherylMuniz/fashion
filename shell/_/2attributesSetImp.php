<?php
ini_set("memory_limit","-1");
require_once '../app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

/**
 * Import product attribute sets and groups. Assign existed attributes to groups and sets. But attributes must be first imported. (also It must be import before import products)
 */
 class Oberig_Import_Attributes{
    public $typeId;
    public $file = '../var/import/attributesFull.ser';
    public $content;
    
    public function __construct(){
        $this->db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        echo "Load file...\n";
        $content = file_get_contents($this->file);
        $this->content = unserialize($content);
        echo "File loaded \n";
    }
    
    public function load(){
        $attrSetCollection = Mage::getModel('eav/entity_attribute_set')->getCollection()->setEntityTypeFilter($this->typeId);
        $this->clear($attrSetCollection); 
        foreach($this->content as $object){
            $this->parse($object);
        }
    }

    public function parse($object){
        $setId = $this->createAttributeSet($object);
        foreach( $object['groups'] as $group ){
            $groupId = $this->createAttributeGroup($setId, $group);
            foreach( $group['attributes'] as $attribute ){
                $this->assignAttribute( $attribute['attribute_code'], $groupId, $setId );
            }
        }
    }
    
    public function clear($collection){
        foreach($collection as $c){
            $c->delete();
        }
    }

    public function createAttributeSet($arrSet){
        $model = Mage::getModel('eav/entity_attribute_set');
        $model->setEntityTypeId($this->typeId);
        $model->setAttributeSetName($arrSet['attribute_set_name']);
        $model->setSortOrder($arrSet['sort_order']);
        $model->validate();
        try{ $model->save(); echo "Created Attr Set Id: ".$model->getId()."\n"; }catch(Exception $ex){ echo "error\n"; }
        return $model->getId();
    }

    public function createAttributeGroup($setId, $object){
        $modelGroup = Mage::getModel('eav/entity_attribute_group');
        $modelGroup->setAttributeSetId($setId);
        $modelGroup->setAttributeGroupName( $object['attribute_group_name'] );
        $modelGroup->setSortOrder( $object['sort_order'] );
        $modelGroup->setDefaultId( $object['default_id'] );
        $modelGroup->setGroups(array($modelGroup));
        try{ $modelGroup->save(); echo "Set group. Id: {$modelGroup->getId()}\n"; }catch(Exception $e){ echo $e->getMessage()."\n"; die;}
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
}

echo date("\nY-d-m H:i:s\n")."Begin\n";
$im = new Oberig_Import_Attributes();
$im->load();
echo date("\nY-d-m H:i:s\n")."End\n";