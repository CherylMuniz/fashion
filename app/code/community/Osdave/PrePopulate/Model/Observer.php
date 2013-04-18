<?php
class Osdave_PrePopulate_Model_Observer
{
    const MODULE_NAME = 'Osdave_PrePopulate';
    
    public function addUpdateOrderCommentsBlock($observer = null)
    {
        if (!$observer) {
            return;
        }
        
        if ('order_history' == $observer->getEvent()->getBlock()->getNameInLayout()) {
            if (!Mage::getStoreConfig('advanced/modules_disable_output/' . self::MODULE_NAME)) {
                $transport = $observer->getEvent()->getTransport();
                
                $block = Mage::app()->getLayout()
                                    ->createBlock('prepopulate/adminhtml_order_messages');
                $block->setPassingTransport($transport['html']);
                $block->setTemplate('prepopulate/order/messages.phtml')
                      ->toHtml();
                
                
            }
        }
        
        return $this;
    }
}