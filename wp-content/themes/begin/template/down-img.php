<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="flex-card-item down-img" <?php aos_a(); ?>>
	<div class="flex-card-area ms">
		<?php the_title( sprintf( '<h2 class="flex-card-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="down-img-main">
			<figure class="thumbnail">
				<?php echo tao_thumbnail(); ?>
				<?php echo t_mark(); ?>
			</figure>

			<div class="card-area">
				<?php echo meta_sub(); ?>
				
				<?php if ( ! get_post_meta( get_the_ID(), 'meta_sub', true ) && be_get_option( 'cms_down_excerpt' ) ) { ?>
					<div class="card-content">
						<?php if ( has_excerpt('') ) {
								echo wp_trim_words( get_the_excerpt(), be_get_option( 'cms_down_excerpt_n' ), '...' );
							} else {
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, be_get_option( 'cms_down_excerpt_n' ), '...' );
					        }
						?>
					</div>
				<?php } ?>

				<?php 
					if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
						if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true ) ) {
							echo '<div class="price-inf">';
							echo be_vip_meta();
							$meta_sub_after = get_post_meta( get_the_ID(), 'meta_sub_after', true );
							if ( $meta_sub_after ) {
								echo '<span class="meta-sub-after">' . $meta_sub_after . '</span>';
							}
							echo '</div>';
						}
					}
				?>

				<?php 
					if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
						if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true )) {
							if ( get_post_meta( get_the_ID(), 'down_price', true ) ) {
								echo '<div class="card-down-more"><a href="' . get_the_permalink() . '" rel="external nofollow" target="_blank">' . $btntext . '</a></div>';
							}
							if ( ! get_post_meta( get_the_ID(), 'down_price_type', true ) ) {
								if ( ! get_post_meta( get_the_ID(), 'down_price', true ) ) {
									if ( get_post_meta( get_the_ID(), 'member_down', true ) ) {
										$member_down = get_post_meta( get_the_ID(), 'member_down', true );
										if ( $member_down > 3 && $member_down = 1 ){
											echo '<span class="be-vip-meta"><span class="be-vip-down"><i class="cx cx-svip"></i></span></span>';
										} else {
											echo '<div class="card-down-more"><a href="' . get_the_permalink() . '" rel="external nofollow" target="_blank">' . $btntext . '</a></div>';
										}
									}
								}
							} else {
								echo '<span class="be-vip-meta"><span class="be-vip-down">' . __( '付费', 'begin' ) . '</span></span>';
							}
						} else {
							echo '<span class="card-down-meta"><i class="ep ep-jifen ri"></i>';
							echo __( '分享', 'begin' );
							$meta_sub_after = get_post_meta( get_the_ID(), 'meta_sub_after', true );
							if ( $meta_sub_after ) {
								echo '<span class="meta-sub-after">' . $meta_sub_after . '</span>';
							}
							echo '</span>';
							echo '<div class="card-down-more card-down-more-btn"><a href="' . get_the_permalink() . '" rel="external nofollow" target="_blank">' . $btntext . '</a></div>';
						}
					} else {
						echo '<span class="card-down-meta">' . be_views_ico_inf() . '</span>';
						echo '<div class="card-down-more card-down-more-btn"><a href="' . get_the_permalink() . '" rel="external nofollow" target="_blank">' . $btntext . '</a></div>';
					}
				?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>