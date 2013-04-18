<script type="text/javascript">
    
    /*
    * return Object { product1_Id =  { option1_Id = "value", option2_Id = "value", ...} }
    */
    function product_ids(){
        var productIds = getSelectedProductId();
        
        for ( key in productIds ) {
            //var _regex = new RegExp( "related-checkbox" + key); //var opts_div = jQuery("input").filter(function() { return this.id.match(_regex); }).next().find('select');
            var opts_div = jQuery("#lens_options" + key + " select");;
            var opts = new Array(opts_div.length);
            productIds[key] = new Object();
            
            var checked; var tmp;
            for( var j=0; j < opts_div.length; j++ ){
                opts[j] = opts_div[j].id.match(/\d+/);                  // opts[0] = 123; opts[1] = 456; 
                tmp = opts_div[j].id.match(/\d+/);
                checked = jQuery("#select_"+opts[j] + ' option:checked').val();
                productIds[key][tmp] = checked;                         
            }
        }
        return productIds;
    }
    
    // Return object prouctId[12345] = 12345;
    function getSelectedProductId()
    {
        var value = jQuery(".checkbox-1:checked").attr('value');
        var productId = new Object();
         productId[value] = value;

        return productId;
    }
    
    function addProducts(){
        var products = product_ids();
        var product; 
        var qty = 1;
        for( var key in products ){
            product = key;
            var options = new Object();
            for( var k in products[key] ){
                options[k] = products[key][k];
            }
            ajaxAddProduct(product, options, 1);
            return;
        }
    }
    
    function ajaxAddProduct(productId, opts, q){
        var response = '';
        var addUrl = 'http://' + window.location.host + '/index.php/addproduct/cart/add/';
            jQuery.ajax({
                url: addUrl,
                data: { product: productId, options: opts, qty: q},
                dataType: "html",
                type: "POST",
                success: function(response){
                }
            });
    }
</script>
