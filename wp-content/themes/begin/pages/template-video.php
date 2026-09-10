<?php
/*
Template Name: 视频分类
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
	<main id="main" class="be-main site-main" role="main">
		<?php
		$taxonomy = 'videos';
		$terms = get_terms($taxonomy); foreach ($terms as $cat) {
		$catid = $cat->term_id;
		$args = array(
			'showposts' => zm_get_option('custom_cat_n'),
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
				<div class="picture-box ms" <?php aos_a(); ?>>
					<figure class="picture-img">
						<?php echo videos_thumbnail(); ?>
					</figure>
					<?php the_title( sprintf( '<h3 class="picture-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				</div>
			</article><!-- #post -->
		<?php endwhile; ?>
		<div class="clear"></div>
		<div class="grid-cat-more" <?php aos_a(); ?>><a href="<?php echo get_term_link( $cat ); ?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a></div>
		<?php } wp_reset_postdata(); ?>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>