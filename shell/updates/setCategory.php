<?php
ini_set("memory_limit","1000M");
require_once "/home/www/demo/app/Mage.php";
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

/* supply parent id */
$parentId = '258';
//$name = 'Mont Blank';

$names = array('Burberry','Swarovski','Bvlgari','Tom Ford','Carrera','Oakley','Chanel','Oliver Peoples','Dior','Paul Smith','D&G','Polo Ralf Lauren','Dsquared','Prada','Diesel','Gucci','Ray Ban','Tiffany','Hugo Boss');
foreach($names as $name){
    $category = new Mage_Catalog_Model_Category();
    $category->setName($name);
    $category->setIsActive(1);
    $category->setDisplayMode('PAGE');
    $category->setIsAnchor(1);
    $category->setIncludeInMenu(0);
     
    $parentCategory = Mage::getModel('catalog/category')->load($parentId);
    $category->setPath($parentCategory->getPath());
     try{
        $category->save();
        echo $category->getName().' - '.$category->getId()."\n";
    } catch (Exception $e ){
        echo $e->getMessage();
    }
    unset($category);
}
