<?php
class Osdave_PrePopulate_Block_Adminhtml_Order_Messages extends Mage_Adminhtml_Block_Template
{
    private $_passedTransportHtml;
    
    /**
     * Get admin defined messages
     * 
     * @return <select>
     */
    public function getMessages()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => 'prepopulated_messages_dropdown',
                'class' => 'select'
            ))
            ->setName('prepopulated_messages_dropdown')
            ->setOptions(Mage::getResourceModel('prepopulate/messages_collection')->toOptionArray());

        return $select->getHtml();
    } 
    
    public function setPassingTransport($transport)
    {
        $this->_passedTransportHtml = $transport;
    }
    
    public function getPassedTransport()
    {
        return $this->_passedTransportHtml;
    }
}