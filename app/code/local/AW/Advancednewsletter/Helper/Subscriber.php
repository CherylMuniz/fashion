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
class AW_Advancednewsletter_Helper_Subscriber extends Mage_Core_Helper_Abstract
{
    public function updateSegments($order, $segmentsCut, $segmentsPaste)
    {
        $email = $order->getCustomerEmail();
        try
        {
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($email);
            $newSubscriberSegments = array();
            if ($subscriber->getId())
            {
                foreach ($subscriber->getSegmentsCodes() as $subscriberSegment)
                {
                    if (!in_array($subscriberSegment, $segmentsCut)) $newSubscriberSegments[] = $subscriberSegment;
                }

                $newSubscriberSegments = array_unique(array_merge($segmentsPaste, $newSubscriberSegments));
                if (Mage::helper('advancednewsletter')->isArrayValuesEmpty($newSubscriberSegments)) $subscriber->unsubscribeFromAll();
                else
                {
                    $arrayToWrite = array('segments_codes' => $newSubscriberSegments);
                    if ($subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
                    {
                        $arrayToWrite['status'] = AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED;
                    }
                    $subscriber->forceWrite( $arrayToWrite );
                }
            }
            else
            {
                if ($customerId = $order->getCustomerId()) $subscriber->setCustomer(Mage::getModel('customer/customer')->load($customerId));
                $subscriber->subscribe($email, $segmentsPaste, array('store_id' => $order->getStoreId()));
            }

        } catch (Exception $ex)
        { Mage::helper('awcore/logger')->log($this, 'Segments update failed - '.$ex->getMessage()); }
    }
}