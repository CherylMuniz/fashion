#!/bin/bash
php 1catssynch.php
php 2catssynch.php
mysql -uroot -pf4sh1oN321 fashion < 3catssynch.sql
