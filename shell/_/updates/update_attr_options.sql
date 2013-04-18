set @attribute='manufacturer';
set @attr_id = (select attribute_id from eav_attribute where attribute_code=@attribute);
set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where attribute_code=@attribute);

-- import values for manufacturer (store_id=0) --
-- do --
set @value = (select v.value from fashione_magento3.eav_attribute_option_value v 
    join fashione_magento3.eav_attribute_option o on v.option_id= o.option_id and o.attribute_id=@live_attr_id 
    left join eav_attribute_option_value fv on v.value = fv.value where fv.value is null limit 1); 
insert into eav_attribute_option (attribute_id, sort_order) VALUES (@attr_id, 0);
insert into eav_attribute_option_value (option_id, store_id, value) VALUES (LAST_INSERT_ID(),0,@value);
-- while ( @value exists) --