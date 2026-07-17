<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片展示
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

<section id="primary" class="content-area cat-dis">
	<div id="picture" class="picture-area content-area category-img-s grid-cat-<?php echo zm_get_option( 'cat_display_f' ); ?>">
		<main id="main" class="be-main site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_a(); ?>>
				<div class="boxs1">
					<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
						<figure class="picture-img">
							<?php echo zm_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			 			<div class="clear"></div>
					</div>
				</div>
			</article>
			<?php endwhile;?>
		</main>
		<?php begin_pagenav(); ?>
	</div>
</section>
<?php get_footer(); ?>