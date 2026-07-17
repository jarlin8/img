<?php
/**
 * Plugin Name: Woody ad snippets premium
 * Plugin URI: http://woody-ad-snippets.webcraftic.com/
 * Description: Premium Executes PHP code, uses conditional logic to insert ads, text, media content and external service’s code. Ensures no content duplication.
 * Author: Webcraftic <wordpress.webraftic@gmail.com>
 * Version: 1.0.6
 * Text Domain: insert-php
 * Domain Path: /languages/
 * Author URI: http://webcraftic.com
 */

// @formatter:off
// Выход при непосредственном доступе
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wasp_premium_load' ) ) {

	function wasp_premium_load() {

		# Если бесплатный плагин не установлен или вызвал ошибку, то прерываем выполнение кода
		if ( ! defined( 'WINP_PLUGIN_ACTIVE' ) || defined( 'WINP_PLUGIN_THROW_ERROR' ) ) {
			return;
		}

		# Если это старая версия плагина, которая не поддерживает премиум плагин, то прерываем выполнение кода
		if( !defined( 'WINP_PLUGIN_VERSION' ) || version_compare(WINP_PLUGIN_VERSION, '2.2.3', '<') ) {
			return;
		}

		# Если лицензия не активирована, то прерываем выполнение кода
		if ( ! WINP_Plugin::app()->premium->is_activate() ) {
			return;
		}

		// Устанавливаем контстанту, что плагин уже используется
		define( 'WASP_PLUGIN_ACTIVE', true );

		// Устанавливаем контстанту c версией плагина
		define( 'WASP_PLUGIN_VERSION', '1.0.6' );

		// Директория плагина
		define( 'WASP_PLUGIN_DIR', dirname( __FILE__ ) );

		// Относительный путь к плагину
		define( 'WASP_PLUGIN_BASE', plugin_basename( __FILE__ ) );

		// Ссылка к директории плагина
		define( 'WASP_PLUGIN_URL', plugins_url( null, __FILE__ ) );

		// Новый тип сниппета
		define( 'WINP_SNIPPET_TYPE_CUSTOM', 'custom' );

		require_once( WASP_PLUGIN_DIR . '/includes/classes/class.core.php' );
		require_once( WASP_PLUGIN_DIR . '/includes/classes/class.helper.php' );

		new WASP_Core();
	}

	add_action( 'plugins_loaded', 'wasp_premium_load', 20 );

	// todo: Добавить удаление файлов из uploads папки. Продумать удаление дополнительного контента связанного с премиум.
	/*function wasp_activate(){
        register_uninstall_hook( __FILE__, 'wasp_uninstall' );
	}

	register_activation_hook( __FILE__, 'wasp_activate' );*/

	// And here goes the uninstallation function:
	/*function wasp_uninstall(){
	    //  codes to perform during unistallation
	}*/
}


// @formatter:on