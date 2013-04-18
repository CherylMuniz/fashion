<?php

class Oberig_Lightimportexport_Adminhtml_ExportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {	
		$this->loadLayout();
		$this->renderLayout();	
    }
	
    public function exportAction()
    {
        $data = $this->getRequest()->getPost();

        if (!empty($data)) {
            try {
            	if(isset($data['attr'])){
	            	$lightExport = Mage::getModel('lightimportexport/export');
					$res = $lightExport->generateCSV($data);
	
	                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lightimportexport')->__('Products were successfully exported to file'));            		
            	}
            	else{
            		Mage::getSingleton('adminhtml/session')->addError($this->__('Please select attributes'));
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