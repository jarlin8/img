<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 分类图片
function module_square( $atts ) {
	$atts = shortcode_atts( array(
		'id'     => 1,
		'number' => 5,
	), $atts );
	$id = sanitize_text_field( $atts['id'] );
	$number = intval( $atts['number'] );
	ob_start();
	?>
	<div class="cms-cat-square" <?php aos_a(); ?>>
			<?php $becat = array_map( 'intval', explode( ',', $id ) ); foreach ( $becat as $category ) {
				$cat = ( be_get_option( 'no_cat_child' ) ) ? 'category' : 'category__in';
			?>
		<div class="cms-cat-main tra ms">
			<h3 class="cat-square-title">
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
			<div class="cat-g5">
				<?php
				$args = array(
					'post_type'      => 'post',
					'posts_per_page' => $number,
					'post_status'    => 'publish',
					'cat'            => $category,
				);
				$query = new WP_Query( $args );
				?>
				<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
					<article id="post-<?php the_ID(); ?>" class="post-item-list post gls" <?php aos_a(); ?>>
						<figure class="thumbnail">
							<?php echo zm_thumbnail(); ?>
						</figure>
						<header class="entry-header entry-header-square">
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						</header>
					</article>
				<?php endwhile; endif; ?>
				<?php wp_reset_postdata(); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php
	return ob_get_clean();
}
// [square id="分类ID" number="篇数"]
add_shortcode( 'square', 'module_square' );