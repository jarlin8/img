<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'picture_box' ) ) { ?>
	<div class="line-four line-four-picture-item betip">
		<?php if ( be_get_option( 'img_id' ) ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}

				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'img_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				if ( $tax_terms ) {
					foreach ( $tax_terms as $tax_term ) {
						$args = array(
							'post_type' => 'any',
							'tax_query' => array(
								array(
									'taxonomy' => $tax_term->taxonomy,
									'field'    => 'term_id',
									'terms'    => $tax_term->term_id,
									'include_children' => $cat,
								),
							),

							'post_status'    => 'publish',
							'posts_per_page' => be_get_option( 'picture_n' ),
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
								<div id="post-<?php the_ID(); ?>" class="xl4 xm<?php echo be_get_option( 'cms_picture_fl' ); ?>">
									<?php if ( $tax_term->taxonomy === 'taobao' ) { ?>
										<?php get_template_part( '/template/tao-home' ); ?>
									<?php } else { ?>
										<div class="boxs1">
											<div class="picture-cms ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?> <?php aos_a(); ?>>
												<figure class="picture-cms-img">
													<?php echo img_thumbnail(); ?>
												</figure>
												<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											</div>
										</div>
									<?php } ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<div class="clear"></div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 图片模块', $number = 'picture_s' ); ?>
	</div>
<?php } ?>