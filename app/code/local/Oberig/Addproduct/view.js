function product_ids(){
    var productIds = getSelectedProductIds();
    
    for ( key in productIds ) {
        var _regex = new RegExp( "related-checkbox" + key);
        var opts_div = jQuery("input").filter(function() { return this.id.match(_regex); }).next().find('select');
        var opts = new Array(opts_div.length);
        productIds[key] = new Object();
        
        var checked; var tmp;
        for( var j=0; j < opts_div.length; j++ ){
            opts[j] = opts_div[j].id.match(/\d+/);
            tmp = opts_div[j].id.match(/\d+/);
            checked = jQuery("#select_"+opts[j] + ' option:checked').val();
            productIds[key][tmp] = checked;
        }
    }
    return productIds;
}

function getSelectedProductIds(){
    var products = jQuery(".checkbox:checked").filter(function() { return this.id.match(/related-checkbox\d+/); });
    var productIds = new Object();
    for(var i=0; i<products.length; i++){
        var value = products[i].id.match(/\d+/);
        productIds[value] = value;
    }
    return productIds;
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
