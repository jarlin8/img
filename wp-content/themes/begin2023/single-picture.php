<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

	<?php begin_primary_class(); ?>
		<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk'); ?>>

					<?php header_title(); ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<?php begin_single_meta(); ?>

					<div class="entry-content">
						<div class="single-content">
							<?php begin_abstract(); ?>
							<?php get_template_part('ad/ads', 'single'); ?>

							<?php the_content(); ?>
							<?php get_template_part( 'inc/file' ); ?>
						</div>

						<?php begin_link_pages(); ?>
						<?php echo bedown_show(); ?>

						<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
							<?php be_like(); ?>
						<?php } ?>
						<?php if (zm_get_option('single_weixin')) { ?>
							<?php get_template_part( 'template/weixin' ); ?>
						<?php } ?>
						<div class="content-empty"></div>

						<?php get_template_part('ad/ads', 'single-b'); ?>

						<footer class="single-footer">
							<div class="single-cat-tag dah">
								<div class="single-cat dah">
									<i class="be be-sort"></i>
									<?php echo get_the_term_list( $post->ID, 'gallery', '' ); ?>
								</div>
							</div>
						</footer><!-- .entry-footer -->

						<div class="clear"></div>
					</div><!-- .entry-content -->

				</article><!-- #post -->

				<?php if (zm_get_option('copyright')) { ?>
					<?php get_template_part( 'template/copyright' ); ?>
				<?php } ?>

				<?php type_nav_single(); ?>

				<?php if (zm_get_option('related_img')) { ?>
					<div id="related-img" class="ms bk bgt" <?php aos_a(); ?>>
						<?php 
							$loop = new WP_Query( array( 'post_type' => 'picture', 'posts_per_page' => zm_get_option('related_n'), 'post__not_in' => array($post->ID) ) );
							while ( $loop->have_posts() ) : $loop->the_post();
						?>
						<div class="r4">
							<div class="related-site">
								<figure class="related-site-img">
									<?php zm_thumbnail(); ?>
								 </figure>
								<div class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
							</div>
						</div>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<?php get_template_part('ad/ads', 'comments'); ?>

				<?php begin_comments(); ?>

			<?php endwhile; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>