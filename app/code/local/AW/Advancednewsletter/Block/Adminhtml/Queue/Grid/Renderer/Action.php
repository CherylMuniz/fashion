<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancednewsletter
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
/**
 * Adminhtml newsletter queue grid block action item renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class AW_Advancednewsletter_Block_Adminhtml_Queue_Grid_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = array();


        if($row->getQueueStatus()==Mage_Newsletter_Model_Queue::STATUS_NEVER) {
               if(!$row->getQueueStartAt() && $row->getSubscribersTotal()) {
                $actions[] = array(
                    'url' => $this->getUrl('*/*/start', array('id'=>$row->getId())),
                    'caption'	=> Mage::helper('newsletter')->__('Start')
                );
            }
        } else if ($row->getQueueStatus()==Mage_Newsletter_Model_Queue::STATUS_SENDING) {
            $actions[] = array(
                    'url' => $this->getUrl('*/*/pause', array('id'=>$row->getId())),
                    'caption'	=>	Mage::helper('newsletter')->__('Pause')
            );

            $actions[] = array(
                'url'		=>	$this->getUrl('*/*/cancel', array('id'=>$row->getId())),
                'confirm'	=>	Mage::helper('newsletter')->__('Do you really want to cancel the queue?'),
                'caption'	=>	Mage::helper('newsletter')->__('Cancel')
            );


        } else if ($row->getQueueStatus()==Mage_Newsletter_Model_Queue::STATUS_PAUSE) {

            $actions[] = array(
                'url' => $this->getUrl('*/*/resume', array('id'=>$row->getId())),
                'caption'	=>	Mage::helper('newsletter')->__('Resume')
            );

        }

        $actions[] = array(
            'url'		=>	$this->getUrl('*/adminhtml_template/preview',array('id'=>$row->getTemplateId())),
            'caption'   =>  Mage::helper('newsletter')->__('Preview'),
            'popup'	    =>	true
        );

        $this->getColumn()->setActions($actions);
        return parent::render($row);
    }

}
