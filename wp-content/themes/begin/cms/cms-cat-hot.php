<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_cat_hot' ) ) { ?>
	<div class="cat-model betip" <?php aos_a(); ?>>
		<?php $display_categories = explode( ',', be_get_option( 'cms_cat_hot_id' ) ); foreach ( $display_categories as $category ) {
			$cat = ( be_get_option( 'no_cat_child' ) ) ? 'category' : 'category__in';
			if ( be_get_option( 'no_cat_top' ) ) {
				$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
				$exclude_posts = array_merge( $do_not_duplicate, $top_id );
			} else {
				$exclude_posts = '';
			}
			$becat = $category;
			if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
				$becat = icl_object_id( $category, 'category', true );
			}
		?>


		<div class="su-cat-main tra ms">
			<h3 class="<?php if ( be_get_option( 'cms_cat_hot_tb' ) ) { ?>cat-square-title su-tb<?php } else { ?>su-model-cat-title<?php } ?>">
				<a href="<?php echo get_category_link( $category ); ?>" rel="bookmark" <?php echo goal(); ?>>
					<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
						<?php if ( get_option( 'zm_taxonomy_icon' . $category ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $category ); ?>"></i><?php } ?>
						<?php if ( get_option( 'zm_taxonomy_svg' . $category ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $category ); ?>"></use></svg><?php } ?>
						<?php if ( ! get_option( 'zm_taxonomy_icon' . $category ) && ! get_option( 'zm_taxonomy_svg'.$category ) ) { ?><?php title_i(); ?><?php } ?>
					<?php } else { ?>
						<?php title_i(); ?>
					<?php } ?>
					<?php if ( be_get_option( 'cms_cat_hot_id' ) ) { ?>
						<?php echo get_cat_name( $becat ); ?>
					<?php } else { ?>
						未输入分类ID
					<?php } ?>
					<?php more_i(); ?>
				</a>
			</h3>

			<div class="su-model-main">
				<div class="su-model-area">
					<div class="su-cat-model-img">
						<?php 
							$args = array(
								'post_type'      => 'post',
								'posts_per_page' => 2,
								'post_status'    => 'publish',
								'post__not_in'   => $exclude_posts,
								$cat             => $category
							);
							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
							<div id="post-<?php the_ID(); ?>" class="su-model-item-img" <?php aos_a(); ?>>
								<figure class="thumbnail">
									<?php echo zm_thumbnail(); ?>
								</figure>
								<?php the_title( sprintf( '<h2 class="su-img-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<p class="su-model-item-words over">
									<?php if ( has_excerpt('') ) {
											echo wp_trim_words( get_the_excerpt(), 28, '...' );
										} else {
											$content = get_the_content();
											$content = wp_strip_all_tags( str_replace( array('[',']' ),array('<','>' ), $content ) );
											echo wp_trim_words( $content, 30, '...' );
								        }
									?>
								</p>
							</div>

						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>

					<ul class="su-cat-model-list lic">
						<?php 
							$args = array(
								'post_type'      => 'post',
								'posts_per_page' => 12,
								'offset'         => 2,
								'post_status'    => 'publish',
								'post__not_in'   => $exclude_posts,
								$cat             => $category
							);
							$s = 0;
							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); $s++; ?>
							<li id="post-<?php the_ID(); ?>" class="su-list-title high-<?php echo mt_rand(1, $s); ?><?php if ( ! be_get_option( 'cms_cat_hot_date' ) ) { ?> no-listate<?php } ?>" <?php aos_a(); ?>>
								<?php the_title( sprintf( '<a class="srm" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php if ( be_get_option( 'cms_cat_hot_date' ) ) { ?>
									<span class="listate"><time datetime="<?php the_date('Y-m-d'); ?> <?php the_time('H:i:s'); ?>"></time><?php the_time('m/d'); ?></span>
								<?php } ?>
							</li>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>
					</ul>
				</div>

				<div class="su-cat-model-hot">
					<h3 class="su-hot-title"><?php _e( '热', 'begin' ); ?></h3>
					<?php 
						$hotday = be_get_option( 'cms_hot_day' );
						$args = array(
							'post_type'      => 'post',
							'posts_per_page' => '9',
							'post_status'    => 'publish',
							'meta_key'       => 'views',
							'orderby'        => 'meta_value_num',
							'order'          => 'date',
							'post__not_in'   => $exclude_posts,
							$cat            => $category,
							'date_query'     => array(
								array(
									'after'     => $hotday . ' days ago',
									'inclusive' => true,
								),
							),
						);
						$i = 1;
						$query = new WP_Query( $args );
					?>

					<ul>
						<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
							<li id="post-<?php the_ID(); ?>" class="su-list-hot-title srm li-one-<?php echo $i; ?>" <?php aos_a(); ?>>
								<?php the_title( sprintf( '<span class="li-icon li-icon-' . $i . '">' . $i++ . '</span><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							</li>
						<?php endwhile; endif; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>
					</ul>

				</div>
			</div>
		</div>

		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 热门分类', $number = 'cms_cat_hot_s' ); ?>
	</div>
<?php } ?>