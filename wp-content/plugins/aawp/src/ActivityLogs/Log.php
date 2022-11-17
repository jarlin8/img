<?php

namespace AAWP\ActivityLogs;

/**
 * Class Log.
 *
 * @since 3.19
 */
class Log {

	/**
	 * Log ID.
	 *
	 * @since 3.19
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Datetime of creating log.
	 *
	 * @since 3.19
	 *
	 * @var string
	 */
	private $timestamp;

	/**
	 * Log Level.
	 *
	 * @since 3.19
	 *
	 * @var int
	 */
	private $level;

	/**
	 * Log user ID.
	 *
	 * @since 3.19
	 *
	 * @var int
	 */
	private $user_id;

	/**
	 * Log component.
	 *
	 * @since 3.19
	 *
	 * @var string
	 */
	private $component;

	/**
	 * Log message.
	 *
	 * @since 3.19
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Log Context.
	 *
	 * @since 3.19
	 *
	 * @var array|string
	 */
	private $context;

	/**
	 * Log constructor.
	 *
	 * @since 3.19
	 *
	 * @param int    $id        Log ID.
	 * @param string $timestamp Datetime of creating log.
	 * @param string $level     Log level.
	 * @param int    $user_id  Log user ID.
	 * @param string $component  Log component.
	 * @param string $message  Log message.
	 * @param string $context  Log context.
	 */
	public function __construct( $id, $timestamp, $level, $user_id, $component, $message, $context ) {

		$this->id        = $id;
		$this->timestamp = strtotime( $timestamp );
		$this->level     = $level;
		$this->user_id   = $user_id;
		$this->component = $component;
		$this->message   = $message;
		$this->context   = $context;
	}

	/**
	 * Get log ID.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function get_id() {

		return $this->id;
	}

	/**
	 * Get date of creating log.
	 *
	 * @since 3.19
	 *
	 * @param string $format Date format full|short|default sql format.
	 *
	 * @return string
	 */
	public function get_timestamp( $format = 'short' ) {

		if ( 'short' === $format ) {
			return date_i18n(
				get_option( 'date_format' ) . ' H:i:s',
				$this->timestamp + ( get_option( 'gmt_offset' ) * 3600 )
			);
		} elseif ( 'sql' === $format ) {
			return gmdate( 'Y-m-d H:i:s', $this->timestamp );
		} else {
			return date_i18n(
				sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ),
				$this->timestamp + ( get_option( 'gmt_offset' ) * 3600 )
			);
		}
	}

	/**
	 * Get log level.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function get_level() {

		return $this->level;
	}

	/**
	 * Get log user id.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function get_user_id() {

		return $this->user_id;
	}

	/**
	 * Get log component.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function get_component() {

		return $this->component;
	}

	/**
	 * Get log message.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function get_message() {

		return wp_kses_post( $this->message );
	}

	/**
	 * Get log context.
	 *
	 * @since 3.19
	 *
	 * @return string
	 */
	public function get_context() {

		return $this->context;
	}

	/**
	 * Create new log.
	 *
	 * @since 3.19
	 *
	 * @param string $level    Log level.
	 * @param int    $user_id  Log user ID.
	 * @param string $component  Log component.
	 * @param string $message  Log message.
	 * @param string $context  Log context.
	 *
	 * @return \AAWP\ActivityLogs\Log
	 */
	public static function create( $level, $user_id = 0, $component, $message, $context ) {

		return new Log(
			0,
			gmdate( 'Y-m-d H:i:s' ),
			$level,
			absint( $user_id ),
			$component,
			wp_kses_post( $message ),
			$context
		);
	}
}
