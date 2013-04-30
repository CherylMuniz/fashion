select count(*) from catalog_product_option o
join catalog_product_option_title t
on o.option_id = t.option_id and t.title='Please select your frame size';


select count(*) from catalog_product_option o
join catalog_product_option_title t
on o.option_id = t.option_id and t.title='Please select your frame size' and o.type = 'drop_down';
-- if != 0 --

update catalog_product_option o
join catalog_product_option_title t
on o.option_id = t.option_id and t.title='Please select your frame size' and o.type = 'drop_down'
set o.type='radio' ;


update catalog_product_option o
join catalog_product_option_title t
on o.option_id = t.option_id and t.title='Please select your frame size' and o.type = 'radio' and o.sku is null
set o.sku='frame_size' ;


select count(*) from catalog_product_option o
join catalog_product_option_title t
on o.option_id = t.option_id and t.title='Please select your frame size' and o.sku is null;