<?php

$installer = $this;

$installer->startSetup();
$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
$installer->addAttribute('catalog_category', 'image_clickable_url',  array(
    'type'     => 'text',
    'label'    => 'Clickable Link for Category Image',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => null
));
$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'image_clickable_url',
    '11'                    //last Magento's attribute position in General tab is 10
);
$installer->endSetup();