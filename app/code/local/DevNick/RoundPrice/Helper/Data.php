<?php
class DevNick_RoundPrice_Helper_Data extends Mage_Checkout_Helper_Data
{
    public function formatPrice($price)
    {
        return $this->getQuote()->getStore()->formatPrice(ceil($price));
    }
}