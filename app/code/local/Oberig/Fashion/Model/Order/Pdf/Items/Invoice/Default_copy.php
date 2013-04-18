<?php
class Oberig_Fashion_Model_Order_Pdf_Items_Invoice_Default extends Mage_Sales_Model_Order_Pdf_Items_Invoice_Default
{
    /**
     * Draw item line
     *
     */
    public function draw()
    {
        $order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        $lines  = array();

        // draw Product name
        $lines[0] = array(array(
            'text' => Mage::helper('core/string')->str_split($item->getName(), 60, true, true),
            'feed' => 35,
        ));

        // draw SKU
        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($this->getSku($item), 25),
            'feed'  => 255
        );

        // draw QTY
        $lines[0][] = array(
            'text'  => $item->getQty()*1,
            'feed'  => 435
        );

        // draw Price
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($item->getPrice()),
            'feed'  => 395,
            'font'  => 'bold',
            'align' => 'right'
        );

        // draw Tax
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($item->getTaxAmount()),
            'feed'  => 495,
            'font'  => 'bold',
            'align' => 'right'
        );

        // draw Subtotal
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($item->getRowTotal()),
            'feed'  => 565,
            'font'  => 'bold',
            'align' => 'right'
        );

        // custom options
        $options = $this->getItemOptions();
        $eye_arr = array();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $text = strip_tags($option['label']);
                if ($text == 'Description') {
                	$text_arr = array(' '); 
                } else {
                	$text_arr = Mage::helper('core/string')->str_split($text, 70, true, true);
                }
                $lines[][] = array(
                    'text' => $text_arr,
                    'font' => 'italic',
                    'feed' => 35
                );

                if ($option['value']) {
                    $_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
					$_printValue = strip_tags($_printValue);
                    $values = explode("\n", $_printValue);
                    foreach ($values as $value) {
                    	if (substr($value, 0, 9) == 'Right Eye' || substr($value, 0, 8) == 'Left Eye') {
                    		$text_arr = array(' ');
                    		$val_arr = explode(':', $value);
                    		$val = trim($val_arr[1]);
                    		switch (substr($value, 0, 12)) {
                    		case 'Right Eye SP':
                    			$eye_arr[0]['Sph']  = $val;
                    			$text_arr = array();
                    			break;
                    		case 'Right Eye CY':
                    			$eye_arr[0]['Cyl']  = $val;
                    			$text_arr = array();
                    			break;
                    		case 'Right Eye Ax':
                    			$eye_arr[0]['Axis'] = $val;
                    			break;
                    		case 'Right Eye Ne':
                    			$eye_arr[0]['Add']  = $val;
                    			break;
                    		case 'Left Eye SPH':
                    			$eye_arr[1]['Sph']  = $val;
                    			break;
                    		case 'Left Eye CYL':
                    			$eye_arr[1]['Cyl']  = $val;
                    			break;
                    		case 'Left Eye Axi':
                    			$eye_arr[1]['Axis'] = $val;
                    			break;
                    		case 'Left Eye Nea':
                    			$eye_arr[1]['Add']  = $val;
                    		}
                    	} else {
                    		$text_arr = Mage::helper('core/string')->str_split($value, 70, true, true);
                    	}
                        $lines[][] = array(
                            'text' => $text_arr,
                            'feed' => 40
                        );
                    }
                }
            }
        }

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 10
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        if (count($eye_arr)) {
	        $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
	        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0));
	        $page->setLineWidth(0.5);
	        $dy = -17;
	        $page->drawRectangle(25, $pdf->y + $dy + 110- 2, 310, $pdf->y + $dy + 95 - 2);
	        $page->drawRectangle(25, $pdf->y + $dy + 95 - 2, 310, $pdf->y + $dy + 80 - 2);
	        $page->drawRectangle(25, $pdf->y + $dy + 80 - 2, 310, $pdf->y + $dy + 65 - 2);
	        $page->drawRectangle(100, $pdf->y + $dy + 110 - 2, 150, $pdf->y + $dy + 65 - 2, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
	        $page->drawRectangle(200, $pdf->y + $dy + 110 - 2, 250, $pdf->y + $dy + 65 - 2, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
	        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
	        $page->drawText('Right Eye', 30, $pdf->y + $dy + 82, 'UTF-8');
	        $page->drawText('Left Eye', 30, $pdf->y + $dy + 67, 'UTF-8');
	        $page->drawText('Sph', 105, $pdf->y + $dy + 97, 'UTF-8');
	        $page->drawText('Cyl', 155, $pdf->y + $dy + 97, 'UTF-8');
	        $page->drawText('Axis',205, $pdf->y + $dy + 97, 'UTF-8');
	        $page->drawText('Near Add', 255, $pdf->y + $dy + 97, 'UTF-8');
	        $page->drawText($eye_arr[0]['Sph'], 105, $pdf->y + $dy + 82, 'UTF-8');
	        $page->drawText($eye_arr[0]['Cyl'], 155, $pdf->y + $dy + 82, 'UTF-8');
	        $page->drawText($eye_arr[0]['Axis'],205, $pdf->y + $dy + 82, 'UTF-8');
	        $page->drawText($eye_arr[0]['Add'], 255, $pdf->y + $dy + 82, 'UTF-8');
	        $page->drawText($eye_arr[1]['Sph'], 105, $pdf->y + $dy + 67, 'UTF-8');
	        $page->drawText($eye_arr[1]['Cyl'], 155, $pdf->y + $dy + 67, 'UTF-8');
	        $page->drawText($eye_arr[1]['Axis'],205, $pdf->y + $dy + 67, 'UTF-8');
	        $page->drawText($eye_arr[1]['Add'], 255, $pdf->y + $dy + 67, 'UTF-8');
        }
        $this->setPage($page);
    }
}
