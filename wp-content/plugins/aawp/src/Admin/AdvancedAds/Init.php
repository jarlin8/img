<?php

namespace AAWP\Admin\AdvancedAds;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Core class for AdvancedAds.
 *
 * @since 3.19
 */
class Init {

	/**
	 * Initialize.
	 */
	public function init() {

		// Make sure Advanced Ads class is available.
		if ( ! class_exists( 'Advanced_Ads' ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );
		add_filter( 'advanced-ads-ad-types', [ $this, 'ad_type_aawp' ] );
	}

	/**
	 * Load admin scripts on AdAds admin pages.
	 *
	 * @since 3.19.
	 */
	public function load_assets() {

		if ( \Advanced_Ads_Admin::screen_belongs_to_advanced_ads() ) {
			\aawp_admin_scripts();

			// Load frontend style for preview functionality.
			wp_enqueue_style(
				'aawp-ad-ads-style',
				plugins_url( 'assets/dist/css/main.css', AAWP_PLUGIN_FILE ),
				[],
				AAWP_VERSION
			);

			$this->add_inline_script_for_product_search();
		}
	}

	/**
	 * Inline script for product search Modal.
	 *
	 * @since 3.19.
	 */
	public function add_inline_script_for_product_search() {

		// Load modal box for product(s) search.
		add_action(
			'admin_footer',
			function() {

				ob_start();

				\aawp_admin_the_table_product_search_modal();

				?>
				<input type="hidden" id="aawp-ajax-search-items-selected" value="" />
				<?php

				echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		);
	}

	/**
	 * Add an AAWP Ad type.
	 *
	 * @param array $types An array of existing types.
	 *
	 * @since 3.19.
	 *
	 * @return array An array of ad types including AAWP.
	 */
	public function ad_type_aawp( $types ) {
		$types['aawp'] = new Ad();

		return $types;
	}
}
