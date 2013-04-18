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
class AW_Advancednewsletter_Model_Mysql4_Subscriber_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Queue joined flag
     *
     * @var boolean
     */
    protected $_queueJoinedFlag = false;

    public function _construct()
    {
        parent::_construct();
        $this->_init('advancednewsletter/subscriber');
        $this->_map['fields']['customer_type'] = 'IF(main_table.customer_id is null,0,1)';
    }

    public function addCustomerType()
    {
        if (preg_match('/^1.3/', Mage::getVersion()))
        {
            $this->getSelect()
                ->from(null, array('customer_type'=>new Zend_Db_Expr('IF(main_table.customer_id is null, 0, 1)')));
        }
        else
        {
            $this->getSelect()
                ->columns(array('customer_type'=>new Zend_Db_Expr($this->_getMappedField('customer_type'))));
        }
        return $this;
    }

    public function addFilterSubscribed()
    {
        $this->getSelect()
            ->where('main_table.status = ?', AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED);
        return $this;
    }

    public function addFilterUnsubscribed()
    {
        $this->getSelect()
            ->where('main_table.status = ?', AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED);
        return $this;
    }

    public function addFilterSubscribedUnsubscribed()
    {
        $this->getSelect()
            ->where('main_table.status = ?', AW_Advancednewsletter_Model_Subscriber::STATUS_UNSUBSCRIBED)
            ->orWhere('main_table.status = ?', AW_Advancednewsletter_Model_Subscriber::STATUS_SUBSCRIBED);
        return $this;
    }

    public function addFilterSegments($segmentsCodes)
    {
        if (!is_array($segmentsCodes)) $segmentsCodes = explode(',', $segmentsCodes);
        $consitions = array();
        foreach ($segmentsCodes as $segmentCode)
        {
            $conditions[] = "find_in_set('".$segmentCode."', main_table.segments_codes)";
        }
        if (!empty($conditions))
        {
            $this->getSelect()
                ->where(implode(' or ', $conditions));
        }
        return $this;
    }

    public function removeSegment($code)
    {
        foreach ($this as $item)
        {
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->load($item->getId());
            $subscriberSegments = $subscriber->getData('segments_codes');
            if (($key = array_search($code, $subscriberSegments)) !== false)
            {
                unset($subscriberSegments[$key]);
                if (Mage::helper('advancednewsletter')->isArrayValuesEmpty($subscriberSegments))
                {
                    $subscriber->unsubscribeFromAll();
                }
                else
                {
                    $subscriber->setData('segments_codes', $subscriberSegments)->save();
                }
            }
        }
    }

    /**
     * Set using of links to only unsendet letter subscribers.
     */
    public function useOnlyUnsent( )
    {
        if($this->_queueJoinedFlag) {
            $this->getSelect()->where("link.letter_sent_at IS NULL");
        }

        return $this;
    }

    /**
     * Adds customer info to select
     *
     * @return AW_Advancednewsletter_Model_Mysql4_Subscriber_Collection
     */
    public function showCustomerInfo()
    {
        $customer = Mage::getModel('customer/customer');
        /* @var $customer Mage_Customer_Model_Customer */
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');

        $this->getSelect()
            ->joinLeft(
                array('customer_lastname_table'=>$lastname->getBackend()->getTable()),
                'customer_lastname_table.entity_id=main_table.customer_id
                 AND customer_lastname_table.attribute_id = '.(int) $lastname->getAttributeId() . '
                 ',
                array('customer_lastname'=>'value')
             )
             ->joinLeft(
                array('customer_firstname_table'=>$firstname->getBackend()->getTable()),
                'customer_firstname_table.entity_id=main_table.customer_id
                 AND customer_firstname_table.attribute_id = '.(int) $firstname->getAttributeId() . '
                 ',
                array('customer_firstname'=>'value')
             );

        return $this;
    }

    /**
     * Set loading mode subscribers by queue
     *
     * @param Mage_Newsletter_Model_Queue $queue
     */
    public function useQueue(Mage_Newsletter_Model_Queue $queue)
    {
        $queueLinkTable = Mage::getSingleton('core/resource')->getTableName('advancednewsletter/queue_link');

        $this->getSelect()->join(array('link'=>$queueLinkTable), "link.subscriber_id = main_table.id", array())
            ->where("link.queue_id = ? ", $queue->getId());
        $this->_queueJoinedFlag = true;
        return $this;
    }

    /**
     * Sets flag for customer info loading on load
     *
     * @return AW_Advancednewsletter_Model_Mysql4_Subscriber_Collection
     */
    public function showStoreInfo()
    {
        $this->getSelect()->join(
            array('store' => Mage::getSingleton('core/resource')->getTableName('core/store')),
            'store.store_id = main_table.store_id',
            array('group_id', 'website_id')
        );

        return $this;
    }

    /**
     * Filter collection by specified store ids
     *
     * @param array|int $storeIds
     * @return AW_Advancednewsletter_Model_Mysql4_Subscriber_Collection
     */
    public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }
}