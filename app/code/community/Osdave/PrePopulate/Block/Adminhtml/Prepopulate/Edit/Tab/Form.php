<?php
class Osdave_PrePopulate_Block_Adminhtml_Prepopulate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('prepopulate_form', array('legend'=>Mage::helper('prepopulate')->__('Message information')));
       
        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('prepopulate')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
		
        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('prepopulate')->__('Status'),
            'name'      => 'status',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('prepopulate')->__('Enabled'),
                ),

                array(
                    'value'     => 2,
                    'label'     => Mage::helper('prepopulate')->__('Disabled'),
                ),
            ),
        ));
        
        $orderStatuses = Mage::getSingleton('sales/order_config')->getStatuses();
        $fieldset->addField('order_status', 'select', array(
            'label'     => Mage::helper('prepopulate')->__('Status'),
            'name'      => 'order_status',
            'values'    => $orderStatuses
        ));
        
        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => Mage::helper('prepopulate')->__('Content'),
            'title'     => Mage::helper('prepopulate')->__('Content'),
            'style'     => 'width:700px; height:100px;',
            'required'  => true,
        ));
       
        if (Mage::getSingleton('adminhtml/session')->getPrepopulateData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPrepopulateData());
            Mage::getSingleton('adminhtml/session')->setPrepopulateData(null);
        } elseif (Mage::registry('prepopulate_data')) {
            $form->setValues(Mage::registry('prepopulate_data')->getData());
        }
        
        return parent::_prepareForm();
    }
}