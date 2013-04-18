<?php
/**
 * Magento Mad Capsule Media Dpd Extension
 * http://www.madcapsule.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2009 Mad Capsule Media (http://www.madcapsule.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     James Mikkelson <james@madcapsule.co.uk>
*/
class MadCapsule_Dpd_Block_Adminhtml_Dpd_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dpdGrid');
        $this->setDefaultSort('dpd_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dpd/consignment')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('dpd_id', array(
            'header'    => Mage::helper('dpd')->__('ID'),
            'align'     =>'right',
            'width'     => '20px',
            'index'     => 'transfer_id',
        ));

        $this->addColumn('service', array(
            'header'    => Mage::helper('dpd')->__('Service'),
            'align'     =>'right',
            'width'     => '140px',
            'index'     => 'service',
        ));

        $this->addColumn('shipment', array(
            'header'    => Mage::helper('dpd')->__('Shipment'),
            'align'     =>'right',
            'width'     => '10px',
            'index'     => 'shipment_id',
        ));

        $this->addColumn('order', array(
            'header'    => Mage::helper('dpd')->__('Order'),
            'align'     =>'right',
            'width'     => '10px',
            'index'     => 'order_id',
        ));


	$this->addColumn('weight', array(
            'header'    => Mage::helper('dpd')->__('Weight'),
            'align'     =>'right',
            'width'     => '10px',
            'index'     => 'weight',
        ));

        $this->addColumn('status', array(
 
            'header'    => Mage::helper('dpd')->__('Status'),
            'align'     => 'left',
            'width'     => '150px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'In Transfer Queue',
                2 => 'Transfered to Ship@Ease',
                3 => 'Returned from Ship@Ease'
            ),
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('dpd')->__('Tracking Email Address'),
            'align'     => 'left',
            'width'     => '230px',
            'index'     => 'email',
            'default'	=> 'Tracking email not sent',
        ));
 
        $this->addColumn('sent_time', array(
            'header'    => Mage::helper('dpd')->__('Queued '),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'sent',
        ));
 
        $this->addColumn('returned_time', array(
            'header'    => Mage::helper('dpd')->__('Returned'),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => 'Trackin not yet returned',
            'index'     => 'returned',
        ));  

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('dpd')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('dpd')->__('Re-send'),
                        'url'       => array('base'=> '*/*/resubmit'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => Mage::helper('dpd')->__('Cancel'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

	//$this->addExportType('*/*/exportXml', Mage::helper('dpd')->__('XML'));
    
 
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        
    }
 
 
}
