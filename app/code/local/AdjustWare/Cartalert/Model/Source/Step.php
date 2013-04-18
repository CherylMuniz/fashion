<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Model/Source/Step.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ cdcNirqQgkkqfOeY('50ccb4cda777575db8c4899cc4f6f5c5'); ?><?php
class AdjustWare_Cartalert_Model_Source_Step extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array(
            0 => array(
                'value' => '',
                'label' => '-'
            )
        );

        foreach (array('first','second','third') as $step)
            $options[] = array(
                'value'=> $step,
                'label' => Mage::helper('adjcartalert')->__(ucfirst($step). ' Email Template')
            );
        
        return $options;
    }
} } 