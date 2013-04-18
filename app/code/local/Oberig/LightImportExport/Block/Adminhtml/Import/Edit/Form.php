<?php

class Oberig_LightImportExport_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form before rendering HTML.
     *
     * @return Mage_ImportExport_Block_Adminhtml_Export_Edit_Form
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('lightimportexport');
        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/import'),
            'method' => 'post',
			'enctype' => 'multipart/form-data'
        ));
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $helper->__('Import Settings')));
        $fieldset->addField(Oberig_LightImportExport_Model_Import::FIELD_NAME_SOURCE_FILE, 'file', array(
            'name'     => Oberig_LightImportExport_Model_Import::FIELD_NAME_SOURCE_FILE,
            'label'    => $helper->__('Select File to Import'),
            'title'    => $helper->__('Select File to Import'),
            'required' => true
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
