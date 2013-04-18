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
class AW_Advancednewsletter_Model_Cron
{
    /**
     * Sync To Mailchimp. Count of subcribers which will be synchronized with mailchimp by one cron execution
     */
    const SYNC_TO_MAILCHIMP_PAGE_SIZE = 100;

    /**
     * Sync From Mailchimp. Count of subcribers which will be synchronized with mailchimp by one cron execution
     */
    const SYNC_FROM_MAILCHIMP_PAGE_SIZE = 20;

    public static $_massSyncFlag = false;

    protected $_syncFromType;

    /**
     * Sending newsletters by cron
     * @param mixed $schedule 
     */
    public function scheduledSend($schedule)
    {
        $countOfQueue  = 3;
        $countOfSubscritions = 20;

        $collection = Mage::getModel('advancednewsletter/queue')->getCollection()
            ->setPageSize($countOfQueue)
            ->setCurPage(1)
            ->addOnlyForSendingFilter()
            ->load();

         $collection->walk('sendPerSubscriber', array($countOfSubscritions));
    }

    protected function _checkInstanceDuplicate($instances, $instanceForCheck)
    {
        $instanceForCheckKeys = $instanceForCheck->getKeys();
        foreach ($instances as $instance)
        {
            $instanceKeys = $instance->getKeys();
            if (
                $instanceKeys[AW_Advancednewsletter_Model_Sync_Mailchimp::MAILCHIMP_APIKEY] == $instanceForCheckKeys[AW_Advancednewsletter_Model_Sync_Mailchimp::MAILCHIMP_APIKEY]
                && $instanceKeys[AW_Advancednewsletter_Model_Sync_Mailchimp::MAILCHIMP_LISTID] == $instanceForCheckKeys[AW_Advancednewsletter_Model_Sync_Mailchimp::MAILCHIMP_LISTID]
                )
            {
                return true;
            }
        }
        return false;
    }

    protected function _addRecordsToStore($records, $storeId, $status)
    {
        foreach ($records as $record)
        {
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($record['email']);
            if ($this->_syncFromType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_STATUSES && $subscriber->getId())
            {
                $subscriber->setStatus($status);
            }
            if ($this->_syncFromType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_LIST)
            {
                // TODO: Throw exception
                if (!isset($record['merges'])) continue;
                $groups = array();
                foreach ($record['merges']['GROUPINGS'] as $grouping)
                {
                    $segmentsInGrouping = explode(', ', $grouping['groups']);
                    $groups = array_merge($segmentsInGrouping, $groups);
                }
                $groups = array_unique($groups);
                foreach ($groups as $keyGroup => $oneGroup)
                {
                    if (empty($oneGroup)) unset($groups[$keyGroup]);
                }

                $email = $record['merges']['EMAIL'];
                $customerByEmail = Mage::getModel('customer/customer')->setStore(Mage::app()->getStore($storeId))->loadByEmail($email);
                if ($customerByEmail->getId()) $subscriber->setCustomerId($customerByEmail->getId());
                
                $subscriber
                    ->setStatus($status)
                    ->setEmail($email)
                    ->setFirstName($record['merges']['FNAME'])
                    ->setLastName($record['merges']['LNAME'])
                    ->setSegmentsCodes($groups)
                    ->setStoreId($storeId);
            }
            try
            {
                $subscriber->save();
            }
            catch(Exception $ex)
            { }
        }
    }

    public function syncFromMailchimp()
    {
        self::$_massSyncFlag = true;
        $syncFromParams = Mage::getModel('advancednewsletter/cache')->loadCache('aw_advancednewsletter_mailchimp_from_params');
        if (!$syncFromParams) return;
        $syncFromParams = unserialize($syncFromParams);
        if (!isset($syncFromParams['sync_for']) || !isset($syncFromParams['sync_stores'])) return;

        $this->_syncFromType = $syncFromParams['sync_for'];
        $storesToSync = $syncFromParams['sync_stores'];

        $currentPage = Mage::getModel('advancednewsletter/cache')->loadCache('aw_advancednewsletter_mailchimp_from_page');
        if (!$currentPage) $currentPage = 0;

        /* Getting instances for sync. Instances with the same apikey and listid joined to one */
        $instances = array();
        foreach ($storesToSync as $storeId)
        {
            try
            {
                $instanceForCheck = AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($storeId);
                if (!$this->_checkInstanceDuplicate($instances, $instanceForCheck)) $instances[] = $instanceForCheck;
            } catch (Exception $ex)
            { continue; }
        }
        

        /* Creation segments for all instances */
        foreach ($instances as $instance)
        {
            try
            {
                $chimpGroupings = $instance->getChimpGroupings();
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
            } catch (Exception $ex)
            {
                continue;
            }
        }

        /* Subscrubers sync */
        foreach ($instances as $instance)
        {
            $records = $instance->getRecords($this->_syncFromType, $currentPage, self::SYNC_FROM_MAILCHIMP_PAGE_SIZE);
            
            if (empty($records['subscribers']) && empty($records['unsubscribers']))
            {
                Mage::getModel('advancednewsletter/cache')->removeCache('aw_advancednewsletter_mailchimp_from_params');
                Mage::getModel('advancednewsletter/cache')->removeCache('aw_advancednewsletter_mailchimp_from_page');
                return;
            }
            if (!empty($records['subscribers']))
            {
                $this->_addRecordsToStore($records['subscribers'], $instance->getStoreId(), AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED);
            }
            if (!empty($records['unsubscribers']))
            {
                $this->_addRecordsToStore($records['unsubscribers'], $instance->getStoreId(), AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED);
            }
        }

        Mage::getModel('advancednewsletter/cache')->saveCache($currentPage+1, 'aw_advancednewsletter_mailchimp_from_page');
        self::$_massSyncFlag = false;
    }

    public function syncToMailchimp()
    {
        self::$_massSyncFlag = true;
        $syncToParams = Mage::getModel('advancednewsletter/cache')->loadCache('aw_advancednewsletter_mailchimp_to_params');
        if (!$syncToParams) return;
        $syncToParams = unserialize($syncToParams);
        if (!isset($syncToParams['sync_for']) || !isset($syncToParams['include_names']) || !isset($syncToParams['sync_stores'])) return;
        
        $syncFor = $syncToParams['sync_for'];
        $includeNames = $syncToParams['include_names'];
        
        $currentPage = Mage::getModel('advancednewsletter/cache')->loadCache('aw_advancednewsletter_mailchimp_to_page');
        if (!$currentPage) $currentPage = 1;

        $subscribersCollection = Mage::getModel('advancednewsletter/subscriber')->getCollection()->addStoreFilter($syncToParams['sync_stores']);
        
        if ($subscribersCollection->getSize() < ($currentPage-1) * self::SYNC_TO_MAILCHIMP_PAGE_SIZE)
        {
            Mage::getModel('advancednewsletter/cache')->removeCache('aw_advancednewsletter_mailchimp_to_params');
            Mage::getModel('advancednewsletter/cache')->removeCache('aw_advancednewsletter_mailchimp_to_page');
            return;
        }
        
        $subscribers = $subscribersCollection
                ->setPageSize(self::SYNC_TO_MAILCHIMP_PAGE_SIZE)
                ->setCurPage($currentPage);

        switch ($syncFor)
        {
            case AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_SUBSCRIBED: $subscribers->addFilterSubscribed(); break;
            case AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_UNSUBSCRIBED: $subscribers->addFilterUnsubscribed(); break;
            default: $subscribers->addFilterSubscribedUnsubscribed(); break;
        }

        $syncWithErrorsFlag = false;
        foreach ($subscribers as $subscriber)
        {
            try
            {
                if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED)
                {
                    AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())
                        ->setSkipChangesCheck(true)
                        ->setIncludeNames($includeNames)
                        ->subscribe($subscriber);
                }
                if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
                {
                    AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())
                        ->setSkipChangesCheck(true)
                        ->setIncludeNames($includeNames)
                        ->subscribe($subscriber)
                        ->unsubscribeFromList($subscriber);
                }
            }catch (Exception $ex)
            {
                $syncWithErrorsFlag = true;
            }
        }
        
        Mage::getModel('advancednewsletter/cache')->saveCache($currentPage+1, 'aw_advancednewsletter_mailchimp_to_page');
        self::$_massSyncFlag = false;
    }
}