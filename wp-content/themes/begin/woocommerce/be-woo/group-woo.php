<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'g_product' ) ) {
	if ( ! co_get_option( 'product_bg' ) || ( co_get_option( 'product_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'product_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'product_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php
	$tax = 'product_cat';
	$tax_terms = get_terms( $tax, array( 'orderby' => 'menu_order', 'order' => 'ASC', 'include' => explode( ',',co_get_option('g_product_id' ) ) ) );
	if ( $tax_terms ) { ?>
		<?php foreach ( $tax_terms as $tax_term ) { ?>
			<?php
				$args = array(
					'post_type'        => 'product',
					"$tax"             => $tax_term->slug,
					'post_status'      => 'publish',
					'posts_per_page'   => co_get_option( 'g_product_n' ),
					'caller_get_posts' => 1
				);
				$be_query = new WP_Query( $args );
			?>

			<?php if ( $be_query->have_posts() ) { ?>
				<div class="line-tao g-row woocommerce g-line<?php echo $bg; ?>" <?php aos(); ?>>
					<div class="g-col">
						<div class="cms-picture-box be-woo-box">
							<div class="group-title" <?php aos_b(); ?>>
								<h3><a href="<?php echo get_category_link( $tax_term ); ?>"><?php echo $tax_term->name; ?></a></h3>
								<div class="group-des"><?php echo $tax_term->description; ?></div>
								<div class="clear"></div>
							</div>
							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
								<div class="xl4 xm<?php echo co_get_option('group_woo_f'); ?>" <?php aos_b(); ?>>
									<div class="tao-h sup bk">
										<figure class="tao-h-img">
											<?php echo tao_thumbnail(); ?>
											<?php
												global $post, $product;
												if ( $product->is_on_sale() ) :
												echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( '促销中', 'begin' ) . '</span>', $post, $product );
												endif;
											?>
										</figure>
										<div class="product-box">
											<?php the_title( sprintf( '<h2 class="product-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											<?php global $product; if ( $price_html = $product->get_price_html() ) : ?>
												<span class="price"><?php echo $price_html; ?></span>
											<?php endif; ?>
											<!-- <div class="woo-url"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( '详情', 'begin' ); ?></a></div> -->
											<div class="clear"></div>
										</div>
									</div>
								</div>
								
							<?php endwhile;wp_reset_query(); ?>
							<div class="clear"></div>
						</div>
						<div class="group-post-more da">
							<a href="<?php echo get_term_link($tax_term); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="external nofollow"><i class="be be-more"></i></a>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>