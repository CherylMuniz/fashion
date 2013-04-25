<?php
class Oberig_Shipmentmass_Model_Observer
{
    public function addMassAction($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if(
            get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && $block->getRequest()->getControllerName() == 'sales_order'
          )
        {
            $block->addItem('shipmentmass', array(
                'label' => 'Mass Shipment',
                'url' => Mage::app()->getStore()->getUrl('shipmentmass/shipment/submit'),
            ));
        }
    }
}