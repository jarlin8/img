<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 图片模块
function module_picture( $atts ) {
	extract( shortcode_atts( array(
		'number' => 4,
		'col'    => 4,
		'id'     => 1,
	),
	$atts ) );
	ob_start();
	?>
	<div class="module-area line-four line-four-picture-item">
		<?php
			$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
			$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
			$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', $id ) ) );
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
						'posts_per_page' => $number,
						'orderby'        => 'date',
						'order'          => 'DESC',
						'ignore_sticky_posts' => 1,
 					);
				$be_query = new WP_Query( $args );
			?>

				<?php if ( $be_query->have_posts() ) { ?>
					<div class="cms-picture-box">
						<h3 class="cms-picture-cat-title"><a href="<?php echo get_category_link( $tax_term ); ?>"><?php echo $tax_term->name; ?></a></h3>

						<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

						<div id="post-<?php the_ID(); ?>" class="xl4 xm<?php echo $col; ?>">
							<div class="boxs1">
								<div class="picture-cms ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?> <?php aos_a(); ?>>
									<figure class="picture-cms-img">
										<?php echo img_thumbnail(); ?>
									</figure>
									<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								</div>
							</div>
						</div>
						<?php endwhile; wp_reset_postdata(); ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<div class="clear"></div>
	</div>

	<?php
	return ob_get_clean();
}
// [picture id=1 number=4 col=4]
add_shortcode( 'picture', 'module_picture' );