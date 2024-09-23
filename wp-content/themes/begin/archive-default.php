<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 标准模板
 */
get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="be-main site-main<?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
		<?php get_template_part( 'template/cat-top' ); ?>

		<?php be_exclude_child_cats(); ?>

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', get_post_format() ); ?>

			<?php get_template_part('ad/ads', 'archive'); ?>

		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'template/content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- .site-main -->

	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>