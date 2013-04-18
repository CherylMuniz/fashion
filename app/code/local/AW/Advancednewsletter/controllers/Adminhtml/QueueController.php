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
 * Adminhtml newsletter queue controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Adminhtml_QueueController extends Mage_Adminhtml_Controller_Action
{
    protected function displayTitle()
    {
        if (!Mage::helper('advancednewsletter')->magentoLess14())
            $this->_title($this->__('AdvancedNewsletter'))->_title($this->__('Newsletter Queue'));
        return $this;
    }
    
    /**
     * Queue list action
     */
    public function indexAction()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this
            ->displayTitle()
            ->loadLayout();

        $this->_setActiveMenu('advancednewsletter/queue');

        $this->_addBreadcrumb(Mage::helper('newsletter')->__('Newsletter Queue'), Mage::helper('newsletter')->__('Newsletter Queue'));

        $this->renderLayout();
    }

    /**
     * Queue list Ajax action
     */
    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('advancednewsletter/adminhtml_queue_grid')->toHtml());
    }

    public function startAction()
    {
        $queue = Mage::getModel('advancednewsletter/queue')
            ->load($this->getRequest()->getParam('id'));
        if ($queue->getId()) {
            if (!in_array($queue->getQueueStatus(),
                          array(Mage_Newsletter_Model_Queue::STATUS_NEVER,
                                 Mage_Newsletter_Model_Queue::STATUS_PAUSE))) {
                return $this->_redirect('*/*');
            }

            $queue->setQueueStartAt(Mage::getSingleton('core/date')->gmtDate())
                ->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_SENDING)
                ->save();
        }

        $this->_redirect('*/*');
    }

    public function pauseAction()
    {
        $queue = Mage::getSingleton('advancednewsletter/queue')
            ->load($this->getRequest()->getParam('id'));

        if (!in_array($queue->getQueueStatus(),
                      array(Mage_Newsletter_Model_Queue::STATUS_SENDING))) {
               $this->_redirect('*/*');
            return;
        }

        $queue->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_PAUSE);
        $queue->save();

        $this->_redirect('*/*');
    }

    public function resumeAction()
    {
        $queue = Mage::getSingleton('advancednewsletter/queue')
            ->load($this->getRequest()->getParam('id'));

        if (!in_array($queue->getQueueStatus(),
                      array(Mage_Newsletter_Model_Queue::STATUS_PAUSE))) {
               $this->_redirect('*/*');
            return;
        }

        $queue->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_SENDING);
        $queue->save();

        $this->_redirect('*/*');
    }

    public function cancelAction()
    {
        $queue = Mage::getSingleton('advancednewsletter/queue')
            ->load($this->getRequest()->getParam('id'));

        if (!in_array($queue->getQueueStatus(),
                      array(Mage_Newsletter_Model_Queue::STATUS_SENDING))) {
               $this->_redirect('*/*');
            return;
        }

        $queue->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_CANCEL);
        $queue->save();

        $this->_redirect('*/*');
    }

    public function sendingAction()
    {
        // Todo: put it somewhere in config!
        $countOfQueue  = 3;
        $countOfSubscritions = 20;

        $collection = Mage::getResourceModel('advancednewsletter/queue_collection')
            ->setPageSize($countOfQueue)
            ->setCurPage(1)
            ->addOnlyForSendingFilter()
            ->load();

        $collection->walk('sendPerSubscriber', array($countOfSubscritions));
    }

    public function editAction()
    {
        $this->displayTitle();

        Mage::register('current_queue', Mage::getSingleton('advancednewsletter/queue'));
        $id = $this->getRequest()->getParam('id');
        $templateId = $this->getRequest()->getParam('template_id');
        if ($id) {
            $queue = Mage::registry('current_queue')->load($id);
        } elseif ($templateId) {
            $template = Mage::getModel('advancednewsletter/template')->load($templateId)->preprocess();
            $queue = Mage::registry('current_queue')->setTemplateId($template->getId());
        }

        $this->loadLayout();

        $this->_setActiveMenu('advancednewsletter/queue');

        $this->_addBreadcrumb(Mage::helper('newsletter')->__('Newsletter Queue'), Mage::helper('newsletter')->__('Newsletter Queue'), $this->getUrl('*/advancednewsletter_queue'));
        $this->_addBreadcrumb(Mage::helper('newsletter')->__('Edit Queue'), Mage::helper('newsletter')->__('Edit Queue'));

        $this->_addContent(
            $this->getLayout()->createBlock('advancednewsletter/adminhtml_queue_edit', 'queue.edit')
        );

        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            // create new queue from template, if specified
            $templateId = $this->getRequest()->getParam('template_id');
            if ($templateId) {
                $template = Mage::getModel('advancednewsletter/template')->load($templateId);
                if (!$template->getId() || $template->getIsSystem()) {
                    Mage::throwException($this->__('Wrong newsletter template.'));
                }
                $template->preprocess();
                $queue = Mage::getModel('advancednewsletter/queue')
                    ->setTemplateId($template->getId())
                    ->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_NEVER);
                $template->save();
            }
            else {
                $queue = Mage::getSingleton('advancednewsletter/queue')
                    ->load($this->getRequest()->getParam('id'));
            }

            if (!in_array($queue->getQueueStatus(),
                          array(Mage_Newsletter_Model_Queue::STATUS_NEVER,
                                 Mage_Newsletter_Model_Queue::STATUS_PAUSE))) {
                   $this->_redirect('*/*');
                return;
            }

            $format = Mage::app()->getLocale()->getDateTimeFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
            );

            if ($queue->getQueueStatus()==Mage_Newsletter_Model_Queue::STATUS_NEVER) {
                if ($this->getRequest()->getParam('start_at')) {
                    $date = Mage::app()->getLocale()->date($this->getRequest()->getParam('start_at'), $format);
                    $time = $date->getTimestamp();
                    $queue->setQueueStartAt(
                        Mage::getModel('core/date')->gmtDate(null, $time)
                    );
                } else {
                    $queue->setQueueStartAt(null);
                }
            }

            $queue->setStores($this->getRequest()->getParam('stores', array()));

            $queue->addTemplateData($queue);
            $queue->getTemplate()
                ->setTemplateSubject($this->getRequest()->getParam('subject'))
                ->setTemplateSenderName($this->getRequest()->getParam('sender_name'))
                ->setTemplateSenderEmail($this->getRequest()->getParam('sender_email'))
                ->setTemplateTextPreprocessed($this->getRequest()->getParam('text'));

            if ($queue->getQueueStatus() == Mage_Newsletter_Model_Queue::STATUS_PAUSE
                && $this->getRequest()->getParam('_resume', false)) {
                $queue->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_SENDING);
            }

            $queue->setSaveTemplateFlag(true);
            $queue->save();
            $this->_redirect('*/*');
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->_redirect('*/*/edit', array('id' => $id));
            }
            else {
                $this->_redirectReferer();
            }
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('advancednewsletter/queue');
    }

}
