<?php

class Oberig_Fashion_Model_Product_Option_Type_Text extends Mage_Catalog_Model_Product_Option_Type_Text
{
    /**
     * Return NOT formatted option value for option
     *
     * @param string $value Prepared for cart option value
     * @return string
     */	
    public function getFormattedOptionValue($value)
    {
        return $value;
    }
}