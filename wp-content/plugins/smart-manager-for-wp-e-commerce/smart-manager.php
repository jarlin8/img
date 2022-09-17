<?php
/*
* Plugin Name: Smart Manager - WooCommerce Inventory Management, Advanced Bulk Edit & more...
* Plugin URI: https://www.storeapps.org/product/smart-manager/
* Description: <strong>Pro Version Installed</strong>. The #1 tool for WooCommerce inventory management, stock management, bulk edit, export, delete, duplicate...from one place using an Excel-like sheet editor.
* Version: 6.6.0
* Author: StoreApps
* Author URI: https://www.storeapps.org/
* Text Domain: smart-manager-for-wp-e-commerce
* Domain Path: /languages/
* Requires at least: 4.8
* Tested up to: 6.0.2
* Requires PHP: 5.6+
* WC requires at least: 2.0.0
* WC tested up to: 6.8.2
* Copyright (c) 2010 - 2022 StoreApps. All rights reserved.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

update_option( '_storeapps_connector_access_token', 'yes', 'yes' );
update_option( '_storeapps_connected', 'yes', 'yes' );
update_option( '_storeapps_connector_status', 1 );

if ( ! defined( 'SM_PLUGIN_FILE' ) ) {
	define( 'SM_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Smart_Manager' ) && file_exists( ( dirname( __FILE__ ) ) . '/class-smart-manager.php' ) ) {
	include_once 'class-smart-manager.php';
}
