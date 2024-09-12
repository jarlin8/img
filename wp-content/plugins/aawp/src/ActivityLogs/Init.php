<?php

namespace AAWP\ActivityLogs;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class for Logs.
 *
 * @since 3.19
 */
class Init {

	/**
	 * Initialize.
	 *
	 * @since 3.19
	 */
	public function init() {

		$check   = get_option( 'aawp_logs_settings' );
		$enabled = isset( $check['enable'] ) && 'on' === $check['enable'];

		if ( $enabled ) {
			$log = new DB();
			$log->create_table();
		}
	}
}
