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

// Gravatar自定义替换
add_filter( 'get_avatar' , 'my_custom_avatar' , 1 ,5 );
function my_custom_avatar( $avatar, $id_or_email, $size, $default, $alt) {
   	if ( ! empty( $id_or_email->user_id ) ) {
        $avatar = "https://testingcf.jsdelivr.net/gh/jarlin8/OSS@main/Gavatar/fx-circle-logo/139.svg";
    }else{
			$random = mt_rand(1,138);
			$avatar = 'https://testingcf.jsdelivr.net/gh/jarlin8/OSS@main/Gavatar/fx-circle-logo/'.$random .'.svg';
         }
    $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

    return $avatar;
}

// Activate Thrive 激活Thrive插件
$thrive_options = array(0 => 'all',);
update_option( 'thrive_license', $thrive_options );

// Disable WP 4.2 emoji 禁用emoji

function ace_remove_emoji() {
	add_filter( 'emoji_svg_url', '__return_false' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', 'ace_disable_emoji_tinymce' );
}
add_action( 'init', 'ace_remove_emoji' );
// Remove tinyMCE emoji
function ace_disable_emoji_tinymce( $plugins ) {
	unset( $plugins['wpemoji'] );
	return $plugins;
}
