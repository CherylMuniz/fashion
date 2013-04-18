<?php
/**
 * Product:     Abandoned Carts Alerts Pro for 1.3.x-1.7.0.0 - 22/05/12
 * Package:     AdjustWare_Cartalert_3.0.7_0.2.3_397295
 * Purchase ID: z5YXD3ukNeCswAIzzfleVbg4dF1tD99hcVluCaOu0k
 * Generated:   2012-10-10 10:37:37
 * File path:   app/code/local/AdjustWare/Cartalert/Block/Adminhtml/History.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Cartalert')){ qWqTqDiUyZZiREMS('bea5204d031ae14f48759a8077ba837f'); ?><?php
class AdjustWare_Cartalert_Block_Adminhtml_History extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_controller = 'adminhtml_history';
    $this->_blockGroup = 'adjcartalert';
    $this->_headerText = Mage::helper('adjcartalert')->__('Sent Alerts');
    $this->_removeButton('add'); 
    //d($this);

  }
} } 