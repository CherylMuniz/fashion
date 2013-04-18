-- customer_entity --
delete from customer_entity;
insert into customer_entity select *,0 from fashione_magento3.customer_entity;

-- customer tables --
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(1) and frontend_input not in('select') group by backend_type;
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='varchar';
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='int';
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='datetime';


-- varchar --
select attribute_code from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='varchar';

DROP PROCEDURE IF EXISTS _varchar;
DELIMITER ||
CREATE PROCEDURE _varchar(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=1;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into customer_entity_varchar (
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        @entity_type_id,
        @attr_id,
        e.entity_id,
        v1.value
    from fashione_magento3.customer_entity_varchar v1
    join fashione_magento3.customer_entity e1 on e1.entity_id = v1.entity_id
    join customer_entity e on e1.entity_id = e.entity_id 
    where v1.attribute_id = @live_attr_id;
END
||
DELIMITER ;


call _varchar('confirmation');
call _varchar('created_in');
call _varchar('firstname');
call _varchar('lastname');
call _varchar('middlename');
call _varchar('password_hash');
call _varchar('prefix');
call _varchar('rp_token');
call _varchar('suffix');
call _varchar('taxvat');


-- datetime -- 
select attribute_code from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='datetime';

DROP PROCEDURE IF EXISTS _datetime;
DELIMITER ||
CREATE PROCEDURE _datetime(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=1;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into customer_entity_datetime (
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        @entity_type_id,
        @attr_id,
        e.entity_id,
        t1.value
    from fashione_magento3.customer_entity_datetime t1
    join fashione_magento3.customer_entity e1 on e1.entity_id = t1.entity_id
    join customer_entity e on e1.entity_id = e.entity_id 
    where t1.attribute_id = @live_attr_id;
END
||
DELIMITER ;

call _datetime('dob');
call _datetime('rp_token_created_at');

-- int --
select attribute_code from eav_attribute where entity_type_id in(1) and frontend_input not in('select') and backend_type='int';

DROP PROCEDURE IF EXISTS _int;
DELIMITER ||
CREATE PROCEDURE _int(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=1;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into customer_entity_int (
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        @entity_type_id,
        @attr_id,
        e.entity_id,
        t1.value
    from fashione_magento3.customer_entity_int t1
    join fashione_magento3.customer_entity e1 on e1.entity_id = t1.entity_id
    join customer_entity e on e1.entity_id = e.entity_id 
    where t1.attribute_id = @live_attr_id
    ON DUPLICATE KEY UPDATE
    value = t1.value;
END
||
DELIMITER ;

call _int('default_billing');
call _int('default_shipping');

-- int select --

DROP PROCEDURE IF EXISTS _intopt;
DELIMITER ||
CREATE PROCEDURE _intopt(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=1;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

    insert into customer_entity_int (
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        @entity_type_id,
        @attr_id,
        e.entity_id,
        o.option_id
    from fashione_magento3.customer_entity_int t1
    join fashione_magento3.customer_entity e1 on e1.entity_id = t1.entity_id
    join customer_entity e on e1.entity_id = e.entity_id 
    join fashione_magento3.eav_attribute_option o1 on t1.value = o1.option_id and o1.attribute_id=@live_attr_id
    join fashione_magento3.eav_attribute_option_value v1 on o1.option_id = v1.option_id
    join eav_attribute_option_value v on v1.value = v.value 
    join eav_attribute_option o on v.option_id = o.option_id and o.attribute_id=@attr_id
    where t1.attribute_id = @live_attr_id;
END
||
DELIMITER ;
call _intopt('gender');

-- CUSTOMER_ADDRESS --
delete from customer_address_entity;
insert into customer_address_entity select * from fashione_magento3.customer_address_entity;

-- customer tables --
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') group by backend_type;
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') and backend_type='varchar';
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') and backend_type='int';
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') and backend_type='text';


-- int --

DROP PROCEDURE IF EXISTS _int;
DELIMITER ||
CREATE PROCEDURE _int(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=2;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

delete from customer_address_entity_int where attribute_id=@attr_id;
insert into customer_address_entity_int (
        value_id,
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        t1.value_id,
        2,
        @attr_id,
        t1.entity_id,
        t1.value
    from fashione_magento3.customer_address_entity_int t1
    where attribute_id=@live_attr_id;
END
||
DELIMITER ;

call _int('region_id');
call _int('vat_is_valid');
call _int('vat_request_success');
    
-- _text --
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') and backend_type='text';

delete from customer_address_entity_text;
DROP PROCEDURE IF EXISTS _text;
DELIMITER ||
CREATE PROCEDURE _text(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=2;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

delete from customer_address_entity_text where attribute_id=@attr_id;
insert into customer_address_entity_text (
        value_id,
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        t1.value_id,
        2,
        @attr_id,
        t1.entity_id,
        t1.value
    from fashione_magento3.customer_address_entity_text t1
    where attribute_id=@live_attr_id;
END
||
DELIMITER ;

call _text('street');

-- _varchar --
select  attribute_id, attribute_code, frontend_input, frontend_label, backend_type from eav_attribute where entity_type_id in(2) and frontend_input not in('select') and backend_type='varchar';

delete from customer_address_entity_varchar;
DROP PROCEDURE IF EXISTS _varchar;
DELIMITER ||
CREATE PROCEDURE _varchar(arg text)
BEGIN
    set @attribute= arg;
    set @entity_type_id=2;
    set @attr_id = (select attribute_id from eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);
    set @live_attr_id = (select attribute_id from fashione_magento3.eav_attribute where entity_type_id=@entity_type_id and attribute_code=@attribute);

delete from customer_address_entity_varchar where attribute_id=@attr_id;
insert into customer_address_entity_varchar (
        value_id,
        entity_type_id,
        attribute_id,
        entity_id,
        value
    ) select 
        t1.value_id,
        2,
        @attr_id,
        t1.entity_id,
        t1.value
    from fashione_magento3.customer_address_entity_varchar t1
    where attribute_id=@live_attr_id;
END
||
DELIMITER ;


call _varchar('city');
call _varchar('company');
call _varchar('fax');
call _varchar('firstname');
call _varchar('lastname');
call _varchar('middlename');
call _varchar('postcode');
call _varchar('prefix');
call _varchar('region');
call _varchar('suffix');
call _varchar('telephone');
call _varchar('vat_id');
call _varchar('vat_request_date');
call _varchar('vat_request_id');
--
call _varchar('country_id');