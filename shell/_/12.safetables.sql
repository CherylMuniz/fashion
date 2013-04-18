create table core_config_data_bkp like core_config_data;
insert into core_config_data_bkp select * from core_config_data;
insert ignore into core_config_data select * from fashione_magento3.core_config_data;


truncate core_email_template;
insert into core_email_template select * from fashione_magento3.core_email_template;