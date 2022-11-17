<?php

namespace AAWP\ActivityLogs;

/**
 * Class DB. DB stuffs for the activity logs.
 *
 * @since 3.19
 */
class DB {

	/**
	 * Cache key name for total logs.
	 *
	 * @since 3.19
	 */
	const CACHE_TOTAL_KEY = 'aawp_logs_total';

	/**
	 * Logs.
	 *
	 * @since 3.19
	 *
	 * @var \AAWP\ActivityLogs\Logs
	 */
	private $logs;

	/**
	 * Get not-limited total query.
	 *
	 * @since 3.19
	 *
	 * @var int
	 */
	private $full_total;

	/**
	 * Log constructor.
	 *
	 * @since 3.19
	 */
	public function __construct() {

		$this->full_total = false;
		$this->logs       = new Logs();

		add_action( 'shutdown', [ $this, 'save' ] );
	}

	/**
	 * Process the event, clear logs. The logs older than the retenion period are deleted permanently from the database.
	 *
	 * @since 3.19
	 */
	public function process_clear_logs() {

		$settings = get_option( 'aawp_logs_settings' );

		if ( empty( $settings['log_retention_period'] ) ) {
			return;
		}

		global $wpdb;

		$period = $settings['log_retention_period'];
		$period = gmdate( 'Y-m-d', strtotime( '-' . $period . 'day' ) );

		$success = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->get_table_name()} WHERE timestamp < %s", $period ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		do_action( 'aawp_process_clear_logs_complete', $success );
	}

	/**
	 * Get log table name.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public static function get_table_name() {

		global $wpdb;
		return $wpdb->prefix . 'aawp_logs';
	}

	/**
	 * Create table for database.
	 *
	 * @since 3.19
	 */
	public function create_table() {

		global $wpdb;

		$table = self::get_table_name();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			timestamp DATETIME NOT NULL,
			level smallint(4) NOT NULL,
			user_id BIGINT(20),
			component varchar(200) NOT NULL,
			message LONGTEXT NOT NULL,
			context longtext NULL,
			PRIMARY KEY (id)
		) {$charset_collate};";

		maybe_create_table( $table, $sql );
	}

	/**
	 * Create new log.
	 *
	 * @since 3.19
	 *
	 * @param int    $level    Log level.
	 * @param int    $user_id  User ID.
	 * @param string $component   Log component.
	 * @param string $message  Log message.
	 * @param string $context  Log context.
	 */
	public function add( $level, $user_id, $component, $message, $context ) {

		$this->logs->push(
			Log::create( $level, $user_id, $component, $message, $context )
		);
	}

	/**
	 * Save logs to database.
	 *
	 * @since 3.19
	 */
	public function save() {

		// We can't use the empty function because it doesn't work with Countable object.
		if ( ! count( $this->logs ) ) {
			return;
		}
		global $wpdb;
		$sql = 'INSERT INTO ' . self::get_table_name() . ' ( `id`, `timestamp`, `level`, `user_id`, `component`, `message`, `context` ) VALUES ';
		foreach ( $this->logs as $log ) {
			$sql .= $wpdb->prepare(
				'( NULL, %s, %d, %d, %s, %s, %s ),',
				$log->get_timestamp( 'sql' ),
				$log->get_level(),
				$log->get_user_id(),
				$log->get_component(),
				$log->get_message(),
				$log->get_context()
			);
		}
		$sql = rtrim( $sql, ',' );

		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
		wp_cache_delete( self::CACHE_TOTAL_KEY );
	}

	/**
	 * Check if the database table exist.
	 *
	 * @since 3.19
	 *
	 * @return bool
	 */
	public function table_exists() {

		global $wpdb;

		$table = self::get_table_name();

		return $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Get total count of logs.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function get_total() {

		if ( ! $this->is_logging_enabled() ) {
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
	 * Check if logging is enabled.
	 *
	 * @since 3.19
	 *
	 * @return bool Enabled/Disabled..
	 */
	public function is_logging_enabled() {

		$check = get_option( 'aawp_logs_settings' );

		return isset( $check['enable'] ) && 'on' === $check['enable'];
	}

	/**
	 * Clear all logs in Database.
	 *
	 * @since 3.19
	 */
	public function clear_all() {

		global $wpdb;

		$wpdb->query( 'TRUNCATE TABLE ' . self::get_table_name() ); // phpcs:ignore
	}
}
