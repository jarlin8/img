<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 简化模板
Template Post Type: post
*/
get_header(); ?>

	<div id="primary" class="content-area no-sidebar">

		<main id="main" class="be-main qa-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>


				<?php if ( ( ! get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'no_show_title', true ) ) && ( ! get_post_meta( get_the_ID(), 'header_bg', true ) ||  get_post_meta( get_the_ID(), 'no_img_title', true ) ) ) { ?>
					<?php header_title(); ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>
				<?php } ?>


					<div class="entry-content">
						<?php begin_single_meta(); ?>

						<div class="single-content">
							<?php the_content(); ?>
						</div>

						<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
							<?php be_like(); ?>
						<?php } ?>

						<div class="clear"></div>
					</div>
				</article>
				<?php begin_comments(); ?>
			<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>