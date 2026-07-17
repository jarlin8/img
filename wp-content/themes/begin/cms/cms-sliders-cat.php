<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_slides_cat' ) ) { ?>
	<?php 
		// 分类幻灯
		function slides_img() {
			global $post;
			$content = $post->post_content;
			$beimg = str_replace( array( "\n", "\r", " " ), '', explode( ',', zm_get_option( 'random_long_url' ) ) );
			$src   = $beimg[array_rand( $beimg )];
			preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
			$n = count( $strResult[1] );
			if ( $n > 0 ) {
				return $strResult[1][0];
			} else {
				return $src;
			}
		}
		$addslides = ( array ) be_get_option( 'cms_slides_cat_add' );
		foreach ( $addslides as $items ) {
	?>
		<div class="cms-slider-text cms-slider-cat betip ms<?php if ( ! empty( $items['cms_slides_cat_r'] ) ) { ?> slider-r<?php } ?>" <?php aos(); ?>>
			<div class="owl-carousel slides-text">
				<?php
					$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
					if ( be_get_option( 'no_cat_top' ) ) {
						$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
						$exclude_posts = array_merge( $do_not_duplicate, $top_id );
					} else {
						$exclude_posts = '';
					}
					$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
					$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => $items['cms_slides_cat_id'] ) );
					if ( $tax_terms ) {
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
								'posts_per_page' => $items['cms_slides_cat_n'],
								'post__not_in'   => $exclude_posts,
								'orderby'        => 'date',
								'order'          => 'DESC',
								'ignore_sticky_posts' => 1,
							);
						$be_query = new WP_Query( $args );
					?>

						<?php if ( $be_query->have_posts() ) { ?>

								<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

									<div class="slides-item">
									<div class="slides-content">
										<div class="slides-item-text<?php if ( ! empty( $slides['cms_slides_cat_ret'] ) ) { ?> ret<?php } ?>">
											<h2 class="slides-item-title"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></h2>
												<div class="slides-item-des">
													<?php if ( has_excerpt('') ) {
															echo wp_trim_words( get_the_excerpt(), 84, '...' );
														} else {
															$content = get_the_content();
															$content = wp_strip_all_tags( str_replace( array('[',']' ),array('<','>' ), $content ) );
															echo wp_trim_words( $content, 86, '...' );
														}
													?>
												</div>
											</div>
										</div>
										<div class="slides-img">
											<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?>><div class="slides-item-img" style="background-image: url(<?php echo slides_img(); ?>);"></div></a>
										</div>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>

						<?php } ?>
					<?php } ?>
				<?php } ?>

			</div>

			<div class="lazy-img ajax-owl-loading">
				<?php
					$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
					if ( be_get_option( 'no_cat_top' ) ) {
						$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
						$exclude_posts = array_merge( $do_not_duplicate, $top_id );
					} else {
						$exclude_posts = '';
					}
					$tax = array( 'category', 'notice', 'products', 'gallery', 'videos', 'taobao', 'favorites', 'products' );
					$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => $items['cms_slides_cat_id'] ) );
					if ( $tax_terms ) {
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
								'posts_per_page' => $items['cms_slides_cat_n'],
								'post__not_in'   => $exclude_posts,
								'orderby'        => 'date',
								'order'          => 'DESC',
								'ignore_sticky_posts' => 1,
							);
						$be_query = new WP_Query( $args );
					?>

						<?php if ( $be_query->have_posts() ) { ?>

								<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

									<div class="slides-item">
									<div class="slides-content">
										<div class="slides-item-text<?php if ( ! empty( $slides['cms_slides_cat_ret'] ) ) { ?> ret<?php } ?>">
											<h2 class="slides-item-title"><?php the_title( sprintf( '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?></h2>
												<div class="slides-item-des">
													<?php if ( has_excerpt('') ) {
															echo wp_trim_words( get_the_excerpt(), 84, '...' );
														} else {
															$content = get_the_content();
															$content = wp_strip_all_tags( str_replace( array('[',']' ),array('<','>' ), $content ) );
															echo wp_trim_words( $content, 86, '...' );
														}
													?>
												</div>
											</div>
										</div>
										<div class="slides-img">
											<div class="slides-item-img" style="background-image: url(<?php echo slides_img(); ?>);"></div>
										</div>
									</div>
								<?php break;endwhile; wp_reset_postdata(); ?>

						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>

			<?php cms_help( $text = '首页设置 → 杂志布局 → 分类幻灯', $number = 'cms_sliders_cat_s' ); ?>
		</div>
	<?php } ?>
<?php } ?>