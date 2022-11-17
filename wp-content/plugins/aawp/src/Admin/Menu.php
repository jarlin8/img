<?php

namespace AAWP\Admin;

/**
 * Admin Menu Pages
 *
 * @package     AAWP\Admin
 * @since       2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu Class. AAWP Menu is initialized at this point.
 */
class Menu {

	/**
	 * Initialize the menu.
	 */
	public function init() {

		add_action( 'admin_menu', [ $this, 'menu_init' ] );
		add_action( 'in_admin_header', [ $this, 'header' ], PHP_INT_MAX );
	}

	/**
	 * Check if current page is an AAWP Page.
	 *
	 * @since 3.19.
	 *
	 * @return bool True if the page is AAWP page, false otherwise.
	 */
	public static function is_aawp_page() {

		global $current_screen;

		$page      = ! empty( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_type = ! empty( $current_screen->post_type ) ? $current_screen->post_type : '';

		$break_page = explode( '-', $page );

		return 'aawp_table' === $post_type || 'aawp' === $break_page[0];
	}

	/**
	 * Add AAWP Header to all AAWP Pages.
	 *
	 * @since 3.19.
	 */
	public function header() {

		$page = ! empty( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Return if it's not an AAWP Page.
		if ( ! self::is_aawp_page() ) {
			return;
		}

		?>
			<div id="aawp-header">
				<img src="<?php echo esc_url( AAWP_PLUGIN_URL . 'assets/img/aawp-banner.svg' ); ?> " >

				<div class="info">
					<p class="help"> <a href="<?php echo esc_url( aawp_get_page_url( 'need-help' ) ); ?> " rel="nofollow noopener noreferrer" target="_blank"> <?php echo esc_html__( 'Need Help?', 'aawp' ); ?> </a> </p>
					<p class="get-started"> <a href="<?php echo esc_url( aawp_get_page_url( 'get-started' ) ); ?>" rel="nofollow noopener noreferrer" target="_blank" class="aawp-get-started-btn"> <?php echo esc_html__( 'GET STARTED', 'aawp' ); ?> </a></p>
				</div>
			</div>
		<?php
	}

	/**
	 * Setup admin menu and submenu pages.
	 */
	public function menu_init() {

		$show_notification = apply_filters( 'aawp_admin_menu_show_notification', false );

		if ( ! aawp_is_license_valid() ) {
			$show_notification = true;
		}

		$notification_html = ( true === $show_notification ) ? ' <span class="update-plugins count-1"><span class="update-count">!</span></span>' : '';

		$menu_cap = apply_filters( 'aawp_admin_menu_cap', 'edit_pages' );

		add_menu_page(
			esc_html__( 'AAWP', 'aawp' ),
			esc_html__( 'AAWP', 'aawp' ) . $notification_html,
			$menu_cap,
			'aawp-welcome',
			'',
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" height="18" viewBox="0 0 22 18">
				<defs>
				<clipPath id="clip-aawp-icon">
					<rect width="22" height="18"/>
				</clipPath>
				</defs>
				<g id="aawp-icon" clip-path="url(#clip-aawp-icon)">
				<g id="product-development" transform="translate(-0.887 -6.2)">
					<path id="Pfad_1809" data-name="Pfad 1809" d="M7.173,18.52h14.8a1.231,1.231,0,0,0,1.173-1.242V7.442A1.2,1.2,0,0,0,21.973,6.2H7.173A1.2,1.2,0,0,0,6,7.442v9.884A1.218,1.218,0,0,0,7.173,18.52Zm7.4-.525a.526.526,0,1,1,.5-.525A.516.516,0,0,1,14.573,17.994ZM7.218,7.3H21.882v9.12H7.218Z" transform="translate(-2.671 0)" fill="#9da1a5"/>
					<path id="Pfad_1810" data-name="Pfad 1810" d="M9.7,9.5H23.264v1H9.7Z" transform="translate(-4.605 -1.724)" fill="#9da1a5"/>
					<path id="Pfad_1811" data-name="Pfad 1811" d="M22.817,37.245l-1.781-3.772a1.034,1.034,0,0,0-.914-.573H3.725a1.034,1.034,0,0,0-.914.573L.984,37.245c-.228.478-.046.86.457.86H22.36c.457,0,.639-.382.457-.86Zm-7.993-.191H9.571a.27.27,0,0,1-.274-.287l.046-.525a.347.347,0,0,1,.32-.287h4.979a.311.311,0,0,1,.32.287l.091.525a.236.236,0,0,1-.228.287Zm5.3-1.671H3.679a.173.173,0,0,1-.183-.239l.457-1.1a.406.406,0,0,1,.365-.239h15.21a.406.406,0,0,1,.365.239l.457,1.1a.251.251,0,0,1-.228.239Z" transform="translate(0 -14.731)" fill="#9da1a5"/>
					<path id="Pfad_1812" data-name="Pfad 1812" d="M17.457,17.34l.048-.43.478.382.955-.716L17.5,15H16.454a.86.86,0,1,1-1.719,0H13.685L12.3,16.576l.907.716.478-.382v2.722h3.247v-.1l-.143-1.48a.718.718,0,0,1,.191-.525A.729.729,0,0,1,17.457,17.34Z" transform="translate(-6.296 -4.844)" fill="#9da1a5"/>
					<path id="Pfad_1813" data-name="Pfad 1813" d="M29.3,15h4.3v.907H29.3Z" transform="translate(-15.571 -4.79)" fill="#9da1a5"/>
					<path id="Pfad_1814" data-name="Pfad 1814" d="M29.3,18.9h4.3v.907H29.3Z" transform="translate(-15.571 -6.913)" fill="#9da1a5"/>
					<path id="Pfad_1815" data-name="Pfad 1815" d="M29.3,22.9h4.3v.907H29.3Z" transform="translate(-15.571 -9.091)" fill="#9da1a5"/>
					<path id="Pfad_1816" data-name="Pfad 1816" d="M24.085,21.421l.334-.287a.1.1,0,0,0-.048-.191l-1.48-.143a.205.205,0,0,0-.191.191l.143,1.48a.1.1,0,0,0,.191.048l.287-.334.812.812a.213.213,0,0,0,.239,0l.573-.573a.213.213,0,0,0,0-.239Z" transform="translate(-11.9 -7.974)" fill="#9da1a5"/>
				</g>
				</g>
			</svg>' ),
			apply_filters( 'aawp_menu_position', 30 )
		);

		do_action( 'aawp_admin_menu', 'aawp-welcome' );
	}
}
