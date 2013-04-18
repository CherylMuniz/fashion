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
 * Adminhtml newsletter queue grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class AW_Advancednewsletter_Block_Adminhtml_Queue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('queueGrid');
        $this->setDefaultSort('start_at');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('advancednewsletter/queue_collection')
                            ->addTemplateInfo()
                            ->addSubscribersInfo();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('queue_id', array(
            'header'    =>  Mage::helper('newsletter')->__('ID'),
            'index'     =>	'queue_id',
            'width'		=>	10
        ));

        $this->addColumn('start_at', array(
            'header'    =>  Mage::helper('newsletter')->__('Queue Start'),
            'type'      =>	'datetime',
            'index'     =>	'queue_start_at',
            'gmtoffset' => true,
            'default'	=> 	' ---- '
        ));

        $this->addColumn('finish_at', array(
            'header'    =>  Mage::helper('newsletter')->__('Queue Finish'),
            'type'      => 	'datetime',
            'index'     =>	'queue_finish_at',
            'gmtoffset' => true,
            'default'	=> 	' ---- '
        ));

        $this->addColumn('template_subject', array(
            'header'    =>  Mage::helper('newsletter')->__('Subject'),
            'index'     =>	'template_subject'
        ));

         $this->addColumn('status', array(
            'header'    => Mage::helper('newsletter')->__('Status'),
            'index'		=> 'queue_status',
            'type'      => 'options',
            'options'   => array(
                Mage_Newsletter_Model_Queue::STATUS_SENT 	=> Mage::helper('newsletter')->__('Sent'),
                Mage_Newsletter_Model_Queue::STATUS_CANCEL	=> Mage::helper('newsletter')->__('Cancelled'),
                Mage_Newsletter_Model_Queue::STATUS_NEVER 	=> Mage::helper('newsletter')->__('Not Sent'),
                Mage_Newsletter_Model_Queue::STATUS_SENDING => Mage::helper('newsletter')->__('Sending'),
                Mage_Newsletter_Model_Queue::STATUS_PAUSE 	=> Mage::helper('newsletter')->__('Paused'),
            ),
            'width'     => '100px',
        ));

        $this->addColumn('subscribers_sent', array(
            'header'    =>  Mage::helper('newsletter')->__('Processed'),
               'type'		=> 'number',
            'index'		=> 'subscribers_sent'
        ));

        $this->addColumn('subscribers_total', array(
            'header'    =>  Mage::helper('newsletter')->__('Recipients'),
            'type'		=> 'number',
            'index'		=> 'subscribers_total'
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('newsletter')->__('Action'),
            'filter'	=>	false,
            'sortable'	=>	false,
            'no_link'   => true,
            'width'		=> '100px',
            'renderer'	=>	'advancednewsletter/adminhtml_queue_grid_renderer_action'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}

