
    --+---------------------------+
    --| Tables_in_fashion (%api%) |
    --+---------------------------+
    --| api2_acl_attribute        |
    --| api2_acl_role             |
    --| api2_acl_rule             |
    --| api2_acl_user             |
    --| api_assert                |
    --| api_role                  |
    --| api_rule                  |
    --| api_session               |
    --| api_user                  |
    --+---------------------------+

    -- delete from api2_acl_attribute; insert into api2_acl_attribute select * from fashione_magento3.api2_acl_attribute;
    -- delete from api2_acl_role; insert into api2_acl_role select * from fashione_magento3.api2_acl_role;
    -- delete from api2_acl_rule;  insert into api2_acl_rule select * from fashione_magento3.api2_acl_rule;
    -- delete from api2_acl_user; insert into api2_acl_user select * from fashione_magento3.api2_acl_user;
delete from api_assert; 
delete from api_role; 
delete from api_rule; 
delete from api_session; 
delete from api_user; 

insert into api_assert select * from fashione_magento3.api_assert;
insert into api_role select * from fashione_magento3.api_role;
insert into api_rule (
    rule_id,
    role_id,
    resource_id,
    api_privileges, 
    assert_id,
    role_type,
    api_permission
)
select 
    t1.rule_id,
    t1.role_id,
    t1.resource_id,
    t1.privileges, 
    t1.assert_id,
    t1.role_type,
    t1.permission
from fashione_magento3.api_rule t1;
insert into api_user select * from fashione_magento3.api_user;
insert into api_session select * from fashione_magento3.api_session;
