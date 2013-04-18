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
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Block_Adminhtml_Customer_Edit_Tab_Newsletter extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('advancednewsletter/customer/tab/newsletter.phtml');
    }

    public function initForm()
    {
        $customer = Mage::registry('current_customer');
        $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($customer->getEmail());
        Mage::register('subscriber', $subscriber);
        return $this;
    }

    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('advancednewsletter/adminhtml_customer_edit_tab_newsletter_grid','advancednewsletter.grid')
        );
        return parent::_prepareLayout();
    }

    public function getSegments()
    {
        return Mage::getModel('advancednewsletter/segment')->getCollection();
    }

    public function isChecked($segment)
    {
        return in_array($segment->getCode(), $this->getSubscriber()->getSegmentsCodes());
    }

    public function getSubscriber()
    {
        if (!Mage::registry('subscriber'))
        {
            $customer = Mage::registry('current_customer');
            $subscriber = Mage::getModel('advancednewsletter/subscriber')->loadByEmail($customer->getEmail());
            Mage::register('subscriber', $subscriber);
        }
        return Mage::registry('subscriber');
    }
}
