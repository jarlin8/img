<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
	if ( ! zm_get_option( 'scroll_hide' ) || ( zm_get_option( 'scroll_hide' ) == 'scroll_mobile' ) ) {
		if ( wp_is_mobile() ) { 
			$scroll_hide = ' scroll-hide';
		} else {
			$scroll_hide = '';
		}
	}
	if ( zm_get_option( 'scroll_hide' ) == 'scroll_desktop' ) {
		$scroll_hide = ' scroll-hide';
	}

	if ( zm_get_option( 'scroll_hide' ) == 'scroll_default' ) {
		$scroll_hide = '';
	}
?>
<?php if ( zm_get_option( 'mobile_scroll' ) && wp_is_mobile() ) { ?>
<?php } else { ?>
<ul id="scroll" class="scroll<?php if ( zm_get_option( 'btn_scroll_show' ) ) { ?> scroll-but<?php } ?><?php echo $scroll_hide; ?>">
	<?php if ( function_exists( 'be_toc' ) ) { ?>
		<?php if ( ! is_active_widget( '', '', 'be_toc_widget' ) || wp_is_mobile() ) { ?>
			<li class="toc-scroll toc-no"><span class="toc-button fo ms"><i class="be be-sort"></i></span><div class="toc-prompt"><div class="toc-arrow dah<?php if ( get_bloginfo( 'language' ) === 'en-US' ) { ?> toc-arrow-en<?php } ?>"><?php _e( '目录', 'begin' ); ?><i class="be be-playarrow"></i></div></div></li>
		<?php } else { ?>
			<li class="toc-scroll toc-no"><span class="toc-widget fo ms"><i class="be be-sort"></i></span><div class="toc-prompt"><div class="toc-arrow dah<?php if ( get_bloginfo( 'language' ) === 'en-US' ) { ?> toc-arrow-en<?php } ?>"><?php _e( '目录', 'begin' ); ?><i class="be be-playarrow"></i></div></div></li>
		<?php } ?>
	<?php } ?>

	<?php 
		if ( zm_get_option( 'admin_placard' ) ) {
			$placard = zm_get_option( 'placard_layer' ) && !current_user_can( 'manage_options' );
		} else {
			$placard = zm_get_option( 'placard_layer' );
		}
	 if ( $placard && zm_get_option( 'placard_but' ) ) { ?>
		<?php if ( be_get_option( 'placard_mobile' ) || ( ! wp_is_mobile() ) ) { ?>
			<li><span class="placard-but fo ms"><i class="be be-volumedown"></i></span></li>
		<?php } ?>
	<?php } ?>

	<?php if ( function_exists( 'epd_assets_vip' ) && zm_get_option( 'vip_scroll' ) ) { ?>
		<li>
			<?php if ( ! is_user_logged_in() ) { ?>
				<span class="scroll-vip fo ms show-layer cur"><i class="cx cx-svip"></i></span>
			<?php } else { ?>
				<a class="scroll-vip fo ms" href="<?php echo zm_get_option('be_vip_but_url'); ?>"><i class="cx cx-svip"></i></a>
			<?php } ?>
		</li>
	<?php } ?>

	<?php if ( zm_get_option( 'scroll_h' ) ) { ?>
		<li>
			<span class="scroll-h ms fo<?php if ( zm_get_option( 'scroll_progress' ) && zm_get_option( 'scroll_percentage' ) ) { ?> scroll-load<?php } ?>">
				<?php if ( zm_get_option( 'scroll_progress' ) ) { ?>
					<span class="progresswrap<?php if ( zm_get_option( 'scroll_progress_m' ) == 'out' ) { ?> progress-out<?php } ?>">
						<svg class="progress-circle">
							<circle stroke="var(--inactive-color)" />
							<circle class="progress-value" stroke="var(--color)" style="stroke-dasharray: calc( 2 * 3.1415 * (var(--size) - var(--border-width)) / 2 * (var(--percent) / 100)), 1000" />
						</svg>
					</span>

					<?php if ( zm_get_option( 'scroll_percentage' ) ) { ?><span class="scroll-percentage"></span><?php } ?>
				<?php } ?>
				<i class="be be-arrowup"></i>
			</span>
		</li>
	<?php } ?>
	<?php if ( zm_get_option( 'scroll_b' ) ) { ?><li><span class="scroll-b ms fo"><i class="be be-arrowdown"></i></span></li><?php } ?>
	<?php if ( zm_get_option( 'scroll_z' ) ) { ?><?php if ( is_singular() || is_category() ) { ?><li class="foh"><a class="scroll-home ms fo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><i class="be be-home"></i></a></li><?php } ?><?php } ?>
	<?php if ( zm_get_option( 'scroll_c' ) && !zm_get_option('close_comments') ) { ?><?php if (is_singular() && comments_open()) { ?><li class="foh"><span class="scroll-c fo"><i class="be be-speechbubble"></i></span></li><?php } ?><?php } ?>
	<?php if ( zm_get_option( 'read_night' ) ) { ?>
		<ul class="night-day">
			<li class="foh"><span class="night-main"><span class="m-night fo ms"><span class="m-moon"><span></span></span></span></span></li>
			<li class="foh"><span class="m-day fo ms"><i class="be be-loader"></i></span></li>
		</ul>
	<?php } ?>
	<?php if ( zm_get_option( 'scroll_s' ) ) { ?><li class="foh"><span class="scroll-search ms fo"><i class="be be-search"></i></span></li><?php } ?>
	<?php if ( zm_get_option( 'gb2' ) ) { ?><li class="gb2-site foh"><a id="gb2big5" class="ms fo"><span class="dah">繁</span></a></li><?php } ?>
	<?php if ( zm_get_option( 'qq_online' ) ) { ?><?php get_template_part( 'template/qqonline' ); ?><?php } ?>
	<?php if ( zm_get_option('qrurl') && !wp_is_mobile() ) { ?>
		<li class="qrshow foh">
			<span class="qrurl ms fo"><i class="be be-qr-code"></i></span>
			<span class="qrurl-box popup">
				<img id="qrious" alt="<?php bloginfo( 'name' ); ?>">
				<?php if ( zm_get_option( 'logo_small_b' ) )  { ?><span class="logo-qr"><img src="<?php echo zm_get_option( 'logo_small_b' ); ?>" alt="<?php bloginfo( 'name' ); ?>"></span><?php } ?>
				<span><?php _e( '本页二维码', 'begin' ); ?></span>
				<span class="arrow-right"></span>
			</span>
		</li>
	<?php } ?>

	<?php if ( zm_get_option( 'scroll_hide' ) !== 'scroll_default' ) { ?>
		<?php if ( ! zm_get_option( 'scroll_hide' ) || ( zm_get_option( 'scroll_hide' ) == 'scroll_mobile' ) ) { ?>
			<?php if ( wp_is_mobile() ) { ?>
				<li><span class="rounded-full fo ms"><i class="be be-more"></i></span></li>
			<?php } ?>
		<?php } ?>
		<?php if ( zm_get_option( 'scroll_hide' ) == 'scroll_desktop' ) { ?>
			<li><span class="rounded-full fo ms"><i class="be be-more"></i></span></li>
		<?php } ?>
	<?php } ?>

	<?php be_help( $text = '主题选项<br />↓<br />辅助功能<br />↓<br />跟随按钮设置' ); ?>
</ul>
<?php } ?>