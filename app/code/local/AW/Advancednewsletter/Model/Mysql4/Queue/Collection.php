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
 * Newsletter queue collection.
 *
 * @category   Mage
 * @package    Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Model_Mysql4_Queue_Collection extends Mage_Newsletter_Model_Mysql4_Queue_Collection {

    protected function _construct() {
        parent::_construct();
        $this->_init('advancednewsletter/queue');
    }

    protected function _addSubscriberInfoToSelect() {
        $this->_addSubscribersFlag = false;
        return parent::_addSubscriberInfoToSelect();
    }

    /**
     * Filter collection by specified store ids
     *
     * @param array|int $storeIds
     * @return AW_Advancednewsletter_Model_Mysql4_Subscriber_Collection
     */
    public function addStoreFilter($storeIds) {
        $this->getSelect()->joinInner(array('store_link' => $this->getTable('queue_store_link')),
                        'main_table.queue_id = store_link.queue_id', array()
                )
                ->where('store_link.store_id IN (?)', $storeIds)
                ->group('main_table.queue_id');
        return $this;
    }

}
