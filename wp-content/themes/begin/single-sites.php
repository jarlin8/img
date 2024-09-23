<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php begin_primary_class(); ?>
	<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$sites_link = get_post_meta( get_the_ID(), 'sites_link', true );
				$sites_url = get_post_meta( get_the_ID(), 'sites_url', true );
				$sites_des = get_post_meta( get_the_ID(), 'sites_des', true );
				$sites_ico = get_post_meta( get_the_ID(), 'sites_ico', true );
				$sites_des = get_post_meta( get_the_ID(), 'sites_description', true );

				if ( cx_get_option( 'site_link_go' ) ) {
					$sites_url_go  = esc_url( get_permalink( cx_get_option( "site_link_go_id" ) ) ) . '?target=' . $sites_url;
					$sites_link_go = esc_url( get_permalink( cx_get_option( "site_link_go_id" ) ) ) . '?target=' . $sites_link;
				} else {
					$sites_url_go  = $sites_url;
					$sites_link_go = $sites_link;
				}
			?>

			<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
				<?php header_title(); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<div class="single-content">
						<?php begin_abstract(); ?>
						<?php get_template_part( 'ad/ads', 'single' ); ?>

						<?php if ( get_post_meta( get_the_ID(), 'sites_link', true ) ||  get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
							<fieldset class="sites-des">
								<legend>
									<div class="sites-icon">
										<?php if ( cx_get_option( 'sites_ico' ) ) { ?>
											<?php if ( get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
												<div class="sites-ico dah load">
												<?php if ( get_post_meta( get_the_ID(), 'sites_ico', true ) ) { ?>
													<img class="sites-img sites-ico-custom" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo $sites_ico; ?>" alt="<?php the_title(); ?>">
												<?php } else { ?>
													<img class="sites-img" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_url; ?>" alt="<?php the_title(); ?>">
												<?php } ?>
												</div>
											<?php } else { ?>
												<div class="sites-ico dah load">
												<?php if ( get_post_meta( get_the_ID(), 'sites_ico', true ) ) { ?>
													<img class="sites-img sites-ico-custom" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo $sites_ico; ?>" alt="<?php the_title(); ?>">
												<?php } else { ?>
													<img class="sites-img" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>">
												<?php } ?>
												</div>
											<?php } ?>
										<?php } else { ?>
											<?php _e( '网站描述', 'begin' ); ?>
										<?php } ?>
										<div class="clear"></div>
									</div>
								</legend>
									<?php if ( get_post_meta( get_the_ID(), 'sites_description', true ) || get_post_meta( get_the_ID(), 'sites_des', true ) ) { ?>
										<?php if ( !get_post_meta( get_the_ID(), 'sites_des', true ) ) { ?>
											<?php echo $sites_des; ?>
										<?php } ?>
										<?php echo $sites_des; ?>
									<?php } else { ?>
										<div style="text-align: center;"><?php _e( '网站描述待添加', 'begin' ); ?></div>
									<?php } ?>
							</fieldset>
						<?php } ?>

						<div class="sites-content">
							<div class="format-sites-inf-box">
								<?php if ( cx_get_option( 'site_sc' ) ) { ?>
									<div class="site-screenshots">
										<div class="site-thumbs">
											<div class="site-but-box">
												<?php if ( get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
													<div class="site-but"><a class="fancy-iframe" data-type="iframe" data-src="<?php echo $sites_url; ?>" href="javascript:;" rel="external nofollow"><?php _e( '预览', 'begin' ); ?></a></div>
													<div class="site-but"><a href="<?php echo $sites_url_go; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a></div>
												<?php } else { ?>
													<div class="site-but"><a class="fancy-iframe" data-type="iframe" data-src="<?php echo $sites_link; ?>" href="javascript:;" rel="external nofollow"><?php _e( '预览', 'begin' ); ?></a></div>
													<div class="site-but"><a href="<?php echo $sites_link_go; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a></div>
												<?php } ?>

											</div>
											<?php $thumbnail = get_post_meta( get_the_ID(), 'thumbnail', true ); ?>
											<?php if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) { ?>
												<div class="site-lazy" data-src="<?php echo $thumbnail; ?>"></div>
											<?php } else { ?>
												<?php if ( get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
													<?php if ( ! cx_get_option( 'screenshot_api' ) || ( cx_get_option( 'screenshot_api' ) == 'api_wp' ) ) { ?>
														<div class="site-lazy" data-src="https://s0.wp.com/mshots/v1/<?php echo $sites_url; ?>?w=253&h=190"></div>
													<?php } ?>
													<?php if ( cx_get_option( 'screenshot_api' ) == 'api_wordpress' ) { ?>
														<div class="site-lazy" data-src="https://s0.wordpress.com/mshots/v1/<?php echo $sites_url; ?>?w=253&h=190"></div>
													<?php } ?>
													<?php if ( cx_get_option( 'screenshot_api' ) == 'api_urlscan' ) { ?>
														<div class="site-lazy" data-src="https://urlscan.io/liveshot/?width=1200&height=901&url=<?php echo $sites_url; ?>"></div>
													<?php } ?>
												<?php } else { ?>
													<?php if ( ! cx_get_option( 'screenshot_api' ) || ( cx_get_option( 'screenshot_api' ) == 'api_wp' ) ) { ?>
														<div class="site-lazy" data-src="https://s0.wp.com/mshots/v1/<?php echo $sites_link; ?>?w=253&h=190"></div>
													<?php } ?>
													<?php if ( cx_get_option( 'screenshot_api' ) == 'api_wordpress' ) { ?>
														<div class="site-lazy" data-src="https://s0.wordpress.com/mshots/v1/<?php echo $sites_link; ?>?w=253&h=190"></div>
													<?php } ?>
													<?php if ( cx_get_option( 'screenshot_api' ) == 'api_urlscan' ) { ?>
														<div class="site-lazy" data-src="https://urlscan.io/liveshot/?width=1300&height=901&url=<?php echo $sites_link; ?>"></div>
													<?php } ?>
													<?php if ( cx_get_option( 'screenshot_api' ) == 'api_screenshotmachine' ) { ?>
														<div class="site-lazy" data-src="https://api.screenshotmachine.com?key=d043df&url=<?php echo $sites_link; ?>&dimension=1300x901"></div>
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								<div class="format-sites-inf">
									<div class="sites-urlc"><span><?php _e( '网址', 'begin' ); ?>：</span>
										<?php if ( get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
											<a href="<?php echo $sites_url_go; ?>" target="_blank" rel="external nofollow"><?php echo $sites_url; ?></a>
										<?php } else { ?>
											<?php if ( get_post_meta( get_the_ID(), 'sites_link', true ) ) { ?>
												<a href="<?php echo $sites_link_go; ?>" target="_blank" rel="external nofollow"><?php echo $sites_link; ?></a>
											<?php } else { ?>
												<a href="<?php echo $sites_link_go; ?>" target="_blank" rel="external nofollow"><?php echo $sites_link; ?></a>
											<?php } ?>
										<?php } ?>

									</div>
									<div class="datec"><span><?php _e( '收录', 'begin' ); ?>：</span><?php echo get_the_date(); ?><?php edit_post_link('<i class="be be-editor"></i>', ' ' ); ?></div>
									<div class="categoryc"><span><?php _e( '类别', 'begin' ); ?>：</span><?php echo get_the_term_list($post->ID,  'favorites', '', ', ', ''); ?></div>
									<?php if ( ! zm_get_option( 'close_comments' ) ) { ?>
										<?php if ( post_password_required() ) { ?>
											<div class="commentc"><span><?php _e( '评论', 'begin' ); ?>：</span><a href="#comments"><?php _e( '密码保护', 'begin' ); ?></a></div>
										<?php } else { ?>
											<div class="commentc"><span><?php _e( '评论', 'begin' ); ?>：</span><?php comments_popup_link( '' . sprintf( __( '发表评论', 'begin' ) ) . '', '1 ' . sprintf( __( '条', 'begin' ) ) . '', '% ' . sprintf( __( '条', 'begin' ) ) . '' ); ?></div>
										<?php } ?>
									<?php } ?>
									<?php if ( zm_get_option( 'post_views' ) ) { ?>
										<?php be_the_views( true, '<div class="views-sitec"><i><span>' . sprintf( __( '热度', 'begin' ) ) . '</i>：</span>','</div>' ); ?>
									<?php } ?>
								</div>
							</div>
							<div class="clear"></div>
							<?php the_content(); ?>
							<div class="sites-qr"><img id="qrsites"></div>
							<div class="sites-go">
								<?php if ( get_post_meta( get_the_ID(), 'sites_url', true ) ) { ?>
									<a href="<?php echo $sites_url_go; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a>
								<?php } else { ?>
									<a href="<?php echo $sites_link_go; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>

					<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
						<?php be_like(); ?>
					<?php } ?>
					<?php if (zm_get_option( 'single_weixin' ) ) { ?>
						<?php get_template_part( 'template/weixin' ); ?>
					<?php } ?>
					<div class="content-empty"></div>
					<?php get_template_part( 'ad/ads', 'single-b' ); ?>
					<footer class="single-footer">
						<div class="single-cat-tag dah">
							<div class="single-cat dah"><i class="be be-sort ri"></i><?php echo get_the_term_list( $post->ID,  'favorites', '' ); ?></div>
						</div>
					</footer><!-- .entry-footer -->

					<div class="clear"></div>
				</div><!-- .entry-content -->

			</article><!-- #post -->

			<?php if (zm_get_option('copyright')) { ?>
				<?php get_template_part( 'template/copyright' ); ?>
			<?php } ?>

			<div class="sites-all" <?php aos_a(); ?>>
				<div class="sites-box sites-single-related">
					<?php 
						$loop = new WP_Query( array( 'post_type' => 'sites', 'posts_per_page' => '12', 'orderby' => 'rand', 'post__not_in' => array( $post->ID ) ) );
						while ( $loop->have_posts() ) : $loop->the_post();
					?>
					<div class="sites-area sites-3">
						<?php sites_favorites(); ?>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<div class="clear"></div>
			</div>

			<?php type_nav_single(); ?>

			<?php get_template_part( 'ad/ads', 'comments' ); ?>

			<?php begin_comments(); ?>

		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<div id="sidebar" class="widget-area all-sidebar">
	<div class="wow fadeInUp">
		<?php if ( ! dynamic_sidebar( 'favorites' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="add-widget-tip">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“网址侧边栏”添加小工具</a>
				</div>
				<div class="clear"></div>
			</aside>
		<?php endif; ?>
	</div>
</div>
<?php } ?>

<script>
jQuery(document).ready(function($){
	var sitesul = sites.sitesul;
	var qr = new QRious({
		element:document.getElementById('qrsites'),
		size:250,
		level:'H',
		value:sitesul
	});
});
</script>
<?php get_footer(); ?>