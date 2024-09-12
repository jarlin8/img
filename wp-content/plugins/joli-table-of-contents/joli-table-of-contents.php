<?php

use  WPJoli\JoliTOC\Application ;
/**
 * @package jolitoc
 */
/*
 * Plugin Name: Joli Table Of Contents
 * Plugin URI: https://wpjoli.com/joli-table-of-contents
 * Description: Sleek Table Of Contents for your posts & pages. 
 * Version: 1.3.8
 * Author: WPJoli
 * Author URI: https://wpjoli.com
 * License: GPLv2 or later
 * Text Domain: joli-table-of-contents
 * Domain Path: /languages
 * 
 */
defined( 'ABSPATH' ) or die( 'Wrong path bro!' );

if ( function_exists( 'jtoc_xy' ) ) {
    jtoc_xy()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'jtoc_xy' ) ) {
        function jtoc_xy()
        {
            global  $jtoc_xy ;
            
            if ( !isset( $jtoc_xy ) ) {
                require_once dirname( __FILE__ ) . '/includes/fs/start.php';
                $jtoc_xy = fs_dynamic_init( array(
                    'id'             => '4516',
                    'slug'           => 'joli-table-of-contents',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e064fd98940b5a52b33eb64b7d517',
                    'is_premium'     => false,
                    'premium_suffix' => '',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'    => 'joli_toc_settings',
                    'account' => false,
                    'contact' => false,
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $jtoc_xy;
        }
        
        jtoc_xy();
        // Signal that SDK was initiated.
        do_action( 'jtoc_xy_loaded' );
    }
    
    define( 'WPJOLI_JOLI_TOC_BASENAME', plugin_basename( __FILE__ ) );
    require_once dirname( __FILE__ ) . '/helpers.php';
    require_once dirname( __FILE__ ) . '/fs-helpers.php';
    require_once dirname( __FILE__ ) . '/autoload.php';
    $app = new Application();
    $app->run();
    register_activation_hook( __FILE__, [ $app, 'activate' ] );
    register_deactivation_hook( __FILE__, [ $app, 'deactivate' ] );
}
