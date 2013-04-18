<?php
/*
Plugin Name: Related Posts via Categories
Plugin URI: http://alphasis.info/developments/wordpress-plugins/related-posts-via-categories/
Description: This Plugin will display the related posts list via categories. It can automatically display the related posts list after the content of any single post of selected post types by options.
Version: 2.1.1
Author: alphasis
Author URI: http://alphasis.info/
*/

/*
Copyright 2010-2012  alphasis  (http://alphasis.info/)
*/


// Admin

if( is_admin() ){
	require_once dirname( __FILE__ ) . '/includes/admin.php';
}


// Display

add_filter( 'the_content', 'relatedPostsViaCategories_autoDisplay', 88 );

function relatedPostsViaCategories_autoDisplay( $content ) {
	$option_data = get_option("related_posts_via_categories");
	if( $option_data['auto_display'] == 'yes' and is_singular( $option_data['post_types'] ) ) { 
		require_once dirname( __FILE__ ) . '/includes/display.php';
		$output = $GLOBALS['relatedPostsViaCategories_display']->core();
		$content = $content . $output;
	}
	return $content;
}

function display_related_posts_via_categories() {
	$option_data = get_option("related_posts_via_categories");
	if( is_singular( $option_data['post_types'] ) ) { 
		require_once dirname( __FILE__ ) . '/includes/display.php';
		$output = $GLOBALS['relatedPostsViaCategories_display']->core();
		echo $output;
	}
}


// Register

register_activation_hook( __FILE__, 'activate_related_posts_via_categories' );

function activate_related_posts_via_categories() {
	require_once dirname( __FILE__ ) . '/includes/activate.php';
	$GLOBALS['relatedPostsViaCategories_activate']->activate();
}

load_plugin_textdomain( 'related-posts-via-categories', false, basename( dirname( __FILE__ ) ) . '/languages' );


?>
