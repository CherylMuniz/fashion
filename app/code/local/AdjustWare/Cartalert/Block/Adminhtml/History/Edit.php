<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Block/Adminhtml/History/Edit.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ hOhRhZoTmaaoQPgY('3af4bb7f722dbc67a71ed7462e12ba80'); ?><?php

class AdjustWare_Cartalert_Block_Adminhtml_History_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id'; // ?
        $this->_blockGroup = 'adjcartalert';
        $this->_controller = 'adminhtml_history';

        $this->_removeButton('reset');
        $this->_removeButton('save');
    }

    public function getHeaderText()
    {
            return Mage::helper('adjcartalert')->__('Sent Alert');
    }
} } 