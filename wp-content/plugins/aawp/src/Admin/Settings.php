<?php

namespace AAWP\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $aawp_settings_page_slug;

/**
 * Settings Class.
 */
class Settings {

	private $current_tab          = ''; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	private $plugin_settings_tabs = []; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	/**
	 * Construct the plugin object
	 */
	public function __construct() {

		if ( ! \aawp_is_license_valid() ) {
			$this->current_tab = 'licensing';
		} elseif ( isset( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->current_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} elseif ( \aawp_is_user_admin() ) {
			$this->current_tab = 'api';
		} else {
			$this->current_tab = 'general';
		}

		// Init menu and settings.
		global $aawp_settings_page_slug;

		$aawp_settings_page_slug = 'aawp-settings';

		add_action( 'aawp_admin_menu', [ &$this, 'add_settings_menu' ], 40 );
		add_action( 'admin_init', [ &$this, 'settings_init' ] );
	}

	/**
	 * Add settings menu.
	 *
	 * @param string $menu_slug Main Menu Slug.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
	 */
	public function add_settings_menu( $menu_slug ) {

		$menu_cap = apply_filters( 'aawp_admin_menu_cap', 'edit_pages' );

		$menu_settings_title = ( aawp_is_license_valid() ) ? __( 'Settings', 'aawp' ) : '<span style="color: red;">' . __( 'Settings', 'aawp' ) . '</span>';

		add_submenu_page(
			$menu_slug,
			__( 'AAWP - Settings', 'aawp' ),
			$menu_settings_title,
			$menu_cap,
			'aawp-settings',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Settings init
	 *
	 * @access      Public
	 * @since       2.0.0
	 * @return      void
	 */
	public function settings_init() {

		// Build tabs.
		$tabs = [];
		$tabs = apply_filters( 'aawp_settings_tabs', $tabs );

		foreach ( $tabs as $key => $label ) {
			$this->plugin_settings_tabs[ $key ] = $label;
		}

		do_action( 'aawp_settings_register' );
	}

	/**
	 * Render Settings Page
	 *
	 * @access      public
	 * @since       2.0.0
	 * @return      void
	 */
	public function render_settings_page() {

		if ( ! aawp_is_user_editor() ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'aawp' ) );
		}

		$current_tab_url = add_query_arg( [ 'tab' => $this->current_tab ], admin_url( 'admin.php?page=aawp-settings' ) );

		?>
		<div class="wrap aawp-wrap">
			<h2>
				<?php esc_html_e( 'Settings', 'aawp' ); ?>
			</h2>

			<?php
			if ( isset( $_REQUEST['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					aawp_log( 'Settings', esc_html__( 'Settings Updated!', 'aawp' ) );
				?>
				<div id="message" class="updated">
					<p><strong><?php esc_html_e( 'Settings updated.', 'aawp' ); ?></strong></p>
				</div>
			<?php } ?>

			<?php $this->handle_renew_cache(); ?>
			<?php $this->handle_renew_images_cache(); ?>

			<?php do_action( 'aawp_settings_notices' ); ?>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2 aawp-clearfix">
					<div id="post-body-content" class="aawp-tabs-wrapper">
						<?php $this->plugin_options_tabs(); ?>
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="inside">
									<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
										<?php wp_nonce_field( 'aawp_update_options', '_wpnonce_aawp_update_options' ); ?>
										<?php settings_fields( 'aawp_' . $this->current_tab ); ?>
										<?php do_settings_sections( 'aawp_' . $this->current_tab ); ?>

										<?php if ( ! in_array( $this->current_tab, [ 'licensing', 'info' ], true ) && aawp_is_user_admin() ) { ?>
										<p class="submit">
											<?php submit_button( '', 'primary', 'aawp_update_options', false ); ?>&nbsp;
										</p>
									</form>

									<form method="post" action="<?php echo esc_url( $current_tab_url ); ?>" style="display: inline-block;">
											<?php wp_nonce_field( 'aawp_renew_cache', '_wpnonce_aawp_renew_cache' ); ?>
											<?php submit_button( __( 'Renew Cache', 'aawp' ), 'delete small', 'aawp_renew_cache', false ); ?>
									</form>

											<?php if ( aawp_is_product_local_images_activated() ) { ?>
										<form method="post" action="<?php echo esc_url( $current_tab_url ); ?>" style="display: inline-block;">
												<?php wp_nonce_field( 'aawp_renew_images_cache', '_wpnonce_aawp_renew_images_cache' ); ?>
												<?php submit_button( __( 'Renew Images Cache', 'aawp' ), 'delete small', 'aawp_renew_images_cache', false ); ?>
										</form>
									<?php } ?>

									<?php } else { ?>
									</form>
											<?php
									}//end if
									?>

									<h4><?php esc_html_e( 'Legend', 'aawp' ); ?></h4>
									<ul class="aawp-admin-legend">
										<li><span class="dashicons dashicons-info"></span> <?php esc_html_e( "I'm a tooltip! Hover me for more information", 'aawp' ); ?></li>
										<li><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'This value can be overwritten individually for each shortcode. Please take a look into the documentation.', 'aawp' ); ?></li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<?php do_action( 'aawp_settings_infoboxes' ); ?>
						</div>
					</div>

				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * plugin_options_page method.
	 */
	public function plugin_options_tabs() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		global $aawp_settings_page_slug;

		$settings_page_url = admin_url( 'admin.php?page=' . $aawp_settings_page_slug );

		ksort( $this->plugin_settings_tabs );

		echo '<ul id="aawp-settings-tabs" class="aawp-clearfix">';

		foreach ( $this->plugin_settings_tabs as $tab ) {
			if ( ! isset( $tab['key'] ) || ! isset( $tab['title'] ) ) {
				continue;
			}

			$tab_url = add_query_arg(
				[
					'tab' => $tab['key'],
				],
				$settings_page_url
			);

			?>
			<li class="aawp-settings-tabs__item
			<?php
			if ( $this->current_tab === $tab['key'] ) {
				echo ' aawp-settings-tabs__item--active';}
			?>
			">
				<a class="aawp-settings-tabs__link
				<?php
				if ( isset( $tab['alert'] ) ) {
					echo ' aawp-settings-tabs__link--' . sanitize_html_class( $tab['alert'] );
				}
				?>
				" href="<?php echo esc_url( $tab_url ); ?>" title="<?php echo esc_attr( $tab['title'] ); ?>">
					<?php
					if ( isset( $tab['icon'] ) ) {
						echo '<span class="dashicons dashicons-' . sanitize_html_class( $tab['icon'] ) . '"></span> ';}
					?>
					<?php echo esc_html( $tab['title'] ); ?>
				</a>
			</li>
			<?php
		}//end foreach

		echo '</ul>';
	}

	/**
	 * Renew cache action.
	 */
	public function handle_renew_cache() {

		if ( ! empty( $_POST['aawp_renew_cache'] ) && check_admin_referer( 'aawp_renew_cache', '_wpnonce_aawp_renew_cache' ) ) {

			aawp_delete_transients();
			aawp_renew_cache();
			?>
			<div id="message" class="updated">
				<p><strong><?php esc_html_e( 'Cache will be updated in the background. This might take some minutes.', 'aawp' ); ?></strong></p>
			</div>
			<?php
		}
	}

	/**
	 * Renew images cache action
	 */
	public function handle_renew_images_cache() {

		if ( ! empty( $_POST['aawp_renew_images_cache'] ) && check_admin_referer( 'aawp_renew_images_cache', '_wpnonce_aawp_renew_images_cache' ) ) {

			\aawp_delete_product_images_cache();
			?>
			<div id="message" class="updated">
				<p>
					<strong><?php esc_html_e( 'Successfully deleted cached images.', 'aawp' ); ?></strong><br />
					<?php esc_html_e( 'In case you are using a caching plugin, please make sure that you empty this cache too.', 'aawp' ); ?>
				</p>
			</div>
			<?php
		}
	}
}
