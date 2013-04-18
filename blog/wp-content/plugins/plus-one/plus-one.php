<?php
/*
Plugin Name: Plus One
Plugin URI: http://wordpress.org/extend/plugins/plus-one/
Description: Use this plugin to easily add the Google +1 button to your WordPress site
Version: 1.0.3
Author: Metronet
Author URI: http://metronet.no/
License: GPL2

Copyright 2011  Metronet Norge  (email : wp@metronet.no)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook( __FILE__, 'plus_one_activate' );
//register_deactivation_hook( __FILE__, 'plus_one_deactivate' );

function plus_one_print_footer_scripts($explicit = false) {
	$plus_one_options = get_option( 'plus_one_options' );
	echo "\n\n<!-- Google +1 Button for WordPress -->\n";
	
	// SSL Check
	if ( is_ssl() ) :
		echo '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">';
	else :
		echo '<script type="text/javascript" src="http://apis.google.com/js/plusone.js">';
	endif;
	
	if ( ($plus_one_options['plus_one_language'] != 'en-US' || $plus_one_options['plus_one_parse'] == 2) ) :
		
		echo "\n\t{";
		
		if ($plus_one_options['plus_one_language'] != 'en-US') :
			echo "lang: '".$plus_one_options['plus_one_language']."'";
			if ($plus_one_options['plus_one_parse'] == 2 && (!is_admin() || $explicit))
				echo ", ";
		endif;
		
		if ($plus_one_options['plus_one_parse'] == 2 && (!is_admin() || $explicit))
			echo "parsetags: 'explicit'";
		
		echo "}\n";
		
	endif;
		
	echo "</script>\n\n";
}
add_action( 'wp_print_footer_scripts', 'plus_one_print_footer_scripts' );

// Generate +1 Button
function plus_one_button($admin = false) {
	
	$plus_one_options = get_option( 'plus_one_options' );
	
	$plus_one_button = '<div class="plus-one-wrap"><g:plusone';
	
	// Size
	if ( $plus_one_options['plus_one_size'] == '1' )
		$plus_one_button .= ' size="small"';
		
	else if ( $plus_one_options['plus_one_size'] == '2' )
		$plus_one_button .= ' size="medium"';
		
	else if ( $plus_one_options['plus_one_size'] == '4' )
		$plus_one_button .= ' size="tall"';
		
	// Count
	if ( !isset($plus_one_options['plus_one_count']) )
		$plus_one_button .= ' count="false"';
		
	// JS Callback
	$plus_one_callback = $plus_one_options['plus_one_js_callback'];
	if ( isset($plus_one_callback) && !empty($plus_one_callback) )
		$plus_one_button .= ' callback="'.$plus_one_callback.'"';
		
	// +1 URL
	if ( !is_admin() && !$admin )
		$plus_one_button .= ' href="'.get_permalink().'"';
	else if ( !$admin )
		$plus_one_button .= ' href="'.site_url().'"';
	
	$plus_one_button .= '>';
	
	$plus_one_button .= '</g:plusone></div>';
	
	return $plus_one_button;
}

// Add +1 Button to 'the_content'
function plus_one_content($content) {
	if ( ! in_the_loop() ) :
			return $content;
	endif;
	
	$plus_one_options = get_option( 'plus_one_options' );
	
	// +1 on Posts
	if ( is_single() && $plus_one_options['plus_one_on_posts'] != '1' )
		return $content;
		
	// +1 on Pages
	if ( is_page() && $plus_one_options['plus_one_on_pages'] != '1' )
		return $content;
	
	// +1 on Home Page
	if ( is_home() && $plus_one_options['plus_one_on_home'] == '2' )
		return $content;
	
	// Above Content
	if ( $plus_one_options['plus_one_location'] == '1' )
		return plus_one_button() . $content;
		
	// Below Content
	else if ( $plus_one_options['plus_one_location'] == '2' )
		return $content . plus_one_button();
		
	// Both
	else
		return plus_one_button() . $content . plus_one_button();
		
}
add_action( 'the_content', 'plus_one_content');

// Add Translation
function plus_one_translation() {
	load_plugin_textdomain( 'plus-one', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'plus_one_translation' );

// Settings
require_once( dirname( __FILE__ ) . '/plus-one-settings.php' );