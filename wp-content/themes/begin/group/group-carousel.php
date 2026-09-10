<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_carousel' ) ) { ?>
<div id="section-gtg" class="g-row" style="background: url('<?php echo co_get_option( 'carousel_bg_img' ); ?>') no-repeat fixed center / cover;">
	<div class="g-col">
		<div class="hot-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option( 'group_carousel_t' ) == '' ) { ?>
					<h3><?php echo co_get_option( 'group_carousel_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('carousel_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option( 'carousel_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div id="slider-hot" class="owl-carousel slider-hot slider-current">
				<?php 
					if ( co_get_option( 'group_gallery' ) ) {
						$args = new WP_Query(
							array(
								'post_type' => 'picture',
								'showposts' => co_get_option( 'carousel_n' ), 
							)
						);

						if ( co_get_option( 'group_gallery_id' ) ) {
							$args = new WP_Query(
								array(
									'showposts' => co_get_option( 'carousel_n' ), 
									'tax_query' => array(
										array(
											'taxonomy' => 'gallery',
											'terms' => explode( ',',co_get_option( 'group_gallery_id' ) )
										),
									)
								)
							);
						}
					} else {
						$args = new WP_Query( array( 'cat' => co_get_option( 'group_carousel_id' ), 'posts_per_page' => co_get_option( 'carousel_n' ), 'post__not_in' => get_option( 'sticky_posts' ), 'post__not_in' => $do_not_cat ) );
					}
				?>
				<?php while ( $args->have_posts() ) : $args->the_post(); ?>

				<div id="post-<?php the_ID(); ?>" class="post-item-list post tup">
					<div class="scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<?php 
					if ( co_get_option( 'group_carousel_c' ) ) {
						$title = ' carousel-title-c';
					} else {
						$title = '';
					}
					?>
					<?php
						if ( co_get_option( 'group_carousel_c' ) ) {
							$title = ' carousel-title-c';
						} else {
							$title = '';
						}
						the_title( sprintf( '<h2 class="carousel-title over' . $title . '"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<div class="slider-rolling-lazy ajax-owl-loading srfl-5">
				<?php while ( $args->have_posts() ) : $args->the_post(); ?>
				<div id="post-<?php the_ID(); ?>" class="post-item-list post">
					<div class="scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
					<div class="clear"></div>
					<h2 class="carousel-title over"><a href="#" rel="bookmark"><?php _e( '加载中...', 'begin' ); ?></a></h2>
				</div>
				<?php break; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

		</div>
		<?php co_help( $text = '公司主页 → 热门推荐', $number = 'group_carousel_s' ); ?>
	</div>
</div>
<?php } ?>