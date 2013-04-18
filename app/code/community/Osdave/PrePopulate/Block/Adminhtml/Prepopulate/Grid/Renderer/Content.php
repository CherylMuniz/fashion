<?php
class Osdave_PrePopulate_Block_Adminhtml_Prepopulate_Grid_Renderer_Content extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$content = $row->getContent();
		
		if (strlen($content) > 200) {
		    $content = substr($content, 0, 200) . ' ... ';
		}

		return $content;
	}
}