<?php
/**
 * Core plugin class
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 08.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WASP_Core {

	/**
	 * @var WASP_Core
	 */
	private static $app;

	/**
	 * WASP_Core constructor.
	 */
	public function __construct() {
		self::$app = $this;

		$this->global_scripts();

		if ( is_admin() ) {
			$this->admin_scripts();

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				require_once( WASP_PLUGIN_DIR . '/admin/ajax/ajax.php' );
			}
		}
	}

	/**
	 * Статический метод для быстрого доступа к информации о плагине, а также часто использумых методах.
	 *
	 * @return WASP_Core
	 */
	public static function app() {
		return self::$app;
	}

	/**
	 * Регистрируем страницы плагина
	 */
	private function register_pages() {
		WINP_Plugin::app()->registerPage( 'WASP_Import_Page', WASP_PLUGIN_DIR . '/admin/pages/import.php' );
		WINP_Plugin::app()->registerPage( 'WASP_RevisionsPage', WASP_PLUGIN_DIR . '/admin/pages/revisions.php' );
	}

	/**
	 * Register shortcodes
	 */
	private function register_shortcodes() {
		$is_cron = WINP_Helper::doing_cron();
		$is_rest = WINP_Helper::doing_rest_api();

		$action = WINP_Plugin::app()->request->get( 'action', '' );
		if ( ! ( ! empty( $action ) && 'edit' == $action && is_admin() ) && ! $is_cron && ! $is_rest ) {
			require_once( WASP_PLUGIN_DIR . '/includes/shortcodes/shortcode-custom.php' );

			WINP_Helper::register_shortcode( 'WASP_Snippet_Shortcode_Custom', WINP_Plugin::app() );
		}
	}

	/**
	 * Подключаем функции бэкенда
	 */
	private function admin_scripts() {
		$this->register_pages();

		$this->get_actions_object()->register_hooks();

		require( WASP_PLUGIN_DIR . '/admin/boot.php' );
	}

	/**
	 * Подключаем глобальные функции
	 */
	private function global_scripts() {
		require_once( WASP_PLUGIN_DIR . '/includes/classes/class.snippet.php' );
		new WASP_Snippet();
		$this->register_shortcodes();

		$this->get_geo_object()->register_hooks();
	}

	/**
	 * Get WASP_Geo_Snippet object
	 *
	 * @return WASP_Geo_Snippet
	 */
	public function get_geo_object() {
		require_once( WASP_PLUGIN_DIR . '/includes/classes/class.geo.snippet.php' );

		return new WASP_Geo_Snippet();
	}

	/**
	 * Get WASP_Actions_Snippet object
	 *
	 * @return WASP_Actions_Snippet
	 */
	public function get_actions_object() {
		require_once( WASP_PLUGIN_DIR . '/admin/includes/class.actions.snippet.php' );

		return new WASP_Actions_Snippet();
	}
}