select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4;
select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where attribute_id='';

-- with options ( select, multiselect, boolean ) (_int, _varchar) --
select distinct(o.attribute_id), a.attribute_code,a.frontend_input,a.backend_type from eav_attribute_option o join eav_attribute a on o.attribute_id=a.attribute_id;


select attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id=4 and frontend_input not in('select','multiselect','boolean') and backend_type not in('text','decimal','static');


-- options ---
select * from eav_attribute_option where attribute_id= group by option_id;
select * from eav_attribute_option_value where option_id in ( select option_id from eav_attribute_option where attribute_id= group by option_id );




-- check synch eav -- 

select e.attribute_code, e1.attribute_code from eav_attribute e left join fashione_magento3.eav_attribute e1 on e.attribute_code = e1.attribute_code 
where e1.attribute_code is null and e.entity_type_id=4;



select attribute_id, attribute_code from eav_attribute order by attribute_code;
select * from eav_attribute where attribute_code like '%%';
select * from eav_attribute where attribute_id in ();


select * from eav_attribute_option where attribute_id = 

select * from eav_attribute_option_value where option_id in(); 
select * from eav_attribute_option_value where  value like '%%';

select * from eav_attribute_option_value where option_id in( select option_id from eav_attribute_option where attribute_id =  ); 


 select attribute_id, attribute_code, frontend_input from eav_attribute where frontend_input in('select', 'multiselect') and entity_type_id=4 limit 50;