<?php
/*
Template Name: 联系我们
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<div id="primary-contact" class="content-area primary-contact">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item post">
			<?php if ( ! get_post_meta( get_the_ID(), 'header_bg', true ) && ! get_post_meta( get_the_ID(), 'header_img', true ) ) { ?>
				<header class="entry-header"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></header>
			<?php } ?>
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<div class="contact-page">
						<?php echo be_display_contact_form(); ?>
					</div>
				</div>
				<div class="clear"></div>
				<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
				<div class="clear"></div>
			</div>
		</article>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>