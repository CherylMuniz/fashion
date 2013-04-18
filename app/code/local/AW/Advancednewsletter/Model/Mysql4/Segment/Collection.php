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
class AW_Advancednewsletter_Model_Mysql4_Segment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancednewsletter/segment');
    }

    public function addCategoryFilter($categoryId)
    {
        $this->getSelect()
            ->where("FIND_IN_SET('$categoryId', display_in_category) OR display_in_category = ".AW_Advancednewsletter_Helper_Data::ANY_CATEGORY_VALUE);
        return $this;
    }

    public function addStoreFilter($storeIds)
    {
        if (!is_array($storeIds)) $storeIds = array($storeIds);
        
        $conditions = array();
        foreach ($storeIds as $StoreId)
        {
            $conditions[] = "FIND_IN_SET('$StoreId', main_table.display_in_store)";
        }
        $conditions[] = 'display_in_store = 0';
        $this->getSelect()->where('('.implode(' OR ', $conditions).')');
        return $this;
    }

    public function addDefaultCategoryFilter($categoryId)
    {
        $this->getSelect()
            ->where("FIND_IN_SET('$categoryId', default_category) OR default_category = ".AW_Advancednewsletter_Helper_Data::ANY_CATEGORY_VALUE);
        return $this;
    }

    public function addDefaultStoreFilter($storeId)
    {
        $this->getSelect()
            ->where("FIND_IN_SET('$storeId', default_store) OR default_store = 0");
        return $this;
    }
}