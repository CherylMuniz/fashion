***** For new import fix *******

1. demo.cms.sql - open and replace domen htttp://demo.fash.. on new domen
2. In admin - change currency: $ on funt.
3. System -> Configuration -> Sales -> Checkout -> Checkout Options -> Allow Guest Checkout -> No

4. config System -> Configuration -> Shipping Methods -> Table Rates: 
        Enabled: yes
        Title: Standard Shipping
        Method Name: Standard
        Condition: Price vs. Destination
5. Copy table: shipping_tablerate
6. /home/www/demo/app/code/local/Oberig/Fashion/Helper/Data.php set isLiveSite fashioneyewear.co.uk
7. Oberig_LightImportExport_Helper_Data  hardcode: static $aAttributeSetInUse = array(9,10,12);



!!! Bugs !!!
Oberig_Fashion_Model_Catalog_Resource_Layer_Filter_Attribute -> getCount()   $fromPart['cat_index']['joinCondition'] = str_replace('cat_index.visibility IN(2, 4)', 'cat_index.visibility IN(2, 3, 4)', $fromPart['cat_index']['joinCondition']);
        set 'Search' visibility to 'Catalog/Search': update catalog_product_entity_int set value=4 where (attribute_id=200 and  value=3);
        
6. Set in admin: Manage Categories -> Designer Frames: change designer-frames to designer-frames-1.


************** Sage pay suite error: currency ********
Hi, I just want to share...

I experienced this error message from SagePay & it was due to problems with the encryption key - both Magento's and therefore SagePay's.

It might be worth noting that if your Magento encryption key changes (in app/etc/local.xml) - because e.g. you moved your Magento site to a new install - you'll have problems. 

The module uses Magento's encryption functions to decrypt the SagePay Encryption Key that is stored in the database. If Magento doesn't decrypt the SagePay key correctly then the module won't encrypt the data it sends to SagePay correctly. 

That's all folks. 



**** BUG MAGENESS ***
 problem: SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'created_at' in where clause is ambiguous
 resolve: 
 Mageness_Tweaks_Model_Observer
 elseif (strpos($_where, self::CREATED_AT) !== false) {
                        $_whereAll .= str_replace(self::CREATED_AT, 'main_table.' . self::CREATED_AT, $_where);
                    }


** BUG AW_Advaced Reports 

js/advancedreports/columns.js   replace (it's because of prototype library fix)
$("custom_columns").value=a.toJSON() to 
$("custom_columns").value=Object.toJSON(a)  

