<?php

namespace AAWP\ClickTracking;

/**
 * Class DB. DB stuffs for the click tracking.
 *
 * @since 3.20
 */
class DB {

	/**
	 * Cache key name for total clicks.
	 *
	 * @since 3.20
	 */
	const CACHE_TOTAL_KEY = 'aawp_clicks_total';

	/**
	 * Clicks.
	 *
	 * @since 3.20
	 *
	 * @var \AAWP\ClickTracking\Clicks
	 */
	private $clicks;

	/**
	 * Get not-limited total query.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	private $full_total;

	/**
	 * Log constructor.
	 *
	 * @since 3.20
	 */
	public function __construct() {

		$this->full_total = false;
		$this->clicks     = new Clicks();

		add_action( 'shutdown', [ $this, 'save' ] );
	}

	/**
	 * Process the event, clear clicks.
	 *
	 * @todo:: Clicks doesn't currently have retention period settings.
	 *
	 * @since 3.20
	 */
	public function process_clear_clicks() {

		$settings = get_option( 'aawp_clicks_settings' );

		if ( empty( $settings['log_retention_period'] ) ) {
			return;
		}

		global $wpdb;

		$period = $settings['log_retention_period'];
		$period = gmdate( 'Y-m-d', strtotime( '-' . $period . 'day' ) );

		$success = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->get_table_name()} WHERE created_at < %s", $period ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		do_action( 'aawp_process_clear_clicks_complete', $success );
	}

	/**
	 * Get clicks table name.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public static function get_table_name() {

		global $wpdb;
		return $wpdb->prefix . 'aawp_clicks';
	}

	/**
	 * Create table for database.
	 *
	 * @since 3.20
	 */
	public function create_table() {

		global $wpdb;

		$table = self::get_table_name();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			link_type VARCHAR(255),
			product_id BIGINT(20) NOT NULL,
			source_type VARCHAR(255),
			source_id INT(20),
			is_widget Tinyint(1),
			referer_url longtext,
			tracking_id VARCHAR(255),
			visitor_hash VARCHAR(255),
			browser VARCHAR(255),
			os VARCHAR(255),
			device VARCHAR(255),
			country VARCHAR(255),
			created_at DATETIME NOT NULL,
			PRIMARY KEY (id)
		) {$charset_collate};";

		maybe_create_table( $table, $sql );
	}

	/**
	 * Create new log.
	 *
	 * @since 3.20
	 *
	 * @param string $link_type     Link Type. E.g. button, image, title.
	 * @param int    $product_id    Product_id.
	 * @param string $source_type       Source Type.
	 * @param int    $source_id         Source ID.
	 * @param int    $is_widget         Is widget.
	 * @param string $referer_url   Referer URl.
	 * @param string $tracking_id   Tracking_id.
	 * @param string $visitor_hash  Visitor_hash.
	 * @param string $browser       Browser.
	 * @param string $os            OS.
	 * @param string $device        Device.
	 * @param string $country       Country.
	 */
	public function add( $link_type, $product_id, $source_type, $source_id, $is_widget, $referer_url, $tracking_id, $visitor_hash, $browser, $os, $device, $country ) {

		$this->clicks->push(
			Click::create( $link_type, $product_id, $source_type, $source_id, $is_widget, $referer_url, $tracking_id, $visitor_hash, $browser, $os, $device, $country )
		);
	}

	/**
	 * Save clicks to database.
	 *
	 * @since 3.20
	 */
	public function save() {

		// We can't use the empty function because it doesn't work with Countable object.
		if ( ! count( $this->clicks ) ) {
			return;
		}
		global $wpdb;
		$sql = 'INSERT INTO ' . self::get_table_name() . ' ( `id`, `link_type`, `product_id`, `source_type`, `source_id`, `is_widget`, `referer_url`, `tracking_id`, `visitor_hash`, `browser`, `os`, `device`, `country`, `created_at` ) VALUES ';
		foreach ( $this->clicks as $click ) {
			$sql .= $wpdb->prepare(
				'( NULL, %s, %d, %s, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s ),',
				$click->get_link_type(),
				$click->get_product_id(),
				$click->source_type,
				$click->source_id,
				$click->is_widget,
				$click->get_referer_url(),
				$click->get_tracking_id(),
				$click->get_visitor_hash(),
				$click->get_browser(),
				$click->get_os(),
				$click->get_device(),
				$click->get_country(),
				$click->get_created_at( 'sql' )
			);
		}
		$sql = rtrim( $sql, ',' );

		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
		wp_cache_delete( self::CACHE_TOTAL_KEY );
	}

	/**
	 * Check if the database table exist.
	 *
	 * @since 3.20
	 *
	 * @return bool
	 */
	public function table_exists() {

		global $wpdb;

		$table = self::get_table_name();

		return $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Get total count of clicks.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function get_total() {

		if ( ! $this->is_click_tracking_enabled() ) {
			return 0;
		}

		global $wpdb;
		$total = wp_cache_get( self::CACHE_TOTAL_KEY );
		if ( ! $total ) {

			$total = $this->full_total ? $wpdb->get_var( 'SELECT FOUND_ROWS()' ) : $wpdb->get_var( 'SELECT COUNT(ID) FROM ' . self::get_table_name() ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

			wp_cache_set( self::CACHE_TOTAL_KEY, $total, 'aawp', DAY_IN_SECONDS );
		}

		return absint( $total );
	}

	/**
	 * Get total clicks by filter.
	 *
	 * @param string $by The filter.
	 * @param string $value The filter value.
	 *
	 * @return int The total clicks number.
	 */
	public static function get_total_clicks_by( $by, $value ) {
		global $wpdb;

		$query = "SELECT COUNT( {$by} ) as count FROM {$wpdb->prefix}aawp_clicks WHERE {$by} = {$value}";

		$result = $wpdb->get_row( $query, ARRAY_A ); //phpcs:ignore

		return isset( $result['count'] ) ? absint( $result['count'] ) : 0;
	}

	/**
	 * Check if click tracking is enabled.
	 *
	 * @since 3.20
	 *
	 * @return bool Enabled/Disabled..
	 */
	public function is_click_tracking_enabled() {

		return ! empty( get_option( 'aawp_clicks_settings' )['enable'] );
	}

	/**
	 * Clear all clicks in Database.
	 *
	 * @since 3.20
	 */
	public function clear_all() {

		global $wpdb;

		$wpdb->query( 'TRUNCATE TABLE ' . self::get_table_name() ); // phpcs:ignore

		aawp_log( 'Clicks', esc_html__( 'All clicks cleared via clicks listtable.' ) );
	}
}
