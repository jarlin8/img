<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cat_lead' ) ) { ?>
	<div class="cms-cat-lead betip">
		<?php if ( be_get_option( 'cat_lead_id' ) ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}
				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'cat_lead_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				if ( $tax_terms ) {
					foreach ( $tax_terms as $tax_term ) { ?>

						<div class="cat-container ms" <?php aos_a(); ?>>
							<h3 class="cat-title">
								<a href="<?php echo get_term_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
										<?php if ( get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $tax_term->term_id ); ?>"></i><?php } ?>
										<?php if ( get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $tax_term->term_id ); ?>"></use></svg><?php } ?>
										<?php if ( ! get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) && ! get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) { ?><?php title_i(); ?><?php } ?>
									<?php } else { ?>
										<?php title_i(); ?>
									<?php } ?>
									<?php echo $tax_term->name; ?>
									<?php more_i(); ?>
								</a>
							</h3>
							<div class="clear"></div>

							<?php
								$img = array(
									'post_type' => 'any',
									'tax_query' => array(
										array(
											'taxonomy'         => $tax_term->taxonomy,
											'field'            => 'term_id',
											'terms'            => $tax_term->term_id,
											'include_children' => $cat,
										),
									),

									'post_status'         => 'publish',
									'posts_per_page'      => 1,
									'post__not_in'        => $exclude_posts,
									'orderby'             => 'date',
									'order'               => 'DESC',
									'ignore_sticky_posts' => 1,
								);

								$query = new WP_Query( $img );
							?>

							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
								<article id="post-<?php the_ID(); ?>" class="post-item-list post cms-cat-lead-post post-default doclose" <?php aos_a(); ?>>
									<figure class="thumbnail">
										<?php echo zm_thumbnail(); ?>
									</figure>
									<div class="post-area">
										<?php header_title(); ?>
											<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										</header>

										<div class="entry-content">
											<div class="archive-content">
												<?php begin_trim_words(); ?>
											</div>
											<div class="clear"></div>
											<span class="entry-meta lbm">
												<?php begin_entry_meta(); ?>
											</span>
										</div>
									</div>
								</article>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>

						<div class="clear"></div>

					<div class="cms-news-grid-container" <?php aos_a(); ?>>
						<?php
							$args = array(
								'post_type' => 'any',
								'tax_query' => array(
									array(
										'taxonomy'         => $tax_term->taxonomy,
										'field'            => 'term_id',
										'terms'            => $tax_term->term_id,
										'include_children' => $cat,
									),
								),

								'post_status'         => 'publish',
								'posts_per_page'      => be_get_option( 'cat_lead_n' ),
								'post__not_in'        => $exclude_posts,
									'offset'          => 1,
								'orderby'             => 'date',
								'order'               => 'DESC',
								'ignore_sticky_posts' => 1,
							);

							$query = new WP_Query( $args );
						?>

						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<article id="post-<?php the_ID(); ?>" class="post-item-list post ms glx">
								<figure class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</figure>

								<header class="entry-header">
									<h2 class="entry-title over"><a href="<?php echo get_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo t_mark(); ?><?php the_title(); ?></a></h2>
								</header>

								<div class="entry-content">
									<span class="entry-meta lbm">
										<?php begin_entry_meta(); ?>
									</span>
									<div class="clear"></div>
								</div>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<div class="cms-news-grid-container" style="border-radius: 8px;" <?php aos_a(); ?>>
				<div class="cat-container ms" style="border-radius: 8px;">
					<h3 class="cat-title">
						<?php title_i(); ?>未输入分类ID
					</h3>

					<div class="post-item-list ms glx" style="padding: 15px;">
						<div class="entry-title over">首页设置 → 杂志布局 → 混排分类列表</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 混排分类列表', $number = 'cat_lead_s' ); ?>
	</div>
<?php } ?>