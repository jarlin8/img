<?php

namespace AAWP\ShortenLinks;

defined( 'ABSPATH' ) || exit;
// Exit if accessed directly.

/**
 * DB For ShortenLinks.
 *
 * @since 3.18
 */
class DB extends \AAWP_DB {

	/**
	 * Initialize DB.
	 *
	 * @since 3.18
	 */
	public function init() {
		add_action( 'admin_init', [ $this, 'process_db' ], 5 );
	}

	/**
	 * Shorten Links DB actions.
	 */
	public function process_db() {

		if ( ! \aawp_is_user_admin() ) {
			return;
		}

		$check = \aawp_get_option( 'affiliate_links', 'general' );

		if ( ! isset( $_GET['page'] ) || 'aawp-settings' !== $_GET['page'] || 'shortened' !== $check ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		if ( ! is_blog_installed() ) {
			return;
		}

		$transient = '_transient_aawp_creating_db_for_link_shortener';

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( $transient ) ) {
			return;
		}

		// Set the transient.
		set_transient( $transient, 'yes', MINUTE_IN_SECONDS * 10 );

		$this->create_tables();

		delete_transient( $transient );

		do_action( 'aawp_db_for_link_shortener_created' );
	}

	/**
	 * Create DB tables for Link Shortener.
	 *
	 * @since 3.18
	 *
	 * @return void
	 */
	private function create_tables() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'aawp_bitly_links';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			url varchar( 512 ) NOT NULL,
			short_url varchar( 512 ) NOT NULL,
			group_id varchar (255) NOT NULL,
			clicks smallint(5) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		$success = empty( $wpdb->last_error );

		if ( $this->table_exists( $table_name ) ) {
			update_option( $table_name . '_db_version', AAWP_VERSION );
		}
	}

	/**
	 * Store data in DB.
	 *
	 * @param array $data The data to store.
	 *
	 * @since 3.18
	 */
	public static function store_data( $data ) {

		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'aawp_bitly_links', $data ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
	}

	/**
	 * Get results from the database.
	 *
	 * @since 3.18
	 *
	 * @return bool
	 */
	public static function get_db_results() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'aawp_bitly_links';

		$sql = "SELECT url, short_url FROM $table_name";

		return $wpdb->get_results( $sql ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Drop or truncate the db table.
	 *
	 * @param bool $force Drop or truncate table.
	 *
	 * @since 3.18
	 */
	public static function clear_data( $force = true ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'aawp_bitly_links';

		if ( true === $force ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" ); // phpcs:ignore
		} else {
			$wpdb->query( "TRUNCATE TABLE IF EXISTS {$table_name}" ); // phpcs:ignore
		}
	}
}
