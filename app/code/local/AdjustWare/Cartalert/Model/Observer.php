<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Model/Observer.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ hOhRhZoTmaaoQPgY('1deb7da78dfc4df81a3119aef79f077b'); ?><?php
/**
 * Cartalert module observer
 *
 * @author Adjustware
 */
class Adjustware_Cartalert_Model_Observer
{
    public function createCartalerts()
    {
        $cartalert = Mage::getModel('adjcartalert/cartalert');
        $cartalert->generate(date('Y-m-d H:i:s'));
        
        //added in 1.2.1
        $this->sendCartalerts();
        
        return $this;
    }

    public function sendCartalerts()
    {
        if (!Mage::getStoreConfig('catalog/adjcartalert/sending_enabled'))
            return $this;
        $collection = Mage::getModel('adjcartalert/cartalert')->getCollection()
            ->addReadyForSendingFilter() 
            ->setPageSize(50)
            ->setCurPage(1)
            ->load();
        foreach ($collection as $cartalert){
            if ($cartalert->send()){
                $cartalert->delete(); 
            } 
        }  
        return $this;
    }
    
    public function processOrderCreated($observer){
        $order = $observer->getEvent()->getOrder(); 
        
        if (Mage::getStoreConfig('catalog/adjcartalert/stop_after_order')){
            $cartalert = Mage::getResourceModel('adjcartalert/cartalert')
                ->cancelAlertsFor($order->getCustomerEmail());
        }
        return $this;

    } 
    
    public function updateAlertsStatus($observer)
    {
    	if (!Mage::registry('alerts_status_updated'))
    	{
    		Mage::register('alerts_status_updated', true);
    		
			$quote = Mage::getSingleton('checkout/session')->getQuote();
			
			if ($quote)
			{
				$quote->setAllowAlerts(1);
				
				if (Mage::getStoreConfig('catalog/adjcartalert/stop_after_order')){
		            $cartalert = Mage::getResourceModel('adjcartalert/cartalert')
		                ->cancelAlertsFor($quote->getCustomerEmail());
		        }
			}
    	}
		
        return $this;
    }
} } 