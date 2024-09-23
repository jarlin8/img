<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 会员资源
Template Post Type: post
*/
if ( get_post_meta( get_the_ID(), 'ed_down_start', true ) && is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
	remove_filter( 'the_content', 'add_ed_down_inf' );
}
get_header(); ?>
	<?php begin_primary_class(); ?>

		<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if ( get_post_meta(get_the_ID(), 'sub_section', true ) ) { ?> sub-h<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" class="post-item post single-download ms" <?php aos_a(); ?>>

					<?php header_title(); ?>
						<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
							<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
						<?php } ?>
					</header>

					<div class="entry-content">
						<?php if ( ! get_post_meta( get_the_ID(), 'header_img', true ) && !get_post_meta( get_the_ID(), 'header_bg', true ) ) : ?>
						<div class="vip-single-meta<?php if ( get_post_meta( get_the_ID(), 'ed_down_basic', true ) ) { ?> vip-meta<?php } ?>">
							<?php begin_single_meta(); ?>
						</div>
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
										<?php $file_os = get_post_meta( get_the_ID(), 'file_os', true ); ?>
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
						<?php if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) { ?>
							<?php echo be_ed_down_inf(); ?>
						<?php } ?>
						<div class="single-content">
							<?php begin_abstract(); ?>
							<?php get_template_part( 'ad/ads', 'single' ); ?>
							<div class="clear"></div>
							<div class="tab-down-wrap">
								<div class="tab-down-nav">
									<div class="tab-down-item active"><?php _e( '资源简介', 'begin' ); ?></div>
									<?php $tab_quid = get_post_meta( get_the_ID(), 'down_tab_quid', true ); ?>
									<?php $tab_qu_title = get_post_meta( get_the_ID(), 'tab_qu_title', true ); ?>
									<?php if ( $tab_quid ) { ?>
										<?php if ( $tab_qu_title ) { ?>
											<div class="tab-down-item"><?php echo $tab_qu_title; ?></div>
										<?php } else { ?>
											<div class="tab-down-item"><?php _e( '常见问题', 'begin' ); ?></div>
										<?php } ?>
									<?php } else { ?>
										<div class="tab-down-item"><?php _e( '下载说明', 'begin' ); ?></div>
									<?php } ?>
									<?php if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) { ?>
										<div class="tab-down-item" onclick="fetchContent()"><?php _e( '文件下载', 'begin' ); ?></div>
									<?php } ?>
									<?php if ( ! zm_get_option( 'close_comments' ) ) { ?>
										<div class="tab-down-item-url"><?php _e( '评论留言', 'begin' ); ?></div>
									<?php } ?>
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

											<?php if ( $tab_quid ) { ?>
												<div class="erphpdown-tips">
													<?php
														$page_data = get_page( $tab_quid );
														if ( $page_data ) {
															echo apply_filters( 'the_content', $page_data->post_content );
														} else {
															echo '请输入正确的页面ID';
														}
													?>
												</div>
											<?php } else { ?>
												<?php if ( get_option( 'ice_tips' ) ) { ?>
													<div class="erphpdown-tips"><?php echo get_option('ice_tips'); ?></div>
												<?php } else { ?>
													<div class="erphpdown-tips">暂无</div>
												<?php } ?>
											<?php } ?>

										<div class="clear"></div>
									</div>
									<?php if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) { ?>
										<div class="tab-content-item tab-content-license">
												<div class="erphpdown-tips">
													<div id="down-version"><div class="ajax-loading"></div><div class="down-version-error">您无权限查看文件下载链接！</div></div>
												</div>

											<div class="clear"></div>
										</div>
									<?php } ?>
								</div>
							</div>

							<?php echo down_doc_box( $post->content ); ?>
							<?php echo begin_show_down( $post->content ); ?>

							<?php if ( zm_get_option( 'single_assets_place' ) == 'content' ) { ?>
								<?php get_template_part( '/template/single-assets' ); ?>
							<?php } ?>

						</div>
						<?php content_support_vip(); ?>
						<div class="clear"></div>
					</div>
				</article>

				<?php be_tags(); ?>

				<?php if ( ! zm_get_option( 'single_assets_place' ) || ( zm_get_option( 'single_assets_place' ) == 'article' ) ) { ?>
					<?php get_template_part( '/template/single-assets' ); ?>
				<?php } ?>

				<?php nav_single(); ?>

				<?php get_template_part('ad/ads', 'comments'); ?>

				<div class="down-area"></div>

				<?php begin_comments(); ?>

			<?php endwhile; ?>

		</main>
	</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>