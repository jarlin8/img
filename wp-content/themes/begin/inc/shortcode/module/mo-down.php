<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 下载模块
function module_down( $atts ) {
	$atts = shortcode_atts( array(
		'id'     => 1,
		'number' => 6,
		'col'    => 3,
		'btn'    => '查看',
	), $atts );

	$cat_id   = sanitize_text_field( $atts['id'] );
	$post_n   = intval( $atts['number'] );
	$col       = intval( $atts['col'] );
	$btntext  = $atts['btn'];
	ob_start();
	?>

	<div class="module-area down-card-<?php echo $col; ?>">
		<?php $becat = array_map( 'intval', explode( ',', $cat_id ) ); foreach ( $becat as $category ) {
			$cat = ( be_get_option( 'no_cat_child' ) ) ? 'category' : 'category__in';
		?>
			<div class="flexbox-card">
				<?php
					$args = array(
						'posts_per_page' => $post_n,
						'post_status'    => 'publish',
						$cat             => $category, 
						'orderby'        => 'date',
						'order'          => 'DESC', 
					);
					$query = new WP_Query( $args );
					if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
						require get_template_directory() . '/template/down-card.php';
					endwhile; endif;
					wp_reset_postdata();
				?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</div>

	<?php
	return ob_get_clean();
}
// [catdown id=1 number=4 col=4 btn=按钮文字]
add_shortcode( 'catdown', 'module_down' );

function module_down_post( $atts ) {
	$atts = shortcode_atts( array(
		'id'  => 1,
		'col' => 3,
		'btn' => '查看',
	), $atts );

	$post_id = sanitize_text_field( $atts['id'] );
	$col     = intval( $atts['col'] );
	$btntext  = $atts['btn'];
	ob_start();
	?>
	<div class="module-area down-card-<?php echo $col; ?>">
		<?php
			$args = array(
				'post__in'  => array_map( 'intval', explode( ',', $post_id ) ),
				'orderby'   => 'post__in', 
				'order'     => 'DESC',
				'ignore_sticky_posts' => true,
				);
			$query = new WP_Query( $args );
		?>
		<div class="flexbox-card">
			<?php
				if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
					require get_template_directory() . '/template/down-card.php';
				endwhile; endif;
				wp_reset_postdata();
			?>
			<div class="clear"></div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
// [postdown id=文章ID col=4 btn=按钮文字]
add_shortcode( 'postdown', 'module_down_post' );