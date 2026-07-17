<?php
/**
 * Table Builder
 *
 * @package     src\Admin\ComparisonTable
 * @since       3.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $aawp_table;
global $aawp_table_id;
global $aawp_tables;

/**
 * Saving meta fields
 *
 * @param $post_id
 *
 * @return string
 */
function aawp_admin_table_save_meta( $post_id, $post ) {

	// aawp_debug_log( 'aawp_admin_table_save_meta' );

	/* Verify the nonce before proceeding. */
	if ( ! isset( $_POST['aawp_admin_table_nonce'] ) ) {
		return $post_id;
	}

	// aawp_debug_log( 'aawp_admin_table_nonce SET' );
	if ( ! wp_verify_nonce( $_POST['aawp_admin_table_nonce'], 'aawp_admin_comparison_table' ) ) {
		return $post_id;
	}

	// aawp_debug_log( 'aawp_admin_table_nonce PASSED' );

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Debug
	// aawp_debug( $_POST, 'Debug: $_POST' );
	// aawp_debug_log( $_POST['aawp_table_rows'] );
	// aawp_debug_log( $_POST['aawp_table_products'] );

	// aawp_debug_log( 'Products submitted: ' . sizeof ( $_POST['aawp_table_products'] ) );

	// Defaults
	$valid_input_row_ids  = [];
	$table_settings       = [];
	$table_rows           = [];
	$table_products       = [];
	$table_customizations = [];

	// Handle settings
	if ( isset( $_POST['aawp_table_settings'] ) ) {

		$settings = [
			'labels' => ( ! empty( $_POST['aawp_table_settings']['labels'] ) ) ? $_POST['aawp_table_settings']['labels'] : '',
		];

		$table_settings = $settings;
	}

	if ( ! empty( $table_settings['labels'] ) ) {
		$table_customizations[] = str_replace( '_', '-', $table_settings['labels'] ) . '-labels';
	}

	// Handling rows
	if ( isset( $_POST['aawp_table_rows'] ) && is_array( $_POST['aawp_table_rows'] ) ) {

		foreach ( $_POST['aawp_table_rows'] as $row_id => $row ) {

			// Kick dummies
			if ( ! is_numeric( $row_id ) || ! aawp_admin_is_table_row_valid( $row ) ) {
				continue;
			}

			$valid_input_row_ids[] = $row_id;

			// Build row data
			$data = [
				'status'    => ( isset( $row['status'] ) && '1' == $row['status'] ) ? true : false,
				'label'     => ( isset( $row['label'] ) ) ? $row['label'] : '',
				'type'      => ( isset( $row['type'] ) ) ? esc_html( $row['type'] ) : '',
				'highlight' => ( isset( $row['highlight'] ) && '1' == $row['highlight'] ) ? true : false,
				'link'      => ( isset( $row['link'] ) && '1' == $row['link'] ) ? true : false,
			];

			$table_rows[] = $data;
		}
	}//end if

	// Handling products
	if ( isset( $_POST['aawp_table_products'] ) && is_array( $_POST['aawp_table_products'] ) ) {

		foreach ( $_POST['aawp_table_products'] as $product_id => $product ) {

			// Kick dummies
			if ( ! is_numeric( $product_id ) ) {
				continue;
			}

			// Validate inputs
			if ( empty( $product['asin'] ) ) {
				continue;
			}

			$data = [
				'status' => ( isset( $product['status'] ) && '1' == $product['status'] ) ? true : false,
				'asin'   => trim( $product['asin'] ),
				'rows'   => [],
			];

			// Build row data
			if ( isset( $product['rows'] ) && is_array( $product['rows'] ) ) {

				foreach ( $product['rows'] as $product_row_id => $product_row ) {

					if ( ! in_array( $product_row_id, $valid_input_row_ids ) ) {
						continue;
					}

					$row_type           = ( isset( $_POST['aawp_table_rows'][ $product_row_id ]['type'] ) ) ? $_POST['aawp_table_rows'][ $product_row_id ]['type'] : '';
					$product_row_type   = ( isset( $product_row['type'] ) && ! in_array( $product_row['type'], aawp_admin_table_get_row_type_drops() ) ) ? $product_row['type'] : '';
					$product_row_values = [];

					// aawp_debug_log( 'row #' . $product_row_id . ' - $row_type: ' . $row_type . ' >> product asin ' . $product['asin'] . ' $product_row_id: ' . $product_row_id . ' $product_row_type: ' . $product_row_type );

					// Values
					if ( 'bool' === $product_row_type || ( ! $product_row_type && 'bool' === $row_type ) ) {
						$product_row_values['bool'] = ( isset( $product_row['values']['bool'] ) && '1' == $product_row['values']['bool'] ) ? true : false;

					} elseif ( 'shortcode' === $product_row_type || ( ! $product_row_type && 'shortcode' === $row_type ) ) {
						$product_row_values['shortcode'] = ( ! empty( $product_row['values']['shortcode'] ) ) ? sanitize_text_field( $product_row['values']['shortcode'] ) : '';

					} elseif ( 'custom_button' === $product_row_type || ( ! $product_row_type && 'custom_button' === $row_type ) ) {
						$product_row_values['custom_button_text']     = ( ! empty( $product_row['values']['custom_button_text'] ) ) ? sanitize_text_field( $product_row['values']['custom_button_text'] ) : '';
						$product_row_values['custom_button_url']      = ( ! empty( $product_row['values']['custom_button_url'] ) ) ? esc_url_raw( $product_row['values']['custom_button_url'] ) : '';
						$product_row_values['custom_button_blank']    = ( isset( $product_row['values']['custom_button_blank'] ) && '1' == $product_row['values']['custom_button_blank'] ) ? true : false;
						$product_row_values['custom_button_nofollow'] = ( isset( $product_row['values']['custom_button_nofollow'] ) && '1' == $product_row['values']['custom_button_nofollow'] ) ? true : false;

					} elseif ( 'custom_text' === $product_row_type || ( ! $product_row_type && 'custom_text' === $row_type ) ) {
						$product_row_values['custom_text'] = ( ! empty( $product_row['values']['custom_text'] ) ) ? sanitize_text_field( $product_row['values']['custom_text'] ) : '';

					} elseif ( 'custom_html' === $product_row_type || ( ! $product_row_type && 'custom_html' === $row_type ) ) {
						$product_row_values['custom_html'] = ( ! empty( $product_row['values']['custom_html'] ) ) ? $product_row['values']['custom_html'] : '';
					}

					// Finish
					$product_row_data = [
						'type'   => $product_row_type,
						'values' => $product_row_values,
					];

					// aawp_debug_log( '$product_row_data' );
					// aawp_debug_log( $product_row_data );

					$data['rows'][] = $product_row_data;
				}//end foreach
			}//end if

			// Options
			$data['highlight'] = false;

			if ( ! empty( $product['highlight_color'] ) ) {
				$data['highlight_color'] = esc_html( $product['highlight_color'] );
				$data['highlight']       = true;
			}

			if ( ! empty( $product['highlight_text'] ) ) {
				$data['highlight_text'] = sanitize_text_field( $product['highlight_text'] );
			}

			if ( $data['highlight'] && ! empty( $product['highlight_color'] ) && ! empty( $product['highlight_text'] ) && ! in_array( 'ribbon', $table_customizations ) ) {
				$table_customizations[] = 'ribbon';
			}

			// Finally store data
			$table_products[] = $data;
		}//end foreach
	}//end if

	// Saving meta
	update_post_meta( $post_id, '_aawp_table_settings', $table_settings );
	update_post_meta( $post_id, '_aawp_table_rows', $table_rows );
	update_post_meta( $post_id, '_aawp_table_products', $table_products );
	update_post_meta( $post_id, '_aawp_table_customizations', $table_customizations );
}

/**
 * Validate table row
 *
 * @param $table_row
 *
 * @return bool
 */
function aawp_admin_is_table_row_valid( $table_row ) {

	if ( ! empty( $table_row['status'] ) ) {
		return true;
	}

	if ( ! empty( $table_row['label'] ) ) {
		return true;
	}

	if ( ! empty( $table_row['highlight'] ) ) {
		return true;
	}

	if ( ! empty( $table_row['link'] ) ) {
		return true;
	}

	return false;
}

/**
 * The product search modal
 */
function aawp_admin_the_table_product_search_modal() {

	aawp_admin_the_modal_header( 'table-product-search', __( 'Product Search', 'aawp' ) );
	?>

	<div class="aawp-modal__form">
		<p>
			<input id="aawp-ajax-search-input" type="text" class="widefat" value="" placeholder="<?php _e( 'Enter search term...', 'aawp' ); ?>" />
			<br />
			<span class="button aawp-table-button" data-aawp-ajax-search=true" style="margin-top: 10px;">
				<span class="dashicons dashicons-search"></span> <?php _e( 'Search products', 'aawp' ); ?>
			</span>
		</p>
	</div>

	<div id="aawp-ajax-search-results" class="aawp-ajax-search-results" data-aawp-ajax-search-items-select="9"></div>
	<div id="aawp-ajax-search-meta" class="aawp-ajax-search-meta">
		<span id="aawp-table-product-select" class="button button-primary button-large aawp-table-button aawp-table-product-select" data-aawp-table-product-search-select="true"><?php _e( 'Confirm selection', 'aawp' ); ?></span>
	</div>

	<?php
	aawp_admin_the_modal_footer();
}

/**
 * Get label col options
 *
 * @return array
 */
function aawp_admin_table_get_label_col_options() {
	return [
		'show'         => __( 'Show', 'aawp' ),
		'hide'         => __( 'Hide', 'aawp' ),
		'hide_mobile'  => __( 'Hide on mobile devices only', 'aawp' ),
		'hide_desktop' => __( 'Show on mobile devices only', 'aawp' ),
	];
}

/**
 * Get available row types
 *
 * @return array
 */
function aawp_admin_table_get_row_types() {

	// $first = ( $default ) ? __( 'Please select...', 'aawp' ) : __( 'Select in order to overwrite...', 'aawp' );

	return [
		'_divider_product_'  => __( '----- Product Data -----', 'aawp' ),
		'title'              => __( 'Title', 'aawp' ),
		'thumb'              => __( 'Thumbnail', 'aawp' ),
		'price'              => __( 'Price', 'aawp' ),
		'prime'              => __( 'Prime Status', 'aawp' ),
		'star_rating'        => __( 'Star Rating', 'aawp' ),
		'reviews'            => __( 'Reviews', 'aawp' ),
		'button'             => __( 'Buy Now Button', 'aawp' ),
		'_divider_elements_' => __( '----- Elements -----', 'aawp' ),
		'bool'               => __( 'Yes/No', 'aawp' ),
		'_divider_custom_'   => '----- Custom Output -----',
		'shortcode'          => __( 'Shortcode', 'aawp' ),
		'custom_button'      => __( 'Custom Button', 'aawp' ),
		'custom_text'        => __( 'Custom Text', 'aawp' ),
		'custom_html'        => __( 'Custom HTML', 'aawp' ),
	];
}

/**
 * Get product row type drops
 *
 * @return array
 */
function aawp_admin_table_get_row_type_drops() {
	return [ '_divider_product_', '_divider_elements_', '_divider_custom_' ];
}

/**
 * Get table rows
 *
 * @param $table_id
 *
 * @return mixed
 */
function aawp_get_table_rows( $table_id ) {

	$rows = get_post_meta( $table_id, '_aawp_table_rows', true );

	return $rows;
}

/**
 * Get table products
 *
 * @param $table_id
 *
 * @return mixed
 */
function aawp_get_table_products( $table_id ) {

	$products = get_post_meta( $table_id, '_aawp_table_products', true );

	return $products;
}

/**
 * Get table customizations
 *
 * @param $table_id
 *
 * @return mixed
 */
function aawp_get_table_customizations( $table_id ) {

	$customizations = get_post_meta( $table_id, '_aawp_table_customizations', true );

	return $customizations;
}

/**
 * Merge table customizations with global settings
 *
 * @param $customizations
 * @return array
 */
function aawp_merge_table_settings_customizations( $customizations ) {

	$options = aawp_get_options( 'functions' );

	// aawp_debug( $customizations, 'aawp_merge_table_settings_customizations >> $customizations' );

	$labels_set = false;

	foreach ( $customizations as $customization ) {

		// Labels
		if ( strpos( $customization, '-labels' ) !== false ) {
			$labels_set = true;
		}
	}

	// Labels
	if ( ! $labels_set && ! empty( $options['table_labels'] ) && 'show' != $options['table_labels'] ) {
		$customizations[] = str_replace( '_', '-', $options['table_labels'] ) . '-labels';
	}

	return $customizations;
}

/**
 * Output table customization classes
 *
 * @param $default_class
 */
function aawp_the_table_customization_classes( $default_class ) {

	global $aawp_table;

	if ( ! isset( $aawp_table['customizations'] ) || ! is_array( $aawp_table['customizations'] ) || sizeof( $aawp_table['customizations'] ) === 0 ) {
		return;
	}

	foreach ( $aawp_table['customizations'] as $customization ) {
		echo ' ' . $default_class . '--' . esc_html( $customization );
	}
}

/**
 * Output table product data classes
 *
 * @param string           $default_class
 * @param $table_row_id
 * @param $table_product_id
 */
function aawp_the_table_product_data_classes( $default_class, $table_row_id, $table_product_id ) {

	global $aawp_table;

	$classes = $default_class;

	// Add type
	if ( ! empty( $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ]['type'] ) ) {
		$type = $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ]['type'];
	} else {
		$type = ( ! empty( $aawp_table['rows'][ $table_row_id ]['type'] ) ) ? $aawp_table['rows'][ $table_row_id ]['type'] : false;
	}

	if ( $type ) {
		$classes .= ' ' . $default_class . '--type-' . esc_html( $type );
	}

	if ( ! empty( $classes ) ) {
		echo $classes;
	}
}

/**
 * Check if table product ribbon is visible
 *
 * @param $table_product_id
 *
 * @return bool
 */
function aawp_show_table_product_ribbon( $table_product_id ) {

	global $aawp_table;

	// aawp_debug( $aawp_table['products'][$table_product_id] );

	if ( empty( $aawp_table['products'][ $table_product_id ]['highlight'] ) ) {
		return false;
	}

	if ( empty( $aawp_table['products'][ $table_product_id ]['highlight_text'] ) ) {
		return false;
	}

	return true;
}

/**
 * Check if table product is highlighted
 *
 * @param $table_product_id
 *
 * @return bool
 */
function aawp_is_table_product_highlighted( $table_product_id ) {

	global $aawp_table;

	if ( ! empty( $aawp_table['products'][ $table_product_id ]['highlight'] ) && ! empty( $aawp_table['products'][ $table_product_id ]['highlight_color'] ) ) {
		return true;
	}

	return false;
}

/**
 * Output table product highlight ribbon
 *
 * @param $table_product_id
 * @param null             $table_row_id
 */
function aawp_the_table_product_highlight_ribbon( $table_product_id, $table_row_id = null ) {

	global $aawp_table;

	if ( aawp_is_table_product_highlighted( $table_product_id ) && ! empty( $aawp_table['products'][ $table_product_id ]['highlight_text'] ) ) {

		// Maybe check row
		if ( ! is_null( $table_row_id ) && $table_row_id != 0 ) {
			return;
		}

		echo '<span class="aawp-tb-ribbon">';
		echo esc_html( $aawp_table['products'][ $table_product_id ]['highlight_text'] );
		echo '</span>';
	}
}

/**
 * Output table product data type
 *
 * @param $table_row_id
 * @param $table_product_id
 */
function aawp_the_table_product_data_type( $table_row_id, $table_product_id ) {

	global $aawp_table;

	if ( ! empty( $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ]['type'] ) ) {
		$type = $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ]['type'];
	} else {
		$type = ( ! empty( $aawp_table['rows'][ $table_row_id ]['type'] ) ) ? $aawp_table['rows'][ $table_row_id ]['type'] : '';
	}

	echo $type;
}

/**
 * Display the product data
 *
 * @param $table_row_id
 * @param $table_product_id
 */
function aawp_the_table_product_data( $table_row_id, $table_product_id ) {

	global $aawp_table;

	if ( ! isset( $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ] ) || empty( $aawp_table['products'][ $table_product_id ]['asin'] ) ) {
		return;
	}

	$data = $aawp_table['products'][ $table_product_id ]['rows'][ $table_row_id ];

	if ( ! empty( $data['type'] ) ) {
		$type = $data['type'];
	} elseif ( ! empty( $aawp_table['rows'][ $table_row_id ]['type'] ) ) {
		$type = $aawp_table['rows'][ $table_row_id ]['type'];
	} else {
		return;
	}

	$options = aawp_get_options();

	$asin   = $aawp_table['products'][ $table_product_id ]['asin'];
	$linked = ( isset( $aawp_table['rows'][ $table_row_id ]['link'] ) && '1' == $aawp_table['rows'][ $table_row_id ]['link'] ) ? true : false;

	$field_args = [];

	if ( $linked ) {
		$field_args['format'] = 'linked';
	}

	// Shortcode attributes
	if ( isset( $aawp_table['atts'] ) ) {

		$table_atts = $aawp_table['atts'];

		if ( ! empty( $table_atts['tracking_id'] ) ) {
			$field_args['tracking_id'] = $table_atts['tracking_id'];
		}
	}

	$link_text = '';

	$output = '-';

	// Product title
	if ( 'title' === $type ) {

		$title = aawp_get_field_value( $asin, 'title', $field_args );

		if ( ! empty( $title ) ) {
			$output = $title;
		}

		// Product thumb
	} elseif ( 'thumb' === $type ) {

		$image = aawp_get_field_value( $asin, 'image' );

		if ( ! empty( $image ) ) {

			$title = aawp_get_field_value( $asin, 'title' );

			// $output = '<span class="aawp-tb-thumb" style="background-image: url(' . esc_html( $image ) . ');"><img src="' . aawp_get_assets_url() . 'img/thumb-spacer.png" alt="' . esc_html( $title ) . '" /></span>';
			$output = '<span class="aawp-tb-thumb"><img src="' . esc_html( $image ) . '" alt="' . esc_html( $title ) . '" /></span>';

			if ( $linked ) {
				$link_text = aawp_get_field_value( $asin, 'title' );
			}
		}

		/*
		$thumb = aawp_get_field_value( $asin, 'thumb' );

		if ( ! empty( $thumb ) )
			$output = $thumb;
		*/

		// Product price
	} elseif ( 'price' === $type ) {

		$price = aawp_get_field_value( $asin, 'price', $field_args );

		if ( ! empty( $price ) ) {
			$output = $price;
		}

		// Product prime status
	} elseif ( 'prime' === $type ) {

		$prime = aawp_get_field_value( $asin, 'prime', $field_args );

		if ( ! empty( $prime ) ) {
			$output = $prime;
		}

		// Product star rating
	} elseif ( 'star_rating' === $type ) {

		$star_rating = aawp_get_field_value( $asin, 'star_rating', $field_args );

		if ( ! empty( $star_rating ) ) {
			$output = $star_rating;
		}

		// Product reviews
	} elseif ( 'reviews' === $type ) {

		$reviews = aawp_get_field_value( $asin, 'reviews' );

		if ( ! empty( $reviews ) ) {
			$output = $reviews;
		}

		if ( $linked ) {
			$link_text = $output;
		}

		// Product button
	} elseif ( 'button' === $type ) {

		$button = aawp_get_field_value( $asin, 'button', $field_args );

		if ( ! empty( $button ) ) {
			$output = $button;
		}

		// Elements: Bool
	} elseif ( 'bool' === $type ) {

		$output = ( ! empty( $data['values']['bool'] ) ) ? '<span class="aawp-icon-yes"></span>' : '<span class="aawp-icon-no"></span>';

		// Shortcode
	} elseif ( 'shortcode' === $type ) {

		if ( ! empty( $data['values']['shortcode'] ) ) {
			$output = do_shortcode( $data['values']['shortcode'] );
		}

		// Custom Button
	} elseif ( 'custom_button' === $type ) {

		$custom_button_text = ( ! empty( $data['values']['custom_button_text'] ) ) ? $data['values']['custom_button_text'] : false;
		$custom_button_url  = ( ! empty( $data['values']['custom_button_url'] ) ) ? $data['values']['custom_button_url'] : false;

		if ( $custom_button_text && $custom_button_url ) {

			$custom_button_classes = 'aawp-button';

			if ( ! empty( $options['output']['button_detail_style'] ) ) {
				$custom_button_classes .= ' aawp-button--' . esc_html( $options['output']['button_detail_style'] );
			}

			if ( ! empty( $options['output']['button_detail_style_rounded'] ) ) {
				$custom_button_classes .= ' rounded';
			}

			if ( ! empty( $options['output']['button_detail_style_shadow'] ) ) {
				$custom_button_classes .= ' shadow';
			}

			$output  = '<a class="' . $custom_button_classes . '"';
			$output .= ' href="' . esc_url( $custom_button_url ) . '"';
			$output .= ' title="' . strip_tags( $custom_button_text ) . '"';

			if ( isset( $data['values']['custom_button_blank'] ) && '1' == $data['values']['custom_button_blank'] ) {
				$output .= ' target="_blank"';
			}

			if ( isset( $data['values']['custom_button_nofollow'] ) && '1' == $data['values']['custom_button_nofollow'] ) {
				$output .= ' rel="nofollow noopener sponsored"';
			}

			$output .= '>';
			$output .= $custom_button_text;
			$output .= '</a>';
		}//end if

		// Custom Text
	} elseif ( 'custom_text' === $type ) {

		if ( ! empty( $data['values']['custom_text'] ) ) {
			$output = do_shortcode( $data['values']['custom_text'] );

			if ( $linked ) {
				$link_text = $data['values']['custom_text'];
			}
		}

		// Custom HTML
	} elseif ( 'custom_html' === $type ) {

		if ( ! empty( $data['values']['custom_html'] ) ) {
			$output = do_shortcode( $data['values']['custom_html'] );
		}
	}//end if

	// Build custom link
	if ( '-' != $output && ! empty( $link_text ) ) {

		if ( empty( $link_url ) ) {

			if ( isset( $field_args['format'] ) && 'linked' === $field_args['format'] ) {
				unset( $field_args['format'] );
				// Prevent double linking
			}

			$link_url = aawp_get_field_value( $asin, 'url', $field_args );
		}

		if ( ! empty( $link_url ) ) {

			$attributes = [];
			// TODO: Move this into a unique way to handle (class.template-functions.php > "the_product_container())

			$attributes['product-id']    = $asin;
			$attributes['product-title'] = '%title%';

			$attributes = apply_filters( 'aawp_product_container_attributes', $attributes );

			$data_attributes = '';

			if ( sizeof( $attributes ) != 0 ) {

				foreach ( $attributes as $key => $value ) {

					// Handle placeholders
					if ( '%title%' === $value ) {
						$value = aawp_get_field_value( $asin, 'title' );
					}

					// Add attribute to output
					if ( ! empty( $value ) ) {
						$data_attributes .= ' data-aawp-' . $key . '="' . str_replace( '"', "'", $value ) . '"';
					}
				}
			}

			$output = '<a href="' . esc_url( $link_url ) . '" title="' . esc_html( $link_text ) . '" target="_blank" rel="nofollow noopener sponsored"' . $data_attributes . '>' . $output . '</a>';
		}//end if
	}//end if

	// Wrap output in order to apply custom styles
	$output = '<div class="aawp-tb-product-data-' . esc_html( $type ) . '">' . $output . '</div>';

	// Finally echo output
	echo $output;
}

/**
 * Add table custom setting css
 *
 * @param $custom_setting_css
 *
 * @return string
 */
function aawp_add_table_custom_setting_css( $custom_setting_css ) {

	$options = aawp_get_options();

	$highlight_bg_color = ( ! empty( $options['functions']['table_highlight_bg_color'] ) ) ? $options['functions']['table_highlight_bg_color'] : aawp_get_default_highlight_bg_color();
	$highlight_color    = ( ! empty( $options['functions']['table_highlight_color'] ) ) ? $options['functions']['table_highlight_color'] : aawp_get_default_highlight_color();

	if ( ! empty( $highlight_bg_color ) ) {
		$custom_setting_css .= '.aawp .aawp-tb__row--highlight{background-color:' . $highlight_bg_color . ';}';
	}

	if ( ! empty( $highlight_color ) ) {
		$custom_setting_css .= '.aawp .aawp-tb__row--highlight{color:' . $highlight_color . ';}';
		$custom_setting_css .= '.aawp .aawp-tb__row--highlight a{color:' . $highlight_color . ';}';
	}

	return $custom_setting_css;
}
add_filter( 'aawp_custom_setting_css', 'aawp_add_table_custom_setting_css' );
add_filter( 'aawp_custom_setting_amp_css', 'aawp_add_table_custom_setting_css' );

/**
 * Add table custom styles
 *
 * @param $styles
 *
 * @return string
 */
function aawp_the_table_custom_styles( $styles ) {

	global $aawp_tables;

	if ( ! is_array( $aawp_tables ) || sizeof( $aawp_tables ) == 0 ) {
		return $styles;
	}

	foreach ( $aawp_tables as $table ) {

		if ( ! isset( $table['id'] ) ) {
			continue;
		}

		$table_id = $table['id'];

		$css_prefix = '#aawp-tb-' . $table_id . ' ';

		// Product customizations
		if ( isset( $table['products'] ) && is_array( $table['products'] ) && sizeof( $table['products'] ) > 0 ) {

			foreach ( $table['products'] as $table_product_id => $table_product ) {

				if ( $table_product['highlight'] ) {

					if ( ! empty( $table_product['highlight_color'] ) ) {

						$highlight_bg_color     = aawp_color_hex2rgba( esc_html( $table_product['highlight_color'] ), 0.1 );
						$highlight_border_color = esc_html( $table_product['highlight_color'] );
						$highlight_text         = ( ! empty( $table_product['highlight_text'] ) ) ? esc_html( $table_product['highlight_text'] ) : '';

						// Desktop
						$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb__row:first-child .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight { border-top-color: ' . $highlight_border_color . '; }';
						$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb__row:last-child .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight { border-bottom-color: ' . $highlight_border_color . '; }';
						$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight:not(.aawp-tb__data--type-thumb) { background-color: ' . $highlight_bg_color . '; }';
						$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight { border-right-color: ' . $highlight_border_color . '; }';
						$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight::after { border-color: ' . $highlight_border_color . '; }';

						if ( ! empty( $highlight_text ) ) {
							$styles .= $css_prefix . '.aawp-tb--desktop .aawp-tb-product-' . $table_product_id . '.aawp-tb__data--highlight .aawp-tb-ribbon { background-color: ' . $highlight_border_color . '; }';
						}

						// Mobile
						$styles .= $css_prefix . '.aawp-tb--mobile .aawp-tb-product-' . $table_product_id . '.aawp-tb__product--highlight { border-color: ' . $highlight_border_color . '; }';
						// $styles .= $css_prefix . '.aawp-tb--mobile .aawp-tb-product-' . $table_product_id . '.aawp-tb__product--highlight .aawp-tb__row { background-color: ' . $highlight_bg_color . '; }';

						if ( ! empty( $highlight_text ) ) {
							$styles .= $css_prefix . '.aawp-tb--mobile .aawp-tb-product-' . $table_product_id . '.aawp-tb__product--highlight .aawp-tb-ribbon { background-color: ' . $highlight_border_color . '; }';
						}
					}//end if
				}//end if
			}//end foreach
		}//end if
	}//end foreach

	return $styles;
}
add_filter( 'aawp_overwrite_styles', 'aawp_the_table_custom_styles' );
