<?php

namespace AAWP\Admin;

/**
 * The Flyout Class.
 *
 * @since 3.19
 */
class Flyout {

	/**
	 * Flyout Init.
	 */
	public function init() {

		// Global $current_screen isn't available yet to detect if it's a comparison table page.
		add_action(
			'current_screen',
			function() {

				if ( ! Menu::is_aawp_page() || ! apply_filters( 'aawp_admin_flyout', true ) ) {
					return;
				}

				add_action( 'admin_footer', [ $this, 'output' ] );
			}
		);
	}

	/**
	 * Output the flyout menu.
	 *
	 * @since 3.19
	 */
	public function output() {

		printf(
			'<div id="aawp-flyout">
				<div id="aawp-flyout-items">
					%1$s
				</div>
				<a href="#" class="aawp-flyout-button aawp-flyout-head">
					<div class="aawp-flyout-label">%2$s</div>
					<div class="aawp-flyout-img-container">
						<img src="%3$s" alt="%2$s" data-active="%4$s" />
					</div>
				</a>
			</div>',
			$this->get_items_html(), // phpcs:ignore
			\esc_attr__( 'See Quick Links', 'aawp' ),
			\esc_url( plugins_url( 'assets/img/flyout-normal.svg', AAWP_PLUGIN_FILE ) ),
			\esc_url( plugins_url( 'assets/img/flyout-open.svg', AAWP_PLUGIN_FILE ) )
		);
	}

	/**
	 * Generate menu items HTML.
	 *
	 * @since 3.19
	 *
	 * @return string Menu items HTML.
	 */
	public function get_items_html() {

		$items      = array_reverse( $this->menu_items() );
		$items_html = '';

		foreach ( $items as $item_key => $item ) {

			$items_html .= sprintf(
				'<a href="%1$s" target="_blank" rel="noopener noreferrer" class="aawp-flyout-button aawp-flyout-item aawp-flyout-item-%2$d"%5$s%6$s>
					<div class="aawp-flyout-label">%3$s</div>
					<i class="dashicons %4$s"></i>
				</a>',
				\esc_url( $item['url'] ),
				(int) $item_key,
				\esc_html( $item['title'] ),
				\sanitize_html_class( $item['icon'] ),
				! empty( $item['bgcolor'] ) ? ' style="background-color: ' . \esc_attr( $item['bgcolor'] ) . '"' : '',
				! empty( $item['hover_bgcolor'] ) ? ' onMouseOver="this.style.backgroundColor=\'' . \esc_attr( $item['hover_bgcolor'] ) . '\'" onMouseOut="this.style.backgroundColor=\'' . \esc_attr( $item['bgcolor'] ) . '\'"' : ''
			);
		}//end foreach

		return $items_html;
	}

	/**
	 * Menu items data.
	 *
	 * @since 3.19
	 */
	public function menu_items() {

		$items = apply_filters(
			'aawp_flyout_menu_items',
			[
				[
					'title' => \esc_html__( 'Beginners Guide', 'aawp' ),
					'url'   => esc_url( aawp_get_page_url( 'get-started' ) ),
					'icon'  => 'dashicons-book',
				],
				[
					'title' => \esc_html__( 'Documentation', 'aawp' ),
					'url'   => esc_url( aawp_get_page_url( 'need-help' ) ),
					'icon'  => 'dashicons-media-document',
				],
				[
					'title' => \esc_html__( 'Contact Support', 'aawp' ),
					'url'   => esc_url( aawp_get_page_url( 'flyout-contact' ) ),
					'icon'  => 'dashicons-superhero',
				],
			]
		);

		return $items;
	}
}
