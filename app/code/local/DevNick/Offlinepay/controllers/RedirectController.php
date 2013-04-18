<?php
class DevNick_Offlinepay_RedirectController extends Mage_Core_Controller_Front_Action
{  
    public function indexAction()
    {
         $this->getResponse()->setBody($this->getLayout()->createBlock('offlinepay/redirect')->toHtml());
    }
}