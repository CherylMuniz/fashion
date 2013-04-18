<?php
 
class DevNick_Rotate360_Model_Mysql4_Img360 extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('rotate360/img360', 'id');
    }
}