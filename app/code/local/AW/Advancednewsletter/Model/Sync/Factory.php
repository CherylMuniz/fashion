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
class AW_Advancednewsletter_Model_Sync_Factory implements AW_Advancednewsletter_Model_Sync_Interface
{
    public function subscribe($observer)
    {
        try
        {
            $subscriber = $observer->getSubscriber();
            AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())->subscribe($subscriber);
        } catch (Exception $ex)
        {}
    }

    public function unsubscribe($observer)
    {
        try
        {
            $subscriber = $observer->getSubscriber();
            AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())->unsubscribe($subscriber);
        } catch (Exception $ex)
        {}
    }

    public function delete($observer)
    {
        try
        {
            $subscriber = $observer->getSubscriber();
            AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())->delete($subscriber);
        } catch (Exception $ex)
        {}
    }

    public function forceWrite($observer)
    {
        try
        {
            $subscriber = $observer->getSubscriber();
            AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($subscriber->getStoreId())->forceWrite($subscriber);
        } catch (Exception $ex)
        {}
    }

    public function removeSegment($observer)
    {
        if ($observer->getSegmentCode())
        {
            try
            {
                foreach (Mage::app()->getStores() as $store)
                {
                    AW_Advancednewsletter_Model_Sync_Mailchimpclient::getInstance($store->getId())->removeSegment($observer->getSegmentCode());
                }
            } catch (Exception $ex)
            {}
        }
    }
}