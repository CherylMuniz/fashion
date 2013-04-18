<?php
class Oberig_Fashion_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isLiveSite()
	{
		return 
			   Mage::app()->getRequest()->getServer('SERVER_NAME') == 'demo.fashioneyewear.co.uk'
			|| Mage::app()->getRequest()->getServer('SERVER_NAME') == 'www.demo.fashioneyewear.co.uk';
	}
}
