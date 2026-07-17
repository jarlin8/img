<?php
/*
* Template Name: 读者排行
* Description：留言者排行
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php get_header(); ?>
	<div id="primary" class="content-area no-sidebar">
		<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
	    		<?php if ( get_the_content() ) { ?>
					<article id="post-<?php the_ID(); ?>" class="comment-authors post-item post ms">
						<div class="entry-content">
							<div class="single-content">
								<?php the_content(); ?>
							</div>
							<div class="clear"></div>
							<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
							<div class="clear"></div>
						</div>
					</article>
				<?php } ?>
			<?php endwhile; ?>
			<?php top_comment_authors( '98' ); ?>
			<div class="clear"></div>
		</main>
	</div>
<?php get_footer(); ?>