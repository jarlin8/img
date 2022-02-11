<?php
/**
 * Обычно в этом файле размещает код, который отвечает за уведомление, совместимость с другими плагинами,
 * незначительные функции, которые должны быть выполнены на всех страницах админ панели.
 *
 * В этом файле должен быть размещен код, которые относится только к области администрирования.
 *
 * @author    Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright Webcraftic 19.09.2018
 * @version   1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Подключаем глобальны скрипты и стили
 */
function wbcr_wasp_enqueue_scripts() {
	$screen = get_current_screen();

	if ( WINP_SNIPPETS_POST_TYPE == $screen->post_type ) {
		wp_enqueue_style(
			'wbcr-inp-admin-general-scripts',
			WASP_PLUGIN_URL . '/admin/assets/css/general.css',
			array(),
			WINP_Plugin::app()->getPluginVersion()
		);
	}
}

add_action( 'admin_enqueue_scripts', 'wbcr_wasp_enqueue_scripts' );

/**
 * Инициализация админки
 */
function wbcr_wasp_admin_init() {
	$plugin = WINP_Plugin::app();

	$is_upgraded_221 = get_option( $plugin->getOptionName( 'upgrade_up_to_221' ) );
	if ( ! $is_upgraded_221 ) {
		$role = get_role( 'administrator' );
		$role->add_cap( 'delete_others_' . WINP_SNIPPETS_POST_TYPE . 's' );
		update_option( $plugin->getOptionName( 'upgrade_up_to_221' ), 1 );
	}
}
add_action( 'admin_init', 'wbcr_wasp_admin_init' );

/**
 * Переопределим блок ревизий из базового плагина
 */
remove_action( 'wbcr/inp/boot/metaboxes/revisions', 'wbcr_inp_admin_revisions' );
function wbcr_wasp_admin_revisions() {
	$plugin = WINP_Plugin::app();

	require_once( WASP_PLUGIN_DIR . '/admin/metaboxes/revisions.php' );
	WINP_Helper::register_factory_metaboxes( new WASP_RevisionsMetaBox( $plugin ), WINP_SNIPPETS_POST_TYPE, $plugin );
}
add_action( 'wbcr/inp/boot/metaboxes/revisions', 'wbcr_wasp_admin_revisions', 20 );
