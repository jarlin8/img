<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'header_normal' ) ) { ?>
<header id="masthead" class="site-header-o<?php if ( zm_get_option( 'nav_extend' ) ) { ?><?php if ( zm_get_option( 'nav_full_width' ) ) { ?> nav-full-width<?php } else { ?> nav-extend<?php } ?><?php } ?>">
	<?php if ( ! zm_get_option('top_bg') == '' ) { ?>
		<?php 
			if ( ! zm_get_option( 'top_bg_m' ) || ( zm_get_option( 'top_bg_m' ) == 'repeat_p' ) ) {
				$top_bg = 'background-position: left top; background-size: cover; background-repeat: no-repeat;';
			}
			if ( zm_get_option( 'top_bg_m' ) == 'repeat_x' ) {
				$top_bg = 'background-repeat: repeat-x;background-position: top center;background-size: auto 260px';
			}
			if ( zm_get_option( 'top_bg_m' ) == 'repeat_y' ) {
				$top_bg = 'background-position: center top;background-size: auto;background-repeat: no-repeat;background-attachment: fixed;';
			}
		?>
		<div id="header-main-o" class="header-main-o<?php if ( zm_get_option('menu_m') == 'menu_d' ){ ?> site-header-up<?php } ?>" style="background: url('<?php if ( !zm_get_option( 'top_bg' ) == '' ) { ?><?php echo zm_get_option( 'top_bg' ); ?><?php } ?>');<?php echo $top_bg; ?>">
	<?php } else { ?>
		<div id="header-main-o" class="header-main-o<?php if ( zm_get_option('menu_m') == 'menu_d' ){ ?> site-header-up<?php } ?>" style="background:<?php echo zm_get_option( 'header_color' ); ?>;">
	<?php } ?>

		<?php if (zm_get_option('top_nav_show')) { ?>
		<nav id="header-top" class="header-top header-top-o">
			<?php menu_top(); ?>
		</nav>
		<?php } ?>

		<div class="logo-box" style="min-height: <?php echo zm_get_option( 'logo_box_height' ); ?>px;">
			<?php if ( zm_get_option( "logo_css" ) && ( !wp_is_mobile() ) ) { ?>
				<div class="logo-site-o<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logos')) { ?> logo-txt<?php } ?>">
			<?php } else { ?>
				<div class="logo-sites-o<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logos')) { ?> logo-txt<?php } ?>">
			<?php } ?>
				<?php menu_logo(); ?>
			</div>
			<?php if ( !zm_get_option( 'h_main_o' ) || ( zm_get_option( 'h_main_o' ) == 'h_search' ) ) { ?>
				<div class="header-top-search"><?php get_search_form(); ?></div>
			<?php } ?>
			<?php if ( zm_get_option( 'h_main_o' ) == 'h_contact' ) { ?>
				<?php if ( zm_get_option('header_contact') ) { ?><div class="contact-header"><?php echo zm_get_option('header_contact'); ?></div><?php } ?>
			<?php } ?>
		</div>
		<div id="menu-container-o" class="menu-container-o menu-container-o-full<?php if ( zm_get_option( 'menu_glass' ) ) { ?> menu-glass<?php } ?>">
			<div id="navigation-top" class="navigation-top">
				<div id="site-nav-wrap-o">
					<div id="sidr-close">
						<div class="toggle-sidr-close"></div>
					</div>
					<nav id="site-nav" class="main-nav-o<?php nav_ace(); ?>">
						<?php menu_nav(); ?>
						<?php be_help( $text = '主题选项 → 菜单设置 → 通用头部模式' ); ?>
					</nav>
					<div class="clear"></div>

					<div class="sidr-login">
						<?php mobile_login(); ?>
					</div>
				</div>
				<div class="site-nav-wrap-o-r betip">
					<?php if ( zm_get_option( 'menu_search_button' ) ) { ?><span class="nav-search nav-search-o<?php echo cur(); ?>"></span><?php } ?>
					<?php if ( zm_get_option( 'front_login' ) ) { ?><?php login_but(); ?><?php } ?>
					<?php be_help( $text = '基本设置<br />↓<br />管理站点<br />↓<br />主菜单登录按钮' ); ?>
				</div>
			</div>
		</div>
	</div>
</header>
<?php } else { ?>
<?php if (!zm_get_option('top_nav_show')) { ?>
<header id="masthead" class="site-header site-header-h<?php if ( zm_get_option( 'nav_extend' ) ) { ?><?php if ( zm_get_option( 'nav_full_width' ) ) { ?> nav-full-width<?php } else { ?> nav-extend<?php } ?><?php } ?>">
<?php } else { ?>
<header id="masthead" class="site-header site-header-s<?php if ( zm_get_option( 'nav_extend' ) ) { ?><?php if ( zm_get_option( 'nav_full_width' ) ) { ?> nav-full-width<?php } else { ?> nav-extend<?php } ?><?php } ?>">
<?php } ?>
<?php if (!zm_get_option('menu_m') || (zm_get_option("menu_m") == 'menu_d')){ ?>
	<div id="header-main" class="header-main<?php if ( zm_get_option( 'top_nav_show' ) ) { ?> top-nav-show<?php } ?>">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_n'){ ?>
	<div id="header-main-n" class="header-main-n">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_g'){ ?>
	<div id="header-main-g" class="header-main-g<?php if ( zm_get_option( 'top_nav_show' ) ) { ?> top-nav-show<?php } ?>">
<?php } ?>
		<?php if (zm_get_option('top_nav_show')) { ?>
		<nav id="header-top" class="header-top<?php if ( zm_get_option('menu_glass' ) ) { ?> nav-glass<?php } ?>">
			<?php menu_top(); ?>
		</nav>
		<?php } ?>
		<div id="menu-container" class="be-menu-main<?php if ( zm_get_option( 'menu_glass' ) ) { ?> menu-glass<?php } ?>">
			<div id="navigation-top" class="navigation-top<?php if (zm_get_option('menu_block')) { ?> menu_c<?php } ?>">
				<div class="be-nav-box">
					<div class="be-nav-l">
						<div class="be-nav-logo">
							<?php if ( zm_get_option( "logo_css" ) && ( ! wp_is_mobile() ) ) { ?>
								<div class="logo-site<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logos')) { ?> logo-txt<?php } ?>">
							<?php } else { ?>
								<div class="logo-sites<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logos')) { ?> logo-txt<?php } ?>">
							<?php } ?>
								<?php menu_logo(); ?>
							</div>
							<?php be_help( $text = '主题选项 → 网站标志' ); ?>
						</div>

						<?php if ( ! zm_get_option( 'main_menu' ) || ( zm_get_option( 'main_menu' ) == 'main_menu_l' ) ) { ?>
							<div class="be-nav-wrap">
								<div id="site-nav-wrap" class="site-nav-main" style="margin-left: <?php echo zm_get_option( 'nav_margin' ); ?>px">
									<div id="sidr-close">
										<div class="toggle-sidr-close"></div>
									</div>
									<nav id="site-nav" class="main-nav<?php nav_ace(); ?>">
										<?php menu_nav(); ?>
									</nav>

									<div class="clear"></div>

									<div class="sidr-login">
										<?php mobile_login(); ?>
									</div>

								</div>
								<?php be_help( $text = '主题选项 → 菜单设置 → 菜单外观' ); ?>
							</div>
						<?php } ?>
					</div>

					<div class="be-nav-r">
						<?php if ( zm_get_option( 'main_menu' ) == 'main_menu_r' ) { ?>
							<div class="be-nav-wrap betip">
								<div id="site-nav-wrap" class="site-nav-main" style="margin-right: <?php echo zm_get_option( 'nav_margin' ); ?>px">
									<div id="sidr-close">
										<div class="toggle-sidr-close"></div>
									</div>
									<nav id="site-nav" class="main-nav<?php nav_ace(); ?>">
										<?php menu_nav(); ?>
									</nav>
									<div class="clear"></div>

									<div class="sidr-login">
										<?php mobile_login(); ?>
									</div>
								</div>
								<?php be_help( $text = '主题选项 → 菜单设置 → 菜单外观' ); ?>
							</div>
						<?php } ?>

						<?php if ( zm_get_option( 'menu_search_button' ) ) { ?>
							<?php if ( ! zm_get_option( 'menu_search' ) ) { ?>
								<?php menu_search(); ?>
							<?php } else { ?>
								<span class="nav-search<?php echo cur(); ?>"></span>
							<?php } ?>
						<?php } else { ?>
							<span class="nav-search-room"></span>
						<?php } ?>

						<?php if ( zm_get_option( 'front_login' ) ) { ?>
							<div class="be-nav-login-but betip">
								<?php login_but(); ?>
								<?php be_help( $text = '基本设置<br />↓<br />管理站点<br />↓<br />主菜单登录按钮' ); ?>
							</div>
						<?php } ?>
					</div>

					<?php if (zm_get_option('weibo_t')) { get_template_part( 'template/weibo' ); } ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php if ( zm_get_option( 'm_nav' ) ) { ?>
		<?php if ( wp_is_mobile() ) { ?><?php mobile_nav(); ?><?php } ?>
	<?php } ?>
</header>
<?php } ?>
<?php if ( zm_get_option( 'menu_search_button' ) && zm_get_option( 'menu_search' ) ) { ?><?php get_template_part( 'template/search-main' ); ?><?php } ?>