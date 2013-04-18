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
 */class AW_Advancednewsletter_Model_Sync_Mailchimpclient implements AW_Advancednewsletter_Model_Sync_Interface
{
    /* Mailchimp configuration options */
    const MAILCHIMP_ENABLED = "advancednewsletter/mailchimpconfig/mailchimpenabled";
    const MAILCHIMP_AUTOSYNC = "advancednewsletter/mailchimpconfig/autosync";
    const MAILCHIMP_APIKEY = "advancednewsletter/mailchimpconfig/apikey";
    const MAILCHIMP_LISTID = "advancednewsletter/mailchimpconfig/listid";
    const MAILCHIMP_XMLRPC = "advancednewsletter/mailchimpconfig/xmlrpc";
    
    /**
     * Force autosync enable/disable variable
     * @var boolean
     */
    public static $disableAutosync = false;
    /**
     * Instance
     * @var AW_Advancednewsletter_Model_Sync_Mailchimpclient 
     */
    protected static $_instance;

    protected $_client;
    protected $_clientStoreId;
    protected $_keysValues;
    protected $_segmentsLoaded = false;
    protected $_skipChangesCheck = false;
    

    private function __construct(){}

    public function getSkipChangesCheck()
    {
        return $this->_skipChangesCheck;
    }

    public function setSkipChangesCheck($_skipChangesCheck)
    {
        $this->_skipChangesCheck = $_skipChangesCheck;
        return $this;
    }

    public function getIncludeNames()
    {
        return $this->_includeNames;
    }

    public function setIncludeNames($includeNames)
    {
        $this->_includeNames = $includeNames;
        return $this;
    }

    public function getStoreId()
    {
        return $this->_clientStoreId;
    }

    public static function getInstance($storeId)
    {
        $createNewInstance = true;
        if (self::$_instance)
        {
            if (self::$_instance->_client && self::$_instance->_clientStoreId == $storeId) $createNewInstance = false;
        }
        
        if ($createNewInstance)
        {
            $instance = new self;
            $instance->setKeys($storeId);
            try
            {
                $instance->_client = new Zend_XmlRpc_Client($instance->_connect());
            } catch (Exception $e)
            {
                throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Couldn\'t connect to MailChimp'));
            }
            $instance->_clientStoreId = $storeId;
            self::$_instance = $instance;
        }

        return self::$_instance;
    }

    public function subscribe($subscriber)
    {
        if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_NOTACTIVE)
            return;

        $merges = $this->checkAndGetMerges($subscriber);

        $this->loadSegments();
        try
        {
            /*
             * Mailchimp API 1.2
             * listSubscribe(string apikey, string id, string email_address, array merge_vars, string email_type, boolean double_optin, boolean update_existing, boolean replace_interests, boolean send_welcome)
             */
            $this->_client->call('listSubscribe',
                array(
                    $this->_keysValues[self::MAILCHIMP_APIKEY],
                    $this->_keysValues[self::MAILCHIMP_LISTID],
                    $subscriber->getData('email'),
                    $merges,
                    'html',
                    null,
                    true,
                    true,
                    false)
                );
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    public function unsubscribe($subscriber)
    {
        $subscriberSegments = $subscriber->getData('segments_codes');
        if (empty($subscriberSegments))
            $this->unsubscribeFromList($subscriber);
        else
            $this->subscribe($subscriber);
        return $this;
    }

    public function unsubscribeFromList($subscriber)
    {
        try
        {
            /*
             * Mailchimp API 1.2
             * listUnsubscribe(string apikey, string id, string email_address, boolean delete_member, boolean send_goodbye, boolean send_notify)
             */
            $this->_client->call('listUnsubscribe',
                    array(
                        $this->_keysValues[self::MAILCHIMP_APIKEY],
                        $this->_keysValues[self::MAILCHIMP_LISTID],
                        $subscriber->getEmail(),
                        false,
                        false,
                        false)
                    );
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    public function delete($subscriber, $deleteFromOriginalData = false)
    {
        if ($deleteFromOriginalData) $email = $subscriber->getOrigData('email');
        else $email = $subscriber->getData('email');

        try
        {
            /*
             * Mailchimp API 1.2
             * listUnsubscribe(string apikey, string id, string email_address, boolean delete_member, boolean send_goodbye, boolean send_notify)
             */
            $this->_client->call('listUnsubscribe',
                array(
                    $this->_keysValues[self::MAILCHIMP_APIKEY],
                    $this->_keysValues[self::MAILCHIMP_LISTID],
                    $email,
                    true,
                    false,
                    false)
                );
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    protected function loadSegments()
    {
        if (!$this->_segmentsLoaded)
        {
            try
            {
                $groups = $this->_client->call('listInterestGroups',
                    array(
                        $this->_keysValues[self::MAILCHIMP_APIKEY],
                        $this->_keysValues[self::MAILCHIMP_LISTID])
                    );
            } catch (Zend_XmlRpc_Client_FaultException $ex)
            {
                throw new AW_Core_Exception($ex->getMessage());
            }

            $segments = Mage::getModel('advancednewsletter/segment')->getCollection();
            foreach ($segments as $segment)
            {
                try
                {
                    if (isset($groups['groups']))
                    {
                        if (!in_array($segment->getCode(), $groups['groups']))
                            $this->_client->call('listInterestGroupAdd',
                                array(
                                    $this->_keysValues[self::MAILCHIMP_APIKEY],
                                    $this->_keysValues[self::MAILCHIMP_LISTID],
                                    $segment->getCode())
                            );
                    }
                    else
                    {
                        $this->_client->call('listInterestGroupAdd',
                            array(
                                $this->_keysValues[self::MAILCHIMP_APIKEY],
                                $this->_keysValues[self::MAILCHIMP_LISTID],
                                $segment->getCode())
                            );
                    }
                } catch (Exception $ex)
                { }
            }
            $this->_segmentsLoaded = true;
        }
        return $this;
    }

    public function removeSegment($segmentCode)
    {
        try
        {
            $this->_client->call('listInterestGroupDel',
                array(
                    $this->_keysValues[self::MAILCHIMP_APIKEY],
                    $this->_keysValues[self::MAILCHIMP_LISTID],
                    $segmentCode)
                );
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
    }

    public function getChimpGroupings()
    {
        try
        {
            $chimpGroupings = $this->_client->call('listInterestGroupings',
                array(
                    $this->_keysValues[self::MAILCHIMP_APIKEY],
                    $this->_keysValues[self::MAILCHIMP_LISTID]
                ));
        } catch (Exception $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $chimpGroupings;
    }

    public function forceWrite($subscriber)
    {
        /**
         * If subscriber status = unsubscribed
         */
        if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
        {
            /**
             * If subscribers store or email changed, remove subscriber from list and subscribe new one
             */
            if (
                $subscriber->getOrigData('store_id') != $subscriber->getData('store_id') ||
                $subscriber->getOrigData('email') != $subscriber->getData('email')
            )
            {
                $instance = self::getInstance($subscriber->getOrigData('store_id'));
                $instance->delete($subscriber, true);
                $this->subscribe($subscriber->setIsNew(true));
            }
            $this->unsubscribeFromList($subscriber);
        }

        /**
         * If subscriber status = subscribed
         */
        if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED)
            $this->subscribe($subscriber);
        return $this;
    }

    public function getRecords($type, $currentPage, $pageSize)
    {
        $records = array();

        $records['subscribers'] = $this->_client->call('listMembers',
            array(
                $this->_keysValues[self::MAILCHIMP_APIKEY],
                $this->_keysValues[self::MAILCHIMP_LISTID],
                'subscribed',
                null,
                $currentPage,
                $pageSize)
            );
        
        $records['unsubscribers'] = $this->_client->call('listMembers',
            array(
                $this->_keysValues[self::MAILCHIMP_APIKEY],
                $this->_keysValues[self::MAILCHIMP_LISTID],
                'unsubscribed',
                null,
                $currentPage,
                $pageSize)
            );

        if ($type == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST)
        {
            foreach ($records['subscribers'] as $key => $subscriber)
            {
                $records['subscribers'][$key] = $this->_client->call('listMemberInfo',
                    array(
                        $this->_keysValues[self::MAILCHIMP_APIKEY],
                        $this->_keysValues[self::MAILCHIMP_LISTID],
                        $subscriber['email'])
                    );
            }
            foreach ($records['unsubscribers'] as $key => $unsubscriber)
            {
                $records['unsubscribers'][$key] = $this->_client->call('listMemberInfo',
                    array(
                        $this->_keysValues[self::MAILCHIMP_APIKEY],
                        $this->_keysValues[self::MAILCHIMP_LISTID],
                        $unsubscriber['email'])
                    );
            }
        }
        
        return $records;
    }

    protected function checkAndGetMerges($subscriber)
    {
        $merges = array();
        if (!$this->getSkipChangesCheck())
        {
            $namesChanged = $segmentsChanged = $storeChanged = $emailChanged = false;

            if ($subscriber->getOrigData('store_id') != $subscriber->getData('store_id'))
            {
                $storeChanged = true;
            }
            if ($subscriber->getOrigData('email') != $subscriber->getData('email'))
            {
                $emailChanged = true;
            }
            if ($subscriber->getOrigData('first_name') != $subscriber->getData('first_name') ||
                $subscriber->getOrigData('last_name') != $subscriber->getData('last_name'))
            {
                $namesChanged = true;
            }

            $subscriberOldSegments = implode(',', $subscriber->getOrigData('segments_codes'));
            $subscriberCurrentSegments = implode(',', $subscriber->getData('segments_codes'));
            if ($subscriberOldSegments != $subscriberCurrentSegments)
            {
                $segmentsChanged = true;
            }

            if ($subscriber->getIsNew())
            {
                $storeChanged = $emailChanged = false;
                $namesChanged = $segmentsChanged = true;
            }
            /*
             * If subscriber store id changed, we remove it from his previous list and set
             * $namesChanged and $segmentsChanged to true to upload this params to this customer
             * in the new list
             */
            if ($storeChanged || $emailChanged)
            {
                $instance = self::getInstance($subscriber->getOrigData('store_id'));
                $instance->delete($subscriber, true);
                $namesChanged = $segmentsChanged = true;
            }

            if ($segmentsChanged)
            {
                $merges['INTERESTS'] = $subscriberCurrentSegments ;
            }

            if ($namesChanged)
            {
                $merges['FNAME'] = $subscriber->getData('first_name');
                $merges['LNAME'] = $subscriber->getData('last_name');
            }
        } else
        {
            $merges['INTERESTS'] = $subscriber->getData('segments_codes');
            if ($this->getIncludeNames())
            {
                $merges['FNAME'] = $subscriber->getData('first_name');
                $merges['LNAME'] = $subscriber->getData('last_name');
            }
        }

        return $merges;
    }

    protected function setKeys($storeId)
    {
        $keys = array(
            self::MAILCHIMP_ENABLED,
            self::MAILCHIMP_AUTOSYNC,
            self::MAILCHIMP_APIKEY,
            self::MAILCHIMP_LISTID,
            self::MAILCHIMP_XMLRPC
        );

        /* AW_Core_Exception exceptions logs into aw_core_logger table, usual exceptions doesn't loging */
        $keys_values = Mage::helper('advancednewsletter')->getSettings($keys, $storeId, true);
        if (isset($keys_values[$storeId]))
            $this->_keysValues = $keys_values[$storeId];
        else
        {
            throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Unknown Store Id'));
        }

        if (!$this->_keysValues[self::MAILCHIMP_APIKEY] || !$this->_keysValues[self::MAILCHIMP_XMLRPC])
        {
            throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Apikey or xmlrpc url are inncorrect'));
        }

        if (!$this->_keysValues[self::MAILCHIMP_ENABLED])
        {
            throw new Exception(Mage::helper('advancednewsletter')->__('MailChimp is disabled for store %s', $storeId));
        }

        if ((!$this->_keysValues[self::MAILCHIMP_AUTOSYNC] && AW_Advancednewsletter_Model_Cron::$_massSyncFlag == false) || self::$disableAutosync)
        {
            throw new Exception(Mage::helper('advancednewsletter')->__('MailChimp auto-sync is disabled for store %s', $storeId));
        }
    }

    public function getKeys()
    {
        return $this->_keysValues;
    }

    protected function _connect()
    {
        $apikey = $this->_keysValues[self::MAILCHIMP_APIKEY];
        $xmlrpcurl = $this->_keysValues[self::MAILCHIMP_XMLRPC];

        if (substr($apikey, -4) != '-us1' && substr($apikey, -4) != '-us2')
            throw new Exception;

        list($key, $dc) = explode('-', $apikey, 2);
        if (!$dc)
            $dc = 'us1';
        list($aux, $host) = explode('http://', $xmlrpcurl);
        $api_host = 'http://' . $dc . '.' . $host;

        return $api_host;
    }
}