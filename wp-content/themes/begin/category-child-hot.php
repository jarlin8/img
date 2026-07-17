<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 分类热门
 */
get_header(); ?>
	<div class="cat-model" <?php aos_a(); ?>>
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

		<div class="su-cat-main tra ms">
			<h3 class="<?php if ( be_get_option( 'cms_cat_hot_tb' ) ) { ?>cat-square-title su-tb<?php } else { ?>su-model-cat-title<?php } ?>">
				<a href="<?php echo get_category_link( $term->term_id ); ?>">
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

			<div class="su-model-main">
				<div class="su-model-area">
					<div class="su-cat-model-img">

						<?php
							$terms = get_posts( array(
								'posts_per_page' => '2',
								'post_status'    => 'publish',
								'category__in'   => $term->term_id,
							) );
						?>

						<?php foreach ( $terms as $post ) : setup_postdata( $post ); ?>
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

						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>

					<ul class="su-cat-model-list">
						<?php
							$terms = get_posts( array(
								'posts_per_page' => '12',
								'offset'         => '2',
								'post_status'    => 'publish',
								'category__in'   => $term->term_id,
							) );
						?>

						<?php foreach ( $terms as $post ) : setup_postdata( $post ); ?>
						<li id="post-<?php the_ID(); ?>" class="su-list-title<?php if ( ! be_get_option( 'cms_cat_hot_date' ) ) { ?> no-listate<?php } ?>" <?php aos_a(); ?>>
							<?php the_title( sprintf( '<a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php if ( be_get_option( 'cms_cat_hot_date' ) ) { ?>
								<span class="listate"><time datetime="<?php the_date('Y-m-d'); ?> <?php the_time('H:i:s'); ?>"></time><?php the_time('m/d'); ?></span>
							<?php } ?>
						</li>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>

					</ul>
				</div>

				<div class="su-cat-model-hot">
					<h3 class="su-hot-title"><?php _e( '热', 'begin' ); ?></h3>
					<?php
					$hotday = be_get_option( 'cms_hot_day' );
						$terms = get_posts( array(
							'posts_per_page' => '9',
							'post_status'    => 'publish',
							'meta_key'       => 'views',
							'orderby'        => 'meta_value_num',
							'order'          => 'date',
							'category__in'   => $term->term_id,
							'date_query'     => array(
								array(
									'after'     => $hotday . ' days ago',
									'inclusive' => true,
								),
							),
						) );

						$i = 1
					?>

					<ul>
						<?php foreach ( $terms as $post ) : setup_postdata( $post ); ?>
							<li id="post-<?php the_ID(); ?>" class="su-list-hot-title srm li-one-<?php echo $i; ?>" <?php aos_a(); ?>>
								<?php the_title( sprintf( '<span class="li-icon li-icon-' . $i . '">' . $i++ . '</span><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
							</li>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
						<div class="clear"></div>
					</ul>

				</div>
			</div>
		</div>
		<?php } ?>
	</div>

<?php get_footer(); ?>