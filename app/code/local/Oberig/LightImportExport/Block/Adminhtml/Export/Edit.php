<?php

class Oberig_LightImportExport_Block_Adminhtml_Export_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
	
        parent::__construct();
		
        $this->removeButton('back')
            ->removeButton('reset')
            ->_updateButton('save', 'label', $this->__('Export Data'))
            ->_updateButton('save', 'id', 'upload_button')
            ->_updateButton('save', 'onclick', 'editForm.submit();');			
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId   = 'export_id';
        //$this->_blockGroup = 'importexport';
		$this->_blockGroup = 'lightimportexport';
        $this->_controller = 'adminhtml_export';
    }

    /**
     * Get header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('lightimportexport')->__('Export');
    }
}
