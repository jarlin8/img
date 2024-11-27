<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 会员商品
function module_assets( $atts ) {
	$atts = shortcode_atts( array(
		'id'     => 1,
		'number' => 3,
		'col'    => 3,
	), $atts );

	$cat_id = sanitize_text_field( $atts['id'] );
	$post_n = intval( $atts['number'] );
	$col     = intval( $atts['col'] );
	ob_start();
	echo '<div class="module-area cms-assets-' .$col . '">';
		$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;

		$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
		$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => array_map( 'intval', explode( ',', $cat_id ) ) ) );
		if ( $tax_terms ) {
			foreach ( $tax_terms as $tax_term ) {
				$args = array(
					'post_type' => array( 'post', 'bulletin', 'picture', 'video', 'tao', 'sites', 'show' ),
					'tax_query' => array(
						array(
							'taxonomy' => $tax_term->taxonomy,
							'field'    => 'term_id',
							'terms'    => $tax_term->term_id,
							'include_children' => $cat,
						),
					),

					'post_status'    => 'publish',
					'posts_per_page' => $post_n,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'ignore_sticky_posts' => true,
				);
				$be_query = new WP_Query( $args );
			
				if ( $be_query->have_posts() ) {
					echo '<div class="module-area flexbox-grid">';
						echo '<h3 class="cms-picture-cat-title"><a href="' . get_category_link( $tax_term ) . '">' . $tax_term->name . '</a></h3>';
						$img = get_posts( array(
							'posts_per_page' => $post_n,
							'post_status'    => 'publish',
							'orderby'        => 'date',
							'order'          => 'DESC', 
						) );

						while ( $be_query->have_posts() ) : $be_query->the_post();
						require get_template_directory() . '/template/assets.php';
						endwhile;
						wp_reset_postdata();
					echo '</div>';
				}
				echo '<div class="clear"></div>';
			}
		}
	echo '</div>';
	return ob_get_clean();
}
// [assets id=1 number=4 col=4]
add_shortcode( 'assets', 'module_assets' );