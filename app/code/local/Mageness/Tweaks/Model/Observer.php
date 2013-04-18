<?php

class Mageness_Tweaks_Model_Observer
{
    const WHERE = 'where';
    const INCREMENT_COLUMN = 'increment_id';
    const CREATED_AT = 'created_at';
    const STATUS = 'status';
    const GRAND_TOTAL = 'grand_total';
    const BASE_GRAND_TOTAL = 'base_grand_total';

    public function addItemsColumn($observer)
    {
        $_block = $observer->getBlock();
        $_type = $_block->getType();
        if ($_type == 'adminhtml/sales_order_grid') {
            $_block->addColumn('total_item_count', array(
                'header'=> Mage::helper('sales')->__('Items'),
                'width' => '80px',
                'type'  => 'text',
                'index' => 'total_item_count',
                'sortable' => false,
                'filter' => false
            ));
            $_block->getColumn('real_order_id')
                ->setData('filter_index', 'main_table.increment_id');

            $collection = $_block->getCollection();
            $collection->clear();
            $_select = $collection->getSelect();
            $_select
                ->joinLeft(array('o' => 'sales_flat_order'), 'o.entity_id = main_table.entity_id', array('total_item_count'));
            $_parts = $_select->getPart(self::WHERE);
            if (is_array($_parts) && !empty($_parts)) {
                $_select->reset(self::WHERE);
                $_whereAll = '';
                foreach ($_parts as $_where) {
                    if (strpos($_where, self::INCREMENT_COLUMN) !== false) {
                        $_whereAll .= str_replace(self::INCREMENT_COLUMN, 'main_table.' . self::INCREMENT_COLUMN, $_where);
                    }
                    elseif (strpos($_where, self::CREATED_AT) !== false) {
                        $_whereAll .= str_replace(self::CREATED_AT, 'main_table.' . self::CREATED_AT, $_where);
                    }
                    elseif (strpos($_where, self::STATUS) !== false) {
                        $_whereAll .= str_replace(self::STATUS, 'main_table.' . self::STATUS, $_where);
                    }
                    elseif (strpos($_where, self::GRAND_TOTAL) !== false) {
                        if (strpos($_where, self::BASE_GRAND_TOTAL) !== false) {
                            $_whereAll .= str_replace(self::BASE_GRAND_TOTAL, 'main_table.' . self::BASE_GRAND_TOTAL, $_where);
                        }else{
                            $_whereAll .= str_replace(self::GRAND_TOTAL, 'main_table.' . self::GRAND_TOTAL, $_where);
                        }
                    }
                     else {
                        $_whereAll .= $_where;
                    }
                }
                $_select->where($_whereAll);
            }
            //echo ($_select->__toString());
            $_block->setCollection($collection);
        }
    }
}