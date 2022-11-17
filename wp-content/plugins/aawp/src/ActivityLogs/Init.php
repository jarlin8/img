<?php

namespace AAWP\ActivityLogs;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core class for Logs.
 *
 * @since 3.19
 */
class Init {

	/**
	 * Initialize.
	 *
	 * @since 3.19
	 */
	public function init() {

		add_action( 'aawp_admin_menu', [ $this, 'add_logs_submenu' ], 50 );

		$check    = get_option( 'aawp_logs_settings' );
		$enabled = isset( $check['enable'] ) && 'on' === $check['enable'];

		if ( $enabled ) {
			$log = new DB();
			$log->create_table();
		}
	}

	/**
	 * The logs submenu under AAWP Menu.
	 *
	 * @param string $menu_slug AAWP Menu Slug (aawp).
	 *
	 * @since 3.19
	 */
	public function add_logs_submenu( $menu_slug ) {

		add_submenu_page(
			$menu_slug,
			esc_html__( 'AAWP - Logs', 'aawp' ),
			esc_html__( 'Logs', 'aawp' ),
			'edit_pages',
			'aawp-logs',
			[ $this, 'pages' ]
		);
	}

	/**
	 * Render Logs page.
	 *
	 * @since 3.19
	 */
	public function pages() {

		$pages = apply_filters( 'aawp_activity_logs_pages', [ 'Logs', 'Settings' ] );
		$navs  = '';

		ob_start();
		?>
			<div class="wrap aawp-wrap">
				<h2>
					<?php esc_html_e( 'Activity Logs', 'aawp' ); ?>
				</h2>
			</div>
			<br/>
		<?php

			$heading = ob_get_clean();

		foreach ( $pages as $page ) {
			$navs .= '<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=aawp-logs&section=' . strtolower( $page ) ), 'aawp-' . strtolower( $page ) ) ) . '" 
                        class="nav-tab ' . ( isset( $_GET['section'] ) && strtolower( $page ) === $_GET['section'] || ( ! isset( $_GET['section'] ) && strtolower( $page ) === 'logs' ) ? 'nav-tab-active' : '' ) . '" 
                    >'
					. esc_html( $page ) .
				'</a>';
		}

			$template = '<h2 class="nav-tab-wrapper">' . $heading . '' . $navs . '</h2>';

			echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( isset( $_GET['section'] ) && 'logs' !== wp_unslash( $_GET['section'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			check_admin_referer( 'aawp-' . wp_unslash( $_GET['section'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( \class_exists( __NAMESPACE__ . '\\' . ucfirst( $_GET['section'] ) ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

				$class = __NAMESPACE__ . '\\' . ucfirst( $_GET['section'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$class = new $class();
				$class->render_page();

				return;
			}
		} else {

			$log = new ListTable( new DB() );
			$log->display_page();
		}
	}
}
