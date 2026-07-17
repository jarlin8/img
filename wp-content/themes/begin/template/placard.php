<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'placard_mobile' ) || ( ! wp_is_mobile() ) ) { ?>
<?php 
	if ( zm_get_option( 'admin_placard' ) ) {
		$placard = zm_get_option( 'placard_layer' ) && ! current_user_can( 'manage_options' );
	} else {
		$placard = zm_get_option( 'placard_layer' );
	}
	if ( $placard ) { ?>
	<div class="placard-pub<?php if ( ! zm_get_option( 'placard_mode' ) || ( zm_get_option( 'placard_mode' ) == 'mask' ) ) { ?> pub-mask<?php } ?>">
		<div class="placard-bg fd">
			<div class="placard-layer fd">
				<div class="placard-box betip">
					<div class="tcb-qq tcb-pl"><div></div><div></div><div></div><div></div><div></div></div>
					<div class="placard-area">
						<?php if (zm_get_option('custom_placard')) { ?>
							<div class="placard-content">
								<h3 class="placard-title"><a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo zm_get_option('custom_placard_title' ); ?></a></h3>
								<?php if ( ! zm_get_option('custom_placard_img') == '' ) { ?>
									<figure class="placard-content-img">
										<a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="bookmark" <?php echo goal(); ?>><img src="<?php echo zm_get_option('custom_placard_img' ); ?>" alt="<?php echo zm_get_option('custom_placard_title' ); ?>"></a>
										<div class="clear"></div>
									</figure>
									<?php } ?>
								<?php echo zm_get_option( 'custom_placard_content' ); ?>
								<div class="placard-more fd"><a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="bookmark" <?php echo goal(); ?>></a></div>
							</div>
						<?php } else { ?>
							<?php if (zm_get_option('placard_img')) { ?>
								<div class="placard-img-box notext">
									<?php if (!zm_get_option('placard_id')=='') { ?>
										<h3 class="placard-new"><?php _e( '公告', 'begin' ); ?></h3>
									<?php } else { ?>
										<h3 class="placard-new"><?php _e( '最近更新', 'begin' ); ?></h3>
									<?php } ?>
									<?php $posts = get_posts( array( 'cat' => zm_get_option( 'placard_cat_id' ), 'post_status' => 'publish', 'post_type' => 'post', 'orderby' => 'rand', 'posts_per_page' => '3' ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
									<div class="placard-img">
										<figure class="thumbnail placard-thumbnail">
											<?php echo zm_thumbnail(); ?>
										</figure>
									</div>
									<?php endforeach; endif; ?>
								</div>
								<div class="clear"></div>
							<?php } ?>
							<?php if (zm_get_option('placard_id')=='') { ?>
								<ul class="placard-content placard-content-t">
									<?php $posts = get_posts( array( 'cat' => zm_get_option( 'placard_cat_id' ), 'post_type' => 'post', 'post_status' => 'publish', 'orderby' => 'rand', 'posts_per_page' => '5' ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
										<?php the_title( sprintf( '<li class="placard-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
									<?php endforeach; endif; ?>
									<?php wp_reset_postdata(); ?>
								</ul>
							<?php } else { ?>
								<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option( 'placard_id' ), 'orderby'   => 'post__in', ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
									<div class="placard-content">
										<?php the_title( sprintf( '<h3 class="placard-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
										<?php if ( has_excerpt('') ){
												echo wp_trim_words( get_the_excerpt(), 120, '...' );
											} else {
												$content = get_the_content();
												$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
												echo wp_trim_words( $content, 120, '...' );
										    }
										?>
										<div class="placard-more fd"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>></a></div>
									</div>
								<?php endforeach; endif; ?>
								<?php wp_reset_postdata(); ?>
							<?php } ?>
						<?php } ?>
						<div class="clear"></div>
					</div>
					<div class="tcb-qq tcb-pl"><div></div><div></div><div></div><div></div><div></div></div>
				</div>
				<div class="placard-close yy<?php echo cur(); ?>"></div>
				<?php be_help( $text = '主题选项 → 基本设置 → 弹窗公告' ); ?>
			</div>
		</div>
	</div>
<script>
jQuery(document).ready(function($) {
	const SITE_ID = window.location.hostname;
	if (jQuery.cookie(SITE_ID + '_be_placard') != '1') {
		var time = plt.time;
		var millisecond = new Date().getTime();
		var expiresTime = new Date(millisecond + 60 * 1000 * time);
		setTimeout(function() {
			$('.placard-bg').addClass("alter");
			$('.placard-close').click(function() {
				$('.placard-bg').removeClass("alter")
			});
			jQuery.cookie(SITE_ID + '_be_placard', '1', {
				expires: expiresTime
			}); 
		}, 8000);
	}
})
</script>
<?php } ?>
<?php } ?>