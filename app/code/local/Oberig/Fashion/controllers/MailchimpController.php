<?php
require_once('app/code/local/Oberig/MCApi/MCAPI.class.php');

class Oberig_Fashion_MailchimpController extends Mage_Core_Controller_Front_Action
{
    //mailchimp api key
    private $_apiKey = 'df6f2a476062ca2df26e9e091d98866d-us2';
    //Prospect list id
    private $prospectListId = '89b47cfe7e';
    //Customer list id
    private $customerListId = '2178420525';
    
    public function addAction()
    {
        $api = new MCAPI($this->_apiKey);
        $email = $this->getRequest()->getParam('email');
        $retval = $api->lists();
         
        if ($api->errorCode){
                Mage::getSingleton('core/session')->addError($this->__('Error: %s', $api->errorMessage));
        } else {
            /*foreach($retval['data'] as $val) {
                if ($val['name'] === "Prospects") {
                    $prospectListId = $val['id'];
                } elseif ($val['name'] === "Fashion Eyewear Customers") {
                    $customerListId = $val['id'];
                }
            }*/

            $customers = Mage::getModel('customer/customer')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('email', $email)->getData();
                        
            $customerId = $customers[0]['entity_id'];
            
                
            $orders = Mage::getResourceModel('sales/order_collection')
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', $customerId)->getData();
            
            if (!empty($orders)) {
                //add to Customer List
                $listId = $this->customerListId;
            }
            else {
                //add to Prospect list
                $listId = $this->prospectListId;
            }
            $merge_vars = array('FNAME'=> $this->getRequest()->getParam('firstname'));
            
            if ($api->listSubscribe($listId, $email, $merge_vars) === true) {
                Mage::getSingleton('core/session')->addSuccess($this->__('Success! Check your email to confirm sign up.'));
            }else{
                 Mage::getSingleton('core/session')->addError($this->__('Error: %s', $api->errorMessage));
            }
        }
        $this->_redirectReferer();
    }
}