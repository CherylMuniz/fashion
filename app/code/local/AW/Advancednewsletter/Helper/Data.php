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
class AW_Advancednewsletter_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SUBSCRIBE_BLOCK_STYLE = 'advancednewsletter/formconfiguration/formstyle';
    const SHOW_SEGMENTS_AT_CUSTOMER_REGISTER_FORM = 'advancednewsletter/general/display_segments_at_customer_registration';
    const ANY_CATEGORY_VALUE = 0;
    const MSS_PATH = 'http://ecommerce.aheadworks.com/market-segmentation-suite.html';

    /* Consts used for AN 1.* export to AN 2.* */
    const AN_SEGMENTS_ALL = 'ALL_SEGMENTS';
	const RULES_NO_CHANGE = 'no_change';

    public function getSettings($configPaths, $storeId = null, $with_default = false)
	{
		if (!is_array($configPaths)) return false;

		if ($storeId) $storeCollection = array(Mage::app()->getStore($storeId));
        else $storeCollection = Mage::getSingleton('adminhtml/system_store')->getStoreCollection();

        $settingsA = array();
		foreach ($storeCollection as $store) {
			$store_id = $store->getStoreId();
			$settingsA[$store_id] = array();
			foreach ($configPaths as $path) {
				$settingsA[$store_id][$path] = Mage::getStoreConfig($path, $store_id);
			}
		}

		if ($with_default)
			foreach ($configPaths as $path)
				$settingsA[0][$path] = Mage::getStoreConfig($path, 0);

		return $settingsA;
	}

    public function isAjaxSubscribeBlock()
    { return Mage::getStoreConfig(self::SUBSCRIBE_BLOCK_STYLE) == AW_Advancednewsletter_Model_Source_Formpositions::AJAX_LAYER; }

    public function getCategoriesOptionHash() {
        $categoriesArray = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSort('path', 'asc')
                ->load()
                ->toArray();

        $categories = array();
        foreach ($categoriesArray as $categoryID => $category)
            if (isset($category['name']))
                $categories[$categoryID]=$category['name'];

        return $categories;
    }

    public function getCategoriesArray()
	{

		$categoriesArray = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSort('path', 'asc')
                ->load()
                ->toArray();
        $categories = array(
            array(
                'label' => '--- Any ---',
                'value' => self::ANY_CATEGORY_VALUE,
            )
        );

		$nbsp = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');

        foreach ($categoriesArray as $categoryID => $category) {
            if (isset($category['name'])) {
                if ($category['level'] < 1) $category['level'] = 1;
                $categories[] = array('label' => str_repeat($nbsp.$nbsp.$nbsp.$nbsp, $category['level'] - 1) . $category['name'], 'value' => $categoryID);
            }
        }

		return $categories;
	}

    public function extensionEnabled($extensionName)
	{
		$modules = (array)Mage::getConfig()->getNode('modules')->children();
		if (!isset($modules[$extensionName])
			|| $modules[$extensionName]->descend('active')->asArray()=='false'
			|| Mage::getStoreConfig('advanced/modules_disable_output/'.$extensionName)
		) return false;
		return true;
	}

    public function addSelectAll($id)
    {
        $html = '<span>';
        $html .= '<a href="#" onclick="$$(\'#'.$id.' option\').each(function(option){option.selected = true})">';
        $html .= 'Select All';
        $html .= '</a>';
        $html .= '</span>';
        return $html;
    }

    public function magentoLess14()
    { return version_compare(Mage::getVersion(), '1.4', '<'); }

    public function showSegmentsAtCustomerRegistration()
    { return Mage::getStoreConfig(self::SHOW_SEGMENTS_AT_CUSTOMER_REGISTER_FORM); }

    public function getMssRulesOptionArray()
    {
        $mssRules = array();
        $mssRules[] = array('label' => 'None', 'value' => 0);
        foreach (Mage::getModel('marketsuite/api')->getRuleCollection() as $rule)
        {
            $mssRules[] = array('label' => $rule->getName(), 'value' => $rule->getId());
        }
        return $mssRules;
    }

    public function getMssAdvertisementText($advertisementText)
    {
        $html = '<a href="'.self::MSS_PATH.'" target="_blank">';
        $html .= $advertisementText;
        $html .= '</a>';
        return $html;
    }

    public function recursiveReplace($search, $replace, $subject)
    {
        if(!is_array($subject))
            return $subject;

        foreach($subject as $key => $value)
            if(is_string($value))
                $subject[$key] = str_replace($search, $replace, $value);
            elseif(is_array($value))
                $subject[$key] = self::recursiveReplace($search, $replace, $value);

        return $subject;
    }

    public function isArrayValuesEmpty($array)
    {
        foreach ($array as $value)
        {
            if ($value) return false;
        }
        return true;
    }

    public function getStoresForRule()
	{
		foreach (Mage::getModel('adminhtml/system_store')->getWebsiteCollection() as $website) {
            $websiteShow = false;
            foreach (Mage::getModel('adminhtml/system_store')->getGroupCollection() as $group) {
                if ($website->getId() != $group->getWebsiteId()) {
                    continue;
                }
                $groupShow = false;
                foreach (Mage::getModel('adminhtml/system_store')->getStoreCollection() as $store) {
                    if ($group->getId() != $store->getGroupId()) {
                        continue;
                    }
                    if (!$websiteShow) {
                        $options[] = array(
                            'label' => $website->getName(),
                            'value' => array()
                        );
                        $websiteShow = true;
                    }
                    if (!$groupShow) {
                        $groupShow = true;
                        $values    = array();
                    }
                    $values[] = array(
                        'label' => $store->getName(),
                        'value' => $store->getId()
                    );
                }
                if ($groupShow) {
                    $options[] = array(
                        'label' => '&nbsp;&nbsp;' . $group->getName(),
                        'value' => $values
                    );
                }
            }
        }
		return $options;
	}
}