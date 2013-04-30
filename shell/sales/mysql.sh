#!/bin/bash
mysql -uroot -pf4sh1oN321 fashion < customers.sql
mysql -uroot -pf4sh1oN321 fashion < wishlist.sql
mysql -uroot -pf4sh1oN321 fashion < orders.sql
mysql -uroot -pf4sh1oN321 fashion < quote.sql
mysql -uroot -pf4sh1oN321 fashion < creditmemo.sql
mysql -uroot -pf4sh1oN321 fashion < sagepay.sql
##mysql -uroot -pf4sh1oN321 fashion < salesrule.sql
mysql -uroot -pf4sh1oN321 fashion < fororder.sql
mysql -uroot -pf4sh1oN321 fashion < messages.sql
