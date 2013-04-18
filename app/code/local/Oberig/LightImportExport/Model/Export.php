<?php
class Oberig_LightImportExport_Model_Export extends Varien_Object
{
	public function generateCSV($aFilter)
	{		
		$sName = Mage::getModel('eav/entity_attribute_set')->load($aFilter['attribute_set_id'])->getAttributeSetName(); 
		$sFileName = str_replace(' ','_',$sName).'_'.date('Y_m_d_His');		
		$sFileName.='.csv';
					
		$aArrHeading = array('id','name');
		foreach($aFilter['attr'] as $fieldName){
			$aArrHeading[] = $fieldName;
		}
		
		$product = Mage::getModel('catalog/product');

		$collection = $product->getCollection()
		->addAttributeToFilter('attribute_set_id',$aFilter['attribute_set_id'])
		//->addAttributeToSelect('*');
		->addAttributeToSelect('name')
		->addAttributeToSelect($aFilter['attr'])
		;
		
		$lightConfig = Mage::getSingleton('lightimportexport/config');
		$lightConfig->initSelectAttr($aFilter['attr']);

		
		$aOutputData = array();
		$aOutputData[] = $aArrHeading;
		
		$iCounter = 0;
		foreach ($collection as $curProduct){
			$iCounter++;
			/*
			if($iCounter%100!=0){
				continue;
			}						
			*/
			$aData = $curProduct->getData();

			$sProductName = $curProduct->getName();
			$sProductName = str_replace(array(',',';','\n'),' ',$sProductName);
			
			$aArr = array($curProduct->getId(),$curProduct->getName());	
			foreach($aFilter['attr'] as $fieldName){
				//$aArr[] = $aData[$fieldName];
				$aArr[] = $lightConfig->getSelectValue($fieldName,$aData[$fieldName])  ;	
			}							


			$aOutputData[] = $aArr;			
		}

		
	
		
		header('Content-type: text/csv'); 
		//header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=\"$sFileName\"");

		$fp = fopen('php://output','w');;		
		foreach ($aOutputData as $fields) {
		    fputcsv($fp, $fields);
		}		
		fclose($fp);			

		die();		
		
	}    
}
