<?php

namespace AAWP\API;

/**
 * Usage Tracking.
 *
 * @since 3.20
 */
class UsageTracking {

	/**
	 * Usage tracking API Endpoint.
	 *
	 * @var $endpoint Usage tracking API Endpoint.
	 *
	 * @since 3.20
	 */
	private $endpoint = 'https://api.getaawp.com/v1/usage';

	/**
	 * Initialize the usage tracking.
	 *
	 * @since 3.20
	 */
	public function init() {

		if ( ! apply_filters( 'aawp_usage_data_enable', true ) ) {
			return;
		}

		if ( defined( 'AAWP_API_URL' ) ) {
			$this->endpoint = AAWP_API_URL . '/usage';
		}

		add_action( 'admin_init', [ $this, 'schedule' ] );
	}

	/**
	 * Schedule the usage data task.
	 *
	 * @since 3.20
	 */
	public function schedule() {

		if ( false === as_next_scheduled_action( 'aawp_usage_data' ) ) {
			as_schedule_recurring_action( strtotime( '+ 1 day' ), DAY_IN_SECONDS, 'aawp_usage_data', [], 'aawp' );
		}

		add_action( 'aawp_usage_data', [ $this, 'api_call' ] );
	}

	/**
	 * Call the API.
	 *
	 * @since 3.20
	 */
	public function api_call() {

		$data = $this->get_basic_data();

		$options = [
			'body'        => wp_json_encode( $data ),
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'data_format' => 'body',
		];

		$response = wp_remote_post( $this->endpoint, $options );

		if ( is_wp_error( $response ) ) {
			aawp_log( 'AAWP Usage API', '<code>' . $response->get_error_message() . '</code>' ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}

		if ( ! empty( $response['response']['code'] ) && ! empty( $response['response']['message'] ) ) {

			aawp_log( 'AAWP Usage API', '<code>' . $response['response']['code'] . '</code>' . $response['response']['message'] );
		}
	}

	/**
	 * Get basic data.
	 *
	 * @since 3.20
	 *
	 * @return array An array of data to send to API.
	 */
	private function get_basic_data() {

		global $wp_version;

		return [
			'site_url'     => wp_parse_url( site_url(), PHP_URL_HOST ),
			'php_version'  => PHP_VERSION,
			'wp_version'   => $wp_version,
			'wp_lang'      => get_bloginfo( 'language' ),
			'aawp_version' => AAWP_VERSION,
			'aawp_license' => \aawp_get_option( 'key', 'licensing' )
		];
	}
}
