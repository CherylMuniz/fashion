<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Model/Customer/Account/Observer.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ ikiciBqCaeeqIakY('e40a59ace5be1879660420d5a84d42d7'); ?><?php
class AdjustWare_Cartalert_Model_Customer_Account_Observer extends Mage_Core_Model_Abstract
{
    public function onControllerFrontInitRouters($observer)
    {
        if(!Mage::registry('aitpagecache_check_14') && Mage::getConfig()->getNode('modules/Aitoc_Aitpagecache/active')==='true')
        {
            if(file_exists(Mage::getBaseDir('magentobooster').DS.'use_cache.ser'))
            {
                Mage::register('aitpagecache_check_14', 1);
            }
            elseif(file_exists(Mage::getBaseDir('app/etc').DS.'use_cache.ser'))
            {
                Mage::register('aitpagecache_check_13', 1);
            }
        }
    }
} } 