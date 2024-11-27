<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

	<?php begin_primary_class(); ?>
		<main id="main" class="site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?><?php if (get_post_meta($post->ID, 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template/content', 'page' ); ?>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php endif; ?>

		<?php endwhile; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php if ( !get_post_meta($post->ID, 'no_sidebar', true) ) { ?>
	<?php if ( class_exists( 'DW_Question_Answer' ) ) { ?>
		<?php if (!is_singular( 'dwqa-question' ) ) { ?>
			<?php get_sidebar(); ?>
		<?php } ?>
	<?php } else { ?>
		<?php get_sidebar(); ?>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>