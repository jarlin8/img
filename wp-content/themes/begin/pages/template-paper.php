<?php
/*
Template Name: 空白模板
Template Post Type: post, page
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<div id="<?php if ( get_post_meta(get_the_ID(), 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="paper-sidebar<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) ) { ?> paper-area no-sidebar<?php } else { ?> content-area<?php } ?>">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>"  class="page-item type-page status-publish">
				<div class="paper-content">
					<?php the_content(); ?>
				<div class="clear"></div>
				<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
				<div class="clear"></div>
				</div>
			</article>
		<?php endwhile; ?>
	</main>
</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) ) { ?>
	<?php if ( class_exists( 'DW_Question_Answer' ) ) { ?>
		<?php if ( ! is_singular( 'dwqa-question' ) ) { ?>
			<?php get_sidebar(); ?>
		<?php } ?>
	<?php } else { ?>
		<?php get_sidebar(); ?>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>