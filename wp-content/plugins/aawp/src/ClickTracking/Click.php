<?php

namespace AAWP\ClickTracking;

/**
 * Class Click.
 *
 * @since 3.20
 */
class Click {

	/**
	 * Click ID.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Datetime of creating click.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $created_at;

	/**
	 * Link Type.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $link_type;

	/**
	 * Click Product ID.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	private $product_id;

	/**
	 * Click Source Type.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	public $source_type;

	/**
	 * Click Source ID.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	public $source_id;

	/**
	 * Is widget.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	public $is_widget;

	/**
	 * Referer URL.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $referer_url;

	/**
	 * Click Tracking ID.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $tracking_id;

	/**
	 * Click Vistor Hash.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $visitor_hash;

	/**
	 * Click Browser.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $browser;

	/**
	 * Click OS.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $os;

	/**
	 * Click Device.
	 *
	 * @since 3.20
	 *
	 * @var string
	 */
	private $device;


	/**
	 * Click constructor.
	 *
	 * @since 3.20
	 *
	 * @param int    $id                Click ID.
	 * @param string $link_type         Link Type.
	 * @param int    $product_id        Product ID.
	 * @param string $source_type       Source Type.
	 * @param int    $source_id         Source ID.
	 * @param int    $is_widget         Is widget.
	 * @param string $referer_url       Referer URL.
	 * @param string $tracking_id       Tracking ID.
	 * @param string $visitor_hash      Visitor Hash.
	 * @param string $browser           Browser.
	 * @param string $os                OS.
	 * @param string $device            Device.
	 * @param string $country           Country.
	 * @param string $created_at        Created At.
	 */
	public function __construct( $id, $link_type, $product_id, $source_type, $source_id, $is_widget, $referer_url, $tracking_id, $visitor_hash, $browser, $os, $device, $country, $created_at ) {

		$this->id           = $id;
		$this->link_type    = $link_type;
		$this->product_id   = $product_id;
		$this->source_type  = $source_type;
		$this->source_id    = $source_id;
		$this->is_widget    = $is_widget;
		$this->referer_url  = $referer_url;
		$this->tracking_id  = $tracking_id;
		$this->visitor_hash = $visitor_hash;
		$this->browser      = $browser;
		$this->os           = $os;
		$this->device       = $device;
		$this->country      = $country;

		$this->created_at = strtotime( $created_at );
	}

	/**
	 * Get click ID.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function get_id() {

		return $this->id;
	}

	/**
	 * Get date of creating click.
	 *
	 * @since 3.20
	 *
	 * @param string $format Date format full|short|default sql format.
	 *
	 * @return string
	 */
	public function get_created_at( $format = 'short' ) {

		if ( 'short' === $format ) {
			return date_i18n(
				get_option( 'date_format' ) . ' H:i:s',
				$this->created_at + ( get_option( 'gmt_offset' ) * 3600 )
			);
		} elseif ( 'sql' === $format ) {
			return gmdate( 'Y-m-d H:i:s', $this->created_at );
		} else {
			return date_i18n(
				sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ),
				$this->created_at + ( get_option( 'gmt_offset' ) * 3600 )
			);
		}
	}

	/**
	 * Get Link Type.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_link_type() {

		return $this->link_type;
	}

	/**
	 * Get Product ID.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function get_product_id() {

		return absint( $this->product_id );
	}

	/**
	 * Get Referer URL.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_referer_url() {

		return $this->referer_url;
	}

	/**
	 * Get Tracking ID.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_tracking_id() {

		return $this->tracking_id;
	}

	/**
	 * Get Visitor Hash.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_visitor_hash() {

		return $this->visitor_hash;
	}

	/**
	 * Get Browser.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_browser() {

		return $this->browser;
	}

	/**
	 * Get OS.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_os() {

		return $this->os;
	}

	/**
	 * Get Device.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_device() {

		return $this->device;
	}

	/**
	 * Get Device.
	 *
	 * @since 3.20
	 *
	 * @return string
	 */
	public function get_country() {

		return $this->country;
	}

	/**
	 * Create new click.
	 *
	 * @since 3.20
	 *
	 * @param string $link_type         Link Type. E.g. button, image, title.
	 * @param int    $product_id        Product ID.
	 * @param string $source_type       Source Type.
	 * @param int    $source_id         Source ID.
	 * @param int    $is_widget         Is widget.
	 * @param string $referer_url       Referer URL.
	 * @param string $tracking_id       Tracking ID.
	 * @param string $visitor_hash      Visitor Hash.
	 * @param string $browser           Browser.
	 * @param string $os                OS.
	 * @param string $device            Device.
	 * @param string $country           Country.
	 *
	 * @return \AAWP\ClickTracking\Click
	 */
	public static function create( $link_type, $product_id, $source_type, $source_id, $is_widget, $referer_url, $tracking_id, $visitor_hash, $browser, $os, $device, $country ) {

		return new Click(
			0,
			$link_type,
			absint( $product_id ),
			$source_type,
			absint( $source_id ),
			absint( $is_widget ),
			$referer_url,
			$tracking_id,
			$visitor_hash,
			$browser,
			$os,
			$device,
			$country,
			gmdate( 'Y-m-d H:i:s' )
		);
	}
}
