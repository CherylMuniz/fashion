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
 */class AW_Advancednewsletter_IndexController extends Mage_Core_Controller_Front_Action {
    const SEGMENTS_STYLE = 'advancednewsletter/formconfiguration/segmentsstyle';
    const DEFAULT_SUBSCRIPTION = 'advancednewsletter/formconfiguration/defaultsubscription';

    /* public function testAction()
      {
      Mage::getModel('advancednewsletter/test')->testSubscribe();
      die;
      } */

    public function subscribeAction() {

        $request = $this->getRequest();
        $segmentsToSubscribe = array();


        /* If customer subscribes as a guest we should first check if there is already customer with such email */
        $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($request->getParam('email'));
        if ($subscriber->getId()) {
            if ($subscriber->getCustomerId()) {
                Mage::getSingleton('core/session')->addError(Mage::helper('advancednewsletter')->__('There is already customer with such email. Please, log in to update your segments'));
                $this->getResponse()->setRedirect($this->_getRefererUrl());
                return;
            }
        }
        /* */

        if (Mage::getStoreConfig(self::SEGMENTS_STYLE) == AW_Advancednewsletter_Model_Source_Segmentsstyle::STYLE_NONE) {
            $segmentsCollection = Mage::getModel('advancednewsletter/segment')->getCollection();
            if (Mage::getStoreConfig(self::DEFAULT_SUBSCRIPTION) == AW_Advancednewsletter_Model_Source_Defaultsubscription::CATEGORY_DEFAULT) {
                $segmentsCollection->addDefaultCategoryFilter($request->getParam('an_category_id'));
            }
            if (Mage::getStoreConfig(self::DEFAULT_SUBSCRIPTION) == AW_Advancednewsletter_Model_Source_Defaultsubscription::STORE_DEFAULT) {
                $segmentsCollection->addDefaultStoreFilter(Mage::app()->getStore()->getId());
            }

            foreach ($segmentsCollection as $segment) {
                $segmentsToSubscribe[] = $segment->getCode();
            }
        } else {
            $segmentsToSubscribe = $request->getParam('segments_select');
        }

        try {
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->subscribe($request->getParam('email'), $segmentsToSubscribe, $request->getParams());
            if ($subscriber->needActivation()) {
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('The email with activation code is send to your address'));
            } else {
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('You have been successfully subscribed'));
            }
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError($ex->getMessage());
        }
        $this->getResponse()->setRedirect($this->_getRefererUrl());
    }

    public function unsubscribeAction() {
        try {
            Mage::getModel('advancednewsletter/subscriber')
                    ->loadByEmail($this->getRequest()->getParam('email'))
                    ->unsubscribe($this->getRequest()->getParam('segments_codes'));
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('You have been successfully unsubscribed'));
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError($ex->getMessage());
        }
        $this->getResponse()->setRedirect($this->_getRefererUrl());
    }

    public function unsubscribeAllAction() {
        $subscriber = Mage::getModel('advancednewsletter/subscriber')
                        ->loadByEmail($this->getRequest()->getParam('email'));

        if ($subscriber->getId()) {
            $enc = $subscriber->getAnMailEncryption($subscriber->getId());
            $act = $this->getRequest()->getParam('key');

            if (($enc === $act) || ($enc === urldecode($act))) {
                try {
                    $subscriber->unsubscribeFromAll();
                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('You have been successfully unsubscribed'));
                } catch (Exception $ex) {
                    Mage::getSingleton('core/session')->addError($ex->getMessage());
                }
            } else {
                Mage::getSingleton('core/session')->addError('Unsubscription error');
            }
        } else {
            Mage::getSingleton('core/session')->addError('Unsubscription error');
        }

        $this->getResponse()->setRedirect($this->_getRefererUrl());
    }

    public function activateAction() {

        try {
            Mage::getModel('advancednewsletter/subscriber')
                    ->loadByEmail($this->getRequest()->getParam('email'))
                    ->activate($this->getRequest()->getParam('confirm_code'));
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('Your subscription is activated now'));
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError($ex->getMessage());
        }
        $this->_redirectUrl(Mage::getBaseUrl());
    }

    public function subscribeAjaxAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function updateStatusAction() {
        try {
            $segments = $this->getRequest()->getParam('segments_codes') ? $this->getRequest()->getParam('segments_codes') : array();
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($this->getRequest()->getParam('email'));
            if ($subscriber->getId() && $subscriber->getStatus() == AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED) {
                $unvisibleSegmentsCodes = array();
                $currentSubscriberSegments = $subscriber->getSegments();
                foreach ($currentSubscriberSegments as $currentSubscriberSegment) {
                    if (!$currentSubscriberSegment->getFrontendVisibility())
                        $unvisibleSegmentsCodes[] = $currentSubscriberSegment->getCode();
                }
                $segments = array_merge($unvisibleSegmentsCodes, $segments);
                if (empty($segments))
                    $subscriber->unsubscribeFromAll();
                else
                    $subscriber->forceWrite(array('segments_codes' => $segments, 'customer_id' => Mage::getModel('customer/session')->getCustomer()->getId()));
            }
            else {
                $customer = Mage::getModel('customer/session')->getCustomer();
                $subscriber->setCustomer($customer)->subscribe($customer->getEmail(), $segments);
            }
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('advancednewsletter')->__('Your subscriptions have been successfully updated'));
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError($ex->getMessage());
        }
        $this->getResponse()->setRedirect(Mage::getUrl('advancednewsletter/manage/'));
    }

}