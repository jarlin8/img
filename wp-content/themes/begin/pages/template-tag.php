<?php
/*
Template Name: 热门标签
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>
	<div id="primary-width" class="content-area">
		<main id="main" class="be-main site-main" role="main">
			<article id="post-<?php the_ID(); ?>" class="post-item post">
				<?php specs_show_tags(); ?>
				<div class="clear"></div>
			</article><!-- #page -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>