<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancednewsletter
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
class AW_AdvancedNewsletter_Model_Source_Mailchimplist
{
    public function toOptionArray()
    {
        $store = null;
        if (Mage::app()->getRequest()->getParam('store'))
        {
            $store = Mage::app()->getRequest()->getParam('store');
        }
        else if (Mage::app()->getRequest()->getParam('website'))
        {
            $store = Mage::app()->getWebsite(Mage::app()->getRequest()->getParam('website'))->getDefaultStore()->getId();
        }

        $xmlrpcurl = Mage::getStoreConfig('advancednewsletter/mailchimpconfig/xmlrpc', $store);
        $apikey = Mage::getStoreConfig('advancednewsletter/mailchimpconfig/apikey', $store);

        if(!$apikey||!$xmlrpcurl) return array();
        
        if(substr($apikey, -4) != '-us1' && substr($apikey, -4) != '-us2'){
            Mage::getSingleton('adminhtml/session')->addError('The API key is not well formed');
            return '';
        }

        try {

			list($key, $dc) = explode('-',$apikey,2);
			if (!$dc) $dc = 'us1';
			list($aux, $host) = explode('http://',$xmlrpcurl);
			$api_host = 'http://'.$dc.'.'.$host;

			$client = new Zend_XmlRpc_Client($api_host);

			$lists = '';
        
            $lists = $client->call('lists', $apikey);
        } catch( Exception $e ) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        if(!is_array($lists)) return '';

        $options = array('label'=>'Select a list..');
        foreach ($lists as $list)
        {
            $options[] = array('value'=>$list['id'],
                                        'label'=>$list['name']);
        }

        return $options;
    }
}