<?php
/**
 * Magento Mad Capsule Media Dpd Extension
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

class MadCapsule_Dpd_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function niceServiceNames($service)
		{
			$serviceNames = array(
						11 => 'DPD Parcel -  Two Day',
						12 => 'DPD Parcel -  Next Day',
						13 => 'DPD Parcel -  Next Day by 12',
						14 => 'DPD Parcel -  Next Day by 10',
						16 => 'DPD Parcel -  Saturday',
						17 => 'DPD Parcel -  Saturday by 12',
						18 => 'DPD Parcel -  Saturday by 10',
						21 => 'DPD Home EVE',
						23 => 'DPD Home AM',
						25 => 'DPD Home PM',
						32 => 'DPD Express Pak - Next Day',
						33 => 'DPD Express Pak - Before 12',
						34 => 'DPD Express Pak - Before 10',
						36 => 'DPD Express Pak - Saturday',
						37 => 'DPD Express Pak - Saturday by 12',
						38 => 'DPD Express Pak - Saturday by 10',
						41 => 'DPD Swapit - Two Day',
						42 => 'DPD Swapit - Next Day',
						43 => 'DPD Swapit - Before 12',
						44 => 'DPD Swapit - Before 10',
						46 => 'DPD Swapit - Saturday',
						47 => 'DPD Swapit - Saturday by 12',
						48 => 'DPD Swapit - Saturday by 10',
						9 => 'DPD Homecall',
						19 => 'DPD Classic - Road',
						10 => 'DPD Express - Parcel by Air',
						30 => 'DPD Express - Document by Air',
						70 => 'DPD Express - Europe by Air',											
						71 => 'DPD Pallet - Two Day',
						72 => 'DPD Pallet - Next Day',
						73 => 'DPD Pallet - Before 12',
						74 => 'DPD Pallet - Before 10',
						76 => 'DPD Pallet - Saturday',
						77 => 'DPD Pallet - Saturday by 12',
						78 => 'DPD Pallet - Saturday by 10',						
						81 => 'DPD Freight - Two Day',
						82 => 'DPD Freight - Next Day',
						83 => 'DPD Freight - Before 12',
						84 => 'DPD Freight - Before 10',
						86 => 'DPD Freight - Saturday',
						87 => 'DPD Freight - Saturday by 12',
						88 => 'DPD Freight - Saturday by 10',						
						91 => 'DPD Contract Pak - Two Day',
						92 => 'DPD Contract Pak - Next Day',
						93 => 'DPD Contract Pak - Before 12',
						94 => 'DPD Contract Pak - Before 10',
						96 => 'DPD Contract Pak - Saturday',
						97 => 'DPD Contract Pak - Saturday by 12',
						98 => 'DPD Contract Pak - Saturday by 10'
					     );	
            if (empty($service)) return array(); //oberig fix
			return $serviceNames[$service];

		}

	public function getWeight($shipment,$real = null)
		{
			$shipmentWeight = 0;
			if($real==2)
			{
				$shipment = Mage::getModel("sales/order_shipment")->load($shipment);
			}else{
				$shipment = Mage::getModel("sales/order_shipment")->loadByIncrementId($shipment);
			}

        		foreach ($shipment->getItemsCollection() as $item) {
            			if (!$item->getOrderItem()->getParentItem()) {
						$shipmentWeight = $shipmentWeight + ($item->getWeight()*$item->getQty());
            			}
        		}
			return $shipmentWeight;
		}
}

