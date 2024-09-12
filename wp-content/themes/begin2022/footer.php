<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
	</div>
	<div class="clear"></div>
	<?php if (zm_get_option('footer_link')) { ?>
		<?php get_template_part( 'template/footer-links' ); ?>
	<?php } ?>
	<?php get_template_part( 'template/footer-widget' ); ?>
	<footer id="colophon" class="site-footer bgt" role="contentinfo">
		<div class="site-info">
			<div class="site-copyright">
				<?php echo zm_get_option('footer_inf_t'); ?>
			</div>
			<div class="add-info">
				<?php echo zm_get_option('tongji_f'); ?>
				<div class="clear"></div>
				<?php if ( ! zm_get_option('wb_info') == '' ) { ?>
					<span class="wb-info">
						<a href="<?php echo zm_get_option('wb_url'); ?>" rel="external nofollow" target="_blank"><?php if ( ! zm_get_option('wb_img') == '' ) { ?><img src="<?php echo zm_get_option('wb_img'); ?>"><?php } ?><?php echo zm_get_option('wb_info'); ?></a>
					</span>
				<?php } ?>
				<?php if ( ! zm_get_option('yb_info') == '' ) { ?>
					<span class="yb-info">
						<a href="<?php echo zm_get_option('yb_url'); ?>" rel="external nofollow" target="_blank"><?php if ( ! zm_get_option('yb_img') == '' ) { ?><img src="<?php echo zm_get_option('yb_img'); ?>"><?php } ?><?php echo zm_get_option('yb_info'); ?></a>
					</span>
				<?php } ?>
			</div>

		<?php if (zm_get_option('web_queries') && current_user_can('manage_options')) { ?><?php if(function_exists('queries')) queries(true) ;?><?php } ?>
		</div>
		<?php if (zm_get_option('footer_menu') && wp_is_mobile()) { ?>
			<div class="footer-clear"></div>
			<nav class="footer-nav-hold<?php if (zm_get_option('nav_weixin_on')) { ?> footer-nav-weixin<?php } ?><?php if (zm_get_option('footer_menu_no')) { ?> footer-nav<?php } ?>">
				<?php if (zm_get_option('nav_weixin_on')) { ?><?php nav_weixin(); ?><?php } ?>
				<?php
					wp_nav_menu( array(
						'theme_location'=> 'footer',
						'menu_class'    => 'footer-menu',
						'fallback_cb'   => 'mobile_menu'
					) );
				?>
			</nav>
		<?php } ?>
	</footer>
	<?php if (zm_get_option('profile') || zm_get_option('menu_login') || zm_get_option('mobile_login')) { ?><?php get_template_part( 'template/login' ); ?><?php } ?>
	<?php get_template_part( 'template/scroll' ); ?>
	<?php get_template_part( 'template/placard' ); ?>
	<?php get_template_part( 'template/the-blank' ); ?>
	<?php get_template_part( 'template/contact' ); ?>
	<?php if (zm_get_option('weibo_t')) { ?>
		<script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
		<html xmlns:wb="https://open.weibo.com/wb">
	<?php } ?>
</div>
<script type='text/javascript' id='superfish-js-after'>var fallwidth = {fall_width: <?php echo zm_get_option( 'fall_width' ); ?>}</script>
<?php wp_footer(); ?>
</body>
</html>