<?php
class Oberig_Fashion_Model_Mailchimp extends Mage_Catalog_Model_Abstract
{
    private $_apiKey = 'df6f2a476062ca2df26e9e091d98866d-us2';
    
    public function moveSubscriber()
    {
        $api = new MCAPI($this->_apiKey);
        
        $retval = $api->lists();
         
        if ($api->errorCode){
                Mage::getSingleton('core/session')->addError($this->__('Error: %s', $api->errorMessage));
        } else {
            foreach($retval['data'] as $val) {
                if ($val['name'] === "Prospects") {
                    $prospectListId = $val['id'];
                }
                elseif ($val['name'] === "Fashion Eyewear Customers") {
                    $customerListId = $val['id'];
                }
            }
            
            if ($api->errorCode!=''){
            // an error occurred while logging in
            Mage::log('Error Code: '.$api->errorCode);
            Mage::log('Error Message: '.$api->errorMessage);
            die();
        }
        
            $retval = $api->listMembers($prospectListId , 'subscribed', null, 0, 10000);
            //var_dump($retval);die;
            if (!$retval){
                Mage::log('Error Code: '.$api->errorCode);
                Mage::log('Error Message: '.$api->errorMessage);
            } else {
                foreach($retval['data'] as $member){
                    //echo $member['email']." - ".$member['timestamp']."<br />";
                    $customerModel = Mage::getModel('customer/customer')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('email', $member['email'])
                        ->setPage(1, 1);
                    $customers = $customerModel->getData();
                        
                    $customerId = $customers[0]['entity_id'];
                    
                        
                    $orders = Mage::getResourceModel('sales/order_collection')
                            ->addFieldToSelect('*')
                            ->addFieldToFilter('customer_id', $customerId)->getData();
                    
                    if (!empty($orders)) {
                        //if ($member['email'] === 'ruffian.ua@gmail.com') {
                            $memInfo = $api->listMemberInfo($prospectListId, array($member['email']));
                            $firstname = $memInfo['data'][0]['merges']['FNAME'];
                            
                            if (empty($firstname)) {
                                $firstname = Mage::getModel('customer/customer')->load($customerId)->getFirstname();
                            }
                            $retuns = $api->listUnsubscribe($prospectListId, $member['email'], 1);
                            if (!$retuns){
                                Mage::log('Unsubscribe Error Code: '.$api->errorCode);
                                Mage::log('Unsubscribe Error Message: '.$api->errorMessage);
                            }
                            $merge_vars = array('FNAME'=> $firstname);
                            $retsub = $api->listSubscribe($customerListId, $member['email'], $merge_vars );

                            if (!$retsub){
                                Mage::log('Subscribe Error Code: '.$api->errorCode);
                                Mage::log('Subscribe Error Message: '.$api->errorMessage);
                            }
                        //}
                    }
                }
            }
        }
    }
}