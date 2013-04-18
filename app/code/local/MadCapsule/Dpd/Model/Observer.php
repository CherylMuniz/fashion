<?php
/**
 * Magento Mad Capsule Media DPD Extension
 * http://www.madcapsule.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2009 Mad Capsule Media (http://www.madcapsule.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     James Mikkelson <james@madcapsule.co.uk>
 * @filever 0.0.1
 *
*/
class MadCapsule_Dpd_Model_Observer
{

	private function serviceCode() 
	{
			if(Mage::getStoreConfig('dpd/misc/servicecode')=='')
			{
				return '32';
			}else{
				return strtoupper(Mage::getStoreConfig('dpd/misc/servicecode'));				
			}
	}

	
	private function specialInstructions($shipment)
		{
			$_order = $shipment->getOrder();
			return $_order->getCustomerNote();

		}


	private function sendTracking($email,$name,$trackingNo)
		{
			$emailTemplate  = Mage::getModel('core/email_template')
				->loadDefault('madcapsule_dpd_tracking_template');									


			$emailTemplateVariables = array();
			$emailTemplateVariables['consignment'] = $trackingNo;

			$emailTemplate->setTemplateSubject('Your order has been dispatched');

			$storeEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			$storeContact = Mage::getStoreConfig('trans_email/ident_general/name'); 
			$emailTemplate->setSenderEmail($storeEmail);
			$emailTemplate->setSenderName($storeContact);
			$emailTemplate->send($email,$name, $emailTemplateVariables);
			$this->activity('DPD Email Sent'); 
			return 1;


	   }

	public function setSpecialInstructions(Varien_Event_Observer $observer)
		{
         		$_order = $observer->getEvent()->getOrder();
        		$_request = Mage::app()->getRequest();
        		$_comments = "<strong>Instructions to courier:</strong> ".strip_tags($_request->getParam('madcapSpecialInstructions'));
				//Mage::log($_request->getParams()); 
        		if(!empty($_comments)){$_order->setCustomerNote($_comments);}
        		return $this;  
		}

	public function forceConsignment(Varien_Event_Observer $observer)
		{
			if(Mage::getStoreConfig('dpd/misc/automatic')==1)
				{
					$shipment_id = $observer->getEvent()->getShipment()->getId();
					$shipment_increment = $observer->getEvent()->getShipment()->getIncrementId();
					$_order = Mage::getModel('sales/order')->load($observer->getEvent()->getShipment()->getOrderId());
					$order_inc_id = $_order->getIncrementId();
    				$consignment = Mage::getModel('dpd/consignment');
					$consignment->setShipmentId($shipment_increment);
					$consignment->setStatus(1);
					$consignment->setOrderId($order_inc_id);
					$consignment->setServiceId($this->serviceCode());
					$consignment->setService(Mage::helper('dpd')->niceServiceNames($this->serviceCode()));
					$consignment->setWeight(Mage::helper('dpd')->getWeight($shipment_id,2));

					try {
						$consignment->save();
					}
					catch (Exception $e) {
						throw new Exception($this->__('Failed to add shipment to queue.'));
					}
				}

		}
		
	public function activity($text)
		{
			if(Mage::getStoreConfig('dpd/advanced/activity')==1)
				{
					Mage::log($text,null,'madcapsule_system.log'); 
				}

		}	
		
	public function unSave($file)
		{
			if(Mage::getStoreConfig('dpd/advanced/trbackup')==1)
				{
					if(unlink($file))
						{
							$this->activity($file.' removed');
						}else{
							$this->activity('Unable to remove '.$file);
						}
				}

		}	




}
?>
