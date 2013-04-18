<?php
class MadCapsule_Dpd_Model_Consignment_Api extends Mage_Api_Model_Resource_Abstract
{
    public function getConsignment()
    {		
     	$transits = Mage::getModel("dpd/consignment")->getCollection();
    	$transits->addFieldToFilter('status', array('like' => '1'));

    	$items = $transits->getItems();
		$consignments = array();
		foreach($items as $item)
		{
			$consignments[] = $item['shipment_id'].','.$item['service_id'].','.$item['weight'].','.$item['transfer_id'].','.$item['order_id'];
		}
		
		return $consignments;
    }

    public function getConfiguration($setting)
    {
		try
		{
			return Mage::getStoreConfig('dpd/misc/'.$setting.'');	

		}
		catch(Exception $e)
		{
			//theres no error reporting available on courier connect so api errors will have to go to logfile
			Mage::log('There was a problem calling a setting via the API: '.$e.'',null,'madcapsule_system.log');
		}
    }

    public function receiveConsignment($shipment)
    {
		
		try
		{		
    		$consignment = Mage::getModel('dpd/consignment');
			$consignment->setTransferId($shipment);
			$consignment->setStatus(2);
			$consignment->save();
			return true;
		}
		catch(Exception $e)
		{
			Mage::log('There was an error receiving a consignment: '.$e.'',null,'madcapsule_system.log');
		}
    }
	
	public function returnVersion()
    {
        return Mage::getVersion();
    }
	
	public function returnConsignment($shipment_id,$consignment_number,$transfer_id)
	{
	
		try
		{
			$shipment = Mage::getModel("sales/order_shipment")->loadByIncrementId($shipment_id);
			$consignment = Mage::getModel('dpd/consignment');
			$consignment->setTransferId($transfer_id);
			if(Mage::getStoreConfig('dpd/misc/emailcustomer')==TRUE)
			{
				$customer = array();
				$customer['name'] = $shipment->getOrder()->getCustomerFirstname().' '.$shipment->getOrder()->getCustomerLastname();
				$customer['email'] = $shipment->getOrder()->getCustomerEmail();	
				$consignment->setEmail($customer['email']);
			}
			$consignment->setConsignment($consignment_number);
			$consignment->setReturned(NOW());
			$consignment->setStatus(3);
			$consignment->save();
			if(Mage::getStoreConfig('dpd/advanced/activity')==1)
			{
				Mage::log('Shipment #'.$shipment_id.'('.$transfer_id.') returned ('.$consignment_number.')',null,'madcapsule_system.log'); 
			}			
		}
		catch(Exception $e)
		{
			Mage::log('Unable to update tracking for shipment #'.$shipment_id.' ('.$consignment_number.')',null,'madcapsule_system.log');
			return;
		}
		
        $track = Mage::getModel('sales/order_shipment_track')
                 ->setShipment($shipment)
                 ->setData('title', 'DPD')
                 ->setData('number', $consignment_number)
                 ->setData('carrier_code', 'custom')
                 ->setData('order_id', $shipment->getData('order_id'))
                 ->save();		
		
	/*
		$tracking_add = Mage::getModel('sales/order_shipment_api')->addTrack($shipment_id, 'custom', 'DPD', $consignment_number);
		*/					
		if($tracking_add && Mage::getStoreConfig('dpd/advanced/activity')==1)
		{
			Mage::log('Tracking added: '.$consignment_number.' for shipment #'.$shipment_id.'',null,'madcapsule_system.log'); 
		}

		if(Mage::getStoreConfig('dpd/misc/emailcustomer')==TRUE)
		{
			
			$emailTemplate  = Mage::getModel('core/email_template')
				->loadDefault('madcapsule_dpd_tracking_template');									
				
			$emailTemplateVariables = array();
			$emailTemplateVariables['consignment'] = $consignment_number;

			$emailTemplate->setTemplateSubject('Your order has been dispatched');
			$storeEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			$storeContact = Mage::getStoreConfig('trans_email/ident_general/name'); 
			$emailTemplate->setSenderEmail($storeEmail);
			$emailTemplate->setSenderName($storeContact);
			$emailTemplate->send($customer['email'],$customer['name'], $emailTemplateVariables);
			if(Mage::getStoreConfig('dpd/advanced/activity')==1)
			{
				Mage::log('DPD tracking email sent to '.$customer['email'].'',null,'madcapsule_system.log'); 
			}
		}
		
	}

}
?>
