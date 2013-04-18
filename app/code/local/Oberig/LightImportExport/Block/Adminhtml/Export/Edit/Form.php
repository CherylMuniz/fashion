<?php

class Oberig_LightImportExport_Block_Adminhtml_Export_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form before rendering HTML.
     *
     * @return Mage_ImportExport_Block_Adminhtml_Export_Edit_Form
     */
    protected function _prepareForm()
    {
		//$entityType = Mage::registry('product')->getResource()->getEntityType();
        $helper = Mage::helper('lightimportexport');
        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/export'),
            'method' => 'post'
        ));
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $helper->__('Export Settings')));
        $fieldset->addField('attribute_set_id', 'select', array(
            'name'     => 'attribute_set_id',
            'title'    => $helper->__('Attribute Set'),
            'label'    => $helper->__('Attribute Set'),
            'required' => true,
			//Mage::getModel('eav/entity_attribute_set')
            'values'   => $helper->getEntityAttributeSetValues()
        ));

        $lightConfig = Mage::getModel('lightimportexport/config');
        $aCheckbox = $lightConfig->getCheckboxArr();
        
        $fieldsetAttr = $form->addFieldset('attr_fieldset', array('legend' => $helper->__('Export Attributes')));
        foreach($aCheckbox as $checkboxConf){
        	$elementId = $checkboxConf['name'];
        	$checkboxConf['value'] = $elementId;
        	$checkboxConf['name'] = 'attr[]';
        	
        	$fieldsetAttr->addField($elementId, 'checkbox', $checkboxConf);                	
        }        
        
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
