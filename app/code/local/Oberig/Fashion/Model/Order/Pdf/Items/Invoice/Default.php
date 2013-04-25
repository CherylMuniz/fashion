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
        $sku = $this->getSku($item);
        if( strpos( $this->getSku($item), 'lens' ) !== false ){ $sku = ''; }
        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($sku, 25),
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
                 if ( '' == $option['value'] ) {continue;}
                // draw options label
                $text_arr = array(); 
                $text = $option['label'];
                $text .= ": ".strip_tags($option['value']);
                if ( !in_array($option['label'], array('SPH (Sphere) right','CYL (Cylinder) right','Axis right','Near/Add right',
                                                      'SPH (Sphere) left', 'CYL (Cylinder) left', 'Axis left', 'Near/Add left')) ) {
                	$text_arr = Mage::helper('core/string')->str_split($text, 70, true, true);
                }
                $lines[][] = array(
                    'text' => $text_arr,
                    'font' => 'italic',
                    'feed' => 35
                );
                //if ($option['value']) {
                    //$_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
					//$_printValue = strip_tags($_printValue);
                    //$values = explode("\n", $_printValue);
                            //mage::d($option['value']);
                    	//if (substr($value, 0, 9) == 'Right Eye' || substr($value, 0, 8) == 'Left Eye') {
                    		$text_arr = array(' ');
                    		$val = $option['value'];
                            $emptyline = true;
                    		switch ($option['label']) {
                    		case 'SPH (Sphere) right':
                    			$eye_arr[0]['Sph']  = $val;
                    			break;
                    		case 'CYL (Cylinder) right':
                    			$eye_arr[0]['Cyl']  = $val;
                    			break;
                    		case 'Axis right':
                    			$eye_arr[0]['Axis'] = $val;
                    			break;
                    		case 'Near/Add right':
                    			$eye_arr[0]['Add']  = $val;
                    			break;
                    		case 'SPH (Sphere) left':
                    			$eye_arr[1]['Sph']  = $val;
                    			break;
                    		case 'CYL (Cylinder) left':
                    			$eye_arr[1]['Cyl']  = $val;
                    			break;
                    		case 'Axis left':
                    			$eye_arr[1]['Axis'] = $val;
                    			break;
                    		case 'Near/Add left':
                    			$eye_arr[1]['Add']  = $val;
                            default : $emptyline = false;
                    		}
                    	//}else { $text_arr = Mage::helper('core/string')->str_split($value, 70, true, true); }
                        if($emptyline){
                            $lines[][] = array(
                                'text' => $text_arr,
                                'feed' => 40
                            );
                        }
                //}
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
            //$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
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
