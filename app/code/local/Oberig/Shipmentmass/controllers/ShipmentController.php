<?php
class Oberig_Shipmentmass_ShipmentController extends Mage_Adminhtml_Controller_Action
{
    public function submitAction()
    {
        $post = Mage::app()->getRequest()->getPost('order_ids');
        echo "<p>***** Work begin *****</p>";
        foreach($post as $id){
            $orderIncrementId = $this->addShipment($id);
            echo (!empty($orderIncrementId)) ? "Order #{$orderIncrementId} - shipment added<br>" : "Error! What's wong, shipment not added.<br>";
        }
        echo "<p>***** Completed *****</p>";
    }
    public function addShipment($id){
        //$item = Mage::getResourceModel('sales/order_collection')->getItemById($id); $item->load();
        $item = Mage::getModel('sales/order')->load($id);
        $orderIncrementId = $item->getIncrementId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        try {
            if($order->canShip()) {
                //#Create shipment
                $shipmentid = Mage::getModel('sales/order_shipment_api')
                                ->create($order->getIncrementId(), array());
                //#Add tracking information
                $ship = Mage::getModel('sales/order_shipment_api')
                                ->addTrack($order->getIncrementId(), null, null, null);  
            }
        }catch (Mage_Core_Exception $e) { }
        return (!empty($shipmentid)) ? $orderIncrementId : false;
    }
}
