<?php 
// 图片新闻
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cms_cat_img' ) ) { ?>
	<div class="cms-cat-img betip" <?php aos_a(); ?>>
		<?php if ( be_get_option( 'cms_cat_img_id' ) ) { ?>
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
					'include' => explode( ',', be_get_option( 'cms_cat_img_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				foreach ( $tax_terms as $tax_term ) { ?>

					<div class="cms-cat-main ms">
						<h3 class="cat-square-title">
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

						<div class="news-cat-img-area">
							<div class="news-cat-img-sole">
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
										'posts_per_page'      => 2,
										'post__not_in'        => $exclude_posts,
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $args );
								?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<article id="post-<?php the_ID(); ?>" class="post-item-list post" <?php aos_a(); ?>>
										<figure class="thumbnail thumbnail-sole">
											<?php echo zm_thumbnail(); ?>
										</figure>
									</article>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
								<div class="clear"></div>
							</div>

							<div class="news-cat-img-item">
								<?php
									$offset = ! wp_is_mobile() ? 2 : 0;
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
										'posts_per_page'      => 16,
										'offset'              => $offset,
										'post__not_in'        => $exclude_posts,
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $args );
								?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<article id="post-<?php the_ID(); ?>" class="post-item-list post" <?php aos_a(); ?>>
										<figure class="thumbnail thumbnail">
											<?php echo zm_thumbnail(); ?>
										</figure>
									</article>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
								<div class="clear"></div>
							</div>
						</div>
					</div>

				<?php } ?>

			<?php } else { ?>
				<div class="cms-cat-grid cms-cat-grid-item betip" <?php aos_a(); ?>>
					<div class="cms-cat-main tra ms">
						<h3 class="cat-grid-title"><?php title_i(); ?>未输入分类ID</h3>
						<article class="post-item-list" style="padding: 15px;">
							首页设置 → 杂志布局 → 图片新闻
						</article>
					</div>
					<div class="clear"></div>
				</div>
			<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 图片新闻', $number = 'cms_cat_img_s' ); ?>
	</div>
<?php } ?>