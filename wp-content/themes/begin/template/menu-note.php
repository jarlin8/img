<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<header id="masthead" class="site-header header-note nav-full-width">
	<div id="header-main-n" class="header-main-note">
		<div id="menu-container" class="be-menu-main<?php if ( zm_get_option( 'menu_glass' ) ) { ?> menu-glass<?php } ?>">
			<div id="navigation-top" class="navigation-top<?php if (zm_get_option('menu_block')) { ?> menu_c<?php } ?>">
				<div class="be-nav-box">
					<div class="be-nav-l">
						<div class="be-nav-logo">
							<?php if ( zm_get_option( "logo_css" ) && ( ! wp_is_mobile() ) ) { ?>
								<div class="logo-site<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logo_small')) { ?> logo-txt<?php } ?>">
							<?php } else { ?>
								<div class="logo-sites<?php if (!zm_get_option('site_sign') || (zm_get_option('site_sign') == 'logo_small')) { ?> logo-txt<?php } ?>">
							<?php } ?>
								<?php menu_logo(); ?>
							</div>
						</div>


					</div>

					<div class="be-nav-r">
	
							<div class="be-nav-wrap betip">
								<div id="site-nav-wrap" class="site-nav-main" style="margin-right: <?php echo zm_get_option( 'nav_margin' ); ?>px">
									<div id="sidr-close">
										<div class="toggle-sidr-close"><span class="sidr-close-ico"></span></div>
										<?php mobile_login(); ?>
									</div>
									<nav id="site-nav" class="main-nav<?php nav_ace(); ?>">
										<?php menu_nav(); ?>
									</nav>
								</div>
							</div>
		

						<?php menu_search(); ?>

						<div class="be-nav-login-but betip">
							<?php login_but(); ?>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</header>

<?php if ( zm_get_option( 'menu_search_button' ) && zm_get_option( 'menu_search' ) ) { ?><?php get_template_part( 'template/search-main' ); ?><?php } ?>