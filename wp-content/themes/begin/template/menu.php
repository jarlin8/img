<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('header_normal')) { ?>
<header id="masthead" class="site-header-o">
	<?php if ( !zm_get_option('top_bg') == '' ) { ?>
		<div id="header-main-o" class="header-main-o da" style="background: url('<?php if ( !zm_get_option('top_bg') == '' ) { ?><?php echo zm_get_option('top_bg'); ?><?php } ?>') repeat-x;background-position: top center;background-size: auto 300px;">
	<?php } else { ?>
		<div id="header-main-o" class="header-main-o da" style="background:<?php echo zm_get_option('header_color'); ?>;">
	<?php } ?>

		<?php if (!zm_get_option('top_nav_no')) { ?>
		<nav id="header-top" class="header-top-o bkx">
			<?php menu_top(); ?>
		</nav>
		<?php } ?>

		<div class="logo-box">
			<?php if ( zm_get_option( "logo_css" ) && ( !wp_is_mobile() ) ) { ?>
				<div class="logo-site-o">
			<?php } else { ?>
				<div class="logo-sites-o">
			<?php } ?>
				<?php menu_logo(); ?>
			</div>
			<?php if ( !zm_get_option( 'h_main_o' ) || ( zm_get_option( 'h_main_o' ) == 'h_search' ) ) { ?>
				<div class="header-top-search"><?php get_search_form(); ?></div>
			<?php } ?>
			<?php if ( zm_get_option( 'h_main_o' ) == 'h_contact' ) { ?>
				<?php if ( zm_get_option('header_contact') ) { ?><div class="contact-header"><?php echo zm_get_option('header_contact'); ?></div><?php } ?>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php if (zm_get_option("menu_full")) { ?>
		<div id="menu-container-o" class="menu-container-o menu-container-o-full da bk">
		<?php } else { ?>
		<div id="menu-container-o" class="menu-container-o menu-container-o-with da bk">
		<?php } ?>
			<div id="navigation-top" class="bgt">
				<?php if (zm_get_option("menu_search_button")) { ?><span class="nav-search nav-search-o"></span><?php } ?>
				<?php login_but(); ?>
				<div id="site-nav-wrap-o">
					<div id="sidr-close">
						<span class="toggle-sidr-close"><i class="be be-cross"></i></span>
						<?php mobile_login(); ?>
					</div>
					<nav id="site-nav" class="main-nav-o<?php nav_ace(); ?>">
						<?php menu_nav(); ?>
					</nav>
				</div>

				<?php if (zm_get_option("nav_cat") && ( !wp_is_mobile() ) ) { ?><div class="ajax-content-box nav-cat-post bk" data-text="<?php echo do_shortcode(base64_encode('[nav_cat]')); ?>"></div><?php } ?>
				<?php if (zm_get_option("menu_post") && ( !wp_is_mobile() ) ) { ?><div class="ajax-content-box nav-img-post bk" data-text="<?php echo do_shortcode(base64_encode('[nav_img]')); ?>"></div><?php } ?>
				<?php if (zm_get_option('weibo_t')) { get_template_part( 'template/weibo' ); } ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</header>
<?php } else { ?>
<?php if (zm_get_option('top_nav_no')) { ?>
<header id="masthead" class="site-header site-header-h">
<?php } else { ?>
<header id="masthead" class="site-header da site-header-s">
<?php } ?>
<?php if (!zm_get_option('menu_m') || (zm_get_option("menu_m") == 'menu_d')){ ?>
	<div id="header-main" class="header-main">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_n'){ ?>
	<div id="header-main-n" class="header-main-n">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_g'){ ?>
	<div id="header-main-g" class="header-main-g">
<?php } ?>
		<?php if (!zm_get_option('top_nav_no')) { ?>
		<nav id="header-top" class="header-top dah">
			<?php menu_top(); ?>
		</nav>
		<?php } ?>
		<div id="menu-container" class="da<?php if (zm_get_option('menu_glass')) { ?> menu-glass<?php } ?>">
			<div id="navigation-top" class="bgt<?php if (zm_get_option('menu_block')) { ?> menu_c<?php } ?>">
				<?php if (zm_get_option("menu_search_button")) { ?><span class="nav-search"></span><?php } else { ?><span class="nav-search-room"></span><?php } ?>
				<?php login_but(); ?>
				<?php if ( zm_get_option( "logo_css" ) && ( !wp_is_mobile() ) ) { ?>
					<div class="logo-site">
				<?php } else { ?>
					<div class="logo-sites">
				<?php } ?>
					<?php menu_logo(); ?>
				</div>

				<?php if (zm_get_option("site_nav_left")) { ?>
					<div id="site-nav-wrap" class="site-nav-wrap-left">
				<?php } else { ?>
					<div id="site-nav-wrap" class="site-nav-wrap-right">
				<?php } ?>
					<div id="sidr-close">
						<span class="toggle-sidr-close"><i class="be be-cross"></i></span>
						<?php mobile_login(); ?>
					</div>
					<nav id="site-nav" class="main-nav<?php nav_ace(); ?>">
						<?php menu_nav(); ?>
					</nav>
				</div>
				<?php if (zm_get_option("nav_cat") && ( !wp_is_mobile() ) ) { ?><div class="ajax-content-box nav-cat-post bk" data-text="<?php echo do_shortcode(base64_encode('[nav_cat]')); ?>"></div><?php } ?>
				<?php if (zm_get_option("menu_post") && ( !wp_is_mobile() ) ) { ?><div class="ajax-content-box nav-img-post bk" data-text="<?php echo do_shortcode(base64_encode('[nav_img]')); ?>"></div><?php } ?>
				<?php if (zm_get_option('weibo_t')) { get_template_part( 'template/weibo' ); } ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</header>
<?php } ?>
<?php if (zm_get_option("menu_search_button")) { ?><?php get_template_part( 'template/search-main' ); ?><?php } ?>