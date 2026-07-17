<?php

namespace AAWP\Admin;

/** Logs is outside of the current namespace. So, import them.*/
use AAWP\ActivityLogs\ListTable as LogsTable;

/**
 * Tools of the plugin.
 *
 * @since 3.20.0
 */
class Tools {

	/**
	 * Initialize tools class.
	 *
	 * @since 3.20.0
	 */
	public function init() {
		add_action( 'aawp_admin_menu', [ $this, 'add_tools_submenu' ], 60 );
		add_action( 'admin_init', [ $this, 'load_as' ], 20 );
	}

	/**
	 * Additionally required because AS logger, store etc. should be loaded in "admin_init".
	 *
	 * @since 3.20.0
	 */
	public function load_as() {

		if ( empty( $_GET['tab'] ) || empty( $_GET['page'] ) || 'aawp-tools' !== $_GET['page'] || 'scheduled-actions' !== $_GET['tab'] ) {
			return;
		}

		new Tools\ScheduledActions();
	}

	/**
	 * Add Tools Submenu in the AAWP Menu.
	 *
	 * @param string $menu_slug Menu slug: "aawp".
	 *
	 * @since 3.20.0
	 */
	public function add_tools_submenu( $menu_slug ) {
		add_submenu_page(
			$menu_slug,
			esc_html__( 'AAWP - Tools', 'aawp' ),
			esc_html__( 'Tools', 'aawp' ),
			'edit_pages',
			'aawp-tools',
			[ $this, 'render_tools_page' ]
		);
	}

	/**
	 * Render tools page. Add logs, support, etc. pages.
	 *
	 * @since 3.20.0
	 */
	public function render_tools_page() { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$pages = apply_filters( 'aawp_tools_pages',
			[
				'support' => 'Support',
				'logs' => 'Logs',
				'scheduled-actions' => 'Scheduled Actions'
			]
		);

		ob_start();
		?>
			<div class="wrap aawp-wrap">
				<h2>
					<?php esc_html_e( 'Tools', 'aawp' ); ?>
				</h2>
				<nav class="nav-tab-wrapper">
					<?php
					foreach ( $pages as $key => $page ) {
						echo '<a href="' . esc_url( admin_url( 'admin.php?page=aawp-tools&tab=' . $key ) ) . ( 'scheduled-actions' === $key ? '&s=aawp' : '' ) . '"
										class="nav-tab ' . ( isset( $_GET['tab'] ) && $key === $_GET['tab'] || ( ! isset( $_GET['tab'] ) && 'support' === $key ) ? 'nav-tab-active' : '' ) . '"
									>'
								. esc_html( $page ) .
							'</a>';
					}
					?>
				</nav>
			</div>
		<?php

		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// The default page is Support.
		if ( isset( $_GET['tab'] ) && 'support' !== wp_unslash( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			switch ( wp_unslash( $_GET['tab'] ) ) { //phpcs:ignore
				case 'logs':	// Because logs is outside of the current namespace, other tabs should be within the "Tools" namespace.

						( new \AAWP\ActivityLogs\Settings() )->render_page();
						( new LogsTable( new \AAWP\ActivityLogs\DB() ) )->render_page();

						return;
					break;

				case 'scheduled-actions':
					( new Tools\ScheduledActions() )->render_page();
					return;
				break;
			}

		} else {

			// The default page is Support.
			( new \AAWP\Admin\Tools\Support() )->render_support_page();
		}//end if
	}
}
