<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

	<?php begin_primary_class(); ?>

		<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php if (zm_get_option('copyright')) { ?>
					<?php get_template_part( 'template/copyright' ); ?>
				<?php } ?>

				<?php if ( zm_get_option( 'single_tab_tags' ) ) { ?>
					<?php get_template_part( '/template/single-code-tag' ); ?>
				<?php } ?>

				<?php if ( zm_get_option( 'related_tao' ) ) { ?>
					<?php get_template_part( 'template/related-tao' ); ?>
				<?php } ?>

				<?php get_template_part( 'template/single-widget' ); ?>

				<?php get_template_part( 'template/single-scrolling' ); ?>

				<?php if ( ! zm_get_option( 'related_img' ) || ( zm_get_option( 'related_img' ) == 'related_outside' ) ) { ?>
					<?php 
						if ( zm_get_option( 'not_related_cat' ) ) {
							$notcat = implode( ',', zm_get_option( 'not_related_cat' ) );
						} else {
							$notcat = '';
						}
						if ( ! in_category( explode( ',', $notcat ) ) ) { ?>
						<?php get_template_part( 'template/related-img' ); ?>
					<?php } ?>
				<?php } ?>

				<?php nav_single(); ?>

				<?php get_template_part('ad/ads', 'comments'); ?>

				<?php begin_comments(); ?>

			<?php endwhile; ?>

		</main>
	</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>