
    -- | admin_assert              |
    -- | admin_role                |
    -- | admin_rule                |
    -- | admin_user                |
    -- | adminnotification_inbox   |

delete from admin_assert;
delete from admin_role;
delete from admin_rule;
delete from admin_user;
delete from adminnotification_inbox;

insert into admin_assert select * from fashione_magento3.admin_assert;
insert into admin_role select * from fashione_magento3.admin_role;
insert into admin_rule select * from fashione_magento3.admin_rule;
insert into admin_user select *,null,null from fashione_magento3.admin_user;
insert into adminnotification_inbox select * from fashione_magento3.adminnotification_inbox;