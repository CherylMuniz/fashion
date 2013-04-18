<?php

class Oberig_Fashion_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice
{
	public function getPdf($invoices = array())
	{
		$this->_beforeGetPdf();
		$this->_initRenderer('invoice');
	
		$pdf = new Zend_Pdf();
		$this->_setPdf($pdf);
		$style = new Zend_Pdf_Style();
		$this->_setFontBold($style, 10);
	
		foreach ($invoices as $invoice) {
			if ($invoice->getStoreId()) {
				Mage::app()->getLocale()->emulate($invoice->getStoreId());
				Mage::app()->setCurrentStore($invoice->getStoreId());
			}
			$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
			$page->translate(0,-60);
	
			$pdf->pages[] = $page;
	
			$order = $invoice->getOrder();
            $store = Mage::app()->getStore()->getStoreId();
			/* Add image */
			$image = Mage::getStoreConfig('sales/identity/logo', $store);
			if ($image) {
				#$image = Mage::getStoreConfig('system/filesystem/media', $store) . '/sales/store/logo/' . $image;
				$image = Mage::getBaseDir('media') . '/sales/store/logo/' . $image;
				if (is_file($image)) {
					$image = Zend_Pdf_Image::imageWithPath($image);
					$page->drawImage($image, 25, 840, 225, 890);
				}
			}
	
			/* Add address */
	        $this->_setFontRegular($page, 5);
	        $this->y = 825;
	        foreach (explode("\n", Mage::getStoreConfig('sales/identity/address', $store)) as $value){
	            if ($value!=='') {
	                $page->drawText(trim(strip_tags($value)), 25, $this->y, 'UTF-8');
	                $this->y -=7;
	            }
	        }
	
			/* Add head */
			$this->insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId()));
	
	
			$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
			$this->_setFontRegular($page);
			$page->drawText(Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId(), 35, 780, 'UTF-8');
	
			/* Add table */
			#$page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
			#$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
			#$page->setLineWidth(0.5);
			#$page->drawRectangle(25, $this->y, 570, $this->y -15);
			$page->translate(0,60);
			$this->y -=70;
	
			/* Add table head */
			$page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
			$page->drawText(Mage::helper('sales')->__('Products'), 35, $this->y, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('SKU'), 255, $this->y, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');
	
			$this->y -=15;
	
			$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
	
			/* Add body */
			foreach ($invoice->getAllItems() as $item){
				if ($item->getOrderItem()->getParentItem()) {
					continue;
				}
	
				if ($this->y < 15) {
					$page = $this->newPage(array('table_header' => true));
				}
	
				/* Draw item */
				$page = $this->_drawItem($item, $page, $order);
			}
	
			/* Add totals */
			$page = $this->insertTotals($page, $invoice);
			
			if ($this->y < 225) {
				$page = $this->newPage(array('table_header' => true));
			}
			$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
			$page->setLineColor(new Zend_Pdf_Color_GrayScale(0));
			$page->setLineWidth(1);
			$page->drawLine(0, 210, 595, 210);
			$this->_setFontRegular($page);
			if ($order instanceof Mage_Sales_Model_Order) {
				$shipment = null;
			} elseif ($order instanceof Mage_Sales_Model_Order_Shipment) {
				$shipment = $order;
				$order = $shipment->getOrder();
			}
			$page->drawText('tel +44 (0)20 3275 0045 email info@fashioneyewear.co.uk VAT no 998 174 553', 135, 220, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Order # ').$order->getRealOrderId(), 25, 180, 'UTF-8');
			$page->drawText('Invoice Matched by:', 25, 140, 'UTF-8');
			$page->drawText('Frame Traced by:', 25, 100, 'UTF-8');
			$page->drawText('Lenses checked by:', 25, 60, 'UTF-8');
			$page->drawText('Final Check:', 400, 180, 'UTF-8');
			$page->drawText('Correct Product', 400, 160, 'UTF-8');
			$page->drawText('Prescription fitted', 400, 140, 'UTF-8');
			$page->drawText('Case/Cloth/Security tag', 400, 120, 'UTF-8');
			$page->drawText('No Damage', 400, 100, 'UTF-8');
			$page->drawText('Cleaned', 400, 80, 'UTF-8');
			$page->drawText('Checked by:', 400, 60, 'UTF-8');
			
			if ($invoice->getStoreId()) {
				Mage::app()->getLocale()->revert();
			}
		}
	
		$this->_afterGetPdf();
	
		return $pdf;
	}
	protected function _setFontRegular($object, $size = 7)
    {
		$size = round($size * 1.3);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontBold($object, $size = 7)
    {
		$size = round($size * 1.3);
		$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontItalic($object, $size = 7)
    {
		$size = round($size * 1.3);
		$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
	
	/**
     * Format address
     *
     * @param string $address
     * @return array
     */
    protected function _formatAddress($address)
    {
        $return = array();
        foreach (explode('|', $address) as $str) {
            foreach (Mage::helper('core/string')->str_split($str, 60, true, true) as $part) {
                if (empty($part)) {
                    continue;
                }
                $return[] = $part;
            }
        }
        return $return;
    }
    
    public function newPage(array $settings = array())
    {
    	/* Add new table head */
    	$page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
    	$this->_getPdf()->pages[] = $page;
    	$this->y = 800;
    
    	if (!empty($settings['table_header'])) {
    		$this->_setFontRegular($page);
    		#$page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
    		#$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
    		#$page->setLineWidth(0.5);
    		#$page->drawRectangle(25, $this->y, 570, $this->y-15);
    		$this->y -=10;
    
    		$page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
    		$page->drawText(Mage::helper('sales')->__('Product'), 35, $this->y, 'UTF-8');
    		$page->drawText(Mage::helper('sales')->__('SKU'), 255, $this->y, 'UTF-8');
    		$page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
    		$page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
    		$page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
    		$page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');
    
    		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    		$this->y -=20;
    	}
    
    	return $page;
    }
}