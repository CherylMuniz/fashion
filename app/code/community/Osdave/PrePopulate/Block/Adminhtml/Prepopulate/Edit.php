<?php

class Osdave_PrePopulate_Block_Adminhtml_Prepopulate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'prepopulate';
        $this->_controller = 'adminhtml_prepopulate';
        
        $this->_updateButton('save', 'label', Mage::helper('prepopulate')->__('Save Message'));
        $this->_updateButton('delete', 'label', Mage::helper('prepopulate')->__('Delete Message'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('prepopulate_data') && Mage::registry('prepopulate_data')->getId() ) {
            return Mage::helper('prepopulate')->__("Edit Message '%s'", $this->htmlEscape(Mage::registry('prepopulate_data')->getTitle()));
        } else {
            return Mage::helper('prepopulate')->__('Create New Message');
        }
    }
}