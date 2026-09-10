<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="flex-card-item down-card" <?php aos_a(); ?>>
	<div class="boxs1">
		<div class="flex-card-area">
			<figure class="thumbnail">
				<?php echo tao_thumbnail(); ?>
				<?php echo t_mark(); ?>
			</figure>

			<div class="card-area">
				<?php the_title( sprintf( '<h2 class="flex-card-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php echo meta_sub(); ?>

				<?php 
					if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
						if ( get_post_meta( get_the_ID(), 'start_down', true ) || get_post_meta( get_the_ID(), 'down_url_free', true ) ) {
							echo '<div class="price-inf">';
							echo be_vip_meta();
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
							echo '<span class="card-down-meta"><i class="ep ep-jifen ri"></i>' . __( '分享', 'begin' ) . '</span>';
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