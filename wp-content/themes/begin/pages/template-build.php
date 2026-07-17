<?php
/*
Template Name: 页面构建
Template Post Type: post, page
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
</div>
	<div id="build" class="build-area">
		<main id="main" class="be-main site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>"  class="page-item type-page status-publish">
					<div class="build-content">
						<?php the_content(); ?>
					<div class="clear"></div>
					<?php edit_post_link('<i class="be be-edit"></i> 编辑', '<div class="page-edit-link edit-link">', '</div>' ); ?>
					<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
	</div>
<div>
<?php get_footer(); ?>