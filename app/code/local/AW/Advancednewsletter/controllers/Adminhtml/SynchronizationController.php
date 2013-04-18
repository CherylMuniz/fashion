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
class AW_Advancednewsletter_Adminhtml_SynchronizationController extends Mage_Adminhtml_Controller_Action
{
    CONST OLD_NEWSLETTER_SEGMENT = 'old_newsletter_subscriber';

    protected function displayTitle()
    {
        if (!Mage::helper('advancednewsletter')->magentoLess14())
            $this->_title($this->__('Advanced Newsletter'))->_title($this->__('Synchronization'));
        return $this;
    }

    public function indexAction() {
        $this
            ->displayTitle()
            ->loadLayout()
            ->_setActiveMenu('advancednewsletter')
            ->renderLayout();
	}

    public function editAction()
    {
        $this
            ->displayTitle()
            ->loadLayout()
            ->_setActiveMenu('advancednewsletter')
            ->renderLayout();
    }

    public function syncAction()
    {
        $helper = Mage::helper('advancednewsletter');
        $request = $this->getRequest();
        $syncType = $this->getRequest()->getParam('type');
        
        $stores = Mage::app()->getStores();
        
        if ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_TO_MAILCHIMP)
        {
            $serializedParams = serialize(
                array(
                    'sync_for' => $request->getParam('sync_for'),
                    'include_names' => (bool)$request->getParam('include_names'),
                    'sync_stores' => array_keys($stores)
                ));
            Mage::getModel('advancednewsletter/cache')->saveCache($serializedParams, 'aw_advancednewsletter_mailchimp_to_params');
            Mage::getSingleton('adminhtml/session')->addSuccess($helper->__('Synchronization was queued'));
        }
        elseif ($syncType == AW_Advancednewsletter_Block_Adminhtml_Synchronization::SYNC_FROM_MAILCHIMP)
        {
            $serializedParams = serialize(
                array(
                    'sync_for' => $request->getParam('sync_for'),
                    'sync_stores' => array_keys($stores)
                ));
            Mage::getModel('advancednewsletter/cache')->saveCache($serializedParams, 'aw_advancednewsletter_mailchimp_from_params');
            Mage::getSingleton('adminhtml/session')->addSuccess($helper->__('Synchronization was queued'));
        }
        else
        {
            Mage::getModel('adminhtml/session')->addError($helper->__('Choose needed parameters'));
        }

        return $this->_redirect('*/*/edit', array('type' => $syncType));
    }

    public function newsletterSubscribersImportAction()
    {
        $resource = Mage::getSingleton('core/resource');
        $connWrite = $resource->getConnection('log_write');
        $subscriberTable = $resource->getTableName("advancednewsletter/subscriber");
        
        if (Mage::helper('advancednewsletter')->magentoLess14())
        {
            $newsletterSubscribersCollection = Mage::getResourceModel('newsletter/subscriber_collection');
        }
        else
        {
            $newsletterSubscribersCollection = Mage::getModel('newsletter/subscriber')->getCollection();
        }
        Mage::getModel('advancednewsletter/segment')->createNewSegment(self::OLD_NEWSLETTER_SEGMENT);
        foreach ($newsletterSubscribersCollection as $subscriber)
        {
            $newStatus = 0;
            switch ($subscriber->getStatus())
            {
                case Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED: $newStatus = AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED; break;
                case Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE: $newStatus = AW_Advancednewsletter_Model_Subscriber::STATUS_NOTACTIVE; break;
                case Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED: $newStatus = AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED; break;
            }
            
            try
            {
                $data = array();
                $data['store_id'] = $subscriber->getStoreId();
                if ($subscriber->getCustomerId()) $data['customer_id'] = $subscriber->getCustomerId();
                $data['email'] = $subscriber->getSubscriberEmail();
                $data['status'] = $newStatus;
                $data['confirm_code'] = $subscriber->getSubscriberConfirmCode();
                $data['segments_codes'] = self::OLD_NEWSLETTER_SEGMENT;
                
                $sql = sprintf("INSERT IGNORE INTO %s (%s) VALUES('%s')",
                   $subscriberTable,
                   implode(',', array_keys($data)),
                   implode("','", $data));
                
                $connWrite->query($sql);

            }
            catch (Exception $ex)
            { }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Subscribers synchronization successfully completed'));
        return $this->_redirect('*/*/index');
    }

    public function newsletterTemplatesImportAction()
    {
        $newsletterTemplatesCollection = Mage::getModel('newsletter/template')->getCollection();
        Mage::getModel('advancednewsletter/segment')->createNewSegment(self::OLD_NEWSLETTER_SEGMENT);
        foreach ($newsletterTemplatesCollection as $template)
        {
            try
            {
                Mage::getModel('advancednewsletter/template')
                    ->addData($template->getData())
                    ->setId(null)
                    ->setSegmentsCodes(array(self::OLD_NEWSLETTER_SEGMENT))
                    ->save();
            }
            catch (Exception $ex)
            { }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Templates synchronization successfully completed'));
        return $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('advancednewsletter/synchronization');
    }
}