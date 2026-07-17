<?php

namespace AAWP\Elementor;

/**
 * Elementor Compatibility.
 *
 * @since 3.19
 */
class Elementor {

	/**
	 * Are we ready?
	 *
	 * @since 3.19
	 *
	 * @return bool
	 */
	public function allow_load() {

		return (bool) did_action( 'elementor/loaded' );
	}

	/**
	 * Initialize.
	 *
	 * @since 3.19.
	 */
	public function init() {

		if ( ! $this->allow_load() || ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'load_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'load_scripts' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );
	}

	/**
	 * Load required scripts on Elementor pages.
	 *
	 * @since 3.19
	 */
	public function load_scripts() {
		\aawp_scripts();

		wp_add_inline_script(
			'aawp',
			'var aawp_elementor_data = ' . wp_json_encode(
				[
					'shortcode' => \aawp_get_shortcode(),
				]
			),
			'before'
		);
	}

	/**
	 * Register AAWP Widget.
	 *
	 * @since 3.19
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widget( $widgets_manager ) {

		include_once AAWP_PLUGIN_DIR . 'src/Elementor/Widget.php';

		$widgets_manager->register( new Widget() );
	}
}
