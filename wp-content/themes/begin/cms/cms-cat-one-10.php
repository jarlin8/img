<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (be_get_option('cat_one_10')) { ?>
	<div class="line-one line-one-10 betip">
		<?php if ( be_get_option( 'cat_one_10_id' ) ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}

				// 获取所有已注册的分类法
				$tax = get_taxonomies();

				// 将所有分类法作为参数传递给 get_terms 函数
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'cat_one_10_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

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
						<div class="cms-cat-area">
							<div class="line-one-img">
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
										'posts_per_page'      => 2,
										'post__not_in'        => $exclude_posts,
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $img );
								?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>

									<figure class="line-one-thumbnail one-img-10">
										<?php echo zm_thumbnail(); ?>
									</figure>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
								<div class="clear"></div>
							</div>
							<ul class="cat-one-list">
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
										'posts_per_page'      => 10,
										'post__not_in'        => $exclude_posts,
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $args );
								?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<?php list_date(); ?>
									<?php the_title( sprintf( '<li class="list-cat-title srm' . date_class() . '"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' ); ?>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
						<div class="clear"></div>
					</div>

				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="cat-container ms" <?php aos_a(); ?>>
				<h3 class="cat-title">
					<?php title_i(); ?>未输入分类ID
				</h3>
				<div class="clear"></div>

				<div class="cms-cat-area">
					<ul class="cat-one-list">
						<li class="list-cat-title srm">首页设置 → 杂志布局 → 单栏分类列表(10篇文章)</li>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 单栏分类列表(10篇文章)', $number = 'cat_one_10_s' ); ?>
	</div>
<?php } ?>