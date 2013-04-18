<?php
class Osdave_PrePopulate_Helper_Data extends Mage_Adminhtml_Helper_Data
{
    public function getPrePopulateMessagesUrl()
    {
        return $this->getUrl('prepopulate/adminhtml_prepopulate/getMessages');
    }
}