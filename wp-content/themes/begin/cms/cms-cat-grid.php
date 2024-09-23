<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cat_grid' ) ) { ?>
	<?php if ( be_get_option( 'cat_grid_id' ) ) { ?>
		<?php
			$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
			if ( be_get_option( 'no_cat_top' ) ) {
				$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
				$exclude_posts = array_merge( $do_not_duplicate, $top_id );
			} else {
				$exclude_posts = '';
			}
			$tax = get_taxonomies();
			$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', be_get_option( 'cat_grid_id' ) ) ) );

			if ( $tax_terms ) {
				foreach ( $tax_terms as $tax_term ) {
					$args = array(
						'post_type' => 'any',
						'tax_query' => array(
							array(
								'taxonomy' => $tax_term->taxonomy,
								'field'    => 'term_id',
								'terms'    => $tax_term->term_id,
								'include_children' => $cat,
							),
						),

						'post_status'    => 'publish',
						'posts_per_page' => be_get_option( 'cat_grid_n' ),
						'post__not_in'   => $exclude_posts,
						'orderby'        => 'date',
						'order'          => 'DESC',
						'ignore_sticky_posts' => 1,
 					);
				$be_query = new WP_Query( $args );

				if ( $be_query->have_posts() ) { ?>

					<div class="cms-cat-grid cms-cat-grid-item betip">

						<div class="cms-cat-main tra ms">
							<h3 class="cat-grid-title">
								<a href="<?php echo get_category_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
										<?php if ( get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $tax_term->term_id ); ?>"></i><?php } ?>
										<?php if ( get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $tax_term->term_id ); ?>"></use></svg><?php } ?>
										<?php if ( ! get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) && ! get_option( 'zm_taxonomy_svg'.$tax_term->term_id ) ) { ?><?php title_i(); ?><?php } ?>
									<?php } else { ?>
										<?php title_i(); ?>
									<?php } ?>
									<?php echo $tax_term->name; ?>
									<?php more_i(); ?>
								</a>
							</h3>

							<div class="clear"></div>
							<div class="cat-g3">
								<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

								<article id="post-<?php the_ID(); ?>" class="post-item-list post glg" <?php aos_a(); ?>>
									<figure class="thumbnail">
										<?php echo zm_thumbnail(); ?>
									</figure>

									<header class="entry-header">
										<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									</header>

									<div class="entry-content">
										<span class="entry-meta">
											<?php begin_grid_meta(); ?>
										</span>
										<div class="clear"></div>
									</div>

								</article>

								<?php endwhile; wp_reset_postdata(); ?>

								<div class="clear"></div>
							</div>
						</div>

						<?php cms_help( $text = '首页设置 → 杂志布局 → 分类网格', $number = 'cat_grid_s' ); ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>
		<div class="cms-cat-grid cms-cat-grid-item betip" <?php aos_a(); ?>>
			<div class="cms-cat-main tra ms">
				<h3 class="cat-grid-title"><?php title_i(); ?>未输入分类ID</h3>
				<article class="post-item-list post">
					首页设置 → 杂志布局 → 分类网格
				</article>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
<?php } ?>