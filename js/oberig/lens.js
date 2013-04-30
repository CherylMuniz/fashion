
// +++++++++++++++++++++++++++++++++ FUNCTION DEFINITION ++++++++++++++++++++++++++++++++++++++ //

function lens_switch(obj){
    jQuery('#lensOptionsTable').siblings().show();
    var lens = jQuery("#related_lens").attr('name');
    switch(lens){
        case 'lens-standard' :
            standard();
            uncheck_standard_options(obj);
            break;
        case 'lens-rimless' :
            rimless();
            break;
        case 'lens-fullyrimmed' :
            fullyrimmed();
            uncheck_fullyrimmed_options(obj);
            break;
        case 'lens-specialty' :
            specialty();
            break;
        case 'lens-oakley' :
            oakley();
            uncheck_oakley_options(obj);
            break;
    }
    setCheckedOptions(obj);
    jQuery('.option_popup').hide();
}

function hide_lenses(){
    jQuery('#lens_type').siblings().hide();
    jQuery('#lensOptionsTable').siblings().hide();
    // jQuery("#lensOptionsTable, #lensPrescriptionTable").find(':checked, :selected').attr('checked', false).attr('selected', false);
    jQuery("#lensOptionsTable").find(':checked, :selected').attr('checked', false).attr('selected', false);
    jQuery("#lens_type .frame_only").attr('checked', true); 
}
function uncheck_options(obj){
        var curLens = jQuery(obj).attr('sku');
        if(curLens == 'single' || curLens == 'varifocal'  || curLens == 'frame_only' ){ //don't remove it! curLens bases on 'this' and can be not lens at all.
            jQuery("#lens_thickness").find(':checked').attr('checked', false);
            jQuery("#lens_tint").find(':checked').attr('checked', false);
            jQuery("#lens_coating").find(':checked').attr('checked', false);
            jQuery("#lens_color").find(':checked').attr('checked', false);
        }
        switch(curLens){
          case 'single' :
                jQuery("#lens_varifocal_type").find(':checked').attr('checked', false);
                break;
          case 'varifocal' :
                jQuery("#lens_glasses_for").find(':checked').attr('checked', false);
                break;
          case 'frame_only' :
                jQuery("#lens_glasses_for").find(':checked').attr('checked', false);
                jQuery("#lens_varifocal_type").find(':checked').attr('checked', false);
                break;
        }
        if (typeof opConfig !== 'undefined' ){
            opConfig.reloadPrice();
        }
}
function uncheck_oakley_options(obj){
    if(!obj.id){ return; }
    var tintOpt = jQuery(obj).attr('sku').match(/tint/);
    if(tintOpt){
        jQuery("#lens_color").find(':checked').attr('checked', false);
    }
    
    var varifocalOpt = jQuery(obj).attr('sku').match(/varifocal/);
    if(varifocalOpt){
        jQuery("#lens_color, #lens_tint").find(':checked').attr('checked', false);
    }
}
function uncheck_standard_options(obj){
    if(!obj.id){ return; }
    var tintDepthOpt = jQuery(obj).attr('sku').match(/tint_depth/);
    if( !tintDepthOpt ){
        if(     jQuery(obj).attr('sku').match(/tint/)               ) {     jQuery("#lens_color,#lens_tint_depth").find(':checked').attr('checked', false);     }
        if(     jQuery(obj).attr('sku') == 'tint_polarised'         ) {     jQuery("#tint_depth_standard").find('.radio').attr('checked', true);                }
    }
    var varifocalOpt = jQuery(obj).attr('sku').match(/varifocal/);
    if(varifocalOpt){
        jQuery("#lens_coating, #lens_thickness").find(':checked').attr('checked', false);
        if(     jQuery(obj).attr('sku') == 'varifocal_basic'       ){                  jQuery("#coating_anti").find('.radio').attr('checked', true);            }
    }
    var thicknessOpt = jQuery(obj).attr('sku').match(/thickness/);
    if(thicknessOpt){
        jQuery("#lens_tint,#lens_tint_depth").find(':checked').attr('checked', false);
    }
}
function uncheck_fullyrimmed_options(obj){
    if(!obj.id){ return; }
    var thicknessOpt = jQuery(obj).attr('sku').match(/thickness/);
    if(thicknessOpt){
        jQuery("#lens_tint").find(':checked').attr('checked', false);
    }
    
    var coatingOpt = jQuery(obj).attr('sku').match(/coating/);
    if(coatingOpt){
        //jQuery("#lens_tint").find(':checked').attr('checked', false);
    }
    var tintOpt = jQuery(obj).attr('sku').match(/tint/);
    if(tintOpt){
        jQuery("#lens_coating").find(':checked').attr('checked', false);
    }
    
    var varifocalBasicOpt = jQuery(obj).attr('sku').match(/varifocal_basic/);
    if(varifocalBasicOpt){
        jQuery("#lens_thickness").find(':checked').attr('checked', false);
    }
}

function setCheckedOptions(obj){
    if(!obj.id){ return; }
    switch( jQuery('#'+obj.id).attr('sku') ){
        case 'single' : 
            jQuery('#lens_glasses_for').find('.radio').first().attr('checked', true);
            break;
        case 'varifocal' : 
            //jQuery('#lens_varifocal_type').find('.radio').first().attr('checked', true);
            break;
    }
}


function show_i_info(){
    jQuery('.i_info').mouseover(function(){
        jQuery(this).next().show();
        return;
        });
    jQuery('.i_info').mouseleave(function(){
        jQuery(this).next().hide();
        });
}


function single_global(){
    jQuery('#lens_glasses_for').css('display','table-row');
        jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row');
    jQuery('#lens_varifocal_type').hide();
}
function bifocal_global(){
    jQuery('#lens_glasses_for').hide();
    jQuery('#lens_varifocal_type').hide();
    jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row');
}
function varifocal_global(){
    jQuery('#lens_glasses_for').hide();
    jQuery('#lens_varifocal_type, #lens_varifocal_type .tr').css('display','table-row');
}


function thickness_single(){
    jQuery('#thickness_standard_sv,#thickness_thin_sv,#thickness_thinner_sv,#thickness_thinnest_sv').show();
    jQuery('#thickness_standard_bf,#thickness_thin_bf,#thickness_thinner_bf,#thickness_thinnest_bf').hide();
    jQuery('#thickness_standard_vf,#thickness_thin_vf,#thickness_thinner_vf,#thickness_thinnest_vf').hide();
}
function thickness_bifocal(){
    jQuery('#thickness_standard_sv,#thickness_thin_sv,#thickness_thinner_sv,#thickness_thinnest_sv').hide();
    jQuery('#thickness_standard_bf,#thickness_thin_bf,#thickness_thinner_bf,#thickness_thinnest_bf').show();
    jQuery('#thickness_standard_vf,#thickness_thin_vf,#thickness_thinner_vf,#thickness_thinnest_vf').hide();
}
function thickness_varifocal(){
    jQuery('#thickness_standard_sv,#thickness_thin_sv,#thickness_thinner_sv,#thickness_thinnest_sv').hide();
    jQuery('#thickness_standard_bf,#thickness_thin_bf,#thickness_thinner_bf,#thickness_thinnest_bf').hide();
    jQuery('#thickness_standard_vf,#thickness_thin_vf,#thickness_thinner_vf,#thickness_thinnest_vf').show();
}

function coating_single(){
    jQuery('#coating_unco_sv,#coating_anti_sv,#coating_premium_sv,#coating_elite_sv,#coating_resistant_sv').show();
    jQuery('#coating_unco_bf,#coating_anti_bf,#coating_premium_bf,#coating_elite_bf,#coating_resistant_bf').hide();
    jQuery('#coating_unco_vf,#coating_anti_vf,#coating_premium_vf,#coating_elite_vf,#coating_resistant_vf,#coating_anti_vf_basic').hide();
}
function coating_bifocal(){
    jQuery('#coating_unco_sv,#coating_anti_sv,#coating_anti_sv,#coating_premium_sv,#coating_elite_sv,#coating_resistant_sv').hide();
    jQuery('#coating_unco_bf,#coating_anti_bf,#coating_premium_bf,#coating_elite_bf,#coating_resistant_bf').show();
    jQuery('#coating_unco_vf,#coating_anti_vf,#coating_premium_vf,#coating_elite_vf,#coating_resistant_vf,#coating_anti_vf_basic').hide();
}
function coating_varifocal(){
    jQuery('#coating_unco_sv,#coating_anti_sv,#coating_premium_sv,#coating_elite_sv,#coating_resistant_sv').hide();
    jQuery('#coating_unco_bf,#coating_anti_bf,#coating_premium_bf,#coating_elite_bf,#coating_resistant_bf').hide();
    jQuery('#coating_unco_vf,#coating_anti_vf,#coating_premium_vf,#coating_elite_vf,#coating_resistant_vf').show();
}
function tint_single(){
    jQuery('#tint_full_sv,#tint_iridium_sv,#tint_polarised_sv,#tint_transitions_sv').show();
    jQuery('#tint_full_vf,#tint_iridium_vf,#tint_polarised_vf,#tint_transitions_vf').hide();
}
function tint_varifocal(){
    jQuery('#tint_full_sv,#tint_iridium_sv,#tint_polarised_sv,#tint_transitions_sv').hide();
    jQuery('#tint_full_vf,#tint_iridium_vf,#tint_polarised_vf,#tint_transitions_vf').show();
}

function array_diff (arr1) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Sanjoy Roy
  // +    revised by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: array_diff(['Kevin', 'van', 'Zonneveld'], ['van', 'Zonneveld']);
  // *     returns 1: {0:'Kevin'}
  var retArr = {},
    argl = arguments.length,
    k1 = '',
    i = 1,
    k = '',
    arr = {};

  arr1keys: for (k1 in arr1) {
    for (i = 1; i < argl; i++) {
      arr = arguments[i];
      for (k in arr) {
        if (arr[k] === arr1[k1]) {
          // If it reaches here, it was found in at least one array, so try next value
          continue arr1keys;
        }
      }
      retArr[k1] = arr1[k1];
    }
  }
  return retArr;
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Standard ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
function standard(){
    var type = jQuery("#lens_type input:checked").attr('sku');
    switch(type){
        case 'single' :
            single();
            break;
        case 'bifocal' :
            bifocal();
            break;
        case 'varifocal' :
            varifocal();
            break;
        default :
            hide_lenses();
    }
    general();
    function general(){
        var type = jQuery("#lens_type input:checked").attr('sku');
        var glasses_for = jQuery("#lens_glasses_for input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        var thickness = jQuery("#lens_thickness input:checked").attr('sku'); 
        var coating = jQuery("#lens_coating input:checked").attr('sku');
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        var color = jQuery("#lens_color input:checked").attr('sku');
        var tint_depth = jQuery("#lens_tint_depth input:checked").attr('sku');

        if ( glasses_for || varifocal_type ){ jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row'); }
        if ( thickness ){ jQuery("#lens_tint, #lens_tint .tr").css('display','table-row'); }
        if ( tint ){ jQuery("#lens_color, #lens_color .tr").css('display','table-row'); }
        if ( color ){ jQuery("#lens_tint_depth, #lens_tint_depth .tr").css('display','table-row'); }
        if ( tint_depth ){ jQuery("#lens_coating, #lens_coating .tr").css('display','table-row'); }
        
        if(thickness == 'thickness_standard'){
            jQuery('#tint_original').show();
        }else{
            jQuery('#tint_original').hide();
        }
        if(tint == 'tint_polarised'){
            jQuery('#color_green,#tint_depth_light,#tint_depth_medium').hide();
        }else{
            jQuery('#color_green,#tint_depth_light,#tint_depth_medium').show();
        }
        if( tint == 'tint_original' ){
            jQuery('#lens_color,#lens_tint_depth').hide();
            jQuery('#lens_coating, #lens_coating .tr').css('display','table-row');
        }
        if(varifocal_type == 'varifocal_basic'){
             jQuery('#thickness_thinner_vf,#coating_elite').hide();
        }else{
             jQuery('#thickness_thinner_vf,#coating_elite').show();
            
        }
        if( thickness == 'thickness_standard' && tint != 'tint_polarised' ){ 
            jQuery("#tint_depth_dark").show();
        }else{
            jQuery("#tint_depth_dark").hide();
        }
    }
    function bifocal(){
        bifocal_global();
        
        var type = jQuery("#lens_type input:checked").attr('sku');
        if(type == 'bifocal'){
             jQuery('#thickness_thinner').hide();
        }else{
             jQuery('#thickness_thinner').show();
        }
    }
    function varifocal(){
        varifocal_global();

        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        
        if( varifocal_type == 'varifocal_basic'){
             jQuery('#thickness_thinner,#coating_elite').hide();
        }else{
             jQuery('#thickness_thinner,#coating_elite').show();
            
        }
    }
    function single(){
        single_global();
    }
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Rimless and Supra ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
function rimless(){
    var type = jQuery("#lens_type input:checked").attr('sku');
    switch(type){
        case 'single' :
            single();
            break;
        case 'bifocal' :
            bifocal();
            break;
        case 'varifocal' :
            varifocal();
            break;
        default :
            hide_lenses();
    }
    general();
    function general(){
        var type = jQuery("#lens_type input:checked").attr('sku');
        var glasses_for = jQuery("#lens_glasses_for input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        var thickness = jQuery("#lens_thickness input:checked").attr('sku'); 
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        var coating = jQuery("#lens_coating input:checked").attr('sku');

        if ( glasses_for || varifocal_type ){ jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row'); }
        if ( thickness ){ jQuery("#lens_tint, #lens_tint .tr").css('display','table-row'); }
        if ( tint ){ jQuery("#lens_coating, #lens_coating .tr").css('display','table-row'); }
        
        if( type == 'bifocal' ){ jQuery("#lens_tint, #lens_tint .tr").hide(); }
    }
    function single(){
        single_global();
        thickness_single();
        coating_single();
    }
    function bifocal(){
        bifocal_global();
        thickness_bifocal();
        coating_bifocal();
    }
    function varifocal(){
        varifocal_global();
        thickness_varifocal();
        coating_varifocal();
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        if(varifocal_type == 'varifocal_basic'){
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_premium_vf,#coating_elite_vf').hide();
            jQuery('#thickness_thin_vf, #coating_anti_vf').find('.radio').attr('checked', true);
        }else{
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_premium_vf,#coating_elite_vf').show();
        }
    }
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Specialty ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

function specialty(){
    var type = jQuery("#lens_type input:checked").attr('sku');
    switch(type){
        case 'single' :
            single();
            break;
        case 'bifocal' :
            bifocal();
            break;
        case 'varifocal' :
            varifocal();
            break;
        default :
            hide_lenses();
    }
    general();
    function general(){
        var type = jQuery("#lens_type input:checked").attr('sku');
        var glasses_for = jQuery("#lens_glasses_for input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        var thickness = jQuery("#lens_thickness input:checked").attr('sku'); 
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        var color = jQuery("#lens_color input:checked").attr('sku');
        var tint_depth = jQuery("#lens_tint_depth input:checked").attr('sku');
        var coating = jQuery("#lens_coating input:checked").attr('sku');

        if ( glasses_for || varifocal_type ){ jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row'); }
        if ( thickness ){ jQuery("#lens_tint, #lens_tint .tr").css('display','table-row'); }
        if ( tint ){ jQuery("#lens_color, #lens_color .tr").css('display','table-row'); }
        if ( color ){ jQuery("#lens_tint_depth, #lens_tint_depth .tr").css('display','table-row'); }
        if ( tint_depth ){ jQuery("#lens_coating, #lens_coating .tr").css('display','table-row'); }
        
        if( thickness == 'thickness_standard' ){ 
            jQuery("#tint_original").show();
        }else{
            jQuery("#tint_original").hide();
        }
        switch (tint) {
            case 'tint_polarised' : 
                jQuery("#color_green, #tint_depth_light,#tint_depth_medium").hide();
                break;
            case 'tint_original' :
                jQuery('#lens_color,#lens_tint_depth').hide();
                jQuery('#lens_coating, #lens_coating .tr').css('display','table-row');
                break;
            default : 
                jQuery("#color_green, #tint_depth_light,#tint_depth_medium").show();
        }
        /*if( tint == 'tint_polarised' ){ 
            jQuery("#color_green, #tint_depth_light,#tint_depth_medium").hide();
        }else{
            jQuery("#color_green, #tint_depth_light,#tint_depth_medium").show();
        }
        if( tint == 'tint_original' ){
            jQuery('#lens_color,#lens_tint_depth').hide();
            jQuery('#lens_coating, #lens_coating .tr').css('display','table-row');
        }*/
        if( thickness == 'thickness_standard' && tint != 'tint_polarised' ){ 
            jQuery("#tint_depth_dark").show();
        }else{
            jQuery("#tint_depth_dark").hide();
        }
    }
    function single(){
        single_global();
    }
    function bifocal(){
        bifocal_global();
    }
    function varifocal(){
        varifocal_global();
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        if(varifocal_type == 'varifocal_basic'){
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_premium_vf,#coating_elite_vf').hide();
            jQuery('#thickness_thin_vf, #coating_anti_vf').find('.radio').attr('checked', true);
        }else{
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_premium_vf,#coating_elite_vf').show();
        }
    }
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Fully Rimmed ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

function fullyrimmed(){
    var type = jQuery("#lens_type input:checked").attr('sku');
    general();
    switch(type){
        case 'single' :
            single();
            break;
        case 'bifocal' :
            bifocal();
            break;
        case 'varifocal' :
            varifocal();
            break;
        default :
            hide_lenses();
    }
    function general(){
        var type = jQuery("#lens_type input:checked").attr('sku');
        var glasses_for = jQuery("#lens_glasses_for input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        var thickness = jQuery("#lens_thickness input:checked").attr('sku'); 
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        var coating = jQuery("#lens_coating input:checked").attr('sku');

        if ( glasses_for || varifocal_type ){ jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row'); }
        if ( thickness ){ jQuery("#lens_tint, #lens_tint .tr").css('display','table-row'); }
        if ( tint ){ jQuery("#lens_coating, #lens_coating .tr").css('display','table-row'); }
    }
    function single(){
        single_global();
        thickness_single();
        coating_single();
        var thickness = jQuery("#lens_thickness input:checked").attr('sku');
        var coating = jQuery("#lens_coating input:checked").attr('sku');
        var tint = jQuery("#lens_tint input:checked").attr('sku');

        jQuery("#coating_anti_vf_basic").hide(); //coating_unco_sv
        if( thickness == 'thickness_standard_sv' ){ jQuery("#coating_unco_sv").show(); }else{ jQuery("#coating_unco_sv").hide(); }
        if( thickness == 'thickness_standard_sv' && (!coating || coating == 'coating_anti_sv' || coating == 'coating_unco_sv') ){ 
            jQuery("#tint_brown_photo, #tint_grey_photo").show();
        }else{
            jQuery("#tint_brown_photo, #tint_grey_photo").hide();
        }
        if( tint == 'tint_brown_photo' || tint == 'tint_grey_photo' ){ 
            jQuery("#coating_premium_sv, #coating_elite_sv").hide();
        }else{
            jQuery("#coating_premium_sv, #coating_elite_sv").show();
        }
    }
    function bifocal(){
        bifocal_global();
        thickness_bifocal();
        coating_bifocal();
        var thickness = jQuery("#lens_thickness input:checked").attr('sku');
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        if( thickness == 'thickness_standard_bf' ){
            jQuery("#lens_tint, #lens_tint .tr").css('display','table-row');
            jQuery("#tint_brown_photo, #tint_grey_photo").hide();
        }else{
            jQuery("#lens_tint, #lens_tint .tr").hide();
        }
        if( tint == 'tint_brown' || tint == 'tint_grey' ){ 
            jQuery("#coating_resistant_bf").hide();
        }else{
            jQuery("#coating_resistant_bf").show();
        }
    }
    function varifocal(){
        varifocal_global();
        thickness_varifocal();
        coating_varifocal();
        jQuery("#coating_anti_vf_basic").hide();
        
        var thickness = jQuery("#lens_thickness input:checked").attr('sku');
        var coating = jQuery("#lens_coating input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        
        if( thickness == 'thickness_standard_vf' ){ jQuery("#coating_unco_vf").show(); }else{ jQuery("#coating_unco_vf").hide(); }
        if( varifocal_type == 'varifocal_basic' && thickness == 'thickness_standard_vf' && (!coating || coating == 'coating_anti_vf' || coating == 'coating_anti_vf_basic'  || coating == 'coating_unco_vf') ){ 
            jQuery("#tint_brown_photo, #tint_grey_photo").show();
        }else{
            jQuery("#tint_brown_photo, #tint_grey_photo").hide();
        }
        
        var tint = jQuery("#lens_tint input:checked").attr('sku');
        if( tint == 'tint_brown_photo' || tint == 'tint_grey_photo' ){ 
            jQuery("#coating_premium_vf, #coating_elite_vf").hide();
        }else{
            jQuery("#coating_premium_vf, #coating_elite_vf").show();
        }
        
        if(varifocal_type == 'varifocal_basic'){
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_premium_vf,#coating_elite_vf').hide();
            //#jQuery('#coating_anti_vf').show().find('.radio').attr('checked', true);
        }else{
            jQuery('#thickness_thinner_vf,#thickness_thinnest_vf,  #coating_anti_vf,#coating_premium_vf,#coating_elite_vf').show();
        }
        
    }
}
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Oakley ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

function oakley(){
    var type = jQuery("#lens_type input:checked").attr('sku');
    var colors = [
        'color_oakley_00_red_iridium',
        'color_oakley_black',
        'color_oakley_black_iridium',
        'color_oakley_black_iridium_bar',
        'color_oakley_bronze',
        'color_oakley_bronze_bar',
        'color_oakley_bronze_polarised',
        'color_oakley_brown_ar',
        'color_oakley_clear_ar',
        'color_oakley_deep_blue',
        'color_oakley_deep_blue_iridium',
        'color_oakley_emerald_iridium_bar',
        'color_oakley_fire',
        'color_oakley_g30',
        'color_oakley_g30_iridium',
        'color_oakley_gold',
        'color_oakley_gold_iridium',
        'color_oakley_gold_iridium_bar',
        'color_oakley_grey',
        'color_oakley_grey_ar',
        'color_oakley_grey_bar',
        'color_oakley_ice',
        'color_oakley_ice_iridium',
        'color_oakley_oo_black',
        'color_oakley_oo_red_iridium',
        'color_oakley_persimon',
        'color_oakley_shallow_blue',
        'color_oakley_tungsten',
        'color_oakley_vr27_black',
        'color_oakley_vr27_black_iridium',
        'color_oakley_vr28',
        'color_oakley_vr28_bar',
        'color_oakley_vr28_black_iridium',
    ];
    general();
    switch(type){
        case 'single' :
            single(colors);
            break;
        case 'varifocal' :
            varifocal(colors);
            break;
        //default :
            //hide_lenses();
    }
    function general(){
        var type = jQuery("#lens_type input:checked").attr('sku');
        var glasses_for = jQuery("#lens_glasses_for input:checked").attr('sku');
        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        var thickness = jQuery("#lens_thickness input:checked").attr('sku'); 
        var tint = jQuery("#lens_tint input:checked").attr('sku');

        if ( glasses_for || varifocal_type ){ jQuery("#lens_thickness, #lens_thickness .tr").css('display','table-row'); }
        if ( thickness ){ jQuery("#lens_tint, #lens_tint .tr").css('display','table-row'); }
        if ( tint ){ jQuery("#lens_color, #lens_color .tr").css('display','table-row'); }
    }
    function single(col){
        single_global();
        thickness_single();
        tint_single();
        var scolor = [
            'color_oakley_black',
            'color_oakley_black_iridium',
            'color_oakley_black_iridium_bar',
            'color_oakley_bronze',
            'color_oakley_bronze_bar',
            'color_oakley_brown_ar',
            'color_oakley_clear_ar',
            'color_oakley_deep_blue',
            'color_oakley_emerald_iridium_bar',
            'color_oakley_fire',
            'color_oakley_g30',
            'color_oakley_gold',
            'color_oakley_gold_iridium',
            'color_oakley_gold_iridium_bar',
            'color_oakley_grey',
            'color_oakley_grey_ar',
            'color_oakley_grey_bar',
            'color_oakley_ice',
            'color_oakley_ice_iridium',
            'color_oakley_oo_black',
            'color_oakley_oo_red_iridium',
            'color_oakley_persimon',
            'color_oakley_shallow_blue',
            'color_oakley_tungsten',
            'color_oakley_vr27_black',
            'color_oakley_vr27_black_iridium',
            'color_oakley_vr28',
            'color_oakley_vr28_bar',
        ];
        var vcolor = [
            "color_oakley_00_red_iridium",
            "color_oakley_bronze_polarised",
            "color_oakley_deep_blue_iridium",
            "color_oakley_g30_iridium",
            "color_oakley_vr28_black_iridium"
        ]; //console.log( array_diff(col, scolor) );
        jQuery.each(vcolor, function(index, value){ jQuery('#'+value).css({ opacity: 0.1 }).find('input').attr("disabled", true).css({ opacity: 0.1 }); } ); //hide varifocal colors

        var thickness = jQuery("#lens_thickness input:checked").attr('sku');
        var tint = jQuery("#lens_tint input:checked").attr('sku');

        jQuery("#coating_anti_vf_basic").hide();
        switch(tint){
            case 'tint_full_sv' : 
                var enable = ['color_oakley_clear_ar','color_oakley_grey','color_oakley_persimon','color_oakley_vr28','color_oakley_bronze'];
                break;
            case 'tint_iridium_sv' : 
                var enable = ['color_oakley_black','color_oakley_gold','color_oakley_fire','color_oakley_ice','color_oakley_g30','color_oakley_vr27_black','color_oakley_tungsten'];
                break;
            case 'tint_polarised_sv' : 
                var enable = ['color_oakley_grey_bar','color_oakley_bronze_bar','color_oakley_vr28_bar','color_oakley_black_iridium','color_oakley_deep_blue','color_oakley_g30','color_oakley_ice_iridium','color_oakley_gold_iridium','color_oakley_vr27_black_iridium','color_oakley_tungsten','color_oakley_oo_red_iridium','color_oakley_shallow_blue','color_oakley_oo_black'];
                break;
            case 'tint_transitions_sv' : 
                var enable = ['color_oakley_grey_ar','color_oakley_brown_ar','color_oakley_black_iridium_bar','color_oakley_gold_iridium_bar','color_oakley_emerald_iridium_bar'];
                break;
        }
        jQuery.each(enable, function(index, value){ jQuery('#'+value).css({ opacity: 1 }).find('input').removeAttr("disabled").css({ opacity: 1 }); } );
        jQuery.each( array_diff(scolor,enable), function(index, value){ jQuery('#'+value).css({ opacity: 0.1 }).find('input').attr("disabled", true).css({ opacity: 0.1 }); } );
    }
    function varifocal(col){
        varifocal_global();
        thickness_varifocal();
        tint_varifocal();

        var vcolor = [
            'color_oakley_00_red_iridium',
            'color_oakley_black_iridium',
            'color_oakley_black_iridium_bar',
            'color_oakley_bronze_bar',
            'color_oakley_bronze_polarised',
            'color_oakley_brown_ar',
            'color_oakley_deep_blue',
            'color_oakley_deep_blue_iridium',
            'color_oakley_emerald_iridium_bar',
            'color_oakley_g30',
            'color_oakley_g30_iridium',
            'color_oakley_gold_iridium',
            'color_oakley_gold_iridium_bar',
            'color_oakley_grey_ar',
            'color_oakley_grey_bar',
            'color_oakley_ice_iridium',
            'color_oakley_oo_black',
            'color_oakley_oo_red_iridium',
            'color_oakley_shallow_blue',
            'color_oakley_tungsten',
            'color_oakley_vr28_bar',
            'color_oakley_vr28_black_iridium'
        ];
        var scolor = [
            "color_oakley_black",
            "color_oakley_bronze",
            "color_oakley_clear_ar",
            "color_oakley_fire",
            "color_oakley_gold",
            "color_oakley_grey",
            "color_oakley_ice",
            "color_oakley_persimon",
            "color_oakley_vr27_black",
            "color_oakley_vr27_black_iridium",
            "color_oakley_vr28"
        ]; //console.log( array_diff(col, vcolor) );
        jQuery.each(scolor, function(index, value){ jQuery('#'+value).css({ opacity: 0.1 }).find('input').attr("disabled", true).css({ opacity: 0.1 }); } ); //hide single colors
        
        jQuery("#coating_anti_vf_basic").hide();

        var varifocal_type = jQuery("#lens_varifocal_type input:checked").attr('sku');
        switch(varifocal_type){
            case 'varifocal_regular' : 
                var enable = ["color_oakley_grey_ar","color_oakley_brown_ar","color_oakley_black_iridium_bar","color_oakley_gold_iridium_bar","color_oakley_emerald_iridium_bar"];
                break;
            case 'varifocal_road' : 
                var enable = ["color_oakley_oo_red_iridium","color_oakley_vr28_black_iridium","color_oakley_black_iridium"];
                jQuery('#tint_transitions_vf').hide();
                break;
            case 'varifocal_golf' : 
                var enable = ["color_oakley_g30","color_oakley_g30_iridium"];
                jQuery('#tint_transitions_vf').hide();
                break;
            case 'varifocal_fishing' : 
                var enable = ["color_oakley_deep_blue_iridium","color_oakley_shallow_blue","color_oakley_bronze_polarised"];
                jQuery('#tint_transitions_vf').hide();
                break;
        }
        jQuery.each(enable, function(index, value){ jQuery('#'+value).css({ opacity: 1 }).find('input').removeAttr("disabled").css({ opacity: 1 }); } );
        jQuery.each( array_diff(vcolor,enable), function(index, value){ jQuery('#'+value).css({ opacity: 0.1 }).find('input').attr("disabled", true).css({ opacity: 0.1 }); } );
    }
}
//]]>
/*
. css({ opacity: 0.1 }).find('input').attr("disabled", true).css({ opacity: 0.1 });
. css({ opacity: 1 }).find('input').removeAttr("disabled").css({ opacity: 1 });
jQuery("#lens_tint, #lens_tint .tr")
*/