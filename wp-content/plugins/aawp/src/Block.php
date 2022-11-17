<?php

namespace AAWP;

/**
 * The Block Class.
 */
class Block {

	/**
	 * Initialize
	 *
	 * @since 3.18
	 */
	public function init() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'print_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_assets' ] );
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
	 *
	 * @since 3.18
	 */
	public function register_block() {

		$string  = [ 'type' => 'string' ];
		$bool    = [ 'type' => 'boolean' ];
		$integer = [ 'type' => 'integer' ];

		$this->register_assets();

		register_block_type(
			'aawp/aawp-block',
			[
				'attributes'      => [
					'look'                 => $string,
					'asin'                 => $string,
					'keywords'             => $string,

					/** Lists (multiple boxes) fields start */
					'items'                => $integer,
					'order'                => $string,
					'orderby'              => $string,
					'order_items'          => $integer,
					'filterby'             => $string,
					'filter'               => $string,
					'filter_items'         => $integer,
					'filter_type'          => $string,
					'filter_compare'       => $string,
					'ribbon'               => $bool,
					'ribbon_text'          => $string,
					/** Lists (multiple boxes) fields start */

					/** Title and links fields start */
					'title'                => $string,
					'title_length'         => $string,
					'link_title'           => $string,
					'link_overwrite'       => $string,
					'link_type'            => $string,
					'link_icon'            => $string,
					'link_class'           => $string,
					/** Title and links fields end */

					/** Descriptions fields start */
					'description'          => $string,
					'description_items'    => $integer,
					'description_length'   => $string,
					/** Descriptions fields end */

					/** Images fields start */
					'image'                => $string,
					'image_size'           => $string,
					'image_alt'            => $string,
					'image_title'          => $string,
					'image_align'          => $string,
					'image_width'          => $string,
					'image_height'         => $string,
					'image_class'          => $string,
					/** Images fields end */

					/** Buttons fields start */
					'button'               => $bool,
					'button_text'          => $string,
					'button_detail'        => $string,
					'button_detail_text'   => $string,
					'button_detail_title'  => $string,
					'button_detail_target' => $string,
					'button_detail_rel'    => $string,
					/** Buttons fields end */

					/** Pricing fields start */
					'price'                => $string,
					'sale_ribbon_text'     => $string,
					/** Pricing fields start */

					/** Star ratings fields start */
					'rating'               => $string,
					'star_rating'          => $bool,
					'reviews'              => $bool,
					/** Star ratings fields end */

					/** Templates & Styles fields start */
					'template'             => $string,
					'grid'                 => $string,
					'numbering'            => $bool,
					'class_attr'           => $string,
					/** Templates & Styles fields end */

					/** Other fields start */
					'tracking_id'          => $string,
					/** Other fields end */

					// For fields value.
					'value_attr'           => $string,
					'apply_link'           => $bool,

					// For comparison table.
					'table'                => $string,
				],
				'render_callback' => [ $this, 'block_content' ],
				'style' => 'aawp-editor-style',
				'editor_style' => 'aawp-aawp-block-editor-style',
				'editor_script' => 'aawp-aawp-block-editor-script'
			]
		);
	}

	/**
	 * Register assets required in the block and front-end. Enqueue later wherever required.
	 *
	 * @since 3.18.3
	 */
	public function register_assets() {

		if( ! is_admin() ) {
			return;
		}

		wp_register_style(
			'aawp-aawp-block-editor-style',
			plugins_url( 'assets/block/dist/index.css', AAWP_PLUGIN_FILE ),
			[],
			AAWP_VERSION,
			false
		);

		wp_register_style(
			'aawp-editor-style',
			plugins_url( 'assets/dist/css/main.css', AAWP_PLUGIN_FILE ),
			[ 'wp-edit-blocks', 'aawp-aawp-block-editor-style' ],
			AAWP_VERSION
		);
	}

	/**
	 * Print script to be available on admin side.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function print_scripts() {

		$tables  = \aawp_get_comparison_tables();
		$options = \aawp_get_options();

		$aawp_data = [
			'icons'   => [
				'logo'       => plugins_url( 'assets/img/awp-logo.svg', AAWP_PLUGIN_FILE ),
				'box'        => plugins_url( 'assets/img/Product_Boxes.svg', AAWP_PLUGIN_FILE ),
				'fields'     => plugins_url( 'assets/img/Data_Fields.svg', AAWP_PLUGIN_FILE ),
				'new'        => plugins_url( 'assets/img/New_Releases.svg', AAWP_PLUGIN_FILE ),
				'link'       => plugins_url( 'assets/img/Text_Links.svg', AAWP_PLUGIN_FILE ),
				'bestseller' => plugins_url( 'assets/img/Bestsellers.svg', AAWP_PLUGIN_FILE ),
				'table'      => plugins_url( 'assets/img/Comparison_Tables.svg', AAWP_PLUGIN_FILE ),
			],
			'tables'  => $tables,
			'options' => $options,
		];

		wp_add_inline_script(
			'aawp-aawp-block-editor-script',
			'var aawp_data = ' . wp_json_encode(
				$aawp_data
			),
			'before'
		);
	}

	/**
	 * Script for product search Modal.
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
	 * Enqueue assets in the block editor.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function block_assets() {

		$this->add_inline_script_for_product_search();

		wp_enqueue_script(
			'aawp-aawp-block-editor-script',
			plugins_url( 'assets/block/dist/index.js', AAWP_PLUGIN_FILE ),
			[ 'wp-i18n' ],
			AAWP_VERSION,
			true
		);

		wp_enqueue_style( 'aawp-aawp-block-editor-style' );

		/**
		 * Set script translations.
		 *
		 * @see https://developer.wordpress.org/block-editor/how-to-guides/internationalization/
		 */
		wp_set_script_translations( 'aawp-aawp-block-editor-script', 'aawp', AAWP_PLUGIN_DIR . 'languages' );
	}

	/**
	 * Renders the block content.
	 *
	 * @param  array $atts Attributes from the block.
	 *
	 * @since 3.18
	 */
	public function block_content( $atts ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$look     = ! empty( $atts['look'] ) ? $atts['look'] : '';
		$asin     = ! empty( $atts['asin'] ) ? $atts['asin'] : '';
		$keywords = ! empty( $atts['keywords'] ) ? $atts['keywords'] : '';

		if ( ! empty( $atts['look'] ) && ( ! empty( $asin ) || ! empty( $keywords ) ) ) {

			$allowed_atts = $this->allowed_atts( $look );
			$filter_atts  = $atts;
			$bool_atts    = [ 'ribbon', 'button', 'star_rating', 'reviews', 'numbering', 'apply_link' ];

			foreach ( $atts as $key => $value ) {

				// We changed to "class_attr" and "value_attr" because "class" and "value" were reserved keyword in JS.
				if ( 'class_attr' === $key || 'value_attr' === $key ) {

					$filter_atts[ strtok( $key, '_' ) ] = $value;

					unset( $filter_atts[ $key ] );
				}

				if ( ! in_array( $key, $allowed_atts, true ) || ( empty( $value ) && ! in_array( $key, $bool_atts, true ) ) ) {
					unset( $filter_atts[ $key ] );
					// Remove the atts that aren't allowed for the selected look and are empty.
				}

				// Apply link in fields value.
				if ( 'apply_link' === $key && ! empty( $value ) ) {
					$filter_atts['format'] = 'linked';
				}

				// The bool atts should have 'none' value if empty.
				if ( in_array( $key, $bool_atts, true ) && empty( $value ) ) {
					$filter_atts[ $key ] = 'none';

					// Numbering is treated differently.
					if ( 'numbering' === $key ) {
						$filter_atts[ $key ] = false;
					}
				}
			}//end foreach

			$params = [
				$look => in_array( $look, [ 'bestseller', 'new' ], true ) ? $keywords : $asin,
			];

			$params = array_merge( $params, $filter_atts );
		} elseif ( ! empty( $atts['look'] ) && 'table' === $atts['look'] && ! empty( $atts['table'] ) ) {
			$params = [
				'table' => absint( $atts['table'] ),
			];

			if ( isset( $atts['tracking_id'] ) ) {
				$params['tracking_id'] = $atts['tracking_id'];
			}
		}//end if

		$core = new \AAWP_Core();

		return $core->render_shortcode( isset( $params ) ? $params : '' );
	}

	/**
	 * Return allowed attributes by look.
	 *
	 * @param  string $look box, best, new, fields, link.
	 *
	 * @since 3.18
	 *
	 * @return array An array of allowed attributes.
	 */
	public function allowed_atts( $look ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		switch ( $look ) {
			case 'box':
				return [ 'order', 'orderby', 'order_items', 'filterby', 'filter', 'filter_items', 'filter_type', 'filter_compare', 'title', 'title_length', 'link_title', 'link_overwrite', 'link_type', 'description', 'description_items', 'description_length', 'image', 'image_size', 'image_alt', 'image_title', 'button', 'button_text', 'button_detail', 'button_detail_text', 'button_detail_title', 'button_detail_target', 'button_detail_rel', 'price', 'rating', 'star_rating', 'reviews', 'template', 'grid', 'numbering', 'class', 'tracking_id' ];

			case 'bestseller':
			case 'new':
				return [ 'items', 'order', 'orderby', 'order_items', 'filterby', 'filter', 'filter_items', 'filter_type', 'filter_compare', 'ribbon', 'ribbon_text', 'title_length', 'link_type', 'description_items', 'description_length', 'image_size', 'image_alt', 'button', 'button_text', 'button_detail', 'button_detail_text', 'button_detail_title', 'button_detail_target', 'button_detail_rel', 'price', 'sale_ribbon_text', 'star_rating', 'reviews', 'template', 'grid', 'numbering', 'class', 'tracking_id' ];

			case 'fields':
				return [ 'title', 'title_length', 'link_title', 'link_overwrite', 'link_type', 'description', 'description_items', 'description_length', 'image', 'image_size', 'image_alt', 'image_title', 'image_align', 'image_width', 'image_height', 'image_class', 'button_text', 'button_detail', 'button_detail_text', 'button_detail_title', 'button_detail_target', 'button_detail_rel', 'price', 'rating', 'template', 'tracking_id', 'value', 'format' ];

			case 'link':
				return [ 'title', 'title_length', 'link_title', 'link_overwrite', 'link_type', 'link_icon', 'link_class', 'template', 'tracking_id' ];

			default:
				return [];
		}//end switch
	}
}
