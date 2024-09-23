<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 分类组合
function module_portfolio( $atts ) {
	$atts = shortcode_atts( array(
		'id'  => 1,
	), $atts );
	$cat_id = sanitize_text_field( $atts['id'] );
	ob_start();
	?>

	<div class="module-area cat-portfolio" <?php aos_a(); ?>>
		<?php $becat = array_map( 'intval', explode( ',', $cat_id ) ); foreach ( $becat as $category ) {
			$cat = ( be_get_option( 'no_cat_child' ) ) ? 'category' : 'category__in';
		?>
		<div class="cms-cat-main tra notext ms">
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
			<div class="cat-portfolio-main">
				<div class="cat-portfolio-area">
					<div class="cat-portfolio-img">
						<?php 
							$args = array(
								'post_type'      => 'post',
								'posts_per_page' => 4,
								'post_status'    => 'publish',
								'cat'            => $category,
							);
							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
							<div id="post-<?php the_ID(); ?>" class="cat-portfolio-item-img" <?php aos_a(); ?>>
								<figure class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</figure>
								<div class="clear"></div>
								<?php the_title( sprintf( '<h2 class="portfolio-img-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							</div>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>

				<div class="cat-portfolio-area">
					<ul class="cat-portfolio-list lic">
						<?php 
							$args = array(
								'post_type'      => 'post',
								'posts_per_page' => 10,
								'offset'         => 4,
								'post_status'    => 'publish',
								'cat'            => $category,
							);
							$s = 0;
							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); $s++; ?>
							<li id="post-<?php the_ID(); ?>" class="portfolio-list-title high-<?php echo mt_rand(1, $s); ?>" <?php aos_a(); ?>>
								<?php the_title( sprintf( '<a class="srm" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							</li>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>
					</ul>
				</div>

				<div class="cat-portfolio-area">
					<div class="cat-portfolio-card">
						<?php 
							$args = array(
								'post_type'      => 'post',
								'posts_per_page' => '3',
								'orderby'        => 'date',
								'order'          => 'ASC',
								'post_status'    => 'publish',
								'cat'            => $category,
							);
							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
							<div id="post-<?php the_ID(); ?>" class="portfolio-card-item" <?php aos_a(); ?>>
								<figure class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</figure>
								<div class="portfolio-card-content">
									<?php the_title( sprintf( '<h2 class="portfolio-card-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<span class="entry-meta">
										<?php begin_grid_meta(); ?>
									</span>
									<div class="clear"></div>
								</div>
							</div>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php
	return ob_get_clean();
}
// [portfolio id=1]
add_shortcode( 'portfolio', 'module_portfolio' );