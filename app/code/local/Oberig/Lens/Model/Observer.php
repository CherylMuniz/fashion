<?php

class Oberig_Lens_Model_Observer
{
    const MODULE_NAME = 'Oberig_Lens';
 
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }
    
    public function deleteLens($observer = NULL)
    {
        if (!$observer) {
            return;
        }
        $item = $observer->getQuoteItem();
        $lensIds = null;                                                //can be two the same products with different lens
        $name = $item->getProduct()->getData('name'); 
        foreach($this->_getCart()->getItems() as $item2){
            if( in_array($item2->getProduct()->getData('sku'), array('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley')) )      //select only lens in cart
            {
                 foreach( $item2->getBuyRequest()->getData('options') as $option ){                                                                     //check options every lens and select lens_id for product name
                     if($option === $name){
                        $lensIds[] = $item2->getId();
                        break;
                     }
                 }
            }
        }
        if ($lensIds) {
            if(
                'cart' === Mage::app()->getFrontController()->getRequest()->getControllerName() &&
                'updateItemOptions' === Mage::app()->getFrontController()->getRequest()->getActionName()
            ){ return; }
            foreach($lensIds as $lensId){
                try {
                    $this->_getCart()->removeItem($lensId)
                      ->save();
                } catch (Exception $e) {
                    $this->_getSession()->addError($this->__('Cannot remove the item.'));
                    Mage::logException($e);
                }
            }
        }

        return $this;
    }
}