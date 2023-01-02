<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
	if ( zm_get_option( 'admin_placard' ) ) {
		$placard = zm_get_option( 'placard_layer' ) && !current_user_can( 'manage_options' );
	} else {
		$placard = zm_get_option( 'placard_layer' );
	}
	if ( $placard ) { ?>
	<div class="placard-bg fd bgt">
		<div class="placard-layer fd bgt">
			<div class="placard-box yy">
				<div class="tcb-qq tcb-pl"><div></div><div></div><div></div><div></div><div></div></div>
				<div class="placard-area">
					<?php if (zm_get_option('custom_placard')) { ?>
						<div class="placard-content bgt">
							<h3 class="placard-title"><a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="external nofollow"><?php echo zm_get_option('custom_placard_title' ); ?></a></h3>
							<?php if (!zm_get_option('custom_placard_img')=='') { ?>
								<figure class="placard-content-img">
									<a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="external nofollow"><img src="<?php echo zm_get_option('custom_placard_img' ); ?>" alt="主题发布"></a>
									<div class="clear"></div>
								</figure>
								<?php } ?>
							<?php echo zm_get_option( 'custom_placard_content' ); ?>
							<div class="placard-more fd"><a href="<?php echo zm_get_option('custom_placard_url' ); ?>" rel="external nofollow"></a></div>
						</div>
					<?php } else { ?>
						<?php if (zm_get_option('placard_img')) { ?>
							<div class="placard-img-box">
								<?php if (!zm_get_option('placard_id')=='') { ?>
									<h3 class="placard-new"><?php _e( '公告', 'begin' ); ?></h3>
								<?php } else { ?>
									<h3 class="placard-new"><?php _e( '最近更新', 'begin' ); ?></h3>
								<?php } ?>
								<?php $posts = get_posts( array( 'cat' => zm_get_option('placard_cat_id'), 'post_status' => 'publish', 'post_type' => 'post', 'posts_per_page' => '3' ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
								<div class="placard-img">
									<figure class="thumbnail placard-thumbnail">
										<?php zm_thumbnail(); ?>
									</figure>
								</div>
								<?php endforeach; endif; ?>
							</div>
							<div class="clear"></div>
						<?php } ?>
						<?php if (zm_get_option('placard_id')=='') { ?>
							<ul class="placard-content placard-content-t bgt">
								<?php $posts = get_posts( array( 'cat' => zm_get_option('placard_cat_id'), 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => '5' ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
									<?php the_title( sprintf( '<li class="placard-title"><a href="%s" rel="external nofollow">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
								<?php endforeach; endif; ?>
								<?php wp_reset_query(); ?>
							</ul>
						<?php } else { ?>
							<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option( 'placard_id' ) ) ); if ( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
								<div class="placard-content bgt">
									<?php the_title( sprintf( '<h3 class="placard-title"><a href="%s" rel="external nofollow">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
									<?php if (has_excerpt('')){
											echo wp_trim_words( get_the_excerpt(), 120, '...' );
										} else {
											$content = get_the_content();
											$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
											echo wp_trim_words( $content, 120, '...' );
									    }
									?>
									<div class="placard-more fd"><a href="<?php the_permalink(); ?>" rel="external nofollow"></a></div>
								</div>
							<?php endforeach; endif; ?>
							<?php wp_reset_query(); ?>
						<?php } ?>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="tcb-qq tcb-pl"><div></div><div></div><div></div><div></div><div></div></div>
			</div>
			<div class="placard-close yy dah"></div>
		</div>
	</div>
<?php } ?>