<?php
/**
 * Adminhtml sales orders creation process controller
 */
require_once 'Mage/Adminhtml/controllers/Sales/Order/CreateController.php';
class Oberig_Lens_Adminhtml_Sales_Order_CreateController extends Mage_Adminhtml_Sales_Order_CreateController
{
    /*
     * Ajax handler to response configuration fieldset of composite product in order
     *
     * @return Mage_Adminhtml_Sales_Order_CreateController
     */
    public function configureProductToAddAction()
    {
        echo 'okk'; return;
        // Prepare data
        $productId  = (int) $this->getRequest()->getParam('id');

        $configureResult = new Varien_Object();
        $configureResult->setOk(true);
        $configureResult->setProductId($productId);
        $sessionQuote = Mage::getSingleton('adminhtml/session_quote');
        $configureResult->setCurrentStoreId($sessionQuote->getStore()->getId());
        $configureResult->setCurrentCustomerId($sessionQuote->getCustomerId());

        // Render page
        /* @var $helper Mage_Adminhtml_Helper_Catalog_Product_Composite */
        $helper = Mage::helper('adminhtml/catalog_product_composite');
        $helper->renderConfigureResult($this, $configureResult);
        echo $this->_lens($productId);
        return $this;
    }
    protected function _lens($productId){
        $productSku = Mage::getModel('catalog/product')->load($productId)->getSku();
        if(!in_array($productSku, array('lens-fullyrimmed','lens-rimless','lens-specialty','lens-standard','lens-oakley'))){ return null; }
        return "
                <script>
                    // observe for change select-option and display next dropdown.
                lens_switch(this);
                jQuery(document).ready(function(){
                    jQuery('#related_lens').attr('value', '{$productId}').attr('name', '{$productSku}');
                    jQuery('#lensOptionsTable input, #lensOptionsTable select').change(function(){
                        uncheck_options(this);
                        lens_switch(this);
                    });

                    jQuery('#lensOptionsTable .tr').first().css('display','table-row');
                    jQuery('#terms-conds').parents('table').first().remove();
                    jQuery('[sku=frame_only]').parent().remove();
                    jQuery('#_pupil_distance').remove();
                });
                </script>
        ";
    }
}
