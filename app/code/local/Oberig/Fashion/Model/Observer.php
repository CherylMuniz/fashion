<?php

class Oberig_Fashion_Model_Observer extends Mage_Core_Block_Abstract 
{
    public function placeOrder()
    {
        Mage::getSingleton('core/session')->setAdminOrderFlag(true);
    }
}

