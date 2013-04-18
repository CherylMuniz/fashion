<?php
class DevNick_Rotate360_Model_Img360 extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('rotate360/img360');
    }
}