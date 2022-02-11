<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<?php begin_primary_class(); ?>
	<main id="main" class="site-main<?php if ( zm_get_option('p_first' ) ) { ?> p-em<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'ms bk' ); ?>>

				<?php header_title(); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<!-- <?php begin_single_meta(); ?> -->

				<div class="entry-content">
					<div class="single-content">
						<div class="tao-goods">
							<figure class="tao-img">
								<?php tao_thumbnail(); ?>
							</figure>

							<div class="brief">
								<span class="product-m">
									<?php $price = get_post_meta( $post->ID, 'product', true );{ echo $price; }?>
									<?php edit_post_link( '<i class="be be-editor"></i>' ); ?>
								</span>
								<span class="pricex"><strong>￥<?php $price = get_post_meta( $post->ID, 'pricex', true );{ echo $price; }?>元</strong></span>
								<?php if ( get_post_meta( $post->ID, 'pricey', true ) ) : ?>
									<span class="pricey"><del>市场价:<?php $price = get_post_meta( $post->ID, 'pricey', true );{ echo $price; }?>元</del></span>
								<?php endif; ?>

								<?php if ( get_post_meta( $post->ID, 'discount', true ) ) : ?>
									<?php
										$discount = get_post_meta( $post->ID, 'discount', true );
										$url = get_post_meta( $post->ID, 'discounturl', true );
										echo '<span class="discount"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url dah bk">' . $discount . '</a></span>';
									 ?>
								<?php endif; ?>

								<?php if ( get_post_meta( $post->ID, 'taourl', true ) ) : ?>
									<?php
										if ( get_post_meta( $post->ID, 'm_taourl', true ) && wp_is_mobile() ) {
											$url = get_post_meta( $post->ID, 'm_taourl', true );
										} else {
											$url = get_post_meta( $post->ID, 'taourl', true );
										}

										$taourl_t = get_post_meta( $post->ID, 'taourl_t', true );
										if ( get_post_meta( $post->ID, 'taourl_t', true ) ) :
											echo '<span class="taourl"><a href=' . $url.' rel="external nofollow" target="_blank" class="url">' . $taourl_t . '</a></span>';
										else :
											echo '<span class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">直接购买</a></span>';
										endif;
									?>
								<?php endif; ?>

							</div>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>

						<?php the_content(); ?>
						<div class="clear"></div>
						<?php begin_link_pages(); ?>
					</div>

						<?php be_like(); ?>
						<?php if ( zm_get_option( 'single_weixin' ) ) { ?>
							<?php get_template_part( 'template/weixin' ); ?>
						<?php } ?>
						<div class="content-empty"></div>

						<footer class="single-footer">
							<div class="single-cat-tag">
								<div class="single-cat">分类：<?php echo get_the_term_list( $post->ID,  'taobao', '' ); ?></div>
							</div>
						</footer>

					<div class="clear"></div>
				</div>

			</article>

			<?php if ( zm_get_option('copyright' ) ) { ?>
				<?php get_template_part( 'template/copyright' ); ?>
			<?php } ?>

			<?php if ( zm_get_option( 'related_img' ) ) { ?>
				<?php get_template_part( 'template/related-tao' ); ?>
			<?php } ?>

			<?php get_template_part( 'ad/ads', 'comments' ); ?>

			<?php type_nav_single(); ?>

			<?php begin_comments(); ?>

		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php if ( get_post_meta( $post->ID, 'no_sidebar', true ) || ( zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php } else { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>