<?php

class Oberig_Fashion_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }

        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));

        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));

        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));

		//
		$this->getMassactionBlock()->addItem('awaiting_customer', array(
             'label'=> Mage::helper('sales')->__('-- Awaiting Customer Reply'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'awaiting_customer')),
        ));
		$this->getMassactionBlock()->addItem('awaiting_frame', array(
             'label'=> Mage::helper('sales')->__('-- Awaiting frame'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'awaiting_frame')),
        ));
		$this->getMassactionBlock()->addItem('awaiting_frame_amended', array(
             'label'=> Mage::helper('sales')->__('-- Awaiting frame (amended)'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'awaiting_frame_amended')),
        ));
		$this->getMassactionBlock()->addItem('awaiting_lenses', array(
             'label'=> Mage::helper('sales')->__('-- Awaiting lenses'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'awaiting_lenses')),
        ));
		$this->getMassactionBlock()->addItem('frame_arrived', array(
             'label'=> Mage::helper('sales')->__('-- Frame arrived'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'frame_arrived')),
        ));
		$this->getMassactionBlock()->addItem('lenses_arrived', array(
             'label'=> Mage::helper('sales')->__('-- Lenses arrived'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'lenses_arrived')),
        ));
		$this->getMassactionBlock()->addItem('pending', array(
             'label'=> Mage::helper('sales')->__('-- Pending'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'pending')),
        ));
		$this->getMassactionBlock()->addItem('refunded', array(
             'label'=> Mage::helper('sales')->__('-- Refunded'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'refunded')),
        ));
		$this->getMassactionBlock()->addItem('review', array(
             'label'=> Mage::helper('sales')->__('-- Review'),
             'url'  => $this->getUrl('oberigfashion/order/mass/', array('status' => 'review')),
        ));
		$this->getMassactionBlock()->addItem('delete_order', array(
				'label'=> Mage::helper('sales')->__('Delete order'),
				'url'  => $this->getUrl('*/sales_order/deleteorder'),
		));
		
		
        return $this;
    }
}
