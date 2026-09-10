<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cat_big_not' ) ) { ?>
	<div class="line-big line-big-n betip">
		<?php if ( be_get_option( 'cat_big_not_id' ) ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				$exclude_posts = be_get_option( 'no_cat_top' ) ? ( be_get_option( 'cms_top' ) ? array_merge( $do_not_duplicate, explode( ',', be_get_option( 'cms_top_id' ) ) ) : [] ) : '';
				// 获取所有已注册的分类法
				$tax = get_taxonomies();

				// 将所有分类法作为参数传递给 get_terms 函数
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'cat_big_not_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				foreach ( $tax_terms as $tax_term ) { ?>
					<?php if ( be_get_option( 'cat_big_not_three' ) ) { ?>
					<div class="cl3">
					<?php } else { ?>
					<div class="xl3 xm3">
					<?php } ?>

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

								<?php if ( be_get_option( 'cat_big_not_id' ) ) { ?>
									<?php echo $tax_term->name; ?>
								<?php } else { ?>
									未输入分类ID
								<?php } ?>

								<?php more_i(); ?>
							</a>
						</h3>

						<div class="clear"></div>

						<div class="cms-cat-area">
							<ul class="cat-list">
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
										'posts_per_page'      => be_get_option( 'cat_big_not_n' ),
										'post__not_in'        => $exclude_posts,
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $args );
								?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<?php list_date(); ?>
									<?php the_title( sprintf( '<li class="list-title' . date_class() . '"><h2 class="cms-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="<?php echo be_get_option( 'cat_big_not_three' ) ? 'cl3' : 'xl3 xm3'; ?>">
				<div class="cat-container ms" <?php aos_a(); ?>>
					<h3 class="cat-title">
						<?php title_i(); ?>未输入分类ID
					</h3>
					<div class="cms-cat-area">
						<ul class="cat-list">
							<li class="list-title srm">首页设置 → 杂志布局 → 底部无缩略图分类列表</li>
						</ul>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 底部无缩略图分类列表', $number = 'cat_big_not_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>