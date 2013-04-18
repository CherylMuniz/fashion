<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/controllers/Adminhtml/HistoryController.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ CVCQwapRMrrpNWBS('df8624bb1d5eace1dbb60e3c5605dddf'); ?><?php

class AdjustWare_Cartalert_Adminhtml_HistoryController extends Mage_Adminhtml_Controller_action
{
	public function indexAction() {
	    $this->loadLayout(); 
        $this->_setActiveMenu('newsletter/adjcartalert/history');
        $this->_addBreadcrumb($this->__('Carts Alerts'), $this->__('History')); 
        $this->_addContent($this->getLayout()->createBlock('adjcartalert/adminhtml_history')); 	    
 	    $this->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('adjcartalert/history')->load($id);

		if (!$model->getId()) {
		    $this->_redirect('*/*/');
		}
    	Mage::register('history_data', $model);

		$this->loadLayout();
		$this->_setActiveMenu('newsletter/adjcartalert/history');
        $this->_addContent($this->getLayout()->createBlock('adjcartalert/adminhtml_history_edit'));
		$this->renderLayout();
	}
 
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('cartalert');
        if (!is_array($ids)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adjcartalert')->__('Please select cartalert(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('adjcartalert/history')->load($id);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function deleteAction() {
		if ($this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('adjcartalert/history');
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adjcartalert')->__('Alert has been deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
	
} } 