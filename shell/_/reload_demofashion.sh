#!bin/bash
mysql --host=127.0.01 --user=root demofashion << END
drop database demofashion;
create database demofashion;
END
mysql -uroot demofashion < ../zbckps/clear_demofashion.sql
mysql --host=127.0.01 --user=root demofashion << END
update core_config_data set value='http://demofashion.loc/' where path in('web/unsecure/base_url','web/secure/base_url');
insert ignore into core_config_data  (path, value) values('dev/restrict/allow_ips', '');
insert ignore into core_config_data (path, value) values ('dev/debug/template_hints', 0), ( 'dev/debug/template_hints_blocks', 0);
insert ignore into  core_config_data (path, value) values ('dev/debug/profiler', 0);
insert ignore into core_config_data (path, value) values ('dev/log/active', 1), ( 'dev/log/file', 'system.log'), ( 'dev/log/exception_file', 'exception.log');
insert ignore into  core_config_data (path, value) values ('admin/security/use_form_key', 0);
insert ignore into  core_config_data (path, value) values ('web/cookie/cookie_lifetime', 360000);
update core_config_data set value =1 where  path in ('dev/log/active');
update core_config_data set value = 0 where path = 'admin/security/use_form_key';
update core_config_data set value=3600000 where path in('web/cookie/cookie_lifetime');
update core_config_data set value = 'likedigital' where path in('design/theme/template', 'design/theme/skin', 'design/theme/layout');
END
