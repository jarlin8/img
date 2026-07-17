<?php if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cms_assets' ) ) {
	echo '<div class="betip cms-assets-' .be_get_option( 'cms_assets_f' ) . '">';

		if ( be_get_option( 'cms_assets_cat' ) ) {
			$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
			if ( be_get_option( 'no_cat_top' ) ) {
				$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
				$exclude_posts = array_merge( $do_not_duplicate, $top_id );
			} else {
				$exclude_posts = '';
			}

			if ( be_get_option( 'cms_assets_id' ) ) {
				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', be_get_option( 'cms_assets_id' ) ) ) );
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
							'posts_per_page' => be_get_option( 'cms_assets_n' ),
							'post__not_in'   => $exclude_posts,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'ignore_sticky_posts' => true,
		 				);
						$be_query = new WP_Query( $args );
					
						if ( $be_query->have_posts() ) {
							echo '<div class="flexbox-grid">';
								echo '<h3 class="cms-picture-cat-title"><a href="' . get_category_link( $tax_term ) . '" rel="bookmark" ' . goal() . '>' . $tax_term->name . '</a></h3>';
								while ( $be_query->have_posts() ) : $be_query->the_post();
								require get_template_directory() . '/template/assets.php';
								endwhile;
								wp_reset_postdata();
							echo '</div>';
						}
						echo '<div class="clear"></div>';
					}
				}
			} else {
				echo '<div class="flexbox-grid"><div class="flex-grid-item" data-aos="fade-up"><div class="flex-grid-area grid-tip">首页设置 → 杂志布局 → 会员商品 → 调用分类，输入分类ID</div></div></div>';
			}
		}

		if ( be_get_option( 'cms_assets_post' ) ) {
			$args = array(
				'post_type' => 'any',
				'post__in'  => explode( ',', be_get_option( 'cms_assets_post_id' ) ),
				'orderby'   => 'post__in',
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $args );

			echo '<div class="flexbox-grid">';
				if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
					require get_template_directory() . '/template/assets.php';
				endwhile;
				else :
					echo '<div class="flex-grid-item" data-aos="fade-up"><div class="flex-grid-area grid-tip">首页设置 → 杂志布局 → 会员商品 → 调用文章，输入文章ID</div></div>';
				endif;
				wp_reset_postdata();

			echo '<div class="clear"></div>';
			echo '</div>';
		}

		cms_help( $text = '首页设置 → 杂志布局 → 会员商品', $number = 'cms_assets_s' );
	echo '</div>';
}