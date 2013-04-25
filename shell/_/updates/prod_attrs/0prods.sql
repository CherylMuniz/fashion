insert into catalog_product_entity (
    entity_type_id,
    attribute_set_id,
    type_id,
    sku,
    has_options,
    required_options,
    created_at,
    updated_at
)
SELECT 
4,
ea.attribute_set_id,
e.type_id,
e.sku,
e.has_options,
e.required_options,
e.created_at,
e.updated_at
FROM fashione_magento3.catalog_product_entity e 
join eav_attribute_set ea on ea.attribute_set_id = e.attribute_set_id and ea.entity_type_id = 4
join eav_attribute_set ea1 on ea1.attribute_set_name = ea.attribute_set_name and ea1.entity_type_id = 4
left join catalog_product_entity fe on e.sku=fe.sku  where fe.sku is null and e.sku is not null;