<?php
/*
Template Name: 留言板
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.comment-reply-title span, .comments-title{
	display: none;
}

.comment-reply-title:after {
	content: '给我留言';
}
</style>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', 'page' ); ?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>