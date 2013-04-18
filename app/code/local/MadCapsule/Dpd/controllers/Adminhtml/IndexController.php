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
*/

class MadCapsule_Dpd_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function queueAction() {
		$shipment_id = $this->getRequest()->getParam('id');
        $shipment_incr = Mage::getModel('sales/order_shipment')->load($shipment_id);
        $shipment_details = $shipment_incr->getIncrementId();	
		$_order = Mage::getModel('sales/order')->load($shipment_incr->getOrderId());
		$order_inc_id = $_order->getIncrementId();
		$service_code = $this->getRequest()->getParam('delOption');
		$override_weight = $this->getRequest()->getParam('overWeight');
		$saveData = array('status'=>1,'shipment_id'=>$shipment_details,'service_id'=>$service_code,'order_id'=>$order_inc_id,'weight'=>$override_weight,'service'=>Mage::helper('dpd')->niceServiceNames($service_code));
    	$consignment = Mage::getModel('dpd/consignment')->setData($saveData);
        	
		try {
			$consignment->save();
		}
		catch (Exception $e) {
			throw new Exception($this->__('Failed to add shipment to queue.'));
		}
   		$this->_getSession()->addSuccess($this->__("Shipment #".$shipment_details." for order #".$order_inc_id." has been successfully queued and will be sent to Ship&#64;Ease shortly."));
    		$this->_redirect('adminhtml/sales_order/');
	}

}
