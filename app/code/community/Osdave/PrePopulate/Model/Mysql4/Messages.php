<?php
class Osdave_PrePopulate_Model_Mysql4_Messages extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the message_id refers to the key field in your database table.
        $this->_init('prepopulate/messages', 'message_id');
    }
}