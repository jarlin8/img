<?php
/*
Plugin Name: G-Core Labs CDN
Plugin URI: http://wordpress.org/plugins/g-core-labs/
Description: CDN plugin
Author URI: https://gcorelabs.com/
Version: 1.1.7
Author: G-Core Labs (G-Core Labs S.A.)
*/


$plugin_dir = dirname(__FILE__);

include($plugin_dir . "/includes/function.php");

add_action('admin_menu', 'g_core_labs_admin_menu');

function g_core_labs_admin_menu()
{

    add_menu_page("G-Core Labs", "G-Core Labs", 'manage_options', 'gcore_labs', 'g_core_labs_cdn_page', plugin_dir_url(__FILE__) . 'plugin-icon.png');
    add_submenu_page('gcore_labs', '' . __("CDN settings", "gcore_translate") . '', '' . __("CDN settings", "gcore_translate") . '', 'manage_options', 'gcore_labs', 'g_core_labs_cdn_page');
    //add_submenu_page('gcore_labs', 'Настройки Streaming', 'Настройки Streaming', 'manage_options', 'gcore_labs_stream', 'g_core_labs_stream_page');
    add_submenu_page('gcore_labs', '' . __("Help", "gcore_translate") . '', '' . __("Help", "gcore_translate") . '', 'manage_options', 'gcore_labs_help', 'g_core_labs_help_page');
    add_submenu_page('gcore_labs', '' . __("About", "gcore_translate") . '', '' . __("About", "gcore_translate") . '', 'manage_options', 'gcore_labs_about', 'g_core_labs_about_page');
}

function g_core_labs_cdn_page()
{
    $plugin_dir = dirname(__FILE__);
    wp_enqueue_style('g_core_css-amaran', plugins_url('css/amaran.min.css', __FILE__));
    wp_enqueue_style('g_core_css-animate', plugins_url('css/animate.min.css', __FILE__));
    wp_enqueue_style('g_core_css-checkbox', plugins_url('css/checkbox.min.css', __FILE__));
    wp_enqueue_style('g_core_css-custom', plugins_url('css/custom.css', __FILE__));
    wp_enqueue_script('g_core_script-amaran', plugin_dir_url(__FILE__) . 'js/jquery.amaran.min.js');
    wp_enqueue_script('g_core_script', plugin_dir_url(__FILE__) . 'js/scripts.js');

    require($plugin_dir . "/includes/admin.php");
}

function g_core_labs_stream_page()
{
    $plugin_dir = dirname(__FILE__);
    require($plugin_dir . "/includes/stream.php");
}

function g_core_labs_help_page()
{
    $plugin_dir = dirname(__FILE__);
    require($plugin_dir . "/includes/help.php");
}

function g_core_labs_about_page()
{
    $plugin_dir = dirname(__FILE__);
    require($plugin_dir . "/includes/about.php");
}

function g_core_labs_activate($template)
{

    if ($template) {
        include(plugin_dir_path(__FILE__) . 'includes/front_cdn.php');
        $template = plugin_dir_path(__FILE__) . 'includes/blank_tpl.php';
    }

    return $template;
}


function g_core_labs_init_action()
{

    load_plugin_textdomain('gcore_translate', false, basename(dirname(__FILE__)) . '/languages');

}

add_action('init', 'g_core_labs_init_action');
/*
//add_action( 'plugins_loaded', 'true_load_plugin_textdomain' );

load_theme_textdomain('gcore_translate', __DIR__ . '/languages');
*/
add_filter('template_include', 'g_core_labs_activate', 999);

register_activation_hook(__FILE__, 'g_core_labs');
function g_core_labs()
{
    update_option('gcore_type_image', 1);
    update_option('gcore_type_video', 1);
    update_option('gcore_type_audio', 1);
    update_option('gcore_type_js', 1);
    update_option('gcore_type_css', 1);
    update_option('gcore_type_archive', 1);
    update_option('gcore_folder_templates', 1);
    update_option('gcore_folder_plugins', 1);
    update_option('gcore_folder_content', 1);
    update_option('gcore_folder_wp', 1);
    update_option('gcore_type_advanced', 0);
    update_option('gcore_folder_advanced', 0);
}