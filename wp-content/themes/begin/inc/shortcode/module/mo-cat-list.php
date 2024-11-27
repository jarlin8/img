<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 分类列表
function module_cat_small( $atts ) {
	$atts = shortcode_atts( array(
		'id'     => 1,
		'number' => 5,
	), $atts );
	$id = sanitize_text_field( $atts['id'] );
	$number = intval( $atts['number'] );
	ob_start();
	?>
	<div class="module-area line-small">
		<?php $becat = array_map( 'intval', explode( ',', $id ) ); foreach ( $becat as $category ) {
			$cat = ( be_get_option( 'no_cat_child' ) ) ? 'category' : 'category__in';
		?>
		<div class="xl2 xm2">
			<div class="cat-container ms<?php if ( be_get_option( 'cat_small_z' ) ) { ?> cms-cat-txt<?php } ?>" <?php aos_a(); ?>>
				<h3 class="cat-title">
					<a href="<?php echo get_category_link( $category ); ?>">
						<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
							<?php if ( get_option( 'zm_taxonomy_icon' . $category ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $category ); ?>"></i><?php } ?>
							<?php if ( get_option( 'zm_taxonomy_svg' . $category ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $category ); ?>"></use></svg><?php } ?>
							<?php if ( ! get_option( 'zm_taxonomy_icon' . $category ) && ! get_option( 'zm_taxonomy_svg'.$category ) ) { ?><?php title_i(); ?><?php } ?>
						<?php } else { ?>
							<?php title_i(); ?>
						<?php } ?>
						<?php echo get_cat_name( $category ); ?>
						<?php more_i(); ?>
					</a>
				</h3>
				<div class="clear"></div>
				<div class="cms-cat-area">
					<?php 
						$args = array(
							'post_type'      => 'post',
							'posts_per_page' => 1,
							'post_status'    => 'publish',
							'cat'            => $category,
						);
						$query = new WP_Query( $args );
					?>
					<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
						<figure class="small-thumbnail">
							<?php echo zm_long_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h2 class="entry-small-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<?php endwhile; endif; ?>
					<?php wp_reset_postdata(); ?>

					<div class="clear"></div>
					<ul class="cat-list">
						<?php
							$args = array(
								'post_type'      => 'post',
								'offset'         => 1,
								'posts_per_page' => $number,
								'post_status'    => 'publish',
								'cat'            => $category,
							);
							$query = new WP_Query( $args );
							if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
							list_date();
							the_title( sprintf( '<li class="list-title' . date_class() . '"><h2 class="cms-list-title"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' );
							endwhile; endif;
							wp_reset_postdata();
						?>
					</ul>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<?php
	return ob_get_clean();
}
// [catlist id="分类ID" number="篇数"]
add_shortcode( 'catlist', 'module_cat_small' );