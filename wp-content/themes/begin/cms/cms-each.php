<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_each' ) ) { ?>
<?php 
	$each = ( array ) be_get_option( 'cms_each_add' );
	foreach ( $each as $items ) {
	$i = 1;
	if ( ! empty( $items['cms_each_img_url'] ) ) {
		$url = $items['cms_each_img_url'];
	} else { 
		$url = get_category_link( $items['cms_each_id'] );
	}
	
?>
<div class="cms-each-box">
	<div class="each-news-list">
		<?php
			$args1 = array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'cat'            => $items['cms_each_id']
			);
			$query1 = new WP_Query( $args1 );

			$args2 = array(
				'post_type'      => 'post',
				'posts_per_page' => 2,
				'cat'            => $items['cms_each_id'], 
				'offset'         => 3,
			);
			$query2 = new WP_Query( $args2 );

			$args3 = array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'cat'            => $items['cms_each_id'], 
				'offset'         => 5,
			);
			$query3 = new WP_Query( $args3 );
		?>

		<?php if ( ! empty( $items['cms_each_cat_m'] ) && $items['cms_each_cat_m'] == 'cms_each_cat_left' ) { ?>
			<div class="each-item each-item-four each-item-four-left">
				<div class="each-area each-area-cat">
					<a href="<?php echo $url; ?>" rel="bookmark" <?php echo goal(); ?> class="each-item-url">
						<div class="each-image-wrap">
							<div class="each-image-inner" style="background-image: url(<?php echo $items['cms_each_img']; ?>)"></div>
							<?php if ( ! empty( $items['cms_each_id'] ) ) { ?>
								<div class="each-img-cat">
									<?php
										if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
											$category = get_category( icl_object_id($items['cms_each_id'], 'category', true ) );
										} else {
											$category = get_category( $items['cms_each_id'] );
										}
										echo $category->name;
									?>
								</div>

								<?php if ( ! empty( $items['cms_each_des'] ) ) { ?>
									<div class="each-img-cat-des"><?php echo $items['cms_each_des']; ?></div>
								<?php } else { ?>
									<?php 
										$category = get_category( $items['cms_each_id'] );
										$description = get_term_field( 'description', $category->term_id, $category->taxonomy );

										if ( ! empty( $description ) ) {
										    echo '<div class="each-img-cat-des">' . $description . '</div>';
										}
									?>
								<?php } ?>
							<?php } ?>
						</div>
					</a>
				</div>

				<?php $i++; if ( $query1->have_posts() ) : ?>
					<?php while ( $query1->have_posts() ) : $query1->the_post(); ?>
						<div class="each-area<?php if ( ! empty( $items['cms_each_bg'] ) ) { ?> each-img<?php if ( ! empty( $items['cms_each_no_bg'] ) ) { ?> each-no-img-<?php echo mt_rand( 1, $i ); ?><?php } ?><?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
								<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?> class="item-inner">
								<?php if ( ! empty( $items['cms_each_bg'] ) && has_images() ) { ?>
									<div class="each-area-img" style="background-image: url(<?php echo each_img(); ?>);"></div>
								<?php } ?>
								<div class="each-date-wrap">
									<div class="each-date">
										<strong class="each-big-date"><?php echo get_the_date( 'd' ); ?></strong>
										<span class="each-small-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php echo get_the_date( 'Y-m' ); ?></time></span>
									</div>
									<i class="dashicons dashicons-arrow-right-alt2"></i>
								</div>
								<div class="each-text-wrap">
									<h3 class="each-title over"><?php the_title(); ?></h3>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>
		<?php } ?>

		<div class="each-item each-item-big">
			<?php $i++; if ( $query2->have_posts() ) : ?>
				<?php while ( $query2->have_posts() ) : $query2->the_post(); ?>
					<div class="each-area<?php if ( ! empty( $items['cms_each_bg'] ) ) { ?> each-img<?php if ( ! empty( $items['cms_each_no_bg'] ) ) { ?> each-no-img-<?php echo mt_rand( 1, $i ); ?><?php } ?><?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
						<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?> class="item-inner">
							<?php if ( ! empty( $items['cms_each_bg'] ) && has_images() ) { ?>
								<div class="each-area-img" style="background-image: url(<?php echo each_img(); ?>);"></div>
							<?php } ?>
							<div class="each-text-wrap">
								<h3 class="each-title"><?php the_title(); ?></h3>
								<p class="each-des">
									<?php 
										$content = get_the_content();
										$content = strip_shortcodes( $content );
										if ( get_bloginfo( 'language' ) === 'en-US' ) {
											echo begin_strimwidth( strip_tags( $content), 0, '46', '...' );
										} else {
											echo wp_trim_words( $content, '46', '...' );
										}
									?>
								</p>
							</div>
									<div class="each-date-wrap">
								<div class="each-date">
									<strong class="each-big-date"><?php echo get_the_date( 'd' ); ?></strong>
									<span class="each-small-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php echo get_the_date( 'Y-m' ); ?></time></span>
								</div>
								<i class="dashicons dashicons-arrow-right-alt2"></i>
							</div>
						</a>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>

		<div class="each-item each-item-small<?php if ( $items['cms_each_cat_m'] == 'cms_each_cat_right' ) { ?> each-item-small-b<?php } ?>">
			<?php $i++; if ( $query3->have_posts() ) : ?>
				<?php while ( $query3->have_posts() ) : $query3->the_post(); ?>
					<div class="each-area<?php if ( ! empty( $items['cms_each_bg'] ) ) { ?> each-img<?php if ( ! empty( $items['cms_each_no_bg'] ) ) { ?> each-no-img-<?php echo mt_rand( 1, $i ); ?><?php } ?><?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
						<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?> class="item-inner">
							<?php if ( ! empty( $items['cms_each_bg'] ) && has_images() ) { ?>
								<div class="each-area-img" style="background-image: url(<?php echo each_img(); ?>);"></div>
							<?php } ?>
							<div class="each-date-wrap">
								<div class="each-date">
									<strong class="each-big-date"><?php echo get_the_date( 'd' ); ?></strong>
									<span class="each-line"></span>
									<span class="each-small-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php echo get_the_date( 'Y-m' ); ?></time></span>
								</div>
							</div>
							<div class="each-text-wrap">
								<h3 class="each-title over"><?php the_title(); ?></h3>
								<i class="dashicons dashicons-arrow-right-alt2"></i>
							</div>
						</a>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>

		<?php if ( $items['cms_each_cat_m'] == 'cms_each_cat_right' ) { ?>
			<div class="each-item each-item-four each-item-four-right">
				<div class="each-area each-area-cat">
					<a href="<?php echo $url; ?>" rel="bookmark" <?php echo goal(); ?> class="each-item-url">
						<div class="each-image-wrap">
							<div class="each-image-inner" style="background-image: url(<?php echo $items['cms_each_img']; ?>)"></div>
							<?php if ( ! empty( $items['cms_each_id'] ) ) { ?>
								<div class="each-img-cat">
									<?php 
										$category = get_category( $items['cms_each_id'] );
										echo $category->name;
									?>
								</div>

								<?php if ( ! empty( $items['cms_each_des'] ) ) { ?>
									<div class="each-img-cat-des"><?php echo $items['cms_each_des']; ?></div>
								<?php } else { ?>
									<?php 
										$category = get_category( $items['cms_each_id'] );
										$description = get_term_field( 'description', $category->term_id, $category->taxonomy );

										if ( ! empty( $description ) ) {
										    echo '<div class="each-img-cat-des">' . $description . '</div>';
										}
									?>
								<?php } ?>
							<?php } ?>
						</div>
					</a>
				</div>

				<?php $i++; if ( $query1->have_posts() ) : ?>
					<?php while ( $query1->have_posts() ) : $query1->the_post(); ?>
						<div class="each-area<?php if ( ! empty( $items['cms_each_bg'] ) ) { ?> each-img<?php if ( ! empty( $items['cms_each_no_bg'] ) ) { ?> each-no-img-<?php echo mt_rand( 1, $i ); ?><?php } ?><?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?> class="item-inner">
								<?php if ( ! empty( $items['cms_each_bg'] ) && has_images() ) { ?>
									<div class="each-area-img" style="background-image: url(<?php echo each_img(); ?>);"></div>
								<?php } ?>
								<div class="each-date-wrap">
									<div class="each-date">
										<strong class="each-big-date"><?php echo get_the_date( 'd' ); ?></strong>
										<span class="each-small-date"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php echo get_the_date( 'Y-m' ); ?></time></span>
									</div>
									<i class="dashicons dashicons-arrow-right-alt2"></i>
								</div>
								<div class="each-text-wrap">
									<h3 class="each-title over"><?php the_title(); ?></h3>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>
		<?php } ?>

	</div>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 图文卡片', $number = 'cms_each_s' ); ?>
</div>
<?php } ?>
<?php } ?>