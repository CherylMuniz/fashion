<?php
// mysql fast and sequre rename database
$login = 'root';
$pass = 'f4sh1oN321';
$host = '127.0.0.1';
mysql_connect($host, $login , $pass);
$old = "fashiondev2";
$new = "fashiondev";
mysql_select_db($old);

mysql_query("CREATE DATABASE {$new}");
$res_tables = mysql_query("SHOW TABLES");
while($tbls = mysql_fetch_assoc($res_tables))
{
    $table = $tbls['Tables_in_' . $old];
    mysql_query("RENAME TABLE {$old}.{$table} TO {$new}.{$table}");
}