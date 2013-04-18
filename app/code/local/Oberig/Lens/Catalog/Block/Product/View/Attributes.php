<?php

/**
 * Lens attributes block
 *
 */
class Oberig_Lens_Catalog_Block_Product_View_Attributes extends Mage_Catalog_Block_Product_View_Attributes
{
    function setProduct($product)
    {
        $this->_product = $product;
        return $this->_product;
    }
}
