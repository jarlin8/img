<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 热门推荐
function module_carousel( $atts ) {
	extract( shortcode_atts( array(
		'id'     => 1,
		'number' => '',
		'img'    => '',
		'title'  => '',
		'des'    => '',
	),
	$atts ) );

	ob_start();
	?>

	<div id="section-gtg" class="g-row"<?php if ( $img ) { ?> style="background: url('<?php echo $img; ?>') no-repeat fixed center / cover;"<?php } ?>>
		<div class="hot-box build-item">
			<?php if ( $title ) { ?>
				<div class="group-title" <?php aos_b(); ?>>
					<h3><?php echo $title; ?></h3>

					<?php if ( $des ) { ?>
						<div class="group-des"><?php echo $des; ?></div>
					<?php } ?>
					<div class="clear"></div>
				</div>
			<?php } ?>
			<div id="slider-hot" class="owl-carousel slider-hot slider-current">
				<?php 
					$args = new WP_Query( array(
					'cat' => $id, 
					'posts_per_page' => $number, 
					'post__not_in' => get_option( 'sticky_posts' )) );
				?>
				<?php while ( $args->have_posts()) : $args->the_post(); ?>
					<div id="post-<?php the_ID(); ?>" class="post-item-list post">
						<div class="scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
						<div class="clear"></div>
						<?php the_title( sprintf( '<h2 class="carousel-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
	</div>
	<?php
	return ob_get_clean();
}
// [carousel id="1" number="8" title="标题" des="说明" img="图片"]
add_shortcode( 'carousel', 'module_carousel' );