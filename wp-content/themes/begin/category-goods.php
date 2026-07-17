<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<section id="tao" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post tao scl cat-tao-fl-<?php echo be_get_option( 'cat_tao_f' ); ?>">
			<div class="tao-box sup" <?php aos_a(); ?>>
				<figure class="tao-img">
					<?php echo tao_thumbnail(); ?>
					<?php if ( get_post_meta( get_the_ID(), 'tao_img_t', true ) ) : ?>
						<div class="tao-dis"><?php $tao_img_t = get_post_meta( get_the_ID(), 'tao_img_t', true );{ echo $tao_img_t; } ?></div>
					<?php endif; ?>
				</figure>
				<div class="product-box">
					<?php the_title( sprintf( '<h2 class="product-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<div class="ded">
						<ul class="price">
						<?php echo be_vip_meta(); ?>
							<?php 
								$pricex = get_post_meta( get_the_ID(), 'pricex', true );
								if ( $pricex ) {
									echo '<li class="pricex">';
									echo '<strong>';
									echo $pricex . '元';
									echo '</strong>';
									echo '</li>';
								} 
							?>

							<li class="pricey">
								<?php if ( !get_post_meta( get_the_ID(), 'pricey', true ) && !get_post_meta( get_the_ID(), 'spare_t', true ) ){ ?>
								<?php } else { ?>
									<?php if ( get_post_meta( get_the_ID(), 'pricey', true ) ) : ?>
										<del><?php $price = get_post_meta( get_the_ID(), 'pricey', true );{ echo $price; } ?>元</del>
									<?php endif; ?>

									<?php if ( get_post_meta( get_the_ID(), 'spare_t', true ) ) : ?>
										<?php $spare_t = get_post_meta( get_the_ID(), 'spare_t', true);{ echo $spare_t; } ?>
									<?php endif; ?>
								<?php } ?>
							</li>
						</ul>
						<div class="go-url">
							<div class="taourl">
								<?php if ( get_post_meta( get_the_ID(), 'taourl', true ) ) : ?>
									<?php
										if ( get_post_meta( get_the_ID(), 'm_taourl', true ) && wp_is_mobile() ) {
											$url = get_post_meta( get_the_ID(), 'm_taourl', true );
										} else {
											$url = get_post_meta( get_the_ID(), 'taourl', true );
										}
										echo '<div class="taourl"><a href=' . $url . ' rel="external nofollow" target="_blank" class="url">购买</a></div>';
									?>
								<?php endif; ?>
							</div>
							<div class="detail"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>>详情</a></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</article>
		<?php endwhile; ?>
	</main><!-- .site-main -->
	<?php begin_pagenav(); ?>
	<div class="clear"></div>
</section><!-- .content-area -->

<?php get_footer(); ?>