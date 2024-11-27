<?php 
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'slider' ) ) {
	$slider = ( array ) be_get_option( 'slider_home' );
	foreach ( $slider as $items ) {
		$slider_count = count( $slider );
	}
?>
<div class="slideshow-box model-slideshow betip">
	<div class="model-slideshow-area">
		<div class="slideshow-img">
			<div id="slideshow" class="slideshow">
				<?php 
					$slider = ( array ) be_get_option( 'slider_home' );
					echo '<div id="slider-home" class="owl-carousel slider-home slider-current be-wol">';
					foreach ( $slider as $items ) {
						if ( ! empty( $items['slider_home_video'] ) ) {
							$muted = ! $items['slider_home_audio'] ? ' muted' : '';
						$autoplay = ( $slider_count < 2 ) ? ' autoplay' : '';
							echo '<div class="slider-item slider-video-area">';
							echo '<video loop' . $autoplay . ' controls' . $muted . '><source src="' . $items['slider_home_video'] . '" type="video/mp4"></video>';
							echo '</div>';
						} else {
							if ( ! empty( $items['slider_home_img'] ) ) {
								echo '<div class="slider-item">';

								if ( be_get_option('show_img_crop' ) ) {
									echo '<a href="' . $items['slider_home_url'] . '" rel="bookmark" ' . goal() . '><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $items['slider_home_img'] . '&w=' . be_get_option( 'img_h_w' ) . '&h=' . be_get_option( 'img_h_h' ) . '&a=' . be_get_option( 'crop_top' ) . '&zc=1" alt="' . $items['slider_home_title'] . '"></a>';
								} else {
									echo '<a href="' . $items['slider_home_url'] . '" rel="bookmark" ' . goal() . '><img class="owl-lazy" data-src="' . $items['slider_home_img'] . '" alt="' . $items['slider_home_title'] . '"></a>';
								}

								if ( ! empty( $items['slider_home_title'] ) ) {
									echo '<p class="slider-home-title">' . $items['slider_home_title'] . '</p>';
								}
								echo '</div>';
							}
						}
					}
					echo '</div>';

					echo '<div class="lazy-img ajax-owl-loading">';
						foreach ( $slider as $items ) {
							if ( be_get_option('show_img_crop' ) ) {
								echo '<img src="' . get_template_directory_uri() . '/prune.php?src=' . $items['slider_home_img'] . '&w=' . be_get_option( 'img_h_w' ) . '&h=' . be_get_option( 'img_h_h' ) . '&a=' . be_get_option( 'crop_top' ) . '&zc=1" alt="lazy-img">';
							} else {
								echo '<img src="' . $items['slider_home_img'] . '" alt="lazy-img">';
							}
							break;
						}
					echo '</div>';
				?>
			</div>

			<div class="slide-post-model">
				<?php 
					$post = ( array ) be_get_option( 'slider_btn' );
					echo '<div class="slider-btn-main">';
					foreach ( $post as $items ) {
						if ( ! empty( $items['slider_btn_bg'] ) ) {
							echo '<div class="slider-btn-item" style="background: url(' . $items['slider_btn_bg'] . ') no-repeat center / cover;">';
							if ( ! empty( $items['slider_btn_title'] ) ) {
								echo '<a href="' . $items['slider_btn_url'] . '" rel="bookmark" ' . goal() . '><h3 class="slider-btn-title">' . $items['slider_btn_title'] . '</h3></a>';
							}
							echo '</div>';
						}
					}
					be_help( $text = '首页设置 → 杂志布局 → 幻灯显示模式 → 三栏链接按钮' );
					echo '</div>';
				?>
			</div>
		</div>
	</div>

	<div class="model-slide-hot-new tra">
		<span class="new-icon slide-new-icon fd"><i class="be be-new"></i></span>
		<div class="model-slide-new lic">
			<h3 class="slide-new-title"><?php echo be_get_option( 'slider_new_text' ); ?></h3>
			<?php
				$sticky = be_get_option( 'slider_sticky' ) ? '0' : '1';
				$args = array(
					'posts_per_page'      => 8,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => $sticky,
				);

				if ( ! $args['ignore_sticky_posts'] ) {
					$stickies = get_option( 'sticky_posts' );
					$stickies_count = count( $stickies );

					if ( $stickies_count > 0 ) {
						$args['posts_per_page'] -= $stickies_count;
						if ( $args['posts_per_page'] < 1 ) {
							$args['posts_per_page'] = 1;
						}
					}
				}

				$recent = new WP_Query( $args );
				$s = 0;
			?>
			<ul>
				<?php while( $recent->have_posts() ) : $recent->the_post(); $do_not_duplicate[] = $post->ID; $s++; ?>
				<li id="post-<?php the_ID(); ?>" class="su-list-title high-<?php echo mt_rand( 1, $s ); ?>" <?php aos_a(); ?>>
					<?php the_title( sprintf( '<a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				<div class="clear"></div>
			</ul>
		</div>

		<div class="model-slide-hot notext betip">
			<h3 class="slide-hot-title"><span class="hot-mark"><?php _e( '热', 'begin' ); ?></span><?php echo be_get_option( 'slider_hot_text' ); ?></h3>
			<?php
				$args = array(
					'post__in'  => explode( ',', be_get_option( 'slider_hot_id' ) ),
					'orderby'   => 'post__in', 
					'order'     => 'DESC',
					'ignore_sticky_posts' => true,
					);
				$query = new WP_Query( $args );
			?>

			<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
				<div id="post-<?php the_ID(); ?>" class="portfolio-card-item" <?php aos_a(); ?>>
					<figure class="thumbnail">
						<?php echo zm_thumbnail(); ?>
					</figure>
					<div class="portfolio-card-content">
						<?php the_title( sprintf( '<h2 class="portfolio-card-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<span class="entry-meta">
							<?php zm_category(); ?>
							<span class="listate"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time('m/d'); ?></time></span>
						</span>
						<div class="clear"></div>
					</div>
				</div>
			<?php endwhile; ?>
			<?php else : ?>
				<div class="be-none">首页设置 → 杂志布局 → 幻灯显示模式 → 三栏热门推荐，输入文章ID</div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
			<?php be_help( $text = '首页设置 → 杂志布局 → 幻灯显示模式 → 三栏热门推荐' ); ?>
		</div>
	</div>
	<div class="clear"></div>
	<?php be_help( $text = '首页设置 → 首页幻灯' ); ?>
</div>
<?php } ?>