<?php
class Oberig_LightImportExport_Model_Import extends Varien_Object
{
    const FIELD_NAME_SOURCE_FILE = 'import_file';
    
    private $_updateProducts = array();
    private $_errorLines = array();
    private $_entityTypes = array();
    private $_attributeIds = array();
    private $_backendType = array();
    private $_heading = array();
    private $_numColumns ;
    private $_skipFields = array('id','name');
    
    private function _addErrorLine($iLine)
    {
    	$this->_errorLines[] = $iLine;
    }
    
    public function getErrorLines()
    {
    	return $this->_errorLines;
    }
    
    private function _addUpdateProduct($iId)
    {
    	$this->_updateProducts[] = $iId;
    }
    
    public function getUpdateProducts()
    {
    	return $this->_updateProducts;
    }
    
    private function _initHeading($data){
    	foreach($data as $key=>$name){
    		$this->_heading[$key] = $name;	
    	}    	
    }
    
    public function getNumColumns(){
    	if(empty($this->_numColumns)){
    		$this->_numColumns = count($this->_heading);
    	}
    	return $this->_numColumns;
    }
    
	public function importCSV($sFileName)
	{		
		$lightConfig = Mage::getSingleton('lightimportexport/config');		
		
		$file_pointer = fopen($sFileName, "r");
		$aNewData = array();
		
		$iCounter = 0;
		$bTrueFormat = true;
		while (($data = fgetcsv($file_pointer, 1000, ",")) !== FALSE) {
			$iCounter++;						
			if($iCounter==1){
				$this->_initHeading($data);
				$lightConfig->initSelectAttr($data);
				continue;
			}	
			
			if(count($data)!=$this->getNumColumns()){
				$bTrueFormat = false;
				break;
			}			
			/*			
			if(!is_numeric($data[0]) || !is_numeric($data[2])){
				$this->_addErrorLine($iCounter);
				continue;			
			}
			*/
			//$aNewData[$data[0]] = $data[2];
			$aNewData[$data[0]] = $data;					
		}
		fclose($file_pointer);

		/* profiler */		
		/*
		$db = Mage::getSingleton('core/resource')->getConnection('core_write');
		$profiler = $db->getProfiler();
		$profiler->setEnabled(true);
		*/				
		/* profiler */
		
		if($bTrueFormat){
			if(count($aNewData)){
				foreach($aNewData as $iProdId=>$aData){
					/*
					if($this->_checkIfProductExists($iProdId)){
						$this->_updatePrices(array($iProdId,$dNewPrice));						
					}
					*/
					
					//$this->_updatePrices(array($iProdId,$dNewPrice));
					$this->_updateData($iProdId,$aData);
				}

						
				/* profiler */	
				/*			
				$profiles = $profiler->getQueryProfiles();
				foreach ($profiles as $profile) {
				    var_dump($profile->getQuery());
				    var_dump($profile->getQueryParams());
				}
				
				$profiler->setEnabled(false);	
				die();
				*/						
				/* profiler */
				
				// reindex price
				if(count($this->_updateProducts) && in_array('price',$this->_heading)){
					Mage::getResourceModel('catalog/product_indexer_price')->reindexProductIds($this->_updateProducts);
				}
			}
			return true;
		}
		else{
			return false;
		}
	} 

	private function _checkIfProductExists($iId){
	    $connection = $this->_getConnection('core_read');
	    $tableName = $this->_getTableName('catalog_product_entity');
	    
	    $sql        = "SELECT COUNT(*) AS count_no FROM " . $tableName . " WHERE entity_id = ?";
	    $count      = $connection->fetchOne($sql, array($iId));
	    
	    if($count > 0){
	        return true;
	    }else{
	        return false;
	    }
	}

	private function _getConnection($type = 'core_read'){
	    return Mage::getSingleton('core/resource')->getConnection($type);
	}
	 
	private function _getTableName($tableName){
	    return Mage::getSingleton('core/resource')->getTableName($tableName);
	}
	 
	private function _getAttributeId($attribute_code = 'price'){
		if(array_key_exists($attribute_code,$this->_attributeIds)){
			return $this->_attributeIds[$attribute_code];
		}		
		
		$connection = $this->_getConnection('core_read');
	    $sql = "SELECT attribute_id
	                FROM " . $this->_getTableName('eav_attribute') . "
	            WHERE
	                entity_type_id = ?
	                AND attribute_code = ?";
	    $entity_type_id = $this->_getEntityTypeId();
	    $attrId =  $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
	    
	    $this->_attributeIds[$attribute_code] = $attrId;
	    return $attrId;
	}
	private function _getBackendType($attribute_code = 'price'){
		if(array_key_exists($attribute_code,$this->_backendType)){
			return $this->_backendType[$attribute_code];
		}		
		
		$connection = $this->_getConnection('core_read');
	    $sql = "SELECT backend_type
	                FROM " . $this->_getTableName('eav_attribute') . "
	            WHERE
	                entity_type_id = ?
	                AND attribute_code = ?";
	    $entity_type_id = $this->_getEntityTypeId();
	    $backendType =  $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
	    
	    $this->_backendType[$attribute_code] = $backendType;
	    return $backendType;
	}
	
	private function _getEntityTypeId($entity_type_code = 'catalog_product'){
		if(array_key_exists($entity_type_code,$this->_entityTypes)){
			return $this->_entityTypes[$entity_type_code];
		}
		
	    $connection = $this->_getConnection('core_read');
	    $sql        = "SELECT entity_type_id FROM " . $this->_getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
	    $entityType = $connection->fetchOne($sql, array($entity_type_code));
	    
	    $this->_entityTypes[$entity_type_code] = $entityType;	   
	    return $entityType;
	}

	private function _getOldPrice($iId){
	    $connection = $this->_getConnection('core_read');
	    $tableName = $this->_getTableName('catalog_product_entity_decimal');
	    
	    $sql        = "SELECT value FROM " . $tableName . " WHERE entity_id = ?";
	    $val      = $connection->fetchOne($sql, array($iId));

	    if(!$val){
	    	return false;
	    }
	    return $val;
	}
	
	
	private function _updatePrices($data){
	    $connection     = $this->_getConnection('core_write');
	    $productId      = $data[0];
	    $newPrice       = $data[1];
		$oldPrice       = $this->_getOldPrice($productId);
	    
		if(!$oldPrice || $newPrice==$oldPrice){
			return false;
		}
		
	    $attributeId    = $this->_getAttributeId();	 
	    
	    $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_decimal') . " cped
	                SET  cped.value = ?
	            WHERE  cped.attribute_id = ?
	            AND cped.entity_id = ?";
	    	    
	    $connection->query($sql, array($newPrice, $attributeId, $productId));
	    
	    $this->_addUpdateProduct($productId);
	}
	
	private function _updateData($iProductId,$data){
		$bUpdateProduct = false;
		
		foreach($this->_heading as $key=>$field){
			$bUpdate = false;
			if(in_array($field,$this->_skipFields)){
				continue;
			}
			$newValue = $data[$key];
			$bUpdate = $this->_updateAttribute($iProductId,$newValue,$field);
			if($bUpdate){
				$bUpdateProduct = true;
			}
		}
				
		if($bUpdateProduct){
			$this->_addUpdateProduct($iProductId);	
		}		
	}
	private function _updateAttribute($productId,$newValue,$field){
	    $connection     = $this->_getConnection('core_write');		
	    $attributeId    = $this->_getAttributeId($field);
	    $type = $this->_getBackendType($field);	 
	    
		$oldValue  = $this->_getOldValue($productId,$attributeId,$type);
		if($type=='datetime' && $newValue!=''){
			$time = strtotime($newValue);
			$newValue = date('Y-m-d H:i:s',$time);
		}
		
		/* check select value */
		$lightConfig = Mage::getSingleton('lightimportexport/config');
		$newValue = $lightConfig->getSelectKey($field,$newValue);	
		/* check select value */		
		
		if($newValue==$oldValue || $newValue===false){
			return false;
		}
		
		if($type=='datetime' && $newValue==''){
		    $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_'.$type) . " cped
		                SET  cped.value = NULL
		            WHERE  cped.attribute_id = ?
		            AND cped.entity_id = ?";	    	    
		    $connection->query($sql, array($attributeId, $productId));						
		}
		else{
		    $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_'.$type) . " cped
		                SET  cped.value = ?
		            WHERE  cped.attribute_id = ?
		            AND cped.entity_id = ?";	    	    
		    $connection->query($sql, array($newValue, $attributeId, $productId));			
		}	    
	    return true;
	}
	
	private function _getOldValue($productId,$attributeId,$type){
	    $connection = $this->_getConnection('core_read');
	    $tableName = $this->_getTableName('catalog_product_entity_'.$type);
	    
	    $sql        = "SELECT value FROM " . $tableName . " WHERE entity_id = ? AND attribute_id = ?";
	    $val      = $connection->fetchOne($sql, array($productId,$attributeId));

	    if(!$val){
	    	return false;
	    }
	    return $val;
	}
	
}
