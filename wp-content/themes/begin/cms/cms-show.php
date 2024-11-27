<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'products_on' ) ) { ?>
	<div class="line-four line-four-video-item sort betip">
		<?php if ( be_get_option( 'products_id') ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}
				$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
				$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', be_get_option( 'products_id' ) ) ) );
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
							'posts_per_page' => be_get_option( 'products_n' ),
							'post__not_in'   => $exclude_posts,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'ignore_sticky_posts' => 1,
 						);
					$be_query = new WP_Query( $args );
				?>

					<?php if ( $be_query->have_posts() ) { ?>
						<div class="cms-picture-box">
							<h3 class="cms-picture-cat-title"><a href="<?php echo get_category_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $tax_term->name; ?></a></h3>

							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

							<div class="xl4 xm<?php echo be_get_option( 'cms_products_fl' ); ?>" <?php aos_a(); ?>>
								<div class="boxs1">
									<div class="picture-cms ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
										<figure class="picture-cms-img">
											<?php echo img_thumbnail(); ?>
											<span class="show-t"></span>
										</figure>
										<?php the_title( sprintf( '<h2 class="picture-s-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 项目模块', $number = 'products_on_s' ); ?>
	</div>
<?php } ?>