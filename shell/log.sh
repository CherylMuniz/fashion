#!bin/bash
mysql -uroot -pf4sh1oN321 fashion << END
delete from dataflow_batch_export;
delete from dataflow_batch_import;
delete from log_customer;
delete from log_quote;
delete from log_summary;
delete from log_summary_type;
delete from log_url;
delete from log_url_info;
delete from log_visitor;
delete from log_visitor_info;
delete from log_visitor_online;
delete from index_event;
delete from report_event;
delete from report_compared_product_index;
delete from report_viewed_product_index;
delete from catalog_compare_item;
delete from am_email_log;
delete from am_notfound_log;
delete from captcha_log;
delete from magenotification_log;
delete from sendfriend_log;
END