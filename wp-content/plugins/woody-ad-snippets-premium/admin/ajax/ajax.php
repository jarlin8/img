<?php
/**
 * Ajax handler
 *
 * Класс для обработки ajax запросов.
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns a list of user countries.
 * Список стран хранится в файле country-codes.txt
 */
function wbcr_inp_ajax_get_user_country() {
	$country_names = [];
	$fp            = fopen( WASP_PLUGIN_DIR . '/includes/geo/country-codes.txt', 'r' );
	while( ( $row = fgetcsv( $fp, 255 ) ) !== false ) {
		if ( $row && count( $row ) > 3 && substr( trim( $row [0] ), 0, 1 ) != '#' ) {
			list ( $country, $iso ) = $row;
			$iso                   = strtoupper( $iso );
			$country               = str_replace( '( ', '(', ucwords( str_replace( '(', '( ', strtolower( $country ) ) ) );
			$country_names[ $iso ] = $country;
		}
	}
	fclose( $fp );

	$values = [];
	foreach ( $country_names as $country_iso => $country ) {
		$values[] = [
			'value' => $country_iso,
			'title' => $country,
		];
	}

	$result = [
		'values' => $values,
	];

	echo json_encode( $result );
	exit;
}

add_action( 'wp_ajax_wbcr_inp_ajax_get_user_country', 'wbcr_inp_ajax_get_user_country' );

/**
 * Update geo database
 */
function wbcr_inp_ajax_update_geo_db() {
	if ( ! WINP_Plugin::app()->currentUserCan() ) {
		wp_die( - 1, 403 );
	}

	if ( is_multisite() && ! is_main_site() || 'webnet' == WINP_Plugin::app()->getOption( 'geo_db', 'webnet' ) ) {
		$result = '';
	} else {
		$result = WASP_Core::app()->get_geo_object()->update_maxmind_db();
	}

	echo( $result );
	exit;
}

add_action( 'wp_ajax_wbcr_inp_ajax_update_geo_db', 'wbcr_inp_ajax_update_geo_db' );
