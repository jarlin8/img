<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'flexisel' ) ) { ?>
	<?php if ( be_get_option( 'flexisel_id' ) ) { ?>
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
				'include' => explode( ',', be_get_option( 'flexisel_id' ) ),
				'orderby' => 'include',
				'order'   => 'ASC',
			));

			if ( $tax_terms ) {
				foreach ( $tax_terms as $tax_term ) {

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
						'posts_per_page'      => be_get_option( 'flexisel_n' ),
						'post__not_in'        => $exclude_posts,
						'orderby'             => 'date',
						'order'               => 'DESC',
						'ignore_sticky_posts' => 1,
					);

					$query = new WP_Query( $args );
				?>

				<div class="slider-rolling-box betip ms" <?php aos_a(); ?>>
					<div id="slider-rolling" class="be-rolling owl-carousel slider-rolling slider-current">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>

						<div id="post-<?php the_ID(); ?>" class="post-item-list post scrolling-img">
							<div class="scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
							<div class="clear"></div>
							<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="clear"></div>
						</div>
		
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>

					<div class="slider-rolling-lazy ajax-owl-loading srfl-<?php echo be_get_option( 'flexisel_f' ); ?>">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>

						<div id="post-<?php the_ID(); ?>" class="post-item-list post scrolling-img">
							<div class="scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
							<div class="clear"></div>
							<h2 class="grid-title over"><a href="#"><?php _e( '加载中...', 'begin' ); ?></a></h2>
							<div class="clear"></div>
						</div>

						<?php break; ?>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
					<?php cms_help( $text = '首页设置 → 杂志布局 → 图片滚动模块', $number = 'flexisel_s' ); ?>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>
		<div class="slider-rolling-box ms" <?php aos_a(); ?>>
			<div class="be-rolling slider-current">
				<div class="scrolling-img">首页设置 → 杂志布局 → 图片滚动模块 → 输入分类ID</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>