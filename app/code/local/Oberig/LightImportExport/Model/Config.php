<?php
class Oberig_LightImportExport_Model_Config extends Varien_Object
{
	static $aAttrConf = array(
								array('name'=>'Price','checkbox'=>array('name'=> 'price','label'=>'Price','value' => 1,'checked'=>true),'db_type'=>'decimal'),							
								array('name'=>'Cost','checkbox'=>array('name'=> 'cost','label'=>'Cost','value' => 1),'db_type'=>'decimal'),
								array('name'=>'Description','checkbox'=>array('name'=> 'description','label'=>'Description','value' => 1)),
								array('name'=>'Short Description','checkbox'=>array('name'=> 'short_description','label'=>'Short Description','value' => 1)),
								array('name'=>'Extra Title','checkbox'=>array('name'=> 'extra_title','label'=>'Extra Title','value' => 1)),
								array('name'=>'Manufacturer','checkbox'=>array('name'=> 'manufacturer','label'=>'Manufacturer','value' => 1)),
								array('name'=>'Sell by Phone only','checkbox'=>array('name'=> 'sell_by_phone_only','label'=>'Sell by Phone only','value' => 1)),
								array('name'=>'Special Price','checkbox'=>array('name'=> 'special_price','label'=>'Special Price','value' => 1)),
								array('name'=>'Special Price from date','checkbox'=>array('name'=> 'special_from_date','label'=>'Special Price from date','value' => 1)),
								array('name'=>'Special Price to date','checkbox'=>array('name'=> 'special_to_date','label'=>'Special Price to date','value' => 1)),
								array('name'=>'Status','checkbox'=>array('name'=> 'status','label'=>'Status','value' => 1)),
								array('name'=>'Visibility','checkbox'=>array('name'=> 'visibility','label'=>'Visibility','value' => 1)),
								array('name'=>'Meta Title','checkbox'=>array('name'=> 'meta_title','label'=>'Meta Title','value' => 1)),
								array('name'=>'Meta Keyword','checkbox'=>array('name'=> 'meta_keyword','label'=>'Meta Keyword','value' => 1)),
								array('name'=>'Meta Description','checkbox'=>array('name'=> 'meta_description','label'=>'Meta Description','value' => 1))
							);

	private $_selectValue = array();							
	private $_selectKey = array();
	private $_selectFields = array('manufacturer','sell_by_phone_only','status','visibility');
	
	function getCheckboxArr(){
		$aRes = array();
		foreach(self::$aAttrConf as $conf){
			$aRes[] = $conf['checkbox']; 	
		}
		
		return $aRes;
	}

	function initSelectAttr($fields){
		if(count($fields)){						
			foreach($fields as $fieldName){
				if(in_array($fieldName,$this->_selectFields)){
					$attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product',$fieldName);
					$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
					$attributeOptions = $attribute->getSource()->getAllOptions();
					if(count($attributeOptions)){
						$this->_selectValue[$fieldName] = array();
						$this->_selectKey[$fieldName] = array();
						foreach($attributeOptions as $v){
							$this->_selectValue[$fieldName][$v['value']] = $v['label'];
							$this->_selectKey[$fieldName][$v['label']] = $v['value'];
						}
					}
										
				}
			}
		}

	}
	
	function getSelectKey($attrName,$val){
		if(!in_array($attrName,$this->_selectFields)){
			return $val;	
		}		
		
		if(isset($this->_selectKey[$attrName][$val])){
			return $this->_selectKey[$attrName][$val];
		}
		return false;				
	}
	
	function getSelectValue($attrName,$key){
		if(!in_array($attrName,$this->_selectFields)){
			return $key;	
		}		
		
		if($key!=='' && isset($this->_selectValue[$attrName][$key]) && $this->_selectValue[$attrName][$key]!=''){
			return $this->_selectValue[$attrName][$key];
		}
		return $key;
	}
}
