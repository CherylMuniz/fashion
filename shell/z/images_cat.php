<?php

ini_set("memory_limit","1000M");
require_once "../app/Mage.php";
umask(0);
Mage::app();
$old_db = 'fashione_magento3';
$category_image = '45';
$category_url_key = '43';
$category_entity_type_id = '3';
$store_id = '0';

$category = Mage::getModel( 'catalog/category' );
$tree = $category->getTreeModel();
$tree->load();
$collection = $tree->getCollection();
$ids = $collection->getAllIds();

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
    $connection->query('DROP TABLE IF EXISTS `oberig_category_images_map`');
    $query = "
                CREATE TABLE `oberig_category_images_map` (
                  `entity_id` int(10) unsigned NOT NULL DEFAULT '0',
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `path_name` varchar(255) NOT NULL DEFAULT '',
                  `image` varchar(255) NOT NULL DEFAULT '',
                  `url_key` varchar(255) NOT NULL DEFAULT ''
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";
 $connection->query($query);
 
$items = $collection->getItems();
foreach($items as $item){
    $item->load();
    //mage::d( $item->getId() );
    //mage::d( $item->getName() );
    //mage::d( $item->getPath() );
    //mage::d( $item->getImage() );
    //mage::d( $item->getUrlKey() );
    //mage::d( $item->getData() );
    
    $pathArr = explode("/", $item->getPath() );
    foreach($pathArr as &$node){
        $node = Mage::getModel( 'catalog/category' )->load($node)->getName();
    }unset($node);
    $path = implode("/", $pathArr );
    //mage::d($path);
     $query = "INSERT INTO oberig_category_images_map (entity_id, name, path_name, image, url_key) VALUES(
     {$item->getId()}, 
     '{$item->getName()}', 
     '{$path}', 
     '{$item->getImage()}', 
     '{$item->getUrlKey()}'
     )";
     //echo $query;
    $connection->exec($query);
    
    //die;
    //mage::d( $item->getData() );
}

    $query = "  DROP TABLE IF EXISTS `oberig_category_mapping2`;
                CREATE TABLE `oberig_category_mapping2` (
                  `cat_new` int(10) unsigned NOT NULL DEFAULT '0',
                  `cat_old` int(10) unsigned NOT NULL DEFAULT '0',
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `path_name` varchar(255) NOT NULL DEFAULT '',
                  `image` varchar(255) NOT NULL DEFAULT '',
                  `url_key` varchar(255) NOT NULL DEFAULT ''
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";
$connection->query($query);

$query = "
    INSERT INTO oberig_category_mapping2 (
    `cat_new`,
    `cat_old`,
    `name`,
    `path_name`,
    `image`,
    `url_key`
    ) SELECT 
    c1.entity_id,
    c2.entity_id,
    c1.name,
    c1.path_name,
    c2.image,
    c2.url_key
    FROM oberig_category_images_map c1
    INNER JOIN {$old_db}.oberig_category_images_map c2
        ON c1.path_name = c2.path_name
";
echo $query;
$connection->query($query);


$query = "INSERT INTO catalog_category_entity_varchar (
        entity_type_id,
        attribute_id,
        store_id,
        entity_id,
        value
    ) SELECT 
        {$category_entity_type_id},
        {$category_image},
        {$store_id},
        m.cat_new,
        m.image
    FROM oberig_category_mapping2 m 
    ON DUPLICATE KEY UPDATE 
        value = m.image
)";
echo $query;
//$connection->query($query);

$query = "INSERT INTO catalog_category_entity_varchar (
        entity_type_id,
        attribute_id,
        store_id,
        entity_id,
        value
    ) SELECT 
        {$category_entity_type_id},
        {$category_url_key},
        {$store_id},
        m.cat_new,
        m.url_key
    FROM oberig_category_mapping2 m 
    ON DUPLICATE KEY UPDATE 
        value = m.url_key
)";
echo $query;
$connection->query($query);
//mage::ms($tree);
//mage::ms($collection);
//mage::d($items);