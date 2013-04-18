<?php
class DevNick_RoundPrice_Model_Currency extends Mage_Directory_Model_Currency
{
    public function format($price, $options=array(), $includeContainer = true, $addBrackets = false)
    {
        return $this->formatPrecision($price, 0, $options, $includeContainer, $addBrackets);
    }
    
    public function formatTxt($price, $options=array())
    {
        if (!is_numeric($price)) {
            $price = Mage::app()->getLocale()->getNumber($price);
        }
        /**
         * Fix problem with 12 000 000, 1 200 000
         *
         * %f - the argument is treated as a float, and presented as a floating-point number (locale aware).
         * %F - the argument is treated as a float, and presented as a floating-point number (non-locale aware).
         */
        $price = sprintf("%F", $price);
        $options['precision'] = 0;
       return Mage::app()->getLocale()->currency($this->getCode())->toCurrency(ceil($price), $options);
    }
}