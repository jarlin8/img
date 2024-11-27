<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_carousel')) { ?>
<div id="section-gtg" class="g-row sort" name="<?php echo zm_get_option('group_carousel_s'); ?>" style="background: url('<?php echo zm_get_option('carousel_bg_img'); ?>') no-repeat fixed center / cover;">
	<div class="g-col bgt">
		<div class="hot-box bgt">
			<div class="group-title bgt" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option('group_carousel_t') == '' ) { ?>
					<h3 class="bgt"><?php echo zm_get_option('group_carousel_t'); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('carousel_des') == '' ) { ?>
					<div class="group-des bgt"><?php echo zm_get_option('carousel_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div id="slider-hot" class="owl-carousel slider-hot slider-current bgt">
				<?php 
					if (zm_get_option('group_gallery')) {
						$args = new WP_Query(
							array(
								'post_type' => 'picture',
								'showposts' => zm_get_option('carousel_n'), 
							)
						);

						if (zm_get_option('group_gallery_id')) {
							$args = new WP_Query(
								array(
									'showposts' => zm_get_option('carousel_n'), 
									'tax_query' => array(
										array(
											'taxonomy' => 'gallery',
											'terms' => explode(',',zm_get_option('group_gallery_id') )
										),
									)
								)
							);
						}
					} else {
						$args = new WP_Query( array( 'cat' => zm_get_option('group_carousel_id'), 'posts_per_page' => zm_get_option('carousel_n'), 'post__not_in' => get_option( 'sticky_posts'), 'post__not_in' => $do_not_cat ) );
					}
					while ($args->have_posts()) : $args->the_post();
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('post bk yy'); ?>>
					<div class="scrolling-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<?php 
					if ( zm_get_option( 'group_carousel_c' ) ) {
						$title = ' carousel-title-c';
					} else {
						$title = '';
					}
					?>
					<?php
						if ( zm_get_option( 'group_carousel_c' ) ) {
							$title = ' carousel-title-c';
						} else {
							$title = '';
						}
						the_title( sprintf( '<h2 class="carousel-title bgt over' . $title . '"><a class="bgt" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>