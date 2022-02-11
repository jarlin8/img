<?php
/*
Template Name: 网址收藏
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>
<div id="primary-width" class="content-area">
	<main id="main" class="site-main site-main-cat<?php if ( zm_get_option( 'site_cat_fixed' ) ) { ?> site-cat-fixed<?php } ?>" role="main">
		<?php if ( zm_get_option( 'all_site_cat' ) ) { ?><?php all_sites_cat(); ?><?php } ?>
		<?php $taxonomy = 'favorites';$terms = get_terms($taxonomy); foreach ( $terms as $cat ) { ?>
		<?php 
			$catid = $cat->term_id;
			$args = array(
				'showposts'   => zm_get_option( 'site_p_n' ),
				'post_status' => 'publish',
				'meta_key'    => 'sites_order',
				'orderby'     => 'meta_value',
				'order'       => 'DESC',
				'tax_query'   => array(
					array(
						'taxonomy' => $taxonomy,
						'terms' => $catid,
						'include_children' => false 
					)
				)
			);
			$query = new WP_Query($args);
		?>
		<div class="sites-all sites-catid-<?php echo $cat->term_id; ?>" <?php aos_a(); ?>>
			<div class="group-title bky" <?php aos_b(); ?>>
				<h3 class="sites-cat-name"><a href="<?php echo get_term_link( $cat ); ?>" ><?php echo $cat->name; ?></a></h3>
				<div class="sites-cat-des"><?php echo category_description( $catid ); ?></div>
				<div class="clear"></div>
			</div>
			<div class="sites-box">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="sites-area sites-<?php echo zm_get_option( 'site_f' ); ?>">
						<?php sites_favorites(); ?>
					</div>
				<?php endwhile; wp_reset_query(); ?>
				<div class="clear"></div>
				<a class="sites-cat-more" href="<?php echo get_term_link( $cat ); ?>" ><i class="be be-more"></i></a>

				<?php if( $catid == zm_get_option( 'sites_widgets_one_n' ) ) { ?>
					<div class="sites-all da">
						<div class="sites-widget da sites-widget-<?php echo zm_get_option( 'sw_f' ); ?>">
							<div class="slf">
								<?php dynamic_sidebar( 'favorites-one' ); ?>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
		<?php } ?>
	</main>
</div>
<?php if( zm_get_option( 'sites_cat_id' ) ) { ?>
<style type="text/css">
.sites-catid-<?php echo zm_get_option( 'sites_cat_id' ); ?> {
	display: none;
}
</style>
<?php } ?>
<?php get_footer(); ?>