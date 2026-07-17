<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 分类组合
 */
get_header(); ?>
<section id="primary-portfolio" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php if ( zm_get_option( 'cat_cover' ) ) { ?>
			<div class="cat-portfolio-box">
				<?php
					$terms = get_terms( 
						array(
							'taxonomy'   => 'category',
							'hide_empty' => false,
							'child_of'   => get_query_var( 'cat' )
						)
					);

					foreach ( $terms as $term ) {
					$term_data = get_term( $term->term_id, 'category' );
				?>

					<div class="cms-cat-main ms">
						<h3 class="cat-square-title">
							<a href="<?php echo get_category_link( $term->term_id ); ?>" rel="bookmark" <?php echo goal(); ?>>
								<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
									<?php if ( get_option( 'zm_taxonomy_icon' . $term->term_id ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
									<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $term->term_id ); ?>"></use></svg><?php } ?>
									<?php if ( ! get_option( 'zm_taxonomy_icon' . $term->term_id ) && ! get_option( 'zm_taxonomy_svg'.$term->term_id ) ) { ?><?php title_i(); ?><?php } ?>
								<?php } else { ?>
									<?php title_i(); ?>
								<?php } ?>
								<?php echo get_cat_name( $term->term_id ); ?><span class="cat-portfolio-count post-count"><?php echo $term_data->count; ?><?php _e( '篇', 'begin' ); ?></span>
								<?php more_i(); ?>
							</a>
						</h3>

						<div class="clear"></div>
						<div class="cat-portfolio-main">
							<div class="cat-portfolio-area">
								<div class="cat-portfolio-img">
									<?php
										$terms = get_posts( array(
											'posts_per_page' => '4',
											'post_status'    => 'publish',
											'category__in'   => $term->term_id,
										) );
									?>
									<?php foreach ( $terms as $post ) : setup_postdata( $post ); ?>
									<div id="post-<?php the_ID(); ?>" class="cat-portfolio-item-img" <?php aos_a(); ?>>
										<figure class="thumbnail">
											<?php echo zm_thumbnail(); ?>
										</figure>
												<div class="clear"></div>
										<?php the_title( sprintf( '<h2 class="portfolio-img-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									</div>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>

							<div class="cat-portfolio-area">
								<div class="cat-portfolio-list lic">
									<?php
										$terms = get_posts( array(
											'posts_per_page' => '10',
											'offset'         => '4',
											'post_status'    => 'publish',
											'category__in'   => $term->term_id,
										) );
										$s=0
									?>
									<?php foreach ( $terms as $post ) : setup_postdata( $post ); $s++; ?>
									<ul id="post-<?php the_ID(); ?>" class="post-item-list" <?php aos_a(); ?>>
										<?php the_title( sprintf( '<li class="portfolio-list-title high-'. mt_rand(1, $s) .'"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
									</ul>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
									<div class="clear"></div>
								</div>
							</div>

							<div class="cat-portfolio-area">
								<div class="cat-portfolio-card">
									<?php
										$terms = get_posts( array(
											'posts_per_page' => '3',
											'orderby'        => 'date',
											'order'          => 'ASC',
											'post_status'    => 'publish',
											'category__in'   => $term->term_id,
										) );
									?>
									<?php foreach ( $terms as $post ) : setup_postdata( $post ); ?>
									<div id="post-<?php the_ID(); ?>" class="portfolio-card-item" <?php aos_a(); ?>>
										<figure class="thumbnail">
											<?php echo zm_thumbnail(); ?>
										</figure>
										<div class="portfolio-card-content">
											<?php the_title( sprintf( '<h2 class="portfolio-card-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											<span class="entry-meta">
												<?php begin_grid_meta(); ?>
											</span>
											<div class="clear"></div>
										</div>
									</div>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>