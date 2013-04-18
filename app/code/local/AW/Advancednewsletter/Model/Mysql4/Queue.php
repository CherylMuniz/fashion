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
 * Newsletter queue saver
 * 
 * @category   Mage
 * @package    Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Model_Mysql4_Queue extends Mage_Newsletter_Model_Mysql4_Queue {

    protected function _construct() {
        $this->_init('advancednewsletter/queue', 'queue_id');
    }

    public function setStores(Mage_Newsletter_Model_Queue $queue) {
        $this->_getWriteAdapter()
                ->delete(
                        $this->getTable('queue_store_link'),
                        $this->_getWriteAdapter()->quoteInto('queue_id = ?', $queue->getId())
        );

        if (!is_array($queue->getStores())) {
            $stores = array();
        } else {
            $stores = $queue->getStores();
        }


        foreach ($stores as $storeId) {
            $data = array();
            $data['store_id'] = $storeId;
            $data['queue_id'] = $queue->getId();
            $this->_getWriteAdapter()->insert($this->getTable('queue_store_link'), $data);
        }

        $this->removeSubscribersFromQueue($queue);

        if (count($stores) == 0) {
            return $this;
        }
        $subscribers = Mage::getResourceSingleton('advancednewsletter/subscriber_collection')
                        ->addFieldToFilter('store_id', array('in' => $stores))
                        ->addFilterSubscribed()
                        ->addFilterSegments($queue->getTemplate()->getSegmentsCodes())
                        ->load();

        $subscriberIds = array();

        foreach ($subscribers as $subscriber) {
            $subscriberIds[] = $subscriber->getId();
        }

        if (count($subscriberIds) > 0) {
            $this->addSubscribersToQueue($queue, $subscriberIds);
        }

        return $this;
    }

    public function getTable($entity) {

        $entity = preg_replace('#newsletter/#is', '', $entity);
        return parent::getTable($entity);
    }

}
