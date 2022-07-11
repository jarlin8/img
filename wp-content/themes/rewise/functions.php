<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if(!defined('REHUB_NAME_ACTIVE_THEME')){
	define('REHUB_NAME_ACTIVE_THEME', 'REWISE');
}
if ( defined( 'RH_GRANDCHILD_DIR' ) ) {
	include( RH_GRANDCHILD_DIR . 'rh-grandchild-func.php' );
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style',11 );
function enqueue_parent_theme_style() {
	if ( !defined( 'RH_MAIN_THEME_VERSION' ) ) {
		define('RH_MAIN_THEME_VERSION', '9.0');
	}	
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', array(), RH_MAIN_THEME_VERSION );
	if (is_rtl()) {
		 wp_enqueue_style( 'parent-rtl', get_template_directory_uri().'/rtl.css', array(), RH_MAIN_THEME_VERSION);
	}    
}
//////////////////////////////////////////////////////////////////
// Translation
//////////////////////////////////////////////////////////////////
//add_action('after_setup_theme', 'rehubchild_lang_setup');
//function rehubchild_lang_setup(){
    //load_child_theme_textdomain('rehubchild', get_stylesheet_directory() . '/lang');
//}

add_action('rh_related_after_title', 'rh_show_price_related');
function rh_show_price_related (){
	global $post;
	rehub_create_price_for_list($post->ID);
	echo '<div class="clearfix mb10"></div>';
}