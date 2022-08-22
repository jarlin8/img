<?php

namespace AAWP\ShortenLinks;

defined( 'ABSPATH' ) || exit;
// Exit if accessed directly.

/**
 * Bitly API.
 *
 * @since 3.18
 */
class BitlyAPI {

	/**
	 * Initialize DB.
	 *
	 * @since 3.18
	 */
	public function init() {
		add_action( 'init', [ $this, 'api_test' ] );
	}

	/**
	 * Test the Bitly Access Token to display any issues in the settings page.
	 *
	 * @since 3.18
	 */
	public function api_test() {

		// Bail if it's not an admin page.
		if ( ! is_admin() ) {
			return;
		}

		$nonce = isset( $_REQUEST['_wpnonce_aawp_update_options'] ) ? sanitize_key( $_REQUEST['_wpnonce_aawp_update_options'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'aawp_update_options' ) ) {
			return;
		}

		if ( isset( $_POST['option_page'], $_POST['aawp_general']['affiliate_links'] ) && 'aawp_general' === $_POST['option_page'] && 'shortened' === $_POST['aawp_general']['affiliate_links'] ) {

			$access_token = isset( $_POST['aawp_general']['bitly_access_token'] ) ? sanitize_text_field( wp_unslash( $_POST['aawp_general']['bitly_access_token'] ) ) : '';

			$access_token_db = \aawp_get_option( 'bitly_access_token', 'general' );

			if ( $access_token === $access_token_db ) {
				return;
			}

			self::request( 'https://amazon.com/api-test', $access_token );
		}
	}

	/**
	 * API Request. Every single link has to be shortened via a single API request
	 *
	 * @param string $url The long url to be shortened.
	 * @param string $access_token The Bitly Access token to request with.
	 *
	 * @since 3.18
	 *
	 * @return array The API response body.
	 */
	public static function request( $url, $access_token ) {

		$endpoint = 'https://api-ssl.bitly.com/v4/shorten';

		$args = [
			'long_url' => $url,
			'domain'   => 'bit.ly',
		];

		$body = wp_json_encode( $args );

		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type'  => 'application/json',
				'Authorization' => sanitize_key( $access_token ),
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];

		$response = wp_remote_post( $endpoint, $options );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
		} else {
			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body );

			if ( empty( $body->link ) ) {

				update_option( 'aawp_bitly_link_creation_failed_msg', (array) $body );

				return $body;
			}

			delete_option( 'aawp_bitly_link_creation_failed_msg' );

			return $body;
		}

		return [];
	}
}
