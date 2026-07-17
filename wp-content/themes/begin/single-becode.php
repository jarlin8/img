<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

	<div id="primary" class="content-area no-sidebar">
		<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>

					<header class="entry-header entry-header-c header-becode">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>
					<div class="begin-single-meta begin-single-meta-c">
						<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' ); ?>
					</div>

					<div class="entry-content">
						<div class="becode-content">
							<?php the_content(); ?>
						</div>
						<div class="clear"></div>
					</div>

				</article>
			<?php endwhile; ?>

		</main>
	</div>
<?php get_footer(); ?>