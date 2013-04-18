<?php
require_once 'Mage/Checkout/controllers/CartController.php';
/**
 * Shopping cart controller
 */
class Oberig_Lens_CartController extends Mage_Checkout_CartController
{
    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        if( $_SERVER['REMOTE_ADDR'] == '62.80.160.230') { echo '123'; die; }

        $id = (int) $this->getRequest()->getParam('id');
        if( $_SERVER['REMOTE_ADDR'] == '62.80.160.230') {
            $lensId = null;
            foreach($this->_getCart()->getItems() as $item){
                
                if( (int) $item->getId() === $id ){                     //select removable frame item
                    $name = $item->getProduct()->getData('name'); 
                    foreach($this->_getCart()->getItems() as $item2){
                        if( in_array($item2->getProduct()->getData('sku'), array('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley')) )      //select only lens in cart
                        {
                             foreach( $item2->getBuyRequest()->getData('options') as $option ){                                                                     //check options every lens and select lens_id for product name
                                 if($option === $name){
                                    $lensId = $item2->getId();
                                    break;
                                 }
                             }
                        }
                    }
                }
            }
        }
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }
        if ($lensId) {
            try {
                $this->_getCart()->removeItem($lensId)
                  ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }

        $this->_redirectReferer(Mage::getUrl('*/*'));
    }
}
