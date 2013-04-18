<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Block/Adminhtml/Cartalert.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ ItIfokhNBEyhuVjS('ed5eee2863854c68634195bcad2a5e46'); ?><?php
class AdjustWare_Cartalert_Block_Adminhtml_Cartalert extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
      
    $this->_addButton('generate', array(
        'label'     => Mage::helper('adjcartalert')->__('Update Queue Now'),
        'onclick'   => "location.href='".$this->getUrl('*/*/generate')."';return false;",
        'class'     => '',
    ));       
      
    $this->_controller = 'adminhtml_cartalert';
    $this->_blockGroup = 'adjcartalert';
    $this->_headerText = Mage::helper('adjcartalert')->__('Alerts Queue');
    $this->_addButtonLabel = Mage::helper('adjcartalert')->__('Add Alert');
    parent::__construct();
  }
} } 