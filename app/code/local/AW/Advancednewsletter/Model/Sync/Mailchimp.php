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
/**
 *  DEPRICATED. Remove this class in future
 */
class AW_Advancednewsletter_Model_Sync_Mailchimp implements AW_Advancednewsletter_Model_Sync_Interface
{
    /* Mailchimp configuration options */
    const MAILCHIMP_ENABLED = "advancednewsletter/mailchimpconfig/mailchimpenabled";
    const MAILCHIMP_AUTOSYNC = "advancednewsletter/mailchimpconfig/autosync";
    const MAILCHIMP_APIKEY = "advancednewsletter/mailchimpconfig/apikey";
    const MAILCHIMP_LISTID = "advancednewsletter/mailchimpconfig/listid";
    const MAILCHIMP_XMLRPC = "advancednewsletter/mailchimpconfig/xmlrpc";

    const SYNC_PAGE_SIZE = 20 ;

    protected $client;
    protected $clientStoreId;
    protected $keys_values = array();
    protected $skipChangesCheck = false;
    protected $includeNames = false;
    protected static $groups;

    /**
     * Return flag for skiping check for changes of customers data (names, store, etc)
     * @return bool
     */
    public function getSkipChangesCheck()
    {
        return $this->skipChangesCheck;
    }

    /**
     * Set flag for skiping check for customers data changing
     * @param bool $skipChangesCheck
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    public function setSkipChangesCheck($skipChangesCheck)
    {
        $this->skipChangesCheck = $skipChangesCheck;
        return $this;
    }

    /**
     * Getting flag for include names for sybscribers sync
     * @return <type> 
     */
    public function getIncludeNames()
    {
        return $this->includeNames;
    }

    /**
     * Setting flag for name inclusion to subscribers sync
     * @param bool $includeNames
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    public function setIncludeNames($includeNames)
    {
        $this->includeNames = $includeNames;
        return $this;
    }

    /**
     * Getting client for connect to Mailchimp according to storeId
     * @param int $storeId
     * @return Zend_XmlRpc_Client
     */
    protected function getClient($storeId)
    {
        if (!$this->client || $this->clientStoreId != $storeId)
        {
            $this->setKeys($storeId);
            try
            {
                $this->client = new Zend_XmlRpc_Client($this->connect());
            } catch (Exception $e)
            {
                throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Couldn\'t connect to MailChimp'));
            }
            $this->clientStoreId = $storeId;
        }
        return $this->client;
    }

    /**
     * Put subscriber to Mailchimp as subscribed
     * @param AW_Advancednewsletter_Model_Subscriber $subscriber
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    public function subscribe($subscriber)
    {
        if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_NOTACTIVE)
            return;
        $merges = $this->checkAndGetMerges($subscriber);
        $this->loadSegments($subscriber->getStoreId());
        try
        {
            $this
                ->getClient($subscriber->getStoreId())
                ->call('listSubscribe', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $subscriber->getData('email'), $merges, 'html', null, true, true, false));
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    /**
     * Unsubscribe subscriber from segments, set unsubscribe if his segments_codes are empty
     * @param AW_Advancednewsletter_Model_Subscriber $subscriber
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    public function unsubscribe($subscriber)
    {
        $subscriberSegments = $subscriber->getData('segments_codes');
        if (empty($subscriberSegments))
            $this->unsubscribeFromList($subscriber->getData('email'), $subscriber->getData('store_id'));
        else
            $this->subscribe($subscriber);
        return $this;
    }

    /**
     * Sync after forceWrite function execution at AW_Advancednewsletter_Model_Subscriber
     * @param AW_Advancednewsletter_Model_Subscriber $subscriber
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
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
                $this->deleteOptional($subscriber->getOrigData('email'), $subscriber->getOrigData('store_id'));
                $this->subscribe($subscriber->setIsNew(true));
            }
            $this->unsubscribeFromList($subscriber->getData('email'), $subscriber->getData('store_id'));
        }

        /**
         * If subscriber status = subscribed
         */
        if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED)
            $this->subscribe($subscriber);
        return $this;
    }

    /**
     * Deleting subscriber from Mailchimp
     * @param AW_Advancednewsletter_Model_Subscriber $subscriber
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    public function delete($subscriber)
    {
        $this->deleteOptional($subscriber->getEmail(), $subscriber->getStoreId());
        return $this;
    }

    /**
     * Removing segment from Mailchimp
     * @param string $segmentCode 
     */
    public function removeSegment($segmentCode)
    {
        try
        {
            foreach (Mage::app()->getStores() as $store)
            {
                $this->getClient($store->getId())
                   ->call('listInterestGroupDel', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $segmentCode));
            }
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        { 
            throw new AW_Core_Exception($ex->getMessage());
        }
    }

    /**
     * Sync group of subscribers from Mailchimp
     * @param int $syncType
     * @param bool $fromCron
     * @return boolean 
     */
    public function syncFrom($syncType, $fromCron = false)
    {
        // TODO: Optimize function
        if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST && $fromCron == false)
        {
            $this->setCurrentRecordPage(0);
            return true;
        }

        $syncedApikeyListid = array();
        foreach (Mage::app()->getStores() as $store)
        {
            $this->getClient($store->getId());
            $currentApikeyListId = $this->keys_values[self::MAILCHIMP_APIKEY].','.$this->keys_values[self::MAILCHIMP_LISTID];
            if (in_array($currentApikeyListId, $syncedApikeyListid)) continue;
            $syncedApikeyListid[] = $currentApikeyListId;
            
            try
            {
                $chimpGroupings = $this->getClient($store->getId())
                    ->call('listInterestGroupings', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID]));
            } catch (Exception $ex)
            {
                continue;
            }
            foreach ($chimpGroupings as $chimpGrouping)
            {
                if (isset($chimpGrouping['groups']))
                {
                    foreach ($chimpGrouping['groups'] as $segment)
                    {
                        Mage::getModel('advancednewsletter/segment')->massCreation(array($segment['name']));
                    }
                }
            }
        }

        $syncWithErrorsFlag = false;
        $recordsForStores = $this->getRecords($syncType);

        foreach ($recordsForStores as $storeId => $recordsForStore)
        {
            if (isset($recordsForStore['subscribers']))
            {
                foreach ($recordsForStore['subscribers'] as $subscriberChimp)
                {
                    $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($subscriberChimp['email'], 'email');
                    if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_STATUSES && $subscriber->getId())
                    {
                        $subscriber->setStatus(AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED);
                    }
                    if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST)
                    {
                        // TODO: Throw exception
                        if (!isset($subscriberChimp['merges'])) continue;
                        $groups = array();
                        foreach ($subscriberChimp['merges']['GROUPINGS'] as $grouping)
                        {
                            $segmentsInGrouping = explode(', ', $grouping['groups']);
                            $groups = array_merge($segmentsInGrouping, $groups);
                        }
                        $groups = array_unique($groups);
                        $subscriber
                            ->setStatus(AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED)
                            ->setEmail($subscriberChimp['merges']['EMAIL'])
                            ->setFirstName($subscriberChimp['merges']['FNAME'])
                            ->setLastName($subscriberChimp['merges']['LNAME'])
                            ->setSegmentsCodes($groups)
                            ->setStoreId($storeId);
                    }
                    try
                    {
                        $subscriber->save();
                    }
                    catch(Exception $ex)
                    { $syncWithErrorsFlag = true; }
                }
            }
            if (isset($recordsForStore['unsubscribers']))
            {
                foreach ($recordsForStore['unsubscribers'] as $unsubscriberChimp)
                {
                    $unsubscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($unsubscriberChimp['email'], 'email');
                    if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_STATUSES && $unsubscriber->getId())
                    {
                        $unsubscriber->setStatus(AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED);
                    }
                    if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST)
                    {
                        // TODO: Throw exception
                        if (!isset($unsubscriberChimp['merges'])) continue;
                        $groups = array();
                        foreach ($subscriberChimp['merges']['GROUPINGS'] as $grouping)
                        {
                            $segmentsInGrouping = explode(', ', $grouping['groups']);
                            $groups = array_merge($segmentsInGrouping, $groups);
                        }
                        $groups = array_unique($groups);
                        $unsubscriber
                            ->setStatus(AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
                            ->setEmail($unsubscriberChimp['merges']['EMAIL'])
                            ->setFirstName($unsubscriberChimp['merges']['FNAME'])
                            ->setLastName($unsubscriberChimp['merges']['LNAME'])
                            ->setSegmentsCodes($groups)
                            ->setStoreId($storeId);
                    }
                    try
                    {
                        $unsubscriber->save();
                    }
                    catch(Exception $ex)
                    { $syncWithErrorsFlag = true; }
                }
            }
        }
        return $syncWithErrorsFlag;
    }

    /**
     * Setting current page number for sync type 'Sync from Mailchimp -> List' 
     * to start from it on cron execution. Param is keeping in Magento cache
     * @param int $value 
     */
    protected function setCurrentRecordPage($value)
    {
        Mage::getModel('advancednewsletter/cache')->saveCache($value, 'aw_advancednewsletter_mailchimp_from_page');
    }

    /**
     * Getting current page number for sync type 'Sync from Mailchimp -> List' 
     * to start from it on cron execution. Param is keeping in Magento cache
     * @return int
     */
    protected function getCurrentRecordPage()
    {
        $currentPage = Mage::getModel('advancednewsletter/cache')->loadCache('aw_advancednewsletter_mailchimp_from_page');
        if ($currentPage === '' || is_null($currentPage)) throw new Exception('Current page for sync is not set');
        return $currentPage;
    }

    /**
     * Get records from Mailchimp for 'Sync from Mailchimp' operation
     * @param int $type
     * @return array
     */
    protected function getRecords($type)
    {
        $syncListNoChanges = true;
        $records = array();
        $syncedApikeyListid = array();
        foreach (Mage::app()->getStores() as $store)
        {
            if ($type == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST)
            {
                $this->getClient($store->getId());
                $currentApikeyListId = $this->keys_values[self::MAILCHIMP_APIKEY].','.$this->keys_values[self::MAILCHIMP_LISTID];
                if (in_array($currentApikeyListId, $syncedApikeyListid)) continue;
                $syncedApikeyListid[] = $currentApikeyListId;
                
                $records[$store->getId()]['subscribers'] = $this->getClient($store->getId())->call('listMembers', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], 'subscribed', null, $this->getCurrentRecordPage(), self::SYNC_PAGE_SIZE));
                $records[$store->getId()]['unsubscribers'] = $this->getClient($store->getId())->call('listMembers', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], 'unsubscribed', null, $this->getCurrentRecordPage(), self::SYNC_PAGE_SIZE));
                
                if (!empty($records[$store->getId()]['subscribers']) || !empty($records[$store->getId()]['unsubscribers']))
                { $syncListNoChanges = false; }
                foreach ($records[$store->getId()]['subscribers'] as $key => $subscriber)
                {
                    $records[$store->getId()]['subscribers'][$key] = $this->getClient($store->getId())->call('listMemberInfo', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $subscriber['email']));
                }
                foreach ($records[$store->getId()]['unsubscribers'] as $key => $unsubscriber)
                {
                    $records[$store->getId()]['unsubscribers'][$key] = $this->getClient($store->getId())->call('listMemberInfo', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $unsubscriber['email']));
                }
            }
            if ($type == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_STATUSES)
            {
                $records[$store->getId()]['subscribers'] = $this->getClient($store->getId())->call('listMembers', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], 'subscribed', null, 0, 10000));
                $records[$store->getId()]['unsubscribers'] = $this->getClient($store->getId())->call('listMembers', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], 'unsubscribed', null, 0, 10000));
            }
        }
        if ($type == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST && $syncListNoChanges)
        { $this->setCurrentRecordPage(null); }
        else
        { $this->setCurrentRecordPage($this->getCurrentRecordPage() + 1); }
        return $records;
    }

    /**
     * Unsubscribe subscriber from whole subscriptions
     * @param string $email
     * @param int $storeId
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    protected function unsubscribeFromList($email, $storeId)
    {
        try
        {
            $this
                ->getClient($storeId)
                ->call('listUnsubscribe', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $email, false, false, false));
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    /**
     * Deletion subscriber according to his store id
     * @param string $email
     * @param int $storeId
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    protected function deleteOptional($email, $storeId)
    {
        try
        {
            $this
                ->getClient($storeId)
                ->call('listUnsubscribe', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $email, true, false, false));
        } catch (Zend_XmlRpc_Client_FaultException $ex)
        {
            throw new AW_Core_Exception($ex->getMessage());
        }
        return $this;
    }

    /**
     * Load segments from Mailchimp
     * @param int $storeId
     * @return AW_Advancednewsletter_Model_Sync_Mailchimp 
     */
    protected function loadSegments($storeId)
    {
        if (!self::$groups)
        {
            try
            {
                self::$groups = $this->getClient($storeId)->call('listInterestGroups', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID]));
            } catch (Zend_XmlRpc_Client_FaultException $ex)
            {
                throw new AW_Core_Exception($ex->getMessage());
            }
        }
        $groups = self::$groups;

        $segments = Mage::getModel('advancednewsletter/segment')->getCollection();
        foreach ($segments as $segment)
        {
            try
            {
                if (isset($groups['groups']))
                {
                    if (!in_array($segment->getCode(), $groups['groups']))
                        $this->getClient($storeId)->call('listInterestGroupAdd', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $segment->getCode()));
                }
                else
                {
                    $this->getClient($storeId)->call('listInterestGroupAdd', array($this->keys_values[self::MAILCHIMP_APIKEY], $this->keys_values[self::MAILCHIMP_LISTID], $segment->getCode()));
                }
            } catch (Exception $ex)
            { }
        }
        return $this;
    }

    /**
     * Checking subscribers data and getting 'merges' array for Mailchimp sync (names, segments_codes)
     * @param <type> $subscriber
     * @return <type> 
     */
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
                $this->deleteOptional($subscriber->getOrigData('email'), $subscriber->getOrigData('store_id'));
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

    /**
     * Setting values from System > Configuration > AN > Mailchimp to keys_values
     * array according to store id
     * @param int $storeId
     */
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
            $this->keys_values = $keys_values[$storeId];
        else
        {
            throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Unknown Store Id'));
        }

        if (!$this->keys_values[self::MAILCHIMP_APIKEY] || !$this->keys_values[self::MAILCHIMP_XMLRPC])
        {
            throw new AW_Core_Exception(Mage::helper('advancednewsletter')->__('Apikey or xmlrpc url are inncorrect'));
        }

        if (!$this->keys_values[self::MAILCHIMP_ENABLED])
        {
            throw new Exception(Mage::helper('advancednewsletter')->__('MailChimp is disabled for store %s', $storeId));
        }

        if (!$this->keys_values[self::MAILCHIMP_AUTOSYNC] && !Mage::app()->getStore()->isAdmin())
        {
            throw new Exception(Mage::helper('advancednewsletter')->__('MailChimp auto-sync is disabled for store %s', $storeId));
        }
    }

    /**
     * Return connection string to Mailchimp in dependence of current store and its
     * apikey and xmlrpc url
     * @return string 
     */
    protected function connect()
    {
        $apikey = $this->keys_values[self::MAILCHIMP_APIKEY];
        $xmlrpcurl = $this->keys_values[self::MAILCHIMP_XMLRPC];

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