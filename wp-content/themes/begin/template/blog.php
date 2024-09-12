<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php blog_template(); ?>
	<?php if ( zm_get_option( 'order_btu' ) && ! is_paged() && ! zm_get_option( 'blog_ajax' ) ) { ?><?php be_order_btu(); ?><?php } ?>
	<div class="blog-main">
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			if ( zm_get_option( 'blog_not_cat' ) ) {
				$notcat = implode( ',', zm_get_option( 'blog_not_cat' ) );
			} else {
				$notcat = '';
			}

			$args = array(
				'category__not_in' => explode( ',', $notcat ),
				'ignore_sticky_posts' => 0, 
				'paged' => $paged
			);
			query_posts( $args );

			if ( zm_get_option( 'order_btu' ) ) {
				be_order();
			}
		?>

		<?php if ( zm_get_option( 'blog_ajax' ) ) { ?>

			<?php 
				if ( zm_get_option( 'blog_ajax_id' ) ) {
					$cat_ids = implode( ',', zm_get_option( 'blog_ajax_id' ) );
				} else {
					$cat_ids = '';
				}
				echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" posts_per_page="' . zm_get_option( 'blog_ajax_n' ) . '" style="' . zm_get_option( 'blog_ajax_cat_style' ) . '" btn="' . zm_get_option( 'blog_ajax_cat_btn' ) . '" more="' . zm_get_option( 'blog_ajax_nav_btn' ) . '" infinite="' . zm_get_option( 'blog_ajax_infinite' ) . '" column="2" sticky="0"]' );
			?>

		<?php } else { ?>

			<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php get_template_part('ad/ads', 'archive'); ?>

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>
			<?php endif; ?>

		<?php } ?>

	</div>
</main>

<?php if ( ! zm_get_option( 'blog_ajax' ) ) { ?><?php begin_pagenav(); ?><?php } ?>

</div>
<?php blog_sidebar(); ?>
<?php get_footer(); ?>