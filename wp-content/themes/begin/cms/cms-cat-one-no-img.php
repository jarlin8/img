<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_get_option( 'cat_one_on_img' ) ) {
	echo '<div class="line-one line-one-no-img betip">';
	if ( be_get_option( 'cat_one_on_img_id' ) ) {
		$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
		if ( be_get_option( 'no_cat_top' ) ) {
			$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
			$exclude_posts = array_merge( $do_not_duplicate, $top_id );
		} else {
			$exclude_posts = '';
		}

		$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
		$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', be_get_option( 'cat_one_on_img_id' ) ) ) );

		foreach ( $tax_terms as $tax_term ) {
			$args = array(
				'post_type' => array( 'post', 'bulletin', 'picture', 'video', 'tao', 'sites', 'show' ),
				'tax_query' => array(
					array(
						'taxonomy' => $tax_term->taxonomy,
						'field'    => 'term_id',
						'terms'    => $tax_term->term_id,
						'include_children' => $cat,
					),
				),

				'post_status'    => 'publish',
				'posts_per_page' => be_get_option( 'cat_one_on_img_n' ),
				'post__not_in'   => $exclude_posts,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'ignore_sticky_posts' => 1,
	 		);
			$be_query = new WP_Query( $args );

		if ( $be_query->have_posts() ) {
				echo '<div class="cat-container ms" data-aos="' . zm_get_option( 'aos_data' ) . '">';
					echo '<h3 class="cat-title">';
							echo '<a href="' . get_category_link( $tax_term ) . '" rel="bookmark" ' . goal() . '>';
								if ( zm_get_option( 'cat_icon' ) ) {
									if ( get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) ) {
										echo '<i class="t-icon ' . zm_taxonomy_icon_code( $tax_term->term_id ) . '"></i>';
									}
									if ( get_option( 'zm_taxonomy_svg' . $tax_term->term_id ) ) {
										echo '<svg class="t-svg icon" aria-hidden="true"><use xlink:href="#' . zm_taxonomy_svg_code( $tax_term->term_id ) . '"></use></svg>';
									}
									if ( ! get_option( 'zm_taxonomy_icon' . $tax_term->term_id ) && ! get_option( 'zm_taxonomy_svg'.$tax_term->term_id ) ) {
										title_i();
									}
								} else {
									title_i();
								}
								echo $tax_term->name;
								more_i();
							echo '</a>';
						echo '</h3>';

						echo '<div class="clear"></div>';
						echo '<div class="cms-cat-area">';
							echo '<ul class="cat-one-list">';
								while ( $be_query->have_posts() ) : $be_query->the_post();
								if ( be_get_option( 'cat_date_time' ) ) {
									list_date_time();
								} else {
									list_date();
								}
								the_title( sprintf( '<li class="list-cat-title srm' . date_class() . '"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' );
								endwhile; wp_reset_postdata();
							echo '</ul>';
							echo '<div class="clear"></div>';
					echo '</div>';
				echo '</div>';
			}
		} 

	} else {
		echo '<div class="cat-container ms">';
		echo '<h3 class="cat-title">';
		echo title_i() . '未输入分类ID';
		echo '</h3>';
		echo '<div class="clear"></div>';

		echo '<div class="cms-cat-area">';
		echo '<ul class="cat-one-list">';
		echo '<li class="list-cat-title srm">首页设置 → 杂志布局 → 单栏分类列表(无缩略图)</li>';
		echo '</ul>';
		echo '<div class="clear"></div>';
		echo '</div>';
		echo '</div>';
	}
	cms_help( $text = '首页设置 → 杂志布局 → 单栏分类列表(无缩略图)', $number = 'cat_one_on_img_s' );
	echo '</div>';
}