<?php
class Magestore_Giftvoucher_IndexController extends Mage_Core_Controller_Front_Action
{
    public function checkAction(){
		
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){return;}
		
    	$this->loadLayout();
		$max = Mage::helper('giftvoucher')->getGeneralConfig('maximum');
    	
    	if ($code = $this->getRequest()->getParam('code')){
    		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('giftvoucher')->getHiddenCode($code));
			
			$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
			$codes = Mage::getSingleton('giftvoucher/session')->getCodes();
			if (!$giftVoucher->getId()){
				$codes[] = $code;
				$codes = array_unique($codes);
				Mage::getSingleton('giftvoucher/session')->setCodes($codes);
			}
			
			if (!Mage::helper('giftvoucher')->isAvailableToAddCode()){
				Mage::getSingleton('giftvoucher/session')->addError(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!',$max));
				$this->_initLayoutMessages('giftvoucher/session');
				$this->renderLayout();
				return ;
			}
			
			if (!$giftVoucher->getId()){
				$errorMessage = Mage::helper('giftvoucher')->__('Invalid voucher code. ');
				if ($max)
					$errorMessage .= Mage::helper('giftvoucher')->__('You have %d times for checking voucher code.',$max-count($codes));
				Mage::getSingleton('giftvoucher/session')->addError($errorMessage);
			}
    	}else{
    		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('giftvoucher')->__('Check Gift Voucher Balance'));
    		if (!Mage::helper('giftvoucher')->isAvailableToAddCode())
				Mage::getSingleton('giftvoucher/session')->addError(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!',$max));
   		}
    	
    	$this->_initLayoutMessages('giftvoucher/session');
    	$this->renderLayout();
    }
}