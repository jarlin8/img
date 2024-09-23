<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// menu
function menu_top() { ?>
<div class="nav-top betip">
	<?php if ( zm_get_option( 'profile' ) && zm_get_option( 'front_login' ) ) { ?>
		<?php login_info(); ?>
		<?php be_help( $text = '主题选项 → 站点管理 → 顶部菜单登录按钮' ); ?>
	<?php } ?>

	<div class="nav-menu-top-box betip">
		<div class="nav-menu-top">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'header',
					'menu_class'     => 'top-menu',
					'fallback_cb'    => 'default_top_menu'
				) );
			?>
		</div>
		<?php be_help( $text = '主题选项 → 菜单设置 → 顶部菜单及站点管理' ); ?>
	</div>
</div>
<?php }

function menu_logo() { ?>
	<?php if ( ! zm_get_option( 'site_sign' ) || ( zm_get_option('site_sign' ) == 'logo_small' ) ) { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>">
			<span class="logo-small"><img class="begd" src="<?php echo zm_get_option( 'logo_small_b' ); ?>" style="width: <?php echo zm_get_option( 'logo_small_width' ); ?>px;" alt="<?php bloginfo( 'name' ); ?>"></span>
			<span class="site-name-main">
				<?php if ( is_front_page() || is_home() ) { ?>
					<h1 class="site-name"<?php if ( zm_get_option( 'site_name_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_name_size' ); ?>rem;"<?php } ?>><?php bloginfo( 'name' ); ?></h1>
				<?php } else { ?>
					<span class="site-name"<?php if ( zm_get_option( 'site_name_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_name_size' ); ?>rem;"<?php } ?>><?php bloginfo( 'name' ); ?></span>
				<?php } ?>
				<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) { ?>
					<span class="site-description"<?php if ( zm_get_option( 'site_des_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_des_size' ); ?>rem;"<?php } ?>><?php echo get_bloginfo( 'description', 'display' ); ?></span>
				<?php } ?>
			</span>
		</a>
	<?php } ?>

	<?php if ( zm_get_option( 'site_sign' ) == 'logos') { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>">
			<img class="begd" src="<?php echo zm_get_option( 'logo' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" style="height: <?php echo zm_get_option( 'logo_height' ); ?>px;" alt="<?php bloginfo( 'name' ); ?>" rel="home">
			<?php if ( is_front_page() || is_home() ) { ?>
				<h1 class="site-name"><?php bloginfo( 'name' ); ?></h1>
			<?php } else { ?>
				<span class="site-name"><?php bloginfo( 'name' ); ?></span>
			<?php } ?>
		</a>
	<?php } ?>

	<?php if ( zm_get_option( 'site_sign' ) == 'no_logo' ) { ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>">
			<span class="site-name-main">
				<?php if ( is_front_page() || is_home() ) { ?>
					<h1 class="site-name"<?php if ( zm_get_option( 'site_name_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_name_size' ); ?>rem;"<?php } ?>><?php bloginfo( 'name' ); ?></h1>
				<?php } else { ?>
					<span class="site-name"<?php if ( zm_get_option( 'site_name_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_name_size' ); ?>rem;"<?php } ?>><?php bloginfo( 'name' ); ?></span>
				<?php } ?>
				<?php if ( get_bloginfo( 'description', 'display' ) || is_customize_preview() ) { ?>
					<span class="site-description"<?php if ( zm_get_option( 'site_des_size' ) ) { ?> style="font-size: <?php echo zm_get_option( 'site_des_size' ); ?>rem;"<?php } ?>><?php echo get_bloginfo( 'description', 'display' ); ?></span>
				<?php } ?>
			</span>
		</a>
	<?php } ?>
<?php }

function menu_nav() { ?>
<?php if (zm_get_option('nav_no')) { ?>
	<div class="nav-mobile menu-but"><a href="<?php echo get_permalink( zm_get_option('nav_url') ); ?>"><div class="menu-but-box"><div class="heng"></div></div></a></div>
<?php } else { ?>
	<?php if (zm_get_option('m_nav')) { ?>
		<?php if ( wp_is_mobile() ) { ?>
			<div class="nav-mobile menu-but menu-mobile-but"><div class="menu-but-box"><div class="heng"></div></div></div>
		<?php } else { ?>
			<div id="navigation-toggle" class="menu-but bars<?php echo cur(); ?>"><div class="menu-but-box"><div class="heng"></div></div></div>
		<?php } ?>
	<?php } else { ?>
		<div id="navigation-toggle" class="menu-but bars<?php echo cur(); ?>"><div class="menu-but-box"><div class="heng"></div></div></div>
	<?php } ?>
<?php } ?>

		<?php
			if (zm_get_option('mobile_nav') ) {
				if ( wp_is_mobile()) {
					$navtheme = 'mobile';
				} else {
					$navtheme = 'navigation';
				}
			} else {
				$navtheme = 'navigation';
			}

			wp_nav_menu( array(
			'theme_location' => $navtheme,
			'menu_class'     => 'down-menu nav-menu',
			'fallback_cb'    => 'default_menu'
			) );
		?>

<?php }

function mobile_login() { ?>
	<?php if ( zm_get_option('mobile_login') ) { ?>
		<?php if ( is_user_logged_in() ) { ?>
			<div class="mobile-userinfo"><?php logged_manage(); ?></div>
		<?php } else { ?>
			<div class="mobile-login-but<?php echo cur(); ?>">
				<div class="mobile-login-author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"></div>
				<?php if ( !zm_get_option('user_l') == '' ) { ?>
					<span class="mobile-login-l"><a href="<?php echo zm_get_option('user_l'); ?>" title="Login"><?php _e( '登录', 'begin' ); ?></a></span>
				<?php } else { ?>
					<span class="mobile-login show-layer<?php echo cur(); ?>"><?php _e( '登录', 'begin' ); ?></span>
				<?php } ?>
				<?php if (zm_get_option('menu_reg') && get_option('users_can_register')) { ?>
					 <span class="mobile-login-reg"><a href="<?php echo zm_get_option('reg_l'); ?>"><?php _e( '注册', 'begin' ); ?></a></span>
				 <?php } ?>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="mobile-login-point">
			<div class="mobile-login-author-back"><img src="<?php echo zm_get_option('user_back'); ?>" alt="bj"></div>
		</div>
	<?php } ?>
<?php }

//mobile nav
function mobile_nav() { ?>
<div id="mobile-nav">
	<div class="off-mobile-nav"></div>
	<div class="mobile-nav-box">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'mobile',
				'menu_class'     => 'mobile-menu',
				'fallback_cb'    => 'mobile_alone_menu'
			) );
		?>
	</div>
	<div class="clear"></div>
	<div class="mobile-nav-b"></div>
</div>
<?php }

// nav_weixin
function nav_weixin() { ?>
<div class="nav-weixin-but">
	<div class="nav-weixin-img">
		<?php if ( get_the_author_meta( 'weixinqr' ) && is_single() && zm_get_option( 'nav_author_weixin' ) ) { ?>
			<div class="copy-weixin">
				<img src="<?php the_author_meta( 'weixinqr' ); ?>" alt="weinxin">
				<div class="weixinbox">
					<div class="btn-weixin-copy"></div>
					<div class="weixin-id"><?php the_author_meta( 'weixin' ); ?></div>
					<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php echo zm_get_option('nav_weixin_tip'); ?></div></div>
				</div>
			</div>
			<p>作者微信，点击复制</p>
		<?php } else { ?>
			<div class="copy-weixin">
				<img src="<?php echo zm_get_option('nav_weixin_img'); ?>" alt="weinxin">
				<div class="weixinbox">
					<div class="btn-weixin-copy"></div>
					<div class="weixin-id"><?php echo zm_get_option( 'nav_weixin_id' ); ?></div>
					<div class="copy-success-weixin fd"><div class="copy-success-weixin-text"><span class="dashicons dashicons-saved"></span><?php echo zm_get_option('nav_weixin_tip'); ?></div></div>
				</div>
			</div>
			<p>点击复制</p>
		<?php } ?>
		<span class="arrow-down"></span>
	</div>
	<div class="nav-weixin-i"><i class="be be-weixin"></i></div>
	<div class="nav-weixin"></div>
</div>
<?php }