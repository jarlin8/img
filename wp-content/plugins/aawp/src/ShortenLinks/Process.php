<?php

namespace AAWP\ShortenLinks;

use AAWP\ShortenLinks\DB;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Process Class For ShortenLinks
 *
 * @since 3.18
 */
class Process {

	/**
	 * Constructor.
	 *
	 * @since 3.18
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 3.18
	 */
	public function init() {

		$check = \aawp_get_option( 'affiliate_links', 'general' );

		if ( 'shortened' !== $check ) {
			return;
		}

		// Modify the contents.
		add_filter( 'aawp_shortcode_content', [ $this, 'initiate_replace' ], 999 );
	}

	/**
	 * Logic to replace links.
	 *
	 * @param string $content The content of the post.
	 *
	 * @since 3.18
	 */
	public function initiate_replace( $content ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( strpos( $content, 'amazon.' ) !== false ) {

			/**
			 * Extract the urls from the content.
			 *
			 * @param string $content The content.
			 *
			 * @link https://developer.wordpress.org/reference/functions/wp_extract_urls/
			 */
			$urls = wp_extract_urls( $content );

			$amzn_urls = array_filter(
				$urls,
				function( $url ) {
					if ( strpos( strtolower( $url ), 'amazon.' ) && ! strpos( strtolower( $url ), 'media-amazon.' ) ) {
						return $url;
					}
				}
			);

			foreach ( $amzn_urls as $url ) {

				// Attempt to get short url, if already exists in db.
				$result = $this->maybe_get_short_url( $url );

				if ( false !== $result ) {

					// Replace the long URL with the short URL from DB.
					$content = str_replace( [ '&#038;', '&amp;' ], '&', $content );
					$content = str_replace( $url, $result->short_url, $content );
				} else {

					$access_token = \aawp_get_option( 'bitly_access_token', 'general' );

					if ( empty( $access_token ) ) {
						return $content;
					}

					$request_body = BitlyAPI::request( $url, $access_token );

					if ( ! isset( $request_body->link ) ) {
						return $content;
					}

					// Replace the long URL with short URL from API Request.
					$content = str_replace( [ '&#038;', '&amp;' ], '&', $content );
					$content = str_replace( $url, $request_body->link, $content );

					// Prepare data to store in db.
					$db_data = $this->prepare_db_data( $request_body, $url );

					// Actually store the data in db.
					DB::store_data( $db_data );
				}//end if
			}//end foreach
		}//end if

		return $content;
	}

	/**
	 * Get the URL and it's short form, if the given URL exists in the db.
	 *
	 * @param string $url URL to get it's short url.
	 *
	 * @since 3.18
	 *
	 * @return mixed object|bool Data in object format or false if not available.
	 */
	public function maybe_get_short_url( $url ) {
		$results = DB::get_db_results();

		foreach ( $results as $result ) {
			if ( $url === $result->url ) {
				return $result;
			}
		}

		return false;
	}

	/**
	 * Prepare data to store in database.
	 *
	 * @param object $body The API request body.
	 * @param string $url  The given URL.
	 *
	 * @since 3.18
	 */
	public function prepare_db_data( $body, $url ) {

		$group     = isset( $body->references->group ) ? $body->references->group : '';
		$crk_group = explode( '/groups/', $group );

		$db_data = [];

		$db_data['url']        = $url;
		$db_data['short_url']  = isset( $body->link ) ? esc_url( $body->link ) : $body->id;
		$db_data['group_id']   = isset( $crk_group[1] ) ? $crk_group[1] : '';
		$db_data['clicks']     = 0;
		$db_data['created_at'] = gmdate( 'Y-m-d H:i:s' );
		$db_data['updated_at'] = gmdate( 'Y-m-d H:i:s' );

		return $db_data;
	}
}
