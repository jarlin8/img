<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 * Version: 2.0.3
 * Text Domain: csf
 * Domain Path: /languages
 */
require_once plugin_dir_path( __FILE__ ) .'classes/setup.class.php';
require_once plugin_dir_path( __FILE__ ) .'customize/options-function.php';
require_once plugin_dir_path( __FILE__ ) .'customize/iosf.class.php';

$io_get_option = false;
function io_get_option($option, $default = null, $key = ''){ 
    global $io_get_option;
    if ($io_get_option) {
        $options = $io_get_option;
    } else {
        $options = get_option('io_get_option');
        $io_get_option = $options;
    } 
    if (isset($options[$option])) {
        if ($key) {
            return isset($options[$option][$key]) ? $options[$option][$key] : $default;
        } else {
            return $options[$option];
        }
    }
    return $default;
}
