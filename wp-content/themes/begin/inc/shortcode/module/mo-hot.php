<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 热门文章
function module_box_hot( $atts, $content = null ) {
	extract( shortcode_atts( array(
	),
	$atts ) );
	ob_start();
	$html  = '<div class="cms-hot">';
	$html .= do_shortcode( $content );
	$html .= '</div>';
	return $html;
	return ob_get_clean();
}
// [boxhot][/boxhot]
add_shortcode( 'boxhot', 'module_box_hot' );

function module_hot( $atts ) {
	extract( shortcode_atts( array(
		'id'    => '1',
		'title' => '标题',
		'col'   => '4',
		'url'   => '#',
		'ico'   => 'be be-skyatlas',
	),
	$atts ) );
	ob_start();
	?>
	<div class="cms-hot-box hot<?php echo $col; ?>">
		<div class="boxs1">
			<div class="cms-hot-main ms betip" <?php aos_a(); ?>>
				<?php if ( ! empty( $title ) ) { ?>
					<h3 class="cms-hot-head">
						<?php if ( ! empty( $ico ) ) { ?>
							<i class="<?php echo $ico; ?>"></i>
						<?php } ?>
						<?php echo $title; ?>
						<?php if ( ! empty( $url ) ) { ?>
							<a href="<?php echo $url; ?>" target="_blank"><?php more_i(); ?></a>
						<?php } ?>
					</h3>
				<?php } ?>

				<?php
					$args = array(
						'post__in'  => explode( ',', $id ),
						'orderby'   => 'post__in',
						'order'     => 'DESC',
						'ignore_sticky_posts' => true,
						);

					$query = new WP_Query( $args );
				?>

				<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="cms-hot-item">
						<span class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</span>
						<span class="cms-hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
						<?php views_span(); ?>
						<?php grid_meta(); ?>
					</div>
					<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
// [hotpost id="96,94,60" title="标题" ico="be be-skyatlas" url="#" col="4"]
add_shortcode( 'hotpost', 'module_hot' );