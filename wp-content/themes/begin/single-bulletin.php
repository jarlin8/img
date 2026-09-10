<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

	<?php begin_primary_class(); ?>
		<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>

					<?php header_title(); ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>

					<?php begin_single_meta(); ?>

					<div class="entry-content">
						<div class="single-content">
							<?php get_template_part('ad/ads', 'single'); ?>
							<?php the_content(); ?>
						</div>

						<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => "")); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="be be-arrowright"></i></span> ')); ?>

						<?php if (zm_get_option('single_weixin')) { ?>
							<?php get_template_part( 'template/weixin' ); ?>
						<?php } ?>
						<div class="content-empty"></div>

						<footer class="single-footer">
							<div class="single-cat-tag">
								<div class="single-cat"><i class="be be-sort"></i><?php echo get_the_term_list( $post->ID, 'notice', '' ); ?></div>
							</div>
						</footer>

						<div class="clear"></div>
					</div>

				</article>

				<?php if (zm_get_option('copyright')) { ?>
					<?php get_template_part( 'template/copyright' ); ?>
				<?php } ?>

				<?php type_nav_single(); ?>

				<?php begin_comments(); ?>

			<?php endwhile; ?>

		</main>
	</div>
<?php get_footer(); ?>