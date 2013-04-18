<?php
/**
 * Magento Mad Capsule Media DPD Extension
 * http://www.madcapsule.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2009 Mad Capsule Media (http://www.madcapsule.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     James Mikkelson <james@madcapsule.co.uk>
*/
class MadCapsule_Dpd_DeliveryOptions
{
    public function toOptionArray()
    {
        return array(
				array('value' => '11', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Two Day')),
				array('value' => '12', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Next Day')),
				array('value' => '13', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Next Day by 12')),
				array('value' => '14', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Next Day by 10')),
				array('value' => '16', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Saturday')),
				array('value' => '17', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Saturday by 12')),
				array('value' => '18', 'label'=>Mage::helper('adminhtml')->__('DPD Parcel -  Saturday by 10')),
				array('value' => '21', 'label'=>Mage::helper('adminhtml')->__('DPD Home EVE')),
				array('value' => '23', 'label'=>Mage::helper('adminhtml')->__('DPD Home AM')),
				array('value' => '25', 'label'=>Mage::helper('adminhtml')->__('DPD Home PM')),
				array('value' => '32', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Next Day')),
				array('value' => '33', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Before 12')),
				array('value' => '34', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Before 10')),
				array('value' => '36', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Saturday')),
				array('value' => '37', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Saturday by 12')),
				array('value' => '38', 'label'=>Mage::helper('adminhtml')->__('DPD Express Pak - Saturday by 10')),
				array('value' => '41', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Two Day')),
				array('value' => '42', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Next Day')),
				array('value' => '43', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Before 12')),
				array('value' => '44', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Before 10')),
				array('value' => '46', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Saturday')),
				array('value' => '47', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Saturday by 12')),
				array('value' => '48', 'label'=>Mage::helper('adminhtml')->__('DPD Swapit - Saturday by 10')),
				array('value' => '9', 'label'=>Mage::helper('adminhtml')->__('DPD Homecall')),
				array('value' => '19', 'label'=>Mage::helper('adminhtml')->__('DPD Classic - Road')),
				array('value' => '10', 'label'=>Mage::helper('adminhtml')->__('DPD Express - Parcel by Air')),
				array('value' => '30', 'label'=>Mage::helper('adminhtml')->__('DPD Express - Document by Air')),
				array('value' => '70', 'label'=>Mage::helper('adminhtml')->__('DPD Express - Europe by Air')),					
				array('value' => '71', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Two Day')),
				array('value' => '72', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Next Day')),
				array('value' => '73', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Before 12')),
				array('value' => '74', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Before 10')),
				array('value' => '76', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Saturday')),
				array('value' => '77', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Saturday by 12')),
				array('value' => '78', 'label'=>Mage::helper('adminhtml')->__('DPD Pallet - Saturday by 10')),
				array('value' => '81', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Two Day')),
				array('value' => '82', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Next Day')),
				array('value' => '83', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Before 12')),
				array('value' => '84', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Before 10')),
				array('value' => '86', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Saturday')),
				array('value' => '87', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Saturday by 12')),
				array('value' => '88', 'label'=>Mage::helper('adminhtml')->__('DPD Freight - Saturday by 10')),
				array('value' => '91', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Two Day')),
				array('value' => '92', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Next Day')),
				array('value' => '93', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Before 12')),
				array('value' => '94', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Before 10')),
				array('value' => '96', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Saturday')),
				array('value' => '97', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Saturday by 12')),
				array('value' => '98', 'label'=>Mage::helper('adminhtml')->__('DPD Contract Pak - Saturday by 10'))
        );
    }
}
