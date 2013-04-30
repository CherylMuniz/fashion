<?php
class Oberig_Fashion_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isLiveSite()
	{
		return 
			   Mage::app()->getRequest()->getServer('SERVER_NAME') == 'fashioneyewear.co.uk'
			|| Mage::app()->getRequest()->getServer('SERVER_NAME') == 'www.fashioneyewear.co.uk';
	}
}
