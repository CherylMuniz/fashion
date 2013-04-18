<?php

class Oberig_Fashion_Block_Catalog_Category_View extends Mage_Catalog_Block_Category_View
{
/*	private $_spectaclesCategoryBanners = array(
		146 => array('id'=> 21,'src'=>'armani.jpg'),//117
		147 => array('id'=> 13,'src'=>'bulgari.jpg'),//50
		148 => array('id'=> 14,'src'=>'carrera.jpg'),//56
		149 => array('id'=> 15,'src'=>'chanel.jpg'),//57
		151 => array('id'=> 16,'src'=>'D&G.jpg'),//49
		152 => array('id'=> 17,'src'=>'dior.jpg'),//46
		153 => array('id'=> 18,'src'=>'dkny.jpg'),//58
		154 => array('id'=> 19,'src'=>'dolce&gabanna.jpg'),//59
		155 => array('id'=> 23,'src'=>'gucci.jpg'),//47
		156 => array('id'=> 24,'src'=>'hugoboss.jpg'),//65
		157 => array('id'=> 27,'src'=>'lindberg.jpg'),//67
		158 => array('id'=> 29,'src'=>'marcjacobs.jpg'),//68,28
		159 => array('id'=> 31,'src'=>'montblanc.jpg'),//71
		160 => array('id'=> 51,'src'=>'oakley.jpg'),//86
		161 => array('id'=> 32,'src'=>'persol.jpg'),//72
		162 => array('id'=> 135,'src'=>'police.jpg'),
		163 => array('id'=> 36,'src'=>'ralphlauren.jpg'),//73
		164 => array('id'=> 34,'src'=>'prada.jpg'),//52
		165 => array('id'=> 35,'src'=>'pradasport.jpg'),//75
		166 => array('id'=> 36,'src'=>'ralphlauren.jpg'),//76
		167 => array('id'=> 37,'src'=>'rayban.jpg'),//48
		168 => array('id'=> 38,'src'=>'raybanjunior.jpg'),//87
		169 => array('id'=> 40,'src'=>'swarovski.jpg'),//80
		170 => array('id'=> 41,'src'=>'tagheuer.jpg'),//81
		171 => array('id'=> 42,'src'=>'tiffany.jpg'),//82
		172 => array('id'=> 43,'src'=>'tomford.jpg')//83
	);
	
	public function isSpectaclesCategory()
    {
		if(array_key_exists($this->getCurrentCategory()->getId(),$this->_spectaclesCategoryBanners)){
			return true;
		}
		return false;
    }
    
    public function getSpectaclesCategoryBannerUrlById($categoryId){
    	$relevantCategoryId = $this->_spectaclesCategoryBanners[$categoryId]['id'];	
    	return Mage::getModel('catalog/category')->load($relevantCategoryId)->getUrl();
    }
    
    public function getSpectaclesCategoryBannerSrcById($categoryId){
    	return $this->_spectaclesCategoryBanners[$categoryId]['src'];
    }    */
}