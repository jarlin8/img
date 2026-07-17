<?php
/*
Template Name: Woo商城
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option('img_f'); ?>">
	<main id="main" class="site-main" role="main">
		<?php
		$taxonomy = 'product_cat'; 
		$terms = get_terms($taxonomy); foreach ($terms as $cat) {
		$catid = $cat->term_id;
		$args = array(
			'showposts' => zm_get_option('custom_cat_n'),
			'tax_query' => array( array( 'taxonomy' => $taxonomy, 'terms' => $catid, 'include_children' => false ) )
		);
		$query = new WP_Query($args);
		if( $query->have_posts() ) { ?>
		<div class="clear"></div>
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_a(); ?>><a href="<?php echo get_term_link( $cat ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $cat->name; ?></a></h3>
			<div class="grid-cat-des" <?php aos_a(); ?>><?php echo category_description( $cat ); ?></div>
		</div>
		<div class="clear"></div>
		<?php while ($query->have_posts()) : $query->the_post();?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="boxs1">
					<div class="picture-box woo-cat-main be-woo-box ms bk" <?php aos_a(); ?>>
						<figure class="picture-img">
							<?php echo zm_thumbnail(); ?>
							<?php
								global $post, $product;
								if ( $product->is_on_sale() ) :
								echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( '促销中', 'begin' ) . '</span>', $post, $product );
								endif;
							?>
						</figure>
						<?php the_title( sprintf( '<h3 class="woo-cat-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
						<?php global $product; if ( $price_html = $product->get_price_html() ) : ?>
							<span class="price"><?php echo $price_html; ?></span>
						<?php endif; ?>
						<!-- <div class="woo-url cat-woo-url"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( '详情', 'begin' ); ?></a></div> -->
					</div>
				</div>
				<div class="clear"></div>
			</article>
		<?php endwhile; ?>
		<div class="clear"></div>
		<div class="grid-cat-more" <?php aos_a(); ?>><a href="<?php echo get_term_link( $cat ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
		<?php } wp_reset_query(); ?>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>