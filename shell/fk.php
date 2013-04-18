<?php
mysql_connect('127.0.0.1', 'root', 'f4sh1oN321');
$dbName = "fashion";
mysql_select_db($dbName);
mysql_query("DROP TABLE IF EXISTS _invalid_foreign_keys");
mysql_query(
"CREATE TABLE _invalid_foreign_keys(
    `db` VARCHAR(64), 
    `_table` VARCHAR(64), 
    `_column` VARCHAR(64), 
    `_constraint` VARCHAR(64),
    `ref_db` VARCHAR(64),
    `ref_tbl` VARCHAR(64),
    `ref_column` VARCHAR(64),
    `invalid_key_count` INT,
    `_sql` VARCHAR(1024)
)");

$res_tables = mysql_query("SHOW TABLES");
while($tbls = mysql_fetch_assoc($res_tables))
{
    $table = $tbls['Tables_in_' . $dbName];
    $query = "
        SELECT
        `TABLE_SCHEMA`,
        `TABLE_NAME`,
        `COLUMN_NAME`,
        `CONSTRAINT_NAME`,
        `REFERENCED_TABLE_SCHEMA`,
        `REFERENCED_TABLE_NAME`,
        `REFERENCED_COLUMN_NAME`
        FROM information_schema.KEY_COLUMN_USAGE WHERE 
        `CONSTRAINT_SCHEMA` = '{$dbName}' AND
        `TABLE_NAME` = '{$table}' AND
        `REFERENCED_TABLE_SCHEMA` IS NOT NULL
    ";
    $result_t = mysql_query($query);
    while($row = mysql_fetch_assoc($result_t))
    {
        $sql = 
        "SELECT COUNT(*) as count FROM {$row['TABLE_NAME']} AS idx 
            LEFT JOIN {$row['REFERENCED_TABLE_NAME']} as base
            ON idx.{$row['COLUMN_NAME']} = base.{$row['REFERENCED_COLUMN_NAME']}
            WHERE idx.{$row['COLUMN_NAME']} IS NOT NULL
            AND base.{$row['REFERENCED_COLUMN_NAME']} IS NULL";
        $fetch = mysql_fetch_assoc(mysql_query($sql));
        $count = $fetch['count'];
        if( $count > 0 ){
            $query = "
                INSERT INTO _invalid_foreign_keys (
                    _table,
                    _column,
                    _constraint,
                    ref_tbl,
                    ref_column,
                    invalid_key_count,
                    _sql
                ) VALUES (
                    '{$row['TABLE_NAME']}',
                    '{$row['COLUMN_NAME']}',
                    '{$row['CONSTRAINT_NAME']}',
                    '{$row['REFERENCED_TABLE_NAME']}',
                    '{$row['REFERENCED_COLUMN_NAME']}',
                    '{$count}',
                    '{$sql}'
                )
            ";
            mysql_query($query);
            
            // create copy 
            /*$query = "DROP TABLE IF EXISTS {$row['TABLE_NAME']}_copy";
            mysql_query($query);
            $query = "CREATE TABLE {$row['TABLE_NAME']}_copy LIKE {$row['TABLE_NAME']}";
            mysql_query($query);
            $query = "INSERT INTO  {$row['TABLE_NAME']}_copy SELECT * FROM {$row['TABLE_NAME']}";
            mysql_query($query);
            
            // deleteing 
            
            $sql = "DELETE idx.* FROM {$row['TABLE_NAME']} AS idx 
            LEFT JOIN {$row['REFERENCED_TABLE_NAME']} as base
            ON idx.{$row['COLUMN_NAME']} = base.{$row['REFERENCED_COLUMN_NAME']}
            WHERE idx.{$row['COLUMN_NAME']} IS NOT NULL
            AND base.{$row['REFERENCED_COLUMN_NAME']} IS NULL";
            mysql_query($sql);*/
            
        }

        
    }
}


//
//select      c.*
//from        `ubloyal`.`catalog_eav_attribute` c
//left join   `ubloyal`.`eav_attribute` p
//on          c.attribute_id=p.attribute_id
//where       p.attribute_id is null;
//
//select      c.*
//from        `ubloyal`.`catalog_category_product_index` c
//left join   `ubloyal`.`core_store` p
//on          c.store_id=p.store_id
//where       p.store_id is null;
//
//select      c.*
//from        `ubloyal`.`icecat_products_feature` c
//left join   `ubloyal`.`icecat_products` p
//on          c.product_id=p.product_id
//where       p.product_id is null
//and         c.product_id is not null;
//
//already fixed // select      c.*
//from        `ubloyal`.`icecat_products_media` c
//left join   `ubloyal`.`icecat_products` p
//on          c.product_id=p.product_id
//where       p.product_id is null
//and         c.product_id is not null;

//select      c.*
//from        `ubloyal`.`icecat_products_related_old` c
//left join   `ubloyal`.`icecat_products` p
//on          c.parent_product_id=p.product_id
//where       p.product_id is null
//and         c.parent_product_id is not null;


//////////////////////////////////////////////////////////

//SELECT REFERRING.`store_id` AS "Invalid: store_id", REFERRING.* FROM `ubloyal`.`catalog_category_product_index` AS REFERRING LEFT JOIN `ubloyal`.`core_store` AS REFERRED ON (REFERRING.`store_id` = REFERRED.`store_id`) WHERE REFERRING.`store_id` IS NOT NULL AND REFERRED.`store_id` IS NULL;
//SELECT REFERRING.`attribute_id` AS "Invalid: attribute_id", REFERRING.* FROM `ubloyal`.`catalog_eav_attribute` AS REFERRING LEFT JOIN `ubloyal`.`eav_attribute` AS REFERRED ON (REFERRING.`attribute_id` = REFERRED.`attribute_id`) WHERE REFERRING.`attribute_id` IS NOT NULL AND REFERRED.`attribute_id` IS NULL;
//SELECT REFERRING.`product_id` AS "Invalid: product_id", REFERRING.* FROM `ubloyal`.`icecat_products_feature` AS REFERRING LEFT JOIN `ubloyal`.`icecat_products` AS REFERRED ON (REFERRING.`product_id` = REFERRED.`product_id`) WHERE REFERRING.`product_id` IS NOT NULL AND REFERRED.`product_id` IS NULL;
//SELECT REFERRING.`parent_product_id` AS "Invalid: parent_product_id", REFERRING.* FROM `ubloyal`.`icecat_products_related_old` AS REFERRING LEFT JOIN `ubloyal`.`icecat_products` AS REFERRED ON (REFERRING.`parent_product_id` = REFERRED.`product_id`) WHERE REFERRING.`parent_product_id` IS NOT NULL AND REFERRED.`product_id` IS NULL;
