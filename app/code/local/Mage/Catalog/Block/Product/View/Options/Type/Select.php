<?php

/**
 * Product options text type block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Product_View_Options_Type_Select
    extends Mage_Catalog_Block_Product_View_Options_Abstract
{
    /**
     * Return html for control element
     *
     * @return string
     */
    public function getValuesHtml()
    {
        //mage::d($this->getRequest()->getRequestedActionName()); die;
        $_option = $this->getOption();
        $title = null;
        switch ( $_option->getSku() ){
            case 'pupil_distance' : 
                $title = '63 (average)';
                break;
            case 'sphere_left' : 
            case 'sphere_right' : 
                $title = '0.00';
                break;
            case 'cylinder_left' : 
            case 'cylinder_right' : 
                $title = '0.00';
                break;
            case 'axis_left' : 
            case 'axis_right' : 
                $title = '0';
                break;
            case 'nearadd_left' : 
            case 'nearadd_right' : 
                $title = '+0.00';
                break;
            default : $title = '';
        }
        if( ($title || $title === '0') && $this->getRequest()->getRequestedActionName() !== 'configure' ){
            foreach($_option->getValues() as $value){
                if( $value->getTitle() == $title ){
                    $predefined = new Varien_Object;
                    $predefined->setOptions( array($_option->getId() => $value->getOptionTypeId()) );
                    $this->getProduct()->setPreconfiguredValues($predefined);
                }
            }
        }
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        // #mage::D($this->getProduct()->getPreconfiguredValues());
        $store = $this->getProduct()->getStore();

        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
            $require = ($_option->getIsRequire()) ? ' required-entry' : '';
            $extraParams = '';
            $select = $this->getLayout()->createBlock('core/html_select')
                ->setData(array(
                    'id' => 'select_'.$_option->getId(),
                    'class' => $require.' product-custom-option ',
                    'title' => $_option->getSku(),
                ));
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {
                $select->setName('options['.$_option->getid().']');
                    //->addOption('', $this->__('-- Please Select --'));
            } else {
                $select->setName('options['.$_option->getid().'][]');
                $select->setClass('multiselect'.$require.' product-custom-option');
            }
            foreach ($_option->getValues() as $_value) {
                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice(($_value->getPriceType() == 'percent'))
                ), false);
                $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . $priceStr . '',
                    array('price' => $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false), 'sku' => $_value->getSku())
                );
            }
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
                $extraParams = ' multiple="multiple"';
            }
            if (!$this->getSkipJsReloadPrice()) {
                $extraParams .= ' onchange="opConfig.reloadPrice()"';
            }
            $select->setExtraParams($extraParams);

            if ($configValue) {
                $select->setValue($configValue);
            }

            return $select->getHtml();
        }

        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX
            ) {
            $selectHtml = '<ul id="options-'.$_option->getId().'-list" class="options-list">';
            $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO:
                    $type = 'radio';
                    $class = 'radio';
                    /*if (!$_option->getIsRequire()) {
                        $selectHtml .= '<li><input type="radio" id="options_' . $_option->getId() . '" class="'
                            . $class . ' product-custom-option" name="options[' . $_option->getId() . ']"'
                            . ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"')
                            . ' value="" checked="checked" /><span class="label"><label for="options_'
                            . $_option->getId() . '">' . $this->__('None') . '</label></span></li>';
                    }*/
                    break;
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX:
                    $type = 'checkbox';
                    $class = 'checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 1;
            foreach ($_option->getValues() as $_value) {
                $count++;

                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent')
                ));

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }
                if ( 1 == count($_option->getValues()) ){ $checked = 'checked'; }
                $selectHtml .= '<li>' . '<input type="' . $type . '" sku="' . $_value->getSku() . '" class="' . $class . ' ' . $require
                    . ' product-custom-option ' . $_value->getSku() . '"' 
                    . ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"')
                    . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                    . '_' . $count . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                    . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                    . '<span class="label"><label for="options_' . $_option->getId() . '_' . $count . '">'
                    . $_value->getTitle();
                $selectHtml .= ( !empty($priceStr) ) ? '<br>' : '&nbsp&nbsp&nbsp&nbsp';
                $selectHtml .= $priceStr . '</label></span>';
                if ($_option->getIsRequire()) {
                    $selectHtml .= '<script type="text/javascript">' . '$(\'options_' . $_option->getId() . '_'
                    . $count . '\').advaiceContainer = \'options-' . $_option->getId() . '-container\';'
                    . '$(\'options_' . $_option->getId() . '_' . $count
                    . '\').callbackFunction = \'validateOptionsCallback\';' . '</script>';
                }
                if ( $checked == 'checked'  && count($_option->getValues()) == 1) { $selectHtml .= '<br />ONE SIZE ONLY'; }
                $selectHtml .= '</li>';
            }
            $selectHtml .= '</ul>';

            return $selectHtml;
        }
    }
    
    public function getValuesHtmlImg()
    {
        $_option = $this->getOption();
        
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();

        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
            $require = ($_option->getIsRequire()) ? ' required-entry' : '';
            $extraParams = '';
            $select = $this->getLayout()->createBlock('core/html_select')
                ->setData(array(
                    'id' => 'select_'.$_option->getId(),
                    'class' => $require.' product-custom-option'
                ));
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {
                $select->setName('options['.$_option->getid().']')
                    ->addOption('', $this->__('-- Please Select --'));
            } else {
                $select->setName('options['.$_option->getid().'][]');
                $select->setClass('multiselect'.$require.' product-custom-option');
            }
            foreach ($_option->getValues() as $_value) {
                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice(($_value->getPriceType() == 'percent'))
                ), false);
                $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . $priceStr . '',
                    array('price' => $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false), 'sku' => $_value->getSku())
                );
            }
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
                $extraParams = ' multiple="multiple"';
            }
            if (!$this->getSkipJsReloadPrice()) {
                $extraParams .= ' onchange="opConfig.reloadPrice()"';
            }
            $select->setExtraParams($extraParams);

            if ($configValue) {
                $select->setValue($configValue);
            }

            return $select->getHtml();
        }
        
        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX
            ) {
            $cntOptions = count( $_option->getValues() );
            $selectHtml = '<ul id="options-'.$_option->getId().'-list" class="options-list">';
            $selectHtml .= '<div class="tbl '.$_option->getSku().'" style="width:100%" >';
            $selectHtml .= '<div class="tr">';
            $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO:
                    $type = 'radio';
                    $class = 'radio';
                    break;
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX:
                    $type = 'checkbox';
                    $class = 'checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 0; $size= count( $_option->getValues() ); $i=0;
            foreach ($_option->getValues() as $_value) {
                #echo $count%4;
                if( $_option->getSku() === 'lens_color' && $count && $count%4 == 0 ){ $selectHtml .= '</div><div class="tr">'; }               // for colors
                $count++;
                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent')
                ));

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }

                $selectHtml .= '<div class="td" id = "' . $_value->getSku() . '" ><li>' 
                        . '<label for='.$_option->getId().'></label>'
                        . '<label for="options_' . $_option->getId() . '_' . $count . '"></label>'
                        . '<div class="tbl center">'
                            . '<div class="tr vmiddle">'
                                . '<div class="td vtop">'
                                        . '<label for="options_' . $_option->getId() . '_' . $count . '">'
                                        . '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'skin/frontend/default/likedigital/images/prescription/'.str_replace(array('_sv', '_bf', '_vf'), '', $_value->getSku()).'.jpg" sku="' . $_value->getSku() . '" /><br/></label>'
                                . '</div>';
                                $popup = Mage::getBaseDir().'/skin/frontend/default/likedigital/images/prescription/popups/'.str_replace(array('_sv', '_bf', '_vf'), '', $_value->getSku()).'_popup.jpg';
                                
                                $popupImg = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'skin/frontend/default/likedigital/images/prescription/popups/'.str_replace(array('_sv', '_bf', '_vf'), '', $_value->getSku()).'_popup.jpg';
                                
                                // ****** special anti-scratch/anti-glare popup fix *************** //
                                if( strpos($_value->getSku(),'coating_anti') !== false ){
                                    if( 'Prescription Sunglasses' == Mage::getModel('catalog/category')->load( Mage::registry('current_product')->getCategoryId() )->getParentCategory()->getName() || 
                                        'Prescription Sunglasses' == Mage::getModel('catalog/category')->load( Mage::registry('current_product')->getCategoryId() )->getName()
                                    ){
                                        $popupImg = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'skin/frontend/default/likedigital/images/prescription/popups/'.str_replace(array('_sv', '_bf', '_vf'), '', $_value->getSku()).'_popup0.jpg';
                                    }
                                }
                                // ************************************************************** //
                                
                                if( file_exists($popup) ){
                                $selectHtml .= 
                                     '<div class="td vbottom">
                                            <div class="i_info"><img src = "'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'skin/frontend/default/likedigital/images/i_info.png"/></div><div class="option_popup" style="display:none"><img src="'.$popupImg.'" /></div>'
                                    . '</div>';
                                }
                                $selectHtml .= 
                              '</div>'
                            . '<div class="tr vmiddle">'
                                . '<div class="td vtop">'
                                    . '<input type="' . $type . '" sku="' . $_value->getSku() . '" class="' . $class . ' ' . $require
                                    . ' product-custom-option ' . $_value->getSku() . '"' 
                                    . ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"')
                                    . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                                    . '_' . $count . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                                    . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                                    . '<label for='.$_option->getId().'></label>'
                                    . '<span class="label" style="text-align:center">'
                                        . '<label for="options_' . $_option->getId() . '_' . $count . '">'
                                            . $_value->getTitle() . '<br>' . $priceStr
                                        . '</label>'
                                    . '</span>'
                                . '<div>'
                            . '</div>'
                        . '</div>'

                ;
                $i++;
                if ($_option->getIsRequire()) {
                    $selectHtml .= '<script type="text/javascript">' . '$(\'options_' . $_option->getId() . '_'
                    . $count . '\').advaiceContainer = \'options-' . $_option->getId() . '-container\';'
                    . '$(\'options_' . $_option->getId() . '_' . $count
                    . '\').callbackFunction = \'validateOptionsCallback\';' . '</script>';
                }
                $selectHtml .= '</li></div>';
            }
            $selectHtml .= '</div></div>';
            $selectHtml .= '</ul>';

            return $selectHtml;
        }
    }

}
/*
SELECT `e`.*, IF(at_is_active.value_id > 0, at_is_active.value, at_is_active_default.value) AS `is_active`, IF(at_include_in_menu.value_id > 0, at_include_in_menu.value, at_include_in_menu_default.value) AS `include_in_menu`, `core_url_rewrite`.`request_path` FROM `catalog_category_entity` AS `e` INNER JOIN `catalog_category_entity_int` AS `at_is_active_default` ON (`at_is_active_default`.`entity_id` = `e`.`entity_id`) AND (`at_is_active_default`.`attribute_id` = '42') AND `at_is_active_default`.`store_id` = 0 LEFT JOIN `catalog_category_entity_int` AS `at_is_active` ON (`at_is_active`.`entity_id` = `e`.`entity_id`) AND (`at_is_active`.`attribute_id` = '42') AND (`at_is_active`.`store_id` = 1) INNER JOIN `catalog_category_entity_int` AS `at_include_in_menu_default` ON (`at_include_in_menu_default`.`entity_id` = `e`.`entity_id`) AND (`at_include_in_menu_default`.`attribute_id` = '67') AND `at_include_in_menu_default`.`store_id` = 0 LEFT JOIN `catalog_category_entity_int` AS `at_include_in_menu` ON (`at_include_in_menu`.`entity_id` = `e`.`entity_id`) AND (`at_include_in_menu`.`attribute_id` = '67') AND (`at_include_in_menu`.`store_id` = 1) LEFT JOIN `core_url_rewrite` ON (core_url_rewrite.category_id=e.entity_id) AND (core_url_rewrite.is_system=1 AND core_url_rewrite.product_id IS NULL AND core_url_rewrite.store_id='1' AND id_path LIKE 'category/%') WHERE (`e`.`entity_type_id` = '3') AND (`e`.`entity_id` IN('4', '5', '8', '9', '10', '11', '13', '12', '14', '175', '15', '16', '17', '18', '19', '20', '21', '22', '24', '23', '25', '26', '45', '27', '28', '29', '30', '31', '32', '33', '35', '36', '37', '38', '133', '134', '34', '39', '169', '172', '180', '181', '182', '183', '184', '6', '74', '48', '47', '44', '50', '49', '40', '43', '53', '52', '51', '196', '54', '55', '41', '56', '57', '58', '59', '60', '61', '75', '46', '64', '62', '63', '65', '76', '42', '66', '67', '68', '69', '70', '71', '72', '73', '194', '217', '225', '226', '7', '78', '160', '79', '80', '81', '82', '83', '84', '85', '142', '161', '86', '162', '87', '88', '141', '89', '90', '91', '92', '93', '94', '95', '96', '97', '98', '99', '100', '101', '165', '163', '164', '102', '103', '104', '105', '170', '171', '173', '177', '178', '190', '191', '192', '193', '195', '201', '106', '107', '108', '109', '110', '111', '112', '113', '114', '115', '116', '117', '118', '166', '119', '120', '121', '122', '123', '124', '125', '126', '127', '128', '129', '130', '131', '132', '139', '140', '167', '168', '174', '176', '179', '185', '186', '187', '188', '189', '202', '227', '228', '135', '136', '137', '199', '197', '216', '198', '200', '203', '204', '205', '206', '207', '208', '209', '210', '211', '212', '213', '214', '215', '218', '138', '143', '144', '145', '146', '147', '148', '149', '150', '151', '159', '152', '153', '154', '155', '156', '157', '158', '219', '220', '221', '222', '223', '224', '229', '230', '231', '232', '233', '234', '235')) AND (`e`.`entity_id` NOT IN('4', '28', '51', '54', '55', '63', '74', '78', '84', '87', '88', '93', '94', '96', '97', '113', '122', '123', '161', '162', '172', '173', '174', '180', '181', '182', '183', '184', '185', '186', '187', '188', '189', '190', '191', '192', '193', '195', '235')) AND (IF(at_is_active.value_id > 0, at_is_active.value, at_is_active_default.value) = '1') AND (IF(at_include_in_menu.value_id > 0, at_include_in_menu.value, at_include_in_menu_default.value) = '1') 
*/