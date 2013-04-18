<?php

class Oberig_Lightimportexport_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {	
    	
		$this->loadLayout();
		$this->renderLayout();	
    }
	
    public function importAction()
    {
		set_time_limit(0);
		ini_set('memory_limit','512M');		    	

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);		
		
		$sFileName = Oberig_LightImportExport_Model_Import::FIELD_NAME_SOURCE_FILE;
	
        $data = $this->getRequest()->getPost();
        if (!empty($data)) {
            try {
				if(isset($_FILES[$sFileName]['name']) and (file_exists($_FILES[$sFileName]['tmp_name']))) {
					$lightImport = Mage::getModel('lightimportexport/import');
					$res = $lightImport->importCSV($_FILES[$sFileName]['tmp_name']);
					if($res){
						$aErrorLines = $lightImport->getErrorLines();
						$aUpdateProducts = $lightImport->getUpdateProducts();
						
						if(!count($aUpdateProducts)){
							Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('lightimportexport')->__('File does not contain new data. Please upload another one'));
						}
						else{
							Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lightimportexport')->__('Products were successfully updated;  Changes total: ').count($aUpdateProducts));	
						}							
						if(count($aErrorLines)){
							Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('lightimportexport')->__('Invalid rows: ').implode(', ',$aErrorLines));
						}	
					}
					else{
						Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('lightimportexport')->__('File is totally invalid. Please fix errors and re-upload file'));
					}
					
				}
				else{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lightimportexport')->__('Please enter the file'));
				}								                
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Somethings went wrong'));
            }
        }
        return $this->_redirect('*/*/');    	    	    	
    }
    
}