<?php

class Oberig_CsvExport_GetController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
	{
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=\"google_merchant.csv\"");
		$collection = Mage::getModel('catalog/product')->getCollection()
		              ->addAttributeToSelect('*');

		if ($attributeSet = $this->getRequest()->getParam('attribute_set', false)) {
			$collection->addAttributeToFilter('attribute_set_id', $attributeSet);
		}
		
		$headers = array(
			'"id"',
			'"title"',
			'"description"',
			'"link"',
			'"image_link"',
			'"condition"',
			'"availability"',
			'"price"',
			'"brand"',
			'"google_product_category"',
			'"product_type"',
		);
		echo implode(',', $headers);
		echo "\n";
		$imageHelper = Mage::helper('catalog/image');
		$baseUrl = Mage::getBaseUrl();
		$baseUrl = preg_replace('#index\.php$#', '', $baseUrl);
		foreach ($collection as $p) {
			switch ($p->getAttributeSetId()) {
				case '9': $titleSuffix = ' glasses'; //Designer Frames
					break;
				case '10': $titleSuffix = ' sunglasses'; //Designer Sunglasses
					break;
				case '12': $titleSuffix = ' sunglasses'; //Prescription Sunglasses
					break;
				case '11'://Oakley
				default: $titleSuffix = '';
			};
			$o = array();
			$o[] = '"' . $p->getId() . '"';
			$o[] = '"' . str_replace('"', '\"', $p->getName() . $titleSuffix) . '"';
			$o[] = '"' . str_replace('"', '\"', $p->getDescription()) . '"';
			$o[] = '"' . str_replace('"', '\"', $baseUrl . $p->getUrlPath()) . '"';
			$o[] = '"' . str_replace('"', '\"', (string)$imageHelper->init($p, 'image')) . '"';
			$o[] = '"new"';
			$o[] = $p->isInStock() ? '"in stock"' : '"out of stock"';
			$o[] = '"' . round($p->getPrice(), 2) . ' GBP"';
			$o[] = '"' . str_replace('"', '\"', $p->getAttributeText('manufacturer')) . '"';
			$o[] = '"Clothing & Accessories > Clothing Accessories > Sunglasses"';
			$o[] = '"Clothing & Accessories > Clothing Accessories > Sunglasses"';
			echo implode(',', $o);
			echo "\n";
		}
		die();
	}
}