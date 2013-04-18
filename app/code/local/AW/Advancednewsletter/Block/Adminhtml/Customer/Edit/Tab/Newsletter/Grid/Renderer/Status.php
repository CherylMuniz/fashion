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
 * Adminhtml newsletter queue grid block status item renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Block_Adminhtml_Customer_Edit_Tab_Newsletter_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    protected static $_statuses;

    public function __construct()
    {
        self::$_statuses = array(
                Mage_Newsletter_Model_Queue::STATUS_SENT 	=> Mage::helper('customer')->__('Sent'),
                Mage_Newsletter_Model_Queue::STATUS_CANCEL	=> Mage::helper('customer')->__('Cancel'),
                Mage_Newsletter_Model_Queue::STATUS_NEVER 	=> Mage::helper('customer')->__('Not Sent'),
                Mage_Newsletter_Model_Queue::STATUS_SENDING => Mage::helper('customer')->__('Sending'),
                Mage_Newsletter_Model_Queue::STATUS_PAUSE 	=> Mage::helper('customer')->__('Paused'),
            );
        parent::__construct();
    }

    public function render(Varien_Object $row)
    {
        return Mage::helper('customer')->__($this->getStatus($row->getQueueStatus()));
    }

    public static function  getStatus($status)
    {
        if(isset(self::$_statuses[$status])) {
            return self::$_statuses[$status];
        }

        return Mage::helper('customer')->__('Unknown');
    }

}
