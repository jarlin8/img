<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php begin_primary_class(); ?>
	<main id="main" class="site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php $sites_link = get_post_meta( $post->ID, 'sites_link', true ); ?>
			<?php $sites_url = get_post_meta( $post->ID, 'sites_url', true ); ?>
			<?php $sites_description = get_post_meta( $post->ID, 'sites_description', true ); ?>
			<?php $sites_des = get_post_meta( $post->ID, 'sites_des', true ); ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk'); ?>>
				<?php header_title(); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<div class="single-content">
						<?php begin_abstract(); ?>
						<?php get_template_part( 'ad/ads', 'single' ); ?>

						<?php if ( get_post_meta( $post->ID, 'sites_link', true ) ||  get_post_meta( $post->ID, 'sites_url', true ) ) { ?>
							<fieldset class="sites-des">
								<legend>
									<div class="sites-icon">
										<?php if ( zm_get_option( 'sites_ico' ) ) { ?>
											<?php if ( get_post_meta( $post->ID, 'sites_url', true ) ) { ?>
												<a href="<?php echo $sites_url; ?>" target="_blank" rel="external nofollow">
													<div class="sites-ico dah bk load"><img class="sites-img" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_url; ?>" alt="<?php the_title(); ?>"></div>
												</a>
											<?php } else { ?>
												<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow">
													<div class="sites-ico dah bk load"><img class="sites-img" src="<?php echo get_template_directory_uri(); ?>/img/loading.png" data-original="<?php echo zm_get_option( 'favicon_api' ); ?><?php echo $sites_link; ?>" alt="<?php the_title(); ?>"></div>
												</a>
											<?php } ?>
										<?php } else { ?>
											<?php _e( '网站描述', 'begin' ); ?>
										<?php } ?>
										<div class="clear"></div>
									</div>
								</legend>
									<?php if ( get_post_meta( $post->ID, 'sites_description', true ) || get_post_meta( $post->ID, 'sites_des', true ) ) { ?>
										<?php if ( !get_post_meta( $post->ID, 'sites_des', true ) ) { ?>
											<?php echo $sites_description; ?>
										<?php } ?>
										<?php echo $sites_des; ?>
									<?php } else { ?>
										<div style="text-align: center;"><?php _e( '网站描述待添加', 'begin' ); ?></div>
									<?php } ?>
							</fieldset>
						<?php } ?>

						<div class="sites-content">
							<div class="format-sites-inf">
								<?php if ( zm_get_option( 'site_sc' ) ) { ?>
									<div class="site-screenshots">
										<div class="site-thumbs">
											<?php $thumbnail = get_post_meta( $post->ID, 'thumbnail', true ); ?>
											<?php if ( get_post_meta( $post->ID, 'thumbnail', true ) ) { ?>
												<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><div class="site-lazy" data-src="<?php echo $thumbnail; ?>"></div></a>
											<?php } else { ?>
												<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><div class="site-lazy" data-src="https://s0.wordpress.com/mshots/v1/<?php echo $sites_link; ?>?w=253&h=190"></div></a>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								<span class="sites-url bkc"><?php _e( '网址', 'begin' ); ?>：
									<?php if ( get_post_meta( $post->ID, 'sites_link', true ) ) { ?>
										<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><?php echo $sites_link; ?></a>
									<?php } else { ?>
										<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><?php echo $sites_link; ?></a>
									<?php } ?>
								</span>
								<span class="date bkc"><?php _e( '日期', 'begin' ); ?>：<?php echo get_the_date(); ?><?php edit_post_link('<i class="be be-editor"></i>', ' ' ); ?></span>
								<span class="category bkc"><?php _e( '分类', 'begin' ); ?>：<?php echo get_the_term_list($post->ID,  'favorites', '', ', ', ''); ?></span>
								<?php if ( post_password_required() ) { ?>
									<span class="comment bkc"><?php _e( '评论', 'begin' ); ?>：<a href="#comments"><?php _e( '密码保护', 'begin' ); ?></a></span>
								<?php } else { ?>
									<span class="comment bkc"><?php _e( '评论', 'begin' ); ?>：<?php comments_popup_link( '' . sprintf( __( '发表评论', 'begin' ) ) . '', '1 ' . sprintf( __( '条', 'begin' ) ) . '', '% ' . sprintf( __( '条', 'begin' ) ) . '' ); ?></span>
								<?php } ?>
								<?php be_the_views( true, '<span class="views-site bkc"><i>' . sprintf( __( '浏览', 'begin' ) ) . '</i>：','</span>' ); ?>
							</div>
							<div class="clear"></div>
							<?php the_content(); ?>
							<div class="sites-qr"><img id="qrsites"></div>
							<div class="sites-go">
								<?php if ( get_post_meta( $post->ID, 'sites_url', true ) ) { ?>
									<a href="<?php echo $sites_url; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a>
								<?php } else { ?>
									<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><?php _e( '访问', 'begin' ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>

					<?php be_like(); ?>
					<?php if (zm_get_option( 'single_weixin' ) ) { ?>
						<?php get_template_part( 'template/weixin' ); ?>
					<?php } ?>
					<div class="content-empty"></div>
					<?php get_template_part( 'ad/ads', 'single-b' ); ?>
					<footer class="single-footer">
						<div class="single-cat-tag dah">
							<div class="single-cat dah"><i class="be be-sort ri"></i><?php echo get_the_term_list( $post->ID,  'favorites', '' ); ?>
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

			<?php get_template_part('ad/ads', 'comments'); ?>

			<?php begin_comments(); ?>

		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php if ( get_post_meta( $post->ID, 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?>
<?php } else { ?>
<div id="sidebar" class="widget-area all-sidebar">
	<div class="wow fadeInUp">
		<?php if ( ! dynamic_sidebar( 'favorites' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="textwidget">
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