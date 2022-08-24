<?php

namespace AAWP;

/**
 * The Loader Class.
 */
class Loader {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize.
	 *
	 * @return void.
	 */
	public function init() {

		add_action( 'plugins_loaded', [ $this, 'load_classes' ] );
	}

	/**
	 * Load Classes.
	 *
	 * @return void.
	 */
	public function load_classes() {

		$classes = [

			// Block & Stuffs.
			'Block',
			'ClassicEditor',
			'MetaBox',
			'DuplicateTable',

			// Shortener.
			'ShortenLinks/Settings',
			'ShortenLinks/Process',
			'ShortenLinks/DB',
			'ShortenLinks/BitlyAPI',

			'Elementor/Elementor',
		];

		foreach ( $classes as $class ) {

			$check_slash = explode( '/', $class );

			if ( ! isset( $check_slash[1] ) ) {
				include_once AAWP_PLUGIN_DIR . 'src/' . $class . '.php';
			} else {
				include_once AAWP_PLUGIN_DIR . 'src/' . $check_slash[0] . '/' . $check_slash[1] . '.php';
			}

			if ( ( ! isset( $check_slash[1] ) ) && \class_exists( __NAMESPACE__ . '\\' . $class ) ) {
				$class = __NAMESPACE__ . '\\' . $class;

				$obj = new $class();
				$obj->init();

			} elseif ( isset( $check_slash[1] ) && \class_exists( __NAMESPACE__ . '\\' . $check_slash[0] . '\\' . $check_slash[1] ) ) {
				$class = __NAMESPACE__ . '\\' . $check_slash[0] . '\\' . $check_slash[1];

				$obj = new $class();
				$obj->init();
			}
		}//end foreach
	}
}
