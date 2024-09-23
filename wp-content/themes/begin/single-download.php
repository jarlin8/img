<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 下载模板
Template Post Type: post
*/
get_header(); ?>
	<?php begin_primary_class(); ?>

		<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" class="post-item post single-download ms" <?php aos_a(); ?>>

					<?php header_title(); ?>
						<?php if ( get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'header_bg', true ) ) { ?>
						<?php } else { ?>
							<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
						<?php } ?>
					</header>

					<div class="entry-content">
						<?php if ( ! get_post_meta( get_the_ID(), 'header_img', true ) && !get_post_meta( get_the_ID(), 'header_bg', true ) ) : ?>
							<?php begin_single_meta(); ?>
						<?php endif; ?>

						<?php if ( ! get_post_meta( get_the_ID(), 'be_inf_ext', true ) && get_post_meta( get_the_ID(), 'down_start', true ) ) { ?>
							<div class="videos-content">
								<div class="video-img-box">
									<div class="video-img">
										<?php echo zm_thumbnail(); ?>
									</div>
								</div>
								<div class="format-videos-inf">
									<span class="category"><strong><?php _e( '所属分类', 'begin' ); ?>：</strong><?php the_category( '&nbsp;&nbsp;' ); ?></span>
									<span>
										<?php $file_os = get_post_meta( get_the_ID(), 'file_os', true); ?>
										<?php if ( get_post_meta( get_the_ID(), 'be_file_os', true ) && get_post_meta( get_the_ID(), 'file_os', true ) ) { ?>
											<strong><?php _e( '应用平台', 'begin' ); ?>：</strong>
										<?php } ?>
										<?php echo $file_os; ?>
									</span>
									<span>
										<?php $file_inf = get_post_meta( get_the_ID(), 'file_inf', true); ?>
										<?php if ( get_post_meta( get_the_ID(), 'be_file_inf', true ) && get_post_meta( get_the_ID(), 'file_inf', true ) ) { ?>
											<strong><?php _e( '资源版本', 'begin' ); ?>：</strong>
										<?php } ?>
										<?php echo $file_inf; ?>
									</span>
									<span class="date"><strong><?php _e( '最后更新', 'begin' ); ?>：</strong><?php the_modified_time('Y年n月j日 H:s'); ?></span>
								</div>
								<div class="clear"></div>
							</div>
						<?php } ?>

						<?php inf_ext(); ?>

						<div class="single-content">
							<?php begin_abstract(); ?>
							<?php get_template_part( 'ad/ads', 'single' ); ?>
							<div class="clear"></div>
							<div class="tab-down-wrap">
								<div class="tab-down-nav">
									<div class="tab-down-item active"><?php _e( '资源简介', 'begin' ); ?></div>
									<div class="tab-down-item"><?php _e( '免责声明', 'begin' ); ?></div>
									<div class="tab-down-item-url"><?php _e( '下载地址', 'begin' ); ?></div>
								</div>
								<div class="clear"></div>

								<div class="tab-down-content">
									<div class="tab-content-item show">
										<?php 
											remove_filter( 'the_content', 'be_ext_inf_content_beforde' );
											remove_filter( 'the_content', 'down_doc_box' );
											remove_filter( 'the_content', 'begin_show_down' );
											the_content();
										?>
										<div class="clear"></div>
									</div>
									<div class="tab-content-item tab-content-license">
										<p><?php echo stripslashes( zm_get_option('down_explain') ); ?></p>
										<div class="clear"></div>
									</div>
								</div>
							</div>
							<div class="down-area"></div>
							<?php logic_notice(); ?>
							<?php echo down_doc_box( $post->content ); ?>
							<?php echo begin_show_down( $post->content ); ?>
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

				<?php get_template_part('ad/ads', 'comments'); ?>

				<?php begin_comments(); ?>

			<?php endwhile; ?>

		</main>
	</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>