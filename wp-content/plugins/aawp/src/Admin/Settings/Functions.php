<?php

namespace AAWP\Admin\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for the Functions tab within the settings.
 */
class Functions extends \AAWP_Functions {

	/**
	 * Construct the plugin object
	 */
	public function __construct() {

		// Call parent constructor first.
		parent::__construct();

		if ( ! aawp_is_license_valid() ) {
			return;
		}

		// Setup identifier.
		$this->func_order = 30;
		$this->func_id    = 'functions';
		$this->func_name  = __( 'Functions', 'aawp' );

		// Settings functions.
		add_filter( $this->settings_tabs_filter, [ &$this, 'add_settings_tabs_filter' ] );
		add_filter( $this->settings_functions_filter, [ &$this, 'add_settings_functions_filter' ] );

		add_action( 'admin_init', [ &$this, 'add_settings' ] );
	}

	/**
	 * Add settings functions.
	 *
	 * @param array $tabs The other tabs.
	 */
	public function add_settings_tabs_filter( $tabs ) {

		$tabs[ $this->func_order ] = [
			'key'   => $this->func_id,
			'icon'  => 'performance',
			'title' => __( 'Functions', 'aawp' ),
		];

		return $tabs;
	}

	/**
	 * Add functions to the settings.
	 *
	 * @param array $functions An array of other functions.
	 */
	public function add_settings_functions_filter( $functions ) {

		$functions[] = $this->func_id;

		return $functions;
	}

	/**
	 * Settings: Register section and fields
	 */
	public function add_settings() {

		register_setting( 'aawp_functions', 'aawp_functions' );

		add_settings_section(
			'aawp_functions_section',
			__( 'Functions settings', 'aawp' ),
			[ &$this, 'settings_functions_section_callback' ],
			'aawp_functions'
		);

		/*
		 * Action to add more settings within this section
		 */
		do_action( 'aawp_settings_functions_register' );
	}

	/**
	 * Settings callbacks
	 */
	public function settings_functions_section_callback() {

		echo esc_html__( 'Here you can specify the settings for each included functionality.', 'aawp' );
	}
}
