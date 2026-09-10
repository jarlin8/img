<?php
/*
Template Name: 关于我们
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<div id="<?php if ( get_post_meta( get_the_ID(), 'sidebar_l', true ) ) { ?>sidebar-l<?php } else { ?>sidebar<?php } ?>" class="widget-area about-sidebar betip<?php if ( ! wp_is_mobile() ) { ?> all-sidebar<?php } ?>">
	<div class="wow fadeInUp">
		<?php if ( ! dynamic_sidebar( 'about' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<div class="add-widget-tip">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“关于我们侧边栏 ”添加小工具</a>
				</div>
				<div class="clear"></div>
			</aside>
		<?php endif; ?>
	</div>
	<?php be_help( $text = '外观<br />↓<br />小工具 <br />↓<br />关于我们侧边栏<br />↓<br />导航菜单小工具' ); ?>
</div>

<div id="<?php if ( get_post_meta(get_the_ID(), 'sidebar_l', true) ) { ?>primary-l<?php } else { ?>primary<?php } ?>" class="content-area about-area<?php if ( get_post_meta( get_the_ID(), 'no_sidebar', true ) ) { ?> no-sidebar<?php } ?>">
	<main id="main" class="be-main site-main<?php if (zm_get_option('p_first') ) { ?> p-em<?php } ?><?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
			<?php if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ) { ?>
			<?php } else { ?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
			<?php } ?>
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php begin_link_pages(); ?>
				</div>
				<div class="clear"></div>
				<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
				<div class="clear"></div>
			</div>
		</article>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>

	<?php endwhile; ?>

	</main>
</div>
<?php get_footer(); ?>