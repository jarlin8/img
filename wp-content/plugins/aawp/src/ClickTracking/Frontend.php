<?php

namespace AAWP\ClickTracking;

/**
 * The Frontend Server-Side functionality of Click Tracking.
 *
 * @since 3.20
 */
class Frontend {

	/**
	 * Parser.
	 *
	 * @var $parser.
	 */
	private $parser;

	/**
	 * Initialize.
	 */
	public function init() {

		if ( $this->is_excluded() ) {
			return;
		}

		add_filter( 'aawp_product_container_attributes', [ $this, 'add_local_click_tracking_attribute' ] );
		add_action( 'wp_footer', [ $this, 'add_inline_scripts' ] );
		add_action( 'rest_api_init', [ $this, 'register_rest' ] );

		// @todo:: optimize loader.
		require_once AAWP_PLUGIN_DIR . 'src/ClickTracking/user-agents/class-aawp-parser.php';

		$user_agent   = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
		$this->parser = new \AAWP_Parser( $user_agent );
	}

	/**
	 * Is geolocation tracking enabled.
	 *
	 * @since 3.20
	 *
	 * @return bool
	 */
	private function is_location_tracking_enabled() {

		return ! empty( get_option( 'aawp_clicks_settings' )['country'] );
	}

	/**
	 * Is User Role excluded from click tracking.
	 *
	 * @since 3.20
	 *
	 * @return bool
	 */
	public function is_excluded() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		$excluded = ! empty( get_option( 'aawp_clicks_settings' )['exclude_roles'] ) ? (array) get_option( 'aawp_clicks_settings' )['exclude_roles'] : [];

		$user  = wp_get_current_user();
		$roles = (array) $user->roles;

		foreach ( $roles as $role ) {
			if ( in_array( $role, $excluded ) ) { //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				return true;
			}
		}

		return false;
	}

	/**
	 * Add local click tracking attribute to product container.
	 *
	 * @param array $attributes The existing attributes.
	 *
	 * @since 3.20
	 *
	 * @return array Attributes containing local-click-tracking.
	 */
	public function add_local_click_tracking_attribute( $attributes ) {

		$attributes['local-click-tracking'] = true;
		return $attributes;
	}

	/**
	 * User IP.
	 */
	private function get_user_ip() {

		$ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? wp_unslash( $_SERVER['REMOTE_ADDR'] ) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$ip = rest_is_ip_address( $ip ) ? $ip : '';

		return wp_privacy_anonymize_ip( $ip );
	}

	/**
	 * User Salt.
	 */
	private function get_user_salt() {
		return gmdate( 'Y-m-d' );
	}

	/**
	 * Generate Hash.
	 */
	private function generate_hash() {
		$ip   = $this->get_user_ip();
		$date = $this->get_user_salt();
		$ua   = $this->parser->ua;
		$hash = $date . $ip . $ua;

		return hash( 'md5', $hash );
	}

	/**
	 * Add Inline Scripts to the Frontend JS.
	 *
	 * @since 3.20.
	 */
	public function add_inline_scripts() {

		$data = [
			'rest_url'    => rest_url(),
			'home_url'    => home_url(),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'referer_url' => isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '',
		];

		if ( $this->is_location_tracking_enabled() ) {

			$data['api_url'] = ! defined( 'AAWP_API_URL' ) ? 'https://api.getaawp.com/v1/country/' . $this->get_user_ip() : AAWP_API_URL . '/country/' . $this->get_user_ip();
			
			if ( 'valid' !==  aawp_get_option( 'info', 'licensing' )['status'] ) {
				$data['api_url'] = 'https://ipinfo.io/' . $this->get_user_ip() . '/json/';
				$data['ipinfo']  = true;
			}
		}

		$data = array_merge( $data, $this->get_source() );

		/**
		 * Adds extra code to a registered script. If "aawp" handle isn't registered, it does nothing.
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_add_inline_script/
		 */
		wp_add_inline_script(
			'aawp',
			'var aawp_data = ' . wp_json_encode( $data ),
			'before'
		);
	}

	/**
	 * Get the source type & it's ID.
	 *
	 * @since 3.20
	 *
	 * @return array An array of source type & ID.
	 */
	private function get_source() {

		// Single posts/pages page.
		if ( is_singular() ) {

			return [
				'source_type' => 'post',
				'source_id'   => get_the_ID(),
			];
		}

		// Terms Page.
		$object = get_queried_object();
		if ( ! empty( $object->term_id ) ) {

			return [
				'source_type' => 'term',
				'source_id'   => $object->term_id,
			];
		}

		// Front Page.
		if ( is_front_page() ) {

			return [
				'source_type' => 'front_page',
				'source_id'   => 0,
			];
		}

		// Posts Page.
		if ( is_home() ) {

			return [
				'source_type' => 'posts_page',
				'source_id'   => 0,
			];
		}

		return [
			'source_type' => '',
			'source_id'   => 0,
		];
	}

	/**
	 * Register REST API Route.
	 *
	 * @since 3.20.
	 */
	public function register_rest() {

		/**
		 * Registers a REST API route.
		 *
		 * @see https://developer.wordpress.org/reference/functions/register_rest_route/
		 */
		register_rest_route(
			'aawp/v1',
			'/click/(?P<cachebreak>\d+)',
			[
				'methods'             => [ 'POST' ],
				'permission_callback' => '__return_true',
				'callback'            => [ $this, 'track_click_v1' ],
			]
		);
	}

	/**
	 * Track a click on a link with JS generated onClick event.
	 *
	 * @param WP_REST_Request $data The data from JS.
	 *
	 * @since 3.20
	 */
	public function track_click_v1( $data ) {

		$post_data   = ! empty( $data->get_body_params() ) ? $data->get_body_params() : [];
		$product_id  = isset( $post_data['productId'] ) ? $post_data['productId'] : 0;
		$source_type = isset( $post_data['sourceType'] ) ? $post_data['sourceType'] : '';
		$source_id   = isset( $post_data['sourceId'] ) ? $post_data['sourceId'] : 0;
		$referer_url = isset( $post_data['refererUrl'] ) ? $post_data['refererUrl'] : '-';
		$is_widget   = isset( $post_data['isWidget'] ) ? $post_data['isWidget'] : 0;
		$tracking_id = isset( $post_data['trackingId'] ) ? $post_data['trackingId'] : aawp_get_default_tracking_id();
		$link_type   = isset( $post_data['linkType'] ) ? $post_data['linkType'] : '-';
		$country     = isset( $post_data['country'] ) ? $post_data['country'] : '';

		$visitor_hash = $this->generate_hash();
		$browser      = $this->parser->ua;
		$os           = $this->parser->os;
		$device       = $this->parser->type;

		$clicks_db = new DB();

		if ( ! method_exists( $clicks_db, 'add' ) ) {
			return;
		}

		if ( ! $this->parser->bot ) {

			$clicks_db->add(
				$link_type,
				$product_id,
				$source_type,
				$source_id,
				$is_widget,
				$referer_url,
				$tracking_id,
				$visitor_hash,
				$browser,
				$os,
				ucfirst( $device ),
				$country
			);
		}
	}
}
