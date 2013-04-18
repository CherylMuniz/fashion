<?php

class Oberig_Fashion_Model_Rate_Result_Method extends Mage_Shipping_Model_Rate_Result_Method
{
	private function _getMethodTitleByPrice(){
		$methodTitle = '';
		if($this->getPrice()==0){
			$methodTitle = Mage::helper('shipping')->__('UK Delivery');
		}
		else if($this->getPrice()==20){
			$methodTitle = Mage::helper('shipping')->__('International Delivery');
		}
		else if($this->getPrice()==12){
			$methodTitle = Mage::helper('shipping')->__('EU delivery');
		}
		return $methodTitle;
	}
	
	function getMethodTitle(){
		if(Mage::app()->getRequest()->getModuleName() != "admin"){
			$methodTitle = $this->_getMethodTitleByPrice();
			if($methodTitle){
				return $methodTitle;
			}			
		}
		return parent::getMethodTitle();
	}
}
