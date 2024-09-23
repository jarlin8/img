<?php
/*
Template Name: 商品分类
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>
<section id="tao" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
	<main id="main" class="be-main site-main" role="main">
		<?php
		$taxonomy = 'taobao';
		$terms = get_terms( $taxonomy ); foreach ( $terms as $cat ) {
		$catid = $cat->term_id;
		$args = array(
			'showposts' => zm_get_option( 'custom_cat_n' ),
			'tax_query' => array( array( 'taxonomy' => $taxonomy, 'terms' => $catid, 'include_children' => false ) )
		);
		$query = new WP_Query($args);
		if ( $query->have_posts() ) { ?>
		<div class="clear"></div>
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_a(); ?>><a href="<?php echo get_term_link( $cat ); ?>" title="<?php _e( '更多', 'begin' ); ?>"><?php echo $cat->name; ?></a></h3>
		</div>
		<div class="clear"></div>
		<?php while ($query->have_posts()) : $query->the_post();?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post">
				<div class="tao-box ms" <?php aos_a(); ?>>
					<figure class="tao-img">
						<?php echo tao_thumbnail(); ?>
						<?php if ( get_post_meta( get_the_ID(), 'tao_img_t', true ) ) : ?>
							<div class="tao-dis"><?php $tao_img_t = get_post_meta( get_the_ID(), 'tao_img_t', true );{ echo $tao_img_t; } ?></div>
						<?php endif; ?>
					</figure>
					<div class="product-box">
						<?php the_title( sprintf( '<h2 class="product-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
								<div class="detail"><a href="<?php the_permalink(); ?>" rel="bookmark">详情</a></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</article>
		<?php endwhile; ?>
		<div class="clear"></div>
		<div class="grid-cat-more" <?php aos_a(); ?>><a href="<?php echo get_term_link( $cat ); ?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a></div>
		<?php } wp_reset_postdata(); ?>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>