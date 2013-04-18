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
            $page  = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
            $this->insertDocumentNumber(
                $page,
                Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId()
            );
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
			
            $dy = -40;
			$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
			$page->setLineColor(new Zend_Pdf_Color_GrayScale(0));
			$page->setLineWidth(1);
			$page->drawLine(0, 200+$dy, 595, 200+$dy);
			$this->_setFontRegular($page, 10);
			if ($order instanceof Mage_Sales_Model_Order) {
				$shipment = null;
			} elseif ($order instanceof Mage_Sales_Model_Order_Shipment) {
				$shipment = $order;
				$order = $shipment->getOrder();
			}

			$page->drawText('tel +44 (0)20 3275 0045 email info@fashioneyewear.co.uk VAT no 998 174 553', 135, 208+$dy, 'UTF-8');
			$page->drawText(Mage::helper('sales')->__('Order # ').$order->getRealOrderId(), 25, 180+$dy, 'UTF-8');
			$page->drawText('Invoice Matched by:', 25, 140+$dy, 'UTF-8');
			$page->drawText('Frame Traced by:', 25, 100+$dy, 'UTF-8');
			$page->drawText('Lenses checked by:', 25, 60+$dy, 'UTF-8');
			$page->drawText('Final Check:', 400, 180+$dy, 'UTF-8');
			$page->drawText('Correct Product', 400, 160+$dy, 'UTF-8');
			$page->drawText('Prescription fitted', 400, 140+$dy, 'UTF-8');
			$page->drawText('Case/Cloth/Security tag', 400, 120+$dy, 'UTF-8');
			$page->drawText('No Damage', 400, 100+$dy, 'UTF-8');
			$page->drawText('Cleaned', 400, 80+$dy, 'UTF-8');
			$page->drawText('Checked by:', 400, 60+$dy, 'UTF-8');
            
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }
}