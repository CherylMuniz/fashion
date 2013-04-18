<?php
/**
 * Magento Mad Capsule Media Dpd Extension
 * http://www.madcapsule.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2009 Mad Capsule Media (http://www.madcapsule.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     James Mikkelson <james@madcapsule.co.uk>
*/
class MadCapsule_Dpd_Adminhtml_DpdController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('dpd/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Consignment Manager'), Mage::helper('adminhtml')->__('Consignment Manager'));
        return $this;
    }   
   
    public function indexAction() {
        $this->_initAction();       
        $this->_addContent($this->getLayout()->createBlock('dpd/adminhtml_dpd'));
        $this->renderLayout();
    }
 
    public function resubmitAction()
    {
        $dpdId     = $this->getRequest()->getParam('id');
        if ($dpdId > 0) {
 
		$dpdModel = Mage::getModel('dpd/consignment');
                $dpdModel->setId($this->getRequest()->getParam('id'))
                    ->setStatus(1)
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Consignment Requeued.'));
                $this->_redirect('*/*/', array('id' => $this->getRequest()->getParam('id')));
               
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dpd')->__('Consignment does not exist'));
            $this->_redirect('*/*/');
        }
    }
   
    public function newAction()
    {
        $this->_forward('resubmit');
    }
   
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $dpdModel = Mage::getModel('dpd/consignment');
               
                $dpdModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                   
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Consignment has been deleted from Magento ONLY. This consignment may have already reached DPD.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/resubmit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('importresubmit/adminhtml_dpd_grid')->toHtml()
        );
    }
}
