<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cat_big' ) ) { ?>
	<div class="line-big line-big-img betip">
		<?php if ( be_get_option( 'cat_big_id' ) ) { ?>
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
					'include' => explode( ',', be_get_option( 'cat_big_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				foreach ( $tax_terms as $tax_term ) { ?>
					<?php if ( be_get_option( 'cat_big_three' ) ) { ?>
						<div class="cl3">
					<?php } else { ?>
						<div class="xl3 xm3">
					<?php } ?>

					<div class="cat-container ms<?php if ( be_get_option( 'cat_big_z' ) ) { ?> cms-cat-txt<?php } ?>" <?php aos_a(); ?>>
						<h3 class="cat-title">
							<a href="<?php echo get_term_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>>

								<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
									<?php if ( get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $tax_term->term_id ); ?>"></i><?php } ?>
									<?php if ( get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $tax_term->term_id ); ?>"></use></svg><?php } ?>
									<?php if ( ! get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) && ! get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) { ?><?php title_i(); ?><?php } ?>
								<?php } else { ?>
									<?php title_i(); ?>
								<?php } ?>

								<?php if ( be_get_option( 'cat_big_id' ) ) { ?>
									<?php echo $tax_term->name; ?>
								<?php } else { ?>
									未输入分类ID
								<?php } ?>

								<?php more_i(); ?>
							</a>
						</h3>
						<div class="clear"></div>
						<div class="cms-cat-area">
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
								<?php if ( be_get_option( 'cat_big_z' ) ) { ?>
									<figure class="small-thumbnail"><?php echo zm_long_thumbnail(); ?></figure>
									<?php the_title( sprintf( '<h2 class="entry-small-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<div class="cat-img-small">
										<figure class="thumbnail"><?php echo zm_thumbnail(); ?></figure>
										<div class="cat-main">
											<?php if ( has_excerpt( '' ) ) {
													echo wp_trim_words( get_the_excerpt(), 92, '...' );
												} else {
													$content = get_the_content();
													$content = wp_strip_all_tags( str_replace( array( '[',']' ),array( '<','>' ),$content ) );
													echo wp_trim_words( $content, 95, '...' );
										        }
											?>
										</div>
									</div>
								<?php } ?>

							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>

							<div class="clear"></div>

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
										'posts_per_page'      => be_get_option( 'cat_big_n' ),
										'offset'              => 1,
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
				<?php if ( be_get_option( 'cat_big_three' ) ) { ?>
					<div class="cl3">
				<?php } else { ?>
					<div class="xl3 xm3">
				<?php } ?>
				<div class="cat-container ms<?php if ( be_get_option( 'cat_big_z' ) ) { ?> cms-cat-txt<?php } ?>" <?php aos_a(); ?>>
					<h3 class="cat-title">
						<?php title_i(); ?>未输入分类ID
					</h3>
					<div class="cms-cat-area">
						<ul class="cat-list">
							<li class="list-title srm">首页设置 → 杂志布局 → 底部分类列表</li>
						</ul>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 底部分类列表', $number = 'cat_big_s' ); ?>
		<div class="clear"></div>
	</div>
<?php } ?>