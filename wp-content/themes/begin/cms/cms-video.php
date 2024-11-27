<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'video_box' ) ) { ?>
	<div class="line-four line-four-video-item sort betip">
		<?php if ( be_get_option( 'video_post_id') ) { ?>
			<?php
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}

				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'video_post_id' ) ),
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
								),
							),

							'post_status'    => 'publish',
							'posts_per_page' => be_get_option( 'video_n' ),
							'post__not_in'   => $exclude_posts,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'ignore_sticky_posts' => 1,
 						);

					$be_query = new WP_Query( $args );
					if ( $be_query->have_posts() ) { ?>
						<div class="cms-picture-box">
							<h3 class="cms-picture-cat-title"><a href="<?php echo get_category_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $tax_term->name; ?></a></h3>

							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

							<div class="xl4 xm<?php echo be_get_option( 'cms_video_fl' ); ?>" <?php aos_a(); ?>>
								<div class="boxs1">
									<div class="picture-cms ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
										<figure class="picture-cms-img">
											<?php if ( get_post_type() == 'video' ) { ?>
												<?php echo videos_thumbnail(); ?>
											<?php } else { ?>
												<?php echo img_thumbnail(); ?>
											<?php } ?>
												<i class="be be-play"></i>
										</figure>
										<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
		<?php cms_help( $text = '首页设置 → 杂志布局 → 视频模块', $number = 'video_s' ); ?>
	</div>
<?php } ?>