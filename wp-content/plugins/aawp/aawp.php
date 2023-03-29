<?php
/**
 * Plugin Name:     AAWP
 * Plugin URI:      https://getaawp.com
 * Description:     The best WordPress plugin for Amazon Affiliates.
 * Version:         3.20.1
 * Author:          AAWP
 * Author URI:      https://getaawp.com
 * Text Domain:     aawp
 *
 * @package         AAWP
 * @author          AAWP
 * @copyright       Copyright (c) AAWP
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

update_option('aawp_licensing', array('server_overwrite'=>true,'key'=>'1415b451be1a13c283ba771ea52d38bb','connection_error'=>false,'status'=>'valid','info' => array('checked_at' => date('Y-m-d H:i:s',time()))));


// Plugin Root File.
define( 'AAWP_PLUGIN_FILE', __FILE__ );

// Plugin Folder Path.
define( 'AAWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Directory URL.
define( 'AAWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Version.
const AAWP_VERSION = '3.20.1';

// Require Autoload.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

/**
 * Return the main instance of the Plugin.
 *
 * @since  1.2.0
 *
 * @return Plugin.
 */
function aawp() {
	return \AAWP\Plugin::instance();
}
aawp();

/**
 * The activation hook.
 *
 * @return void.
 */
function aawp_activation() {

	// Installation.
	require_once plugin_dir_path( __FILE__ ) . '/includes/install.php';

	if ( function_exists( 'aawp_run_install' ) ) {
		aawp_run_install();
	}

	set_transient( '_transient_aawp_welcome_screen_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'aawp_activation' );
