<?php
class DevNick_RoundPrice_Model_Store extends Mage_Core_Model_Store
{
    public function formatPrice($price, $includeContainer = true)
    {
	$price = ceil($price);
        if ($this->getCurrentCurrency()) {
            return $this->getCurrentCurrency()->format($price, array(), $includeContainer);
        }
        return $price;
    }
}
