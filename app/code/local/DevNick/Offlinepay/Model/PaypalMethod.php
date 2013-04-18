<?php

class DevNick_Offlinepay_Model_PaypalMethod extends Mage_Payment_Model_Method_Checkmo
{
    protected $_code = 'offpaypal';
 
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