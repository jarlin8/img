<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<?php begin_primary_class(); ?>
	<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
				<?php header_title(); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div class="entry-content">
					<div class="single-content">
						<div class="tao-goods">
							<figure class="tao-img">
								<?php echo tao_thumbnail(); ?>
							</figure>

							<div class="brief">
								<span class="product-m">
									<?php $price = get_post_meta( get_the_ID(), 'product', true );{ echo $price; }?>
									<?php edit_post_link( '<i class="be be-editor"></i>' ); ?>
								</span>

								<?php be_vip_meta(); ?>

								<div class="clear"></div>

								<?php 
									$pricex = get_post_meta( get_the_ID(), 'pricex', true );
									if ( $pricex ) {
										echo '<span class="pricex">';
										echo '<strong>';
										echo '￥' . $pricex . ' 元';
										echo '</strong>';
										echo '</span>';
									}
								?>

								<?php 
									$pricey = get_post_meta( get_the_ID(), 'pricey', true );
									if ( $pricey ) {
										echo '<span class="pricey">';
										echo '<del>';
										echo $pricey . ' 元';
										echo '</del>';
										echo '</span>';
									}
								?>

								<div class="clear"></div>

								<?php if ( get_post_meta( get_the_ID(), 'discount', true ) ) : ?>
									<?php
										$discount = get_post_meta( get_the_ID(), 'discount', true );
										$url = get_post_meta( get_the_ID(), 'discounturl', true );
										echo '<span class="discount"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">' . $discount . '</a></span>';
									 ?>
								<?php endif; ?>

								<?php if ( get_post_meta( get_the_ID(), 'taourl', true ) ) : ?>
									<?php
										if ( get_post_meta( get_the_ID(), 'm_taourl', true ) && wp_is_mobile() ) {
											$url = get_post_meta( get_the_ID(), 'm_taourl', true );
										} else {
											$url = get_post_meta( get_the_ID(), 'taourl', true );
										}

										$taourl_t = get_post_meta( get_the_ID(), 'taourl_t', true );
										if ( get_post_meta( get_the_ID(), 'taourl_t', true ) ) :
											echo '<span class="taourl"><a href=' . $url.' rel="external nofollow" target="_blank" class="url">' . $taourl_t . '</a></span>';
										else :
											echo '<span class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">直接购买</a></span>';
										endif;
									?>
								<?php endif; ?>

								<!-- VIP下载 -->
								<?php if ( get_post_meta( get_the_ID(), 'vip_url', true ) ) { ?>
									<?php
										$url = get_post_meta( get_the_ID(), 'vip_url', true );
										$vip_text = get_post_meta( get_the_ID(), 'vip_text', true );
										$login_text = get_post_meta( get_the_ID(), 'vip_login_text', true );
										if ( ! is_user_logged_in() ) {
											if ( ! $login_text ) {
												echo '<span class="tao-vip-login show-layer">会员免费下载</span>';
											} else {
												echo '<span class="tao-vip-login show-layer">' . $login_text . '</span>';
											}
										} else {
											if ( ! $vip_text ) {
												echo '<span class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">立即升级会员</a></span>';
											} else {
												echo '<span class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">' . $vip_text . '</a></span>';
											}
										}
									?>
									<span class="taourl"><span class="tao-down-btn">立即下载</span></span>
								<?php } ?>

							</div>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>

						<?php the_content(); ?>
						<div class="clear"></div>
						<?php begin_link_pages(); ?>
						<?php echo bedown_show(); ?>
					</div>

						<?php logic_notice(); ?>

						<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
							<?php be_like(); ?>
						<?php } ?>
						<?php if ( zm_get_option( 'single_weixin' ) ) { ?>
							<?php get_template_part( 'template/weixin' ); ?>
						<?php } ?>
						<div class="content-empty"></div>

						<footer class="single-footer">
							<div class="single-cat-tag dah">
								<div class="single-cat dah">分类：<?php echo get_the_term_list( $post->ID, 'taobao', '' ); ?></div>
							</div>
						</footer>

					<div class="clear"></div>
				</div>

			</article>

			<div class="single-tag"><?php echo get_the_term_list( $post->ID, 'taotag', '<ul class="wow fadeInUp" data-wow-delay="0.3s"><li>', '</li><li>', '</li></ul>' ); ?></div>

			<?php if ( zm_get_option('copyright' ) ) { ?>
				<?php get_template_part( 'template/copyright' ); ?>
			<?php } ?>

			<?php get_template_part( 'template/related-tao' ); ?>

			<?php get_template_part( 'ad/ads', 'comments' ); ?>

			<?php type_nav_single(); ?>

			<?php begin_comments(); ?>

		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>