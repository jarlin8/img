<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 展示模板
Template Post Type: post
*/
get_header(); ?>

	<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
		<div id="sidebar" class="widget-area dis-sidebar betip<?php if ( ! wp_is_mobile() ) { ?> all-sidebar<?php } ?>">
			<div class="wow fadeInUp">
				<?php if ( ! dynamic_sidebar( 'display' ) ) : ?>
					<aside id="add-widgets" class="widget widget_text">
						<div class="add-widget-tip">
							<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“关于我们侧边栏 ”添加小工具</a>
						</div>
						<div class="clear"></div>
					</aside>
				<?php endif; ?>
			</div>
			<?php be_help( $text = '外观<br />↓<br />小工具 <br />↓<br />展示侧边栏<br />↓<br />导航菜单小工具' ); ?>
		</div>
	<?php } ?>

	<div id="primary" class="content-area content-dis<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) || ( zm_get_option('single_no_sidebar') ) ) { ?> no-sidebar<?php } ?>">
		<main id="main" class="be-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?><?php if (get_post_meta( get_the_ID(), 'sub_section', true ) ) { ?> sub-h<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template/content', get_post_format() ); ?>

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

<?php get_footer(); ?>