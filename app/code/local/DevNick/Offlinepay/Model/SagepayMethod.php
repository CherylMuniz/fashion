<?php

class DevNick_Offlinepay_Model_SagePayMethod extends Mage_Payment_Model_Method_Checkmo
{
    protected $_code = 'offsagepay';
 
    protected $_isGateway = true;

    protected $_canAuthorize = false;
 
    protected $_canCapture = false;
 
    protected $_canCapturePartial = false;
 
    protected $_canRefund = false;
 
    protected $_canVoid = false;
 
    protected $_canUseInternal = true;

    protected $_canUseCheckout = false;
 
    protected $_canUseForMultishipping = true;
 
    protected $_canSaveCc = false;

}