<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 会员商品
 */
get_header(); ?>
	<section id="picture" class="picture-area content-area assets-main grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
		<main id="main" class="be-main site-main" role="main">
			<?php be_exclude_child_cats(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_a(); ?>>
					<div class="boxs1">
						<div class="picture-box ms">
							<figure class="picture-img">
								<?php echo tao_thumbnail(); ?>
								<?php echo t_mark(); ?>
							</figure>

							<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

							<div class="assets-area">
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
											if ( get_post_meta( $post->ID, 'down_price', true ) ) {
												echo '<div class="assets-more"><a href="' . get_the_permalink() . '" target="_blank">' . __( '购买', 'begin' ) . '</a></div>';
											}
											if ( ! get_post_meta( $post->ID, 'down_price_type', true ) ) {
												if ( ! get_post_meta( $post->ID, 'down_price', true ) ) {
													if ( get_post_meta( $post->ID, 'member_down', true ) ) {
														$member_down = get_post_meta( $post->ID, 'member_down', true );
														if ( $member_down > 3 && $member_down = 1 ){
															echo '<span class="be-vip-meta"><span class="be-vip-down"><i class="cx cx-svip"></i></span></span>';
														} else {
															echo '<div class="assets-more"><a href="' . get_the_permalink() . '" target="_blank">' . __( '详情', 'begin' ) . '</a></div>';
														}
													}
												}
											} else {
												echo '<span class="be-vip-meta"><span class="be-vip-down">' . __( '付费', 'begin' ) . '</span></span>';
											}
										} else {
											echo '<span class="assets-meta"><i class="ep ep-jifen ri"></i>' . __( '分享', 'begin' ) . '</span>';
											echo '<div class="assets-more assets-more-btn"><a href="' . get_the_permalink() . '" target="_blank">' . __( '查看', 'begin' ) . '</a></div>';
										}
									} else {
										echo '<span class="assets-meta">' . views_span() . '</span>';
										echo '<div class="assets-more assets-more-btn"><a href="' . get_the_permalink() . '" target="_blank">' . __( '查看', 'begin' ) . '</a></div>';
									}
								?>
							</div>
				 			<div class="clear"></div>
						</div>
					</div>
				</article>
			<?php endwhile;?>
		</main>
		<div><?php begin_pagenav(); ?></div>
		<div class="clear"></div>
	</section>
<?php get_footer(); ?>