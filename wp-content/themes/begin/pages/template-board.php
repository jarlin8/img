<?php
/*
Template Name: 留言板
Template Post Type: page
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<style type="text/css">
.comment-reply-title span, .comments-title{
	display: none;
}

.comment-reply-title:after {
	content: '给我留言';
}
</style>

<div id="primary" class="content-area no-sidebar">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', 'page' ); ?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>