<?php

namespace AAWP\ShortenLinks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Settings For ShortenLinks
 *
 * @since 3.18
 */
class Settings {

	/**
	 * Constructor.
	 *
	 * @since 3.18
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 3.18
	 */
	public function init() {

		// Settings.
		add_filter( 'aawp_settings_affiliate_link_types', [ $this, 'link_types' ] );
		add_action( 'aawp_settings_general_affiliate_links', [ $this, 'add_fields' ] );
	}

	/**
	 * Add shortened link types in General Settings.
	 *
	 * @param array $types An array of affiliate link types.
	 *
	 * @since 3.18
	 *
	 * @return array An array of affiliate link types including Shortened Links.
	 */
	public function link_types( $types ) {
		$types['shortened'] = esc_html__( 'Shortened (Amzn.to)', 'aawp' );

		return $types;
	}

	/**
	 * Add fields in General Settings page such as Access Token For Bitly.
	 *
	 * @since 3.18.
	 */
	public function add_fields() {

		$access_token = \aawp_get_option( 'bitly_access_token', 'general' );
		$link_type    = \aawp_get_option( 'affiliate_links', 'general' );

		?>
			<div id ="aawp-settings-shorten-links-options" style="display:none">
				<?php

				echo '<br/>' . wp_kses(
					__( '<b>Attention:</b> <i>With shortened links, the geotargeting function can currently not be used.</i>', 'aawp' ),
					[
						'br' => [],
						'b'  => [],
						'i'  => [],
					]
				);

				?>

				<h4><?php echo esc_html__( 'Bitly Access Token', 'aawp' ); ?></h4>

				<input type="text" value="<?php echo esc_attr( $access_token ); ?>" id="aawp_bitly_access_token" name="aawp_general[bitly_access_token]" value="1">
				<p>
					<?php
						printf(
							wp_kses(  /* translators: %1$s - URL to the documentation. */
								__( 'More information about the Bitly Access Token can be found in our <a href="%s" target="_blank">documentation</a>.', 'aawp' ),
								[
									'a' => [
										'href'   => [],
										'target' => '_blank',
									],
								]
							),
							esc_url( aawp_get_page_url( 'docs:shortened_links' ) )
						);
					?>
				</p>

				<?php
					$response = get_option( 'aawp_bitly_link_creation_failed_msg' );

				if ( empty( $access_token ) && 'shortened' === $link_type ) {
					?>
						<blockquote class="notice notice-error amzn-link-shortener-notice">
							<p><strong><?php esc_html_e( 'Heads up! Bitly Access Token is required for the Shortened Affiliate links to work.', 'aawp' ); ?></strong></p>
						</blockquote>
					<?php
				} elseif ( ! empty( $response['message'] ) ) {
					?>
						<blockquote class="notice notice-error amzn-link-shortener-notice">
							<p><strong><?php esc_html_e( 'Heads up! Bitly failed to create short links last time with the response below:', 'aawp' ); ?></strong></p>
							<p><?php echo esc_html( $response['message'] ); ?>
							<?php echo ! empty( $response['description'] ) ? ': ' . esc_html( $response['description'] ) : ''; ?>
							</p>
						</blockquote>
					<?php
				}
				?>
			</div>
		<?php
	}
}
