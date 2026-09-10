<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_each' ) ) { ?>
<?php 
	$each = ( array ) be_build( get_the_ID(), 'group_each_add' );
	foreach ( $each as $items ) {
	if ( ! empty( $items['group_each_img_url'] ) ) {
		$url = $items['group_each_img_url'];
	} else { 
		$url = get_category_link( $items['group_each_id'] );
	}
	$args = [
		'auto'  => '',
		'white' => ' group-white',
		'gray'  => ' group-gray',
	];
	$bg = '';
	if ( isset( $items['each_bg'] ) && isset( $args[$items['each_bg']] ) ) {
		$bg = $args[$items['each_bg']];
	}
?>
<div class="g-row g-line group-each<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-each-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! empty( $items['group_each_t'] ) ) { ?>
					<h3><?php echo $items['group_each_t']; ?></h3>
				<?php } ?>
				<?php if ( ! empty( $items['group_each_des'] ) ) { ?>
					<div class="group-des"><?php echo $items['group_each_des']; ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="each-news-list">
				<?php if ( ! empty( $items['group_each_img_m'] ) && $items['group_each_img_m'] == 'group_each_img_left' ) { ?>
					<div class="each-item each-item-img">
						<a href="<?php echo $url; ?>" rel="bookmark" <?php echo goal(); ?> class="each-item-url">
							<div class="each-image-wrap">
								<div class="each-image-inner" style="background-image: url(<?php echo $items['group_each_img']; ?>)"></div>
								<div class="each-text-wrap">
									<?php if ( ! empty( $items['group_each_id'] ) ) { ?>
										<div class="each-img-cat">
											<?php
												if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
													$category = get_category( icl_object_id($items['group_each_id'], 'category', true ) );
												} else {
													$category = get_category( $items['group_each_id'] );
												}
												echo $category->name;
											?>
										</div>
									<?php } ?>
									<?php if ( ! empty( $items['group_each_title'] ) ) { ?>
										<h3 class="each-img-cat-des over"><?php echo $items['group_each_title']; ?></h3>
									<?php } ?>
								</div>
							</div>
						</a>
					</div>
				<?php } ?>

				<?php
					$args1 = array(
						'post_type'      => 'post',
						'posts_per_page' => 2,
						'cat'            => $items['group_each_id']
					);
					$query1 = new WP_Query( $args1 );

					$args2 = array(
						'post_type'      => 'post',
						'posts_per_page' => 3,
						'cat'            => $items['group_each_id'], 
						'offset'         => 2,
					);
					$query2 = new WP_Query( $args2 );
				?>

				<div class="each-item each-item-big">
					<?php if ( $query1->have_posts() ) : ?>
						<?php while ( $query1->have_posts() ) : $query1->the_post(); ?>
							<div class="each-area<?php if ( ! empty( $items['group_each_bg'] ) ) { ?> each-img<?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
								<a class="item-inner" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" <?php echo goal(); ?>>
									<?php if ( ! empty( $items['group_each_bg'] ) && has_images() ) { ?>
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

				<div class="each-item each-item-small">
					<?php if ( $query2->have_posts() ) : ?>
						<?php while ( $query2->have_posts() ) : $query2->the_post(); ?>
							<div class="each-area<?php if ( ! empty( $items['group_each_bg'] ) ) { ?> each-img<?php if ( ! has_images() ) { ?> each-no-img<?php } ?><?php } ?>">
								<a href="<?php echo esc_url( get_permalink() ); ?>" class="item-inner" rel="bookmark" <?php echo goal(); ?>>
									<?php if ( ! empty( $items['group_each_bg'] ) && has_images() ) { ?>
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

				<?php if ( $items['group_each_img_m'] == 'group_each_img_right' ) { ?>
					<div class="each-item each-item-img">
						<a href="<?php echo $url; ?>" class="each-item-url" rel="bookmark" <?php echo goal(); ?>>
							<div class="each-image-wrap">
								<div class="each-image-inner" style="background-image: url(<?php echo $items['group_each_img']; ?>)"></div>
								<div class="each-text-wrap">
									<?php if ( ! empty( $items['group_each_id'] ) ) { ?>
										<div class="each-img-cat">
											<?php 
												$category = get_category( $items['group_each_id'] );
												echo $category->name;
											?>
										</div>
									<?php } ?>
									<?php if ( ! empty( $items['group_each_title'] ) ) { ?>
										<h3 class="each-img-cat-des"><?php echo $items['group_each_title']; ?></h3>
									<?php } ?>
								</div>
							</div>
						</a>
					</div>
				<?php } ?>
			</div>
			<div class="group-post-more"><a href="<?php echo get_category_link( $items['group_each_id'] ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
		</div>
		<?php bu_help( $text = '行业新闻', $number = 'group_each_s' ); ?>
	</div>
</div>
<?php } ?>
<?php } ?>