<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<div id="primary-width" class="content-area">
	<main id="main" class="be-main site-main sites-main site-main-cat<?php if ( zm_get_option( 'site_cat_fixed' ) ) { ?> site-cat-fixed<?php } ?>" role="main">
		<?php if ( zm_get_option( 'all_site_cat' ) ) { ?><?php all_sites_cat(); ?><?php } ?>
		<div class="sites-all bk" <?php aos_a(); ?>>
			<?php
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$tax = get_query_var( 'taxonomy' );
			?>

			<?php if ( get_term_children( $term->term_id, $tax ) != null ) { ?>
				<?php $term_children = get_terms( $tax, array( 'child_of' => $term->term_id ) ); ?>
				<?php foreach ( $term_children as $term_child ) { ?>

					<div class="group-title bky" <?php aos_b(); ?>>
						<h3 class="sites-cat-name"><a href="<?php echo get_term_link( $term_child->term_id ); ?>" ><?php echo $term_child->name; ?></a></h3>
						<div class="clear"></div>
					</div>

					<?php query_posts( array( 'favorites' => $term_child->slug ) ); ?>
					<div class="sites-box">
						<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<div class="sites-area sites-<?php echo zm_get_option( 'site_f' ); ?>">
								<?php sites_favorites(); ?>
							</div>
						<?php endwhile; ?>
						<div class="clear"></div>
						<a class="sites-cat-more" href="<?php echo get_term_link( $term_child->term_id ); ?>" ><i class="be be-more"></i></a>
						<?php else : ?>
							您还没有添加网址
						<?php endif;wp_reset_query(); ?>
					</div>
				<?php } ?>

			<?php } else { ?>

				<div class="sites-box">
					<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="sites-area sites-<?php echo zm_get_option( 'site_f' ); ?>">
							<?php sites_favorites(); ?>
						</div>
					<?php endwhile; ?>
					<?php else : ?>
						您还没有添加网址
					<?php endif;wp_reset_query(); ?>
				</div>
				<?php begin_pagenav(); ?>

			<?php } ?>

		</div>
	</main>
</div>
<?php if ( zm_get_option( 'sites_cat_id' ) ) { ?>
<style type="text/css">
.term-<?php echo zm_get_option( 'sites_cat_id' ); ?> .sites-cat-count {
	display: none;
}
</style>
<?php } ?>
<?php get_footer(); ?>