<?php

namespace AAWP\Admin\ComparisonTable;

/**
 * Admin Menu Pages
 *
 * @package     AAWP\Admin
 * @since       2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AAWP_TABLE_ROWS_MAX', 20 );

// higher than 20 lead to missing products -.-
define( 'AAWP_TABLE_PRODUCTS_MAX', 6 );

global $aawp_menu_slug;

class Table {

	/**
	 * Initialize table builder.
	 *
	 * @return void.
	 */
	public function init() {

		add_action( 'init', [ $this, 'register_table' ] );
		add_action( 'aawp_admin_menu', [ $this, 'add_submenu' ], 30 );
		add_filter( 'parent_file', [ $this, 'parent_file' ] );
		add_filter( 'manage_aawp_table_posts_columns', [ $this, 'add_shortcode_column' ] );
		add_action( 'manage_aawp_table_posts_custom_column', [ $this, 'add_shortcode_column_content' ], 10, 2 );
		add_action( 'admin_head', [ $this, 'remove_elements' ] );

		if ( is_admin() ) {
			add_action( 'load-post.php', [ $this, 'aawp_admin_table_setup_meta_boxes' ] );
			add_action( 'load-post-new.php', [ $this, 'aawp_admin_table_setup_meta_boxes' ] );
		}
	}

	/**
	 * Register "aawp_table" custom post type.
	 *
	 * @return void.
	 */
	public function register_table() {
		$labels = [
			'name'                  => _x( 'Tables', 'Post Type General Name', 'aawp' ),
			'singular_name'         => _x( 'Table', 'Post Type Singular Name', 'aawp' ),
			'menu_name'             => __( 'Tables', 'aawp' ),
			'name_admin_bar'        => __( 'Table', 'aawp' ),
			'archives'              => __( 'Table Archives', 'aawp' ),
			'attributes'            => __( 'Table Attributes', 'aawp' ),
			'parent_item_colon'     => __( 'Parent Table:', 'aawp' ),
			'all_items'             => __( 'All Tables', 'aawp' ),
			'add_new_item'          => __( 'Add New Table', 'aawp' ),
			'add_new'               => __( 'Add New', 'aawp' ),
			'new_item'              => __( 'New Table', 'aawp' ),
			'edit_item'             => __( 'Edit Table', 'aawp' ),
			'update_item'           => __( 'Update Table', 'aawp' ),
			'view_item'             => __( 'View Table', 'aawp' ),
			'view_items'            => __( 'View Tables', 'aawp' ),
			'search_items'          => __( 'Search Table', 'aawp' ),
			'not_found'             => __( 'Not found', 'aawp' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'aawp' ),
			'featured_image'        => __( 'Featured Image', 'aawp' ),
			'set_featured_image'    => __( 'Set featured image', 'aawp' ),
			'remove_featured_image' => __( 'Remove featured image', 'aawp' ),
			'use_featured_image'    => __( 'Use as featured image', 'aawp' ),
			'insert_into_item'      => __( 'Insert into table', 'aawp' ),
			'uploaded_to_this_item' => __( 'Uploaded to this table', 'aawp' ),
		];
		$args   = [
			'label'               => __( 'Table', 'aawp' ),
			'description'         => __( 'Table Post Type', 'aawp' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 25,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
			/*
			'capabilities' => array(
				'create_posts' => 'do_not_allow',
			),
			*/
			'show_in_rest'        => false,
		];
		register_post_type( 'aawp_table', $args );
	}

	/**
	 * Add a "Tables" submenu page under AAWP menu.
	 *
	 * @return void.
	 */
	public function add_submenu( $aawp_menu_slug ) {

		if ( ! aawp_is_license_valid() ) {
					return;
		}

		add_submenu_page(
			$aawp_menu_slug,
			__( 'AAWP - Tables', 'aawp' ),
			__( 'Tables', 'aawp' ),
			'edit_pages',
			'edit.php?post_type=aawp_table'
		);
	}

	/**
	 * Correct active submenu item
	 *
	 * Source: http://stackoverflow.com/a/23002306
	 */
	public function parent_file( $parent_file ) {

		global $submenu_file, $current_screen;

		if ( $current_screen->post_type == 'aawp_table' ) {
			$submenu_file = 'edit.php?post_type=aawp_table';
			$parent_file  = 'aawp-settings';
		}

		return $parent_file;
	}

	/**
	 * Add shortcode column to the aawp_tables CPT.
	 *
	 * @param array $defaults The default columns
	 *
	 * @return array An array of columns including the Shortcode.
	 */
	public function add_shortcode_column( $defaults ) {

		$defaults['aawp_table_shortcode'] = __( 'Shortcode', 'aawp' );

		return $defaults;
	}

	/**
	 * Add contents the shortcodes column.
	 *
	 * @param string $column_name Column Name.
	 * @param string $post_id     Post ID
	 */
	public function add_shortcode_column_content( $column_name, $post_id ) {

		if ( 'aawp_table_shortcode' !== $column_name ) {
			return;
		}

		$shortcode = aawp_get_shortcode();
		?>
			<span class="shortcode aawp-shortcode-field">
				<input type='text' onClick="this.select();" value='[<?php echo $shortcode; ?> table="<?php echo $post_id; ?>"]'readonly='readonly' />
				<button class="button aawp-copy-shortcode-button" type="button" href="#" >
					<span class="dashicons dashicons-clipboard aawp-copy-shortcode"></span>
					<span class="aawp-tooltiptext"><?php echo esc_html__( 'Copy Shortcode', 'aawp' ); ?></span>
				</button>
			</span>
		<?php
	}

	/**
	 * Remove admin elements in "aawp_table" CPT.
	 *
	 * @return void.
	 */
	public function remove_elements() {

		global $pagenow, $typenow;

		if ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
			$post    = get_post( $_GET['post'] );
			$typenow = $post->post_type;
		}

		if ( is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) && $typenow == 'aawp_table' ) {
			?>
				<style type="text/css">
					#postdivrich {
						display: none;
					}
				</style>
			<?php
		}
	}

	/**
	 * Setup metaboxes.
	 *
	 * @return void.
	 */
	public function aawp_admin_table_setup_meta_boxes() {

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', [ $this, 'aawp_admin_table_add_meta_boxes' ] );

		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', 'aawp_admin_table_save_meta', 10, 2 );
	}

	/**
	 * Add metaboxes.
	 *
	 * @return void.
	 */
	public function aawp_admin_table_add_meta_boxes() {

		add_meta_box(
			'aawp-table-config-metabox',
			'<span class="dashicons dashicons-admin-plugins"></span> ' . __( 'Configuration', 'aawp' ),
			[ $this, 'aawp_admin_table_config_meta_box_render' ],
			'aawp_table',
			'normal',
			'high'
		);

		add_meta_box(
			'aawp-table-products-metabox',
			'<span class="dashicons dashicons-cart"></span> ' . __( 'Products', 'aawp' ),
			[ $this, 'aawp_admin_table_products_meta_box_render' ],
			'aawp_table',
			'normal',
			'high'
		);

		add_action( 'aawp_sidebar_metabox_init', [ $this, 'aawp_admin_table_shortcode_meta_box_render' ] );
	}


	/**
	 * Rendering config meta box.
	 *
	 * @param Obj $post Post Object.
	 */
	public function aawp_admin_table_config_meta_box_render( $post ) {

		// Use nonce for verification to secure data sending
		wp_nonce_field( 'aawp_admin_comparison_table', 'aawp_admin_table_nonce' );

		$table_id = $post->ID;

		// Get data from db
		$table_settings = get_post_meta( $table_id, '_aawp_table_settings', true );
		$rows           = get_post_meta( $table_id, '_aawp_table_rows', true );

		// aawp_debug( $table_settings, '$table_settings' );
		// aawp_debug( $rows, '$rows' );
		?>

		<div class="aawp-table-wrap">

			<h3><?php _e( 'Customizations', 'aawp' ); ?></h3>
			<p>
				<?php
				printf( wp_kses( __( 'By editing the following customizations, you are going to overwrite the <a href="%s">global settings</a> for this table only.', 'aawp' ), [ 'a' => [ 'href' => [] ] ] ), esc_url( aawp_admin_get_settings_page_url( 'functions' ) . '#aawp_table_template' ) );
				?>
			</p>
			<table class="form-table">
				<tbody>
				<tr class="row">
					<th><?php _e( 'Labels', 'aawp' ); ?></th>
					<td>
						<?php
						$label_col_options = aawp_admin_table_get_label_col_options();
						$label_col         = ( ! empty( $table_settings['labels'] ) ) ? $table_settings['labels'] : '';
						?>
						<select id="aawp_table_settings_labels" name="aawp_table_settings[labels]">
							<option value="" <?php selected( $label_col, '' ); ?>><?php _e( 'Standard', 'aawp' ); ?></option>
							<?php foreach ( $label_col_options as $key => $label ) { ?>
								<option value="<?php echo $key; ?>" <?php selected( $label_col, $key ); ?>><?php echo $label; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
					<?php
					/*
					<tr class="row">
						<th><?php _e('Highlight color', 'aawp' ); ?></th>
						<td><input type="text" class="aawp-input-colorpicker" value="#e9e9e9" /></td>
					</tr>
					*/
					?>
				</tbody>
			</table>

			<h3><?php _e( 'Rows', 'aawp' ); ?></h3>

			<div id="aawp-table-rows" class="aawp-table-rows" data-aawp-table-sortable-rows="true">
				<?php for ( $row_id = 0; $row_id < AAWP_TABLE_ROWS_MAX; $row_id++ ) { ?>
					<?php $row_active = ( isset( $rows[ $row_id ] ) && aawp_admin_is_table_row_valid( $rows[ $row_id ] ) ) ? true : false; ?>
					<div id="aawp-table-row-item-<?php echo $row_id; ?>" class="aawp-table-rows__item" style="display: <?php echo ( $row_active ) ? 'block' : 'none'; ?>">
						<input type="hidden"  />
						<div class="aawp-table-rows__col aawp-table-rows__col--move">
							<span class="dashicons dashicons-move"></span>
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--status">
							<label class="aawp-control-switch" title="<?php _e( 'Show/hide row', 'aawp' ); ?>">
								<input id="aawp-table-row-status-<?php echo $row_id; ?>" type="checkbox"
									   name="aawp_table_rows[<?php echo $row_id; ?>][status]"
									   value="1"
									   <?php
										if ( isset( $rows[ $row_id ]['status'] ) && '1' == $rows[ $row_id ]['status'] ) {
											echo ' checked="checked"';}
										?>
										>
								<span class="aawp-control-switch__slider"></span>
							</label>
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--input">
							<input class="widefat" type="text"
								   name="aawp_table_rows[<?php echo $row_id; ?>][label]"
								   value="<?php echo ( isset( $rows[ $row_id ]['label'] ) ) ? esc_html( $rows[ $row_id ]['label'] ) : ''; ?>"
								   data-aawp-table-rows-input="<?php echo $row_id; ?>" placeholder="<?php _e( 'Enter a label or leave empty...', 'aawp' ); ?>" />
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--type">
							<?php
							$row_types = aawp_admin_table_get_row_types();
							$row_type  = ( isset( $rows[ $row_id ]['type'] ) ) ? $rows[ $row_id ]['type'] : '';
							?>
							<select class="widefat" name="aawp_table_rows[<?php echo $row_id; ?>][type]" data-aawp-table-row-type="<?php echo $row_id; ?>">
								<option value=""><?php _e( 'Please select...', 'aawp' ); ?></option>
								<?php foreach ( $row_types as $type_key => $type_label ) { ?>
									<option
											value="<?php echo $type_key; ?>" <?php selected( $row_type, $type_key ); ?>
										<?php
										if ( in_array( $type_key, aawp_admin_table_get_row_type_drops() ) ) {
											echo ' disabled="disabled"';}
										?>
									><?php echo $type_label; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--highlight">
							<label class="aawp-control-icon-switch" title="<?php _e( 'Highlight row', 'aawp' ); ?>">
								<input type="checkbox"
									   name="aawp_table_rows[<?php echo $row_id; ?>][highlight]"
									   value="1"
									   <?php
										if ( isset( $rows[ $row_id ]['highlight'] ) && '1' == $rows[ $row_id ]['highlight'] ) {
											echo ' checked="checked"';}
										?>
										>
								<span class="aawp-control-icon-switch__icon"><span class="dashicons dashicons-admin-customizer"></span></span>
							</label>
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--link">
							<label class="aawp-control-icon-switch" title="<?php _e( 'Link output', 'aawp' ); ?>">
								<input type="checkbox"
									   name="aawp_table_rows[<?php echo $row_id; ?>][link]"
									   value="1"
									   <?php
										if ( isset( $rows[ $row_id ]['link'] ) && '1' == $rows[ $row_id ]['link'] ) {
											echo ' checked="checked"';}
										?>
										>
								<span class="aawp-control-icon-switch__icon"><span class="dashicons dashicons-admin-links"></span></span>
							</label>
						</div>
						<div class="aawp-table-rows__col aawp-table-rows__col--actions">
							<span class="aawp-table-rows__action aawp-table-rows__action--delete" data-aawp-table-delete-row="<?php echo $row_id; ?>" title="<?php _e( 'Remove row', 'aawp' ); ?>"><span class="dashicons dashicons-trash"></span></span>
						</div>
					</div>

					<?php
				}//end for
				?>
			</div>

			<p id="aawp-table-no-rows" style="display: <?php echo ( empty( $rows ) ) ? 'block' : 'none'; ?>;">
				<?php _e( 'There are no rows added yet.', 'aawp' ); ?>
			</p>

			<p>
				<span class="button aawp-table-button" data-aawp-table-add-row="true"><span class="dashicons dashicons-plus-alt"></span> <?php _e( 'Add new row', 'aawp' ); ?></span>
			</p>

		</div>

		<?php
	}

	/**
	 * Rendering products meta box
	 *
	 * @param $post
	 */
	public function aawp_admin_table_products_meta_box_render( $post ) {

		$table_id = $post->ID;

		$options = aawp_get_options();

		// Reset
		// update_post_meta( $table_id, '_aawp_table_products', '' );
		// update_post_meta( $table_id, '_aawp_table_rows', '' );

		// Gett data from db
		$rows     = get_post_meta( $table_id, '_aawp_table_rows', true );
		$products = get_post_meta( $table_id, '_aawp_table_products', true );
		?>

		<div class="aawp-table-wrap">

			<div id="aawp-table-products" class="aawp-table-products" data-aawp-table-sortable-products="true">
				<?php for ( $product_id = 0; $product_id < AAWP_TABLE_PRODUCTS_MAX; $product_id++ ) { ?>
					<div id="aawp-table-product-<?php echo $product_id; ?>" class="aawp-table-product" style="display: <?php echo ( isset( $products[ $product_id ] ) ) ? 'block' : 'none'; ?>">
						<div class="aawp-table-product__header">
							<!-- Status -->
							<div class="aawp-table-product__status">
								<label class="aawp-control-switch" title="<?php _e( 'Show/hide product', 'aawp' ); ?>">
									<input id="aawp-table-product-status-<?php echo $product_id; ?>" type="checkbox"
										   name="aawp_table_products[<?php echo $product_id; ?>][status]"
										   value="1"
										   <?php
											if ( isset( $products[ $product_id ]['status'] ) && '1' == $products[ $product_id ]['status'] ) {
												echo ' checked="checked"';}
											?>
											>
									<span class="aawp-control-switch__slider"></span>
								</label>
							</div>
							<!-- ASIN -->
							<div id="aawp-table-product-asin-<?php echo $product_id; ?>" class="aawp-table-product__asin">
								<label for="aawp-table-product-asin-field-<?php echo $product_id; ?>" class="aawp-table-product__asin-label"><?php printf( esc_html__( 'Product no. %d', 'aawp' ), ( $product_id + 1 ) ); ?></label>
								<input type="text" id="aawp-table-product-asin-field-<?php echo $product_id; ?>" class="aawp-table-product__asin-input"
									   name="aawp_table_products[<?php echo $product_id; ?>][asin]"
									   value="<?php echo ( isset( $products[ $product_id ]['asin'] ) ) ? $products[ $product_id ]['asin'] : ''; ?>"
									   placeholder="<?php _e( 'Enter ASIN ...', 'aawp' ); ?>" />
								<a id="aawp-table-product-asin-search-<?php echo $product_id; ?>" class="aawp-table-product__asin-search" href="#aawp-modal-table-product-search" data-aawp-modal="true" data-aawp-table-search-product="<?php echo $product_id; ?>" title="<?php _e( 'Click in order to start product search', 'aawp' ); ?>">
									<span class="dashicons dashicons-search"></span></a>
							</div>
							<?php
							/*
							<!-- Highlight -->
							<div id="aawp-table-product-highlight-<?php echo $product_id; ?>" class="aawp-table-product__highlight">
								<label class="aawp-control-icon-switch" title="<?php _e('Highlight product', 'aawp' ); ?>">
									<input type="checkbox"
										   name="aawp_table_products[<?php echo $product_id; ?>][highlight]"
										   value="1"<?php if ( isset( $products[$product_id]['highlight'] ) && '1' == $products[$product_id]['highlight'] ) echo ' checked="checked"'; ?>>
									<span class="aawp-control-icon-switch__icon"><span class="dashicons dashicons-admin-customizer"></span></span>
								</label>
							</div>
							*/
							?>
							<!-- Options -->
							<div id="aawp-table-product-options-<?php echo $product_id; ?>" class="aawp-table-product__options">
								<a href="#" data-aawp-table-product-footer-toggle="<?php echo $product_id; ?>"><?php _e( 'Show more options', 'aawp' ); ?></a>
							</div>
							<!-- Title -->
							<?php if ( ! empty( $products[ $product_id ]['title'] ) ) { ?>
								<div class="aawp-table-product__title"><?php echo $products[ $product_id ]['title']; ?></div>
							<?php } ?>
							<!-- Delete -->
							<span class="aawp-table-product__action aawp-table-product__action--delete" data-aawp-table-delete-product="<?php echo $product_id; ?>"><span class="dashicons dashicons-trash"></span></span>
						</div>
						<div class="aawp-table-product__body" data-aawp-table-sortable-rows="true">
							<?php for ( $row_id = 0; $row_id <= AAWP_TABLE_ROWS_MAX; $row_id++ ) { ?>

								<?php
								$product_row_types = aawp_admin_table_get_row_types();
								$product_row_type  = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['type'] ) ) ? $products[ $product_id ]['rows'][ $row_id ]['type'] : '';

								// Row type overwritten?
								if ( ! empty( $product_row_type ) ) {
									$product_row_type_value = $product_row_type;
									// Otherwise check default row type
								} elseif ( ! empty( $rows[ $row_id ]['type'] ) ) {
									$product_row_type_value = $rows[ $row_id ]['type'];
								} else {
									$product_row_type_value = '';
								}
								// $product_row_value = ( ! empty( $products[$product_id]['rows'][$row_id]['value'] ) && ! in_array( $product_row_type, aawp_admin_table_get_row_type_drops() )  ) ? $products[$product_id]['rows'][$row_id]['value'] : '';
								?>

								<div class="aawp-table-product__row" data-aawp-table-product-row="<?php echo $row_id; ?>" style="display: <?php echo ( isset( $rows[ $row_id ] ) ) ? 'block' : 'none'; ?>">
									<div class="aawp-table-product__data aawp-table-product__data--move">
										<span class="dashicons dashicons-move"></span>
									</div>
									<div class="aawp-table-product__data aawp-table-product__data--label" data-aawp-table-product-label-field="<?php echo $row_id; ?>">
										<?php echo ( ! empty( $rows[ $row_id ]['label'] ) ) ? $rows[ $row_id ]['label'] : ''; ?>
									</div>
									<div class="aawp-table-product__data aawp-table-product__data--type">
										<select class="widefat" name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][type]" data-aawp-table-product-row-type="true">
											<option value=""><?php _e( 'Select in order to overwrite...', 'aawp' ); ?></option>
											<?php foreach ( $product_row_types as $type_key => $type_label ) { ?>
												<option
														value="<?php echo $type_key; ?>" <?php selected( $product_row_type, $type_key ); ?>
														<?php
														if ( in_array( $type_key, aawp_admin_table_get_row_type_drops() ) ) {
															echo ' disabled="disabled"';}
														?>
												><?php echo $type_label; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="aawp-table-product__data aawp-table-product__data--value">
										<div class="aawp-table-product__value
										<?php
										if ( ! empty( $product_row_type_value ) ) {
											echo ' aawp-table-product__value--' . $product_row_type_value;}
										?>
										" data-aawp-table-product-row-value="true">
											<?php
											/*
											<!-- Shared values -->
											<div class="aawp-table-product-value aawp-table-product-value--linked">
												Linked?
											</div>
											*/
											?>
											<!-- Bool -->
											<div class="aawp-table-product-value aawp-table-product-value--bool">
												<?php $product_row_value_bool = ( isset( $products[ $product_id ]['rows'][ $row_id ]['values']['bool'] ) && '1' == $products[ $product_id ]['rows'][ $row_id ]['values']['bool'] ) ? true : false; ?>
												<input id="aawp-table-product-<?php echo $product_id; ?>-row-<?php echo $row_id; ?>-value-bool-yes" class="widefat" type="radio"
													   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][bool]" value="1" <?php checked( $product_row_value_bool, true ); ?> /><label for="aawp-table-product-<?php echo $product_id; ?>-row-<?php echo $row_id; ?>-value-bool-yes"><?php _e( 'Yes', 'aawp' ); ?></label>
												<input id="aawp-table-product-<?php echo $product_id; ?>-row-<?php echo $row_id; ?>-value-bool-no" class="widefat" type="radio"
													   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][bool]" value="0" <?php checked( $product_row_value_bool, false ); ?> /><label for="aawp-table-product-<?php echo $product_id; ?>-row-<?php echo $row_id; ?>-value-bool-no"><?php _e( 'No', 'aawp' ); ?></label>
											</div>
											<!-- Shortcode -->
											<div class="aawp-table-product-value aawp-table-product-value--shortcode">
												<?php $product_row_value_shortcode = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['values']['shortcode'] ) ) ? esc_html( $products[ $product_id ]['rows'][ $row_id ]['values']['shortcode'] ) : ''; ?>
												<input class="widefat" type="text"
													   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][shortcode]"
													   value="<?php echo $product_row_value_shortcode; ?>" />
											</div>
											<!-- Custom Button -->
											<div class="aawp-table-product-value aawp-table-product-value--custom_button">
												<?php
												$product_row_value_custom_button_text     = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_text'] ) ) ? esc_html( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_text'] ) : '';
												$product_row_value_custom_button_url      = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_url'] ) ) ? $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_url'] : '';
												$product_row_value_custom_button_blank    = ( isset( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_blank'] ) && '1' == $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_blank'] ) ? true : false;
												$product_row_value_custom_button_nofollow = ( isset( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_nofollow'] ) && '1' == $products[ $product_id ]['rows'][ $row_id ]['values']['custom_button_nofollow'] ) ? true : false;
												?>
												<div class="aawp-table-product-value-group">
													<input class="widefat" type="text"
														   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_button_text]"
														   value="<?php echo $product_row_value_custom_button_text; ?>"
														   placeholder="<?php _e( 'Enter button text...', 'aawp' ); ?>" />
												</div>
												<div class="aawp-table-product-value-group">
													<input class="widefat" type="text"
														   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_button_url]"
														   value="<?php echo $product_row_value_custom_button_url; ?>"
														   placeholder="<?php _e( 'Enter button url...', 'aawp' ); ?>" />
												</div>
												<div class="aawp-table-product-value-group">
													<label>
														<input class="widefat" type="checkbox"
															   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_button_blank]"
															   value="1"
															   <?php
																if ( $product_row_value_custom_button_blank ) {
																	echo 'checked="checked"';}
																?>
																 /> <?php _e( 'Open in new window', 'aawp' ); ?>
													</label>
												</div>
												<div class="aawp-table-product-value-group">
													<label>
														<input class="widefat" type="checkbox"
															   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_button_nofollow]"
															   value="1"
															   <?php
																if ( $product_row_value_custom_button_nofollow ) {
																	echo 'checked="checked"';}
																?>
																 /> <?php _e( 'sponsored', 'aawp' ); ?>
													</label>
												</div>
											</div>
											<!-- Custom Text -->
											<div class="aawp-table-product-value aawp-table-product-value--custom_text">
												<?php $product_row_value_custom_text = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_text'] ) ) ? esc_html( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_text'] ) : ''; ?>
												<input class="widefat" type="text"
													   name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_text]"
													   value="<?php echo $product_row_value_custom_text; ?>" />
											</div>
											<!-- Custom HTML -->
											<div class="aawp-table-product-value aawp-table-product-value--custom_html">
												<?php $product_row_value_custom_html = ( ! empty( $products[ $product_id ]['rows'][ $row_id ]['values']['custom_html'] ) ) ? $products[ $product_id ]['rows'][ $row_id ]['values']['custom_html'] : ''; ?>
												<textarea class="widefat" name="aawp_table_products[<?php echo $product_id; ?>][rows][<?php echo $row_id; ?>][values][custom_html]"><?php echo esc_html( $product_row_value_custom_html ); ?></textarea>
											</div>
										</div>
									</div>
								</div>

								<?php
							}//end for
							?>
						</div>
						<div id="aawp-table-product-footer-<?php echo $product_id; ?>" class="aawp-table-product__footer" style="display: <?php echo ( empty( $products[ $product_id ]['highlight_color'] ) || ! empty( $products[ $product_id ]['highlight_text'] ) ) ? 'block' : 'none'; ?>">
							<!-- Highlight -->
							<label class="aawp-table-product__highlight-label"><?php _e( 'Highlight Product:', 'aawp' ); ?></label>
							<input type="text" class="aawp-input-colorpicker"
								   name="aawp_table_products[<?php echo $product_id; ?>][highlight_color]"
								   value="<?php echo ( isset( $products[ $product_id ]['highlight_color'] ) ) ? $products[ $product_id ]['highlight_color'] : ''; ?>"
								   placeholder="<?php _e( 'Select color...', 'aawp' ); ?>" />
							<input type="text" class="aawp-table-product__highlight-text"
								   name="aawp_table_products[<?php echo $product_id; ?>][highlight_text]"
								   value="<?php echo ( isset( $products[ $product_id ]['highlight_text'] ) ) ? $products[ $product_id ]['highlight_text'] : ''; ?>"
								   placeholder="<?php _e( 'Maybe enter text ...', 'aawp' ); ?>" />
						</div>
					</div>
					<?php
				}//end for
				?>
			</div>

			<p id="aawp-table-no-products" style="display: <?php echo ( empty( $products ) ) ? 'block' : 'none'; ?>;">
				<?php _e( 'There are no products added yet.', 'aawp' ); ?>
			</p>

			<hr />

			<p>
				<strong><?php _e( 'Add new products', 'aawp' ); ?></strong>
			</p>
			<p id="aawp-table-add-product-actions" class="aawp-table-add-product-actions">
				<span class="aawp-table-add-product-by-asin">
					<input type="text" value="" placeholder="<?php _e( 'Enter ASIN...', 'aawp' ); ?>" style="width: 125px;" data-aawp-table-add-product-by-asin="true" /><span class="button aawp-table-button" data-aawp-table-add-product-by-asin-submit="true"><?php _e( 'Add product by ASIN', 'aawp' ); ?></span>
				</span>
				&nbsp;<?php _e( 'or', 'aawp' ); ?>&nbsp;
				<a class="aawp-table-add-products-search" href="#aawp-modal-table-product-search" data-aawp-modal="true" data-aawp-table-add-products-search="true">
					<span class="button aawp-table-button"><?php _e( 'Search for product(s)', 'aawp' ); ?></span>
				</a>
			</p>

			<div id="aawp-table-add-product-notices" class="aawp-table-add-product-notices">
				<p id="aawp-table-add-product-notice-asin-length" class="aawp-notice aawp-notice--warning"><?php _e( 'The ASIN you enter must contain at least 10 digits.', 'aawp' ); ?></p>
			</div>

			<?php aawp_admin_the_table_product_search_modal(); ?>
			<?php
			// aawp_admin_the_modal_link( 'table-product-search', 'Open modal' );
			?>

			<input type="hidden" id="aawp-post-id" value="<?php echo $table_id; ?>">
			<input type="hidden" id="aawp-table-active-product-search" value="">
			<input type="hidden" id="aawp-ajax-search-items-selected" value="" />

		</div>

		<?php
		// aawp_debug( $products, '$products' );

		// aawp_debug( $rows, '$rows' );
	}

	/**
	 * Rendering shortcode meta box
	 *
	 * @param $post
	 */
	public function aawp_admin_table_shortcode_meta_box_render( $post ) {

		if ( empty( $post->post_type ) || 'aawp_table' !== $post->post_type ) {
			return;
		}

		$table_id = $post->ID;

		$shortcode = aawp_get_shortcode();
		?>
		<p><?php echo esc_html__( 'Shortcode', 'aawp' ); ?></p>
		<span class="shortcode aawp-shortcode-field">
			<input type='text' onClick="this.select();" value='[<?php echo $shortcode; ?> table="<?php echo $table_id; ?>"]'readonly='readonly' style="width:100%" />
			<button class="button aawp-copy-shortcode-button help_tip" type="button" href="#" >
				<span class="dashicons dashicons-clipboard aawp-copy-shortcode"></span>
				<span class="aawp-tooltiptext"><?php echo esc_html__( 'Copy Shortcode', 'aawp' ); ?></span>
			</button>
		</span>

		<?php
	}
}
