<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'flexisel' ) ) { ?>
	<?php 
		if (!zm_get_option('flexisel_m') || (zm_get_option('flexisel_m') == 'flexisel_cat')) {
			$args = array( 'cat' => zm_get_option( 'flexisel_cat_id' ), 'posts_per_page' => zm_get_option( 'flexisel_n' ), 'post__not_in' => get_option( 'sticky_posts' ), 'post__not_in' => $do_not_duplicate );
		}

		if (zm_get_option('flexisel_m') == 'flexisel_img') {
			$args = array(
				'post_type' => 'picture',
				'showposts' => zm_get_option( 'flexisel_n' ), 
			);

			if ( zm_get_option( 'gallery_id' ) ) {
				$args = array(
					'showposts' => zm_get_option( 'flexisel_n' ), 
					'tax_query' => array(
						array(
							'taxonomy' => 'gallery',
							'terms' => explode(',',zm_get_option( 'gallery_id' ) )
						),
					)
				);
			}
		}

		if (zm_get_option('flexisel_m') == 'flexisel_key') {
			$args = array(
				'meta_key' => zm_get_option( 'key_n' ), 
				'orderby' => 'meta_value',
				'order' => 'DESC',
				'posts_per_page' => zm_get_option('flexisel_n'),
				'post__not_in' => get_option( 'sticky_posts')
			);
		}

	?>
	<div class="slider-rolling-box sort ms bk" name="<?php echo zm_get_option( 'flexisel_s' ); ?>" <?php aos_a(); ?>>
		<div id="slider-rolling" class="be-rolling owl-carousel slider-rolling slider-current">
			<?php $be_query = new WP_Query( $args ); while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('scrolling-img'); ?> >
				<div class="scrolling-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
				<div class="clear"></div>
				<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<div class="clear"></div>
			</div>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
		</div>
	</div>
<?php } ?>