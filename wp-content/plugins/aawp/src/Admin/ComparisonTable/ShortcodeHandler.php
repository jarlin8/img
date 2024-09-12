<?php

namespace AAWP\Admin\ComparisonTable;

/**
 * Comparison Table Shortcode Handler.
 * A class responsible for handling the comparison table functionality like displaying tables in frontend.
 *
 * @since 3.19
 */
class ShortcodeHandler extends \AAWP_Functions {

	public $func_id, $func_attr;

	public function __construct() {

		parent::__construct();

		$this->func_id       = 'table';
		$this->func_listener = 'table';
		$this->func_attr     = $this->setup_func_attr( $this->func_id );

		// Hooks
		add_action( 'aawp_shortcode_handler', [ &$this, 'shortcode' ], 10, 2 );
	}

	function shortcode( $atts, $content ) {

		if ( empty( $atts[ $this->func_listener ] ) ) {
			return false;
		}

		$this->display( $atts[ $this->func_listener ], $content, $atts );
	}

	function display( $table_id, $content, $atts = [] ) {

		if ( ! is_numeric( $table_id ) || 'aawp_table' != get_post_type( $table_id ) ) {
			_e( 'Invalid table id.', 'aawp' );
			return;
		}

		if ( 'publish' !== get_post_status( $table_id ) ) {
			return;
			// Don't execute when table was not published.
		}

		$table_rows           = aawp_get_table_rows( $table_id );
		$table_products       = aawp_get_table_products( $table_id );
		$table_customizations = aawp_get_table_customizations( $table_id );
		$table_timestamp      = 0;

		if ( ( ! is_array( $table_rows ) || sizeof( $table_rows ) == 0 ) || ( ! is_array( $table_products ) || sizeof( $table_products ) == 0 ) ) {
			_e( 'Table setup not completed.', 'aawp' );
			return;
		}

		// Merge with settings customizations
		$table_customizations = aawp_merge_table_settings_customizations( $table_customizations );

		// Loop rows
		$row_labels_exist = false;

		foreach ( $table_rows as $table_row_id => $table_row ) {

			if ( ! empty( $table_row['label'] ) ) {
				$row_labels_exist = true;
				break;
			}
		}

		if ( ! $row_labels_exist && ! in_array( 'hide-labels', $table_customizations ) ) {
			$table_customizations[] = 'hide-labels';
		}

		// Preload products ( Use cache or fetch items from API )
		// aawp_debug( $table_products, '$table_products' );

		$table_product_asins = [];

		foreach ( $table_products as $table_product_id => $table_product ) {

			// Check if ASIN is set
			if ( empty( $table_product['asin'] ) ) {
				unset( $table_products[ $table_product_id ] );
			}

			// Check if products are hidden
			if ( empty( $table_product['status'] ) ) {
				unset( $table_products[ $table_product_id ] );
			}

			$table_product_asins[ $table_product_id ] = $table_product['asin'];
		}

		// Use cache or fetch items from API
		$items = $this->get_items( $table_product_asins, $this->func_id );

		// aawp_debug( $items, '$items' );

		$table_items = [];

		if ( is_array( $items ) && sizeof( $items ) > 0 ) {

			foreach ( $items as $item ) {

				if ( empty( $item['asin'] ) ) {
					continue;
				}

				$original_table_product_id = array_search( $item['asin'], $table_product_asins );

				$table_items[ $original_table_product_id ] = $item;

				if ( empty( $table_timestamp ) && ! empty( $item['date_updated'] ) ) {
					$table_timestamp = strtotime( $item['date_updated'] );
				}
			}
		}

		$table_products_missing = array_diff_key( $table_products, $table_items );
		// aawp_debug( $table_products_missing, '$table_products_missing' );

		$table_products_final = array_diff_key( $table_products, $table_products_missing );
		// aawp_debug( $table_products_final, '$table_products_final' );

		$table_products = $table_products_final;

		// Still enough products to show the table?
		if ( sizeof( $table_products ) == 0 ) {
			_e( 'Table could not be displayed.', 'aawp' );
			return;
		}

		// aawp_debug( $table_products,'$table_products AFTER' );

		// Prepare rendering
		global $aawp_table;
		global $aawp_tables;

		$aawp_table = [
			'id'             => $table_id,
			'rows'           => $table_rows,
			'products'       => $table_products,
			'items'          => $table_items,
			'customizations' => $table_customizations,
			'atts'           => $atts,
		];

		$aawp_tables[] = $aawp_table;

		// Setup vars
		$this->setup_shortcode_vars( $this->intersect_atts( $atts, $this->func_attr ), $content );

		// Setup template handler and render output
		$template_handler = new \AAWP_Template_Handler();
		$template_handler->set_atts( $this->atts );
		$template_handler->set_type( $this->func_id );
		// $template_handler->set_template_variables( $template_variables );
		$template_handler->set_timestamp_template( $table_timestamp );
		$template_handler->render_template( 'table-builder' );

		// aawp_debug( $aawp_table, '$aawp_table' );
		// aawp_debug( $table_rows, '$table_rows' );
		// aawp_debug( $table_products, '$table_products' );
		// aawp_debug( $table_customizations, '$table_customizations' );
	}
}
