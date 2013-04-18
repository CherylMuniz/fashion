#!bin/bash
mysql --host=127.0.01 --user=root fashiondev << END
-- drop database fashiondev;
-- create database fashiondev;
END
mysql -uroot fashiondev < ../zbckp/empty_categories_fashiondev.sql
