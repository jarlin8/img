<?php

namespace AAWP\Admin\ProductsTable;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Core class for Products.
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

		if ( ! \aawp_is_license_valid() ) {
			return;
		}

		add_action( 'aawp_admin_menu', [ $this, 'add_products_submenu' ], 20 );
	}

	/**
	 * The products submenu under AAWP Menu.
	 *
	 * @param string $menu_slug AAWP Menu Slug (aawp).
	 *
	 * @since 3.19
	 */
	public function add_products_submenu( $menu_slug ) {

		add_submenu_page(
			$menu_slug,
			esc_html__( 'AAWP - Products', 'aawp' ),
			esc_html__( 'Products', 'aawp' ),
			'edit_pages',
			'aawp-products',
			[ $this, 'pages' ]
		);
	}

	/**
	 * Render Products.
	 *
	 * @since 3.19
	 */
	public function pages() {

		ob_start();
		?>
			<div class="wrap aawp-wrap">
				<h2>
					<?php esc_html_e( 'Products', 'aawp' ); ?>
				</h2>
			</div>
			<br/>
		<?php

		$heading = ob_get_clean();

		echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$products = new ListTable();
		$products->display_page();
	}
}
