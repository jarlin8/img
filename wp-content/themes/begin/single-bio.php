<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 简介模板
Template Post Type: post
*/
get_header(); ?>
	<?php begin_primary_class(); ?>

		<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" class="post-item post single-download ms" <?php aos_a(); ?>>
					
					<?php if ( ! get_post_meta( get_the_ID(), 'be_inf_ext', true ) ) { ?>
						<?php header_title(); ?>
							<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
						</header>
					<?php } ?>
					<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
					<?php inf_bio(); ?>

					<div class="bio-entry-content">
						<div class="tab-down-wrap">
							<div class="tab-down-nav">
								<div class="tab-down-item active"><?php echo zm_get_option('tab_bio_a'); ?></div>
								<div class="tab-down-item"><?php echo zm_get_option('tab_bio_b'); ?></div>
							</div>
							<div class="clear"></div>

							<div class="tab-down-content">
								<div class="tab-content-item show">
									<div class="single-content">
										<?php 
											remove_filter( 'the_content', 'be_ext_inf_content_beforde' );
											remove_filter( 'the_content', 'down_doc_box' );
											remove_filter( 'the_content', 'begin_show_down' );
											the_content();
										?>
									</div>
									<div class="clear"></div>
								</div>
								<div class="tab-content-item tab-content-comments">
									<?php begin_comments(); ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<?php content_support_down(); ?>
						<div class="clear"></div>
					</div>
				</article>

				<?php be_tags(); ?>

				<?php if (zm_get_option('copyright')) { ?>
					<?php get_template_part( 'template/copyright' ); ?>
				<?php } ?>

				<?php if ( zm_get_option( 'single_tab_tags' ) ) { ?>
					<?php get_template_part( '/template/single-code-tag' ); ?>
				<?php } ?>

				<?php if ( zm_get_option( 'related_tao' ) ) { ?>
					<?php get_template_part( 'template/related-tao' ); ?>
				<?php } ?>

				<?php get_template_part( 'template/single-widget' ); ?>

				<?php get_template_part( 'template/single-scrolling' ); ?>

				<?php if ( ! zm_get_option( 'related_img' ) || ( zm_get_option( 'related_img' ) == 'related_outside' ) ) { ?>
					<?php 
						if ( zm_get_option( 'not_related_cat' ) ) {
							$notcat = implode( ',', zm_get_option( 'not_related_cat' ) );
						} else {
							$notcat = '';
						}
						if ( ! in_category( explode( ',', $notcat ) ) ) { ?>
						<?php get_template_part( 'template/related-img' ); ?>
					<?php } ?>
				<?php } ?>

				<?php nav_single(); ?>

			<?php endwhile; ?>

		</main>
	</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>