<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
</div>
<div class="clear"></div>
<?php if ( be_get_option( 'footer_link' ) ) { ?>
	<?php get_template_part( 'template/footer-links' ); ?>
<?php } ?>
<?php get_template_part( 'template/footer-widget' ); ?>
<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="site-info">
		<div class="site-copyright">
			<?php echo wpautop( zm_get_option( 'footer_inf_t' ) ); ?>
		</div>
		<div class="add-info">
			<?php echo zm_get_option( 'tongji_f' ); ?>
			<div class="clear"></div>
			<?php if ( ! zm_get_option( 'wb_info' ) == '' ) { ?>
				<span class="wb-info">
					<a href="<?php echo zm_get_option( 'wb_url' ); ?>" rel="external nofollow" target="_blank"><?php if ( ! zm_get_option( 'wb_img' ) == '' ) { ?><img src="<?php echo zm_get_option( 'wb_img' ); ?>"><?php } ?><?php echo zm_get_option( 'wb_info' ); ?></a>
				</span>
			<?php } ?>
			<?php if ( ! zm_get_option( 'yb_info' ) == '' ) { ?>
				<span class="yb-info">
					<a href="<?php echo zm_get_option( 'yb_url' ); ?>" rel="external nofollow" target="_blank"><?php if ( ! zm_get_option( 'yb_img' ) == '' ) { ?><img src="<?php echo zm_get_option( 'yb_img' ); ?>"><?php } ?><?php echo zm_get_option( 'yb_info' ); ?></a>
				</span>
			<?php } ?>
			<?php if ( cx_get_option( 'web_queries' ) && current_user_can( 'manage_options' ) ) { ?><p><?php if ( function_exists( 'queries') ) queries( true ); ?></p><?php } ?>
		</div>
		<?php be_help( $text = '主题选项 → SEO设置 → 页脚信息' ); ?>
		<div class="clear"></div>
	</div>
	<?php if ( zm_get_option( 'be_debug' ) && current_user_can( 'manage_options' ) ) { ?>
		<div class="be-debug"><span class="cx cx-begin"></span><a href="<?php echo admin_url( 'themes.php?page=begin-options' ); ?>" target="_blank">当前处于调试模式，可能会影响部分功能，点此进入主题选项，关闭调试模式！</a></div>
	<?php } ?>
	<?php if ( zm_get_option( 'footer_menu' ) ) { ?>
		<?php if ( zm_get_option( 'footer_menu_mobile' ) || wp_is_mobile() ) { ?>
			<div class="footer-clear"></div>
			<nav class="footer-nav-hold<?php if ( ! zm_get_option( 'footer_menu_ico_txt' ) || ( zm_get_option( 'footer_menu_ico_txt' ) == 'txt' ) ) { ?> footer-no-ico<?php } ?><?php if ( zm_get_option( 'nav_weixin_on' ) ) { ?> footer-nav-weixin<?php } ?><?php if ( zm_get_option( 'footer_menu_no' ) ) { ?> footer-nav<?php } ?>">
				<?php if ( zm_get_option( 'nav_weixin_on' ) ) { ?><?php nav_weixin(); ?><?php } ?>
				<?php
					wp_nav_menu( array(
						'theme_location'=> 'footer',
						'menu_class'    => 'footer-menu',
						'fallback_cb'   => 'mobile_menu'
					) );
				?>
				<?php be_help( $text = '主题选项 → 菜单设置 → 移动端菜单→ 移动端页脚菜单' ); ?>
			</nav>
		<?php } ?>
	<?php } ?>
	<?php if ( zm_get_option( 'profile' ) || zm_get_option( 'menu_login' ) || zm_get_option( 'mobile_login' ) ) { ?><?php get_template_part( 'template/login' ); ?><?php } ?>
	<?php get_template_part( 'template/scroll' ); ?>
	<?php get_template_part( 'template/placard' ); ?>
	<?php get_template_part( 'template/contact' ); ?>
	<?php if (zm_get_option('weibo_t')) { ?>
		<script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
		<html xmlns:wb="https://open.weibo.com/wb">
	<?php } ?>
	<?php if ( zm_get_option( 'fix_mail_form' ) ) { ?>
		<div class="slide-mail-box">
			<div class="slide-mail-btu"><span class="dashicons dashicons-email-alt"></span></div>
				<div class="slide-mail-wrap">
					<div class="slide-mail-main mail-main"><?php echo be_display_contact_form(); ?></div>
				</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
	<?php if ( zm_get_option( 'copy_post' ) && is_single() ) { ?>
		<div class="copy-content-tip fadeInZoom animated"><i class="be be-clipboard ri"></i><?php _e( '文章复制成功', 'begin' ); ?></div>
	<?php } ?>
	<?php if ( is_singular() ) { ?><?php do_action( 'betoc' ); ?><?php } ?>
	<?php wp_footer(); ?>
</footer>
</div>
</body>
</html>