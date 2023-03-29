<?php
/*
Plugin Name: Interlinks Manager
Description: Manages the internal links of your WordPress website.
Version: 1.32
Author: DAEXT
Author URI: https://daext.com
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) { die(); }

//Class shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daim-shared.php' );

//Public
require_once( plugin_dir_path( __FILE__ ) . 'public/class-daim-public.php' );
add_action( 'plugins_loaded', array( 'Daim_Public', 'get_instance' ) );

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
    
    //Admin
    require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daim-admin.php' );
    add_action( 'plugins_loaded', array( 'Daim_Admin', 'get_instance' ) );
    
    //Activate
    register_activation_hook( __FILE__, array( Daim_Admin::get_instance(), 'ac_activate' ) );

	//Deactivate
	register_deactivation_hook( __FILE__, array( Daim_Admin::get_instance(), 'dc_deactivate' ) );

}

//Ajax
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    
    //Admin
    require_once( plugin_dir_path( __FILE__ ) . 'class-daim-ajax.php' );
    add_action( 'plugins_loaded', array( 'Daim_Ajax', 'get_instance' ) );
    
}

//add_action('init', 'my_custom_init' );
//
//function my_custom_init() {
//	add_filter( 'cron_schedules', 'devhub_custom_cron_schedule' );
//}
//
///**
// * Adds a custom cron schedule for every 5 minutes.
// *
// * @param array $schedules An array of non-default cron schedules.
// * @return array Filtered array of non-default cron schedules.
// */
//function devhub_custom_cron_schedule( $schedules ) {
//	$schedules[ 'every-5-minutes' ] = array( 'interval' => 5 * MINUTE_IN_SECONDS, 'display' => __( 'Every 5 minutes', 'devhub' ) );
//	return $schedules;
//}
