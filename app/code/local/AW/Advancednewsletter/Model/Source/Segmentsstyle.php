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
class AW_Advancednewsletter_Model_Source_Segmentsstyle
{
    const STYLE_CHECKBOXES = 'checkboxes';
    const STYLE_RADIO = 'radio';
    const STYLE_MULTISELECT = 'multiselect';
    const STYLE_SELECT = 'select';
    const STYLE_NONE = 'none';
    
    public function toOptionArray()
    {
        return array(
            array('value'=>self::STYLE_SELECT, 'label'=>Mage::helper('advancednewsletter')->__('Select')),
            array('value'=>self::STYLE_MULTISELECT, 'label'=>Mage::helper('advancednewsletter')->__('Multiselect')),
            array('value'=>self::STYLE_RADIO, 'label'=>Mage::helper('advancednewsletter')->__('Radio buttons')),
            array('value'=>self::STYLE_CHECKBOXES, 'label'=>Mage::helper('advancednewsletter')->__('Check boxes')),
            array('value'=>self::STYLE_NONE, 'label'=>Mage::helper('advancednewsletter')->__('None')),
        );
    }
}