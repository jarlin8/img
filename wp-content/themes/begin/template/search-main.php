<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="search-main" class="search-main">
	<div class="search-box fadeInDown animated">
		<div class="off-search-a<?php echo cur(); ?>"></div>
		<div class="search-area">
			<div class="search-wrap betip">
				<?php be_help( $text = '主题选项 → 搜索设置 → 弹窗搜索' ); ?>
				<div class="search-tabs">
					<?php if ( zm_get_option( 'wp_s' ) ) { ?><div class="search-item searchwp"><span class="search-wp"><?php _e( '站内', 'begin' ); ?></span></div><?php } ?>
					<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'baidu_s', zm_get_option( 'search_mode' ) ) ) { ?><div class="search-item searchbaidu"><span class="search-baidu"><?php _e( '百度', 'begin' ); ?></span></div><?php } ?>
					<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'google_s', zm_get_option( 'search_mode' ) ) ) { ?><div class="search-item searchgoogle"><span class="search-google"><?php _e( '谷歌', 'begin' ); ?></span></div><?php } ?>
					<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'bing_s', zm_get_option( 'search_mode' ) ) ) { ?><div class="search-item searchbing"><span class="search-bing"><?php _e( '必应', 'begin' ); ?></span></div><?php } ?>
					<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'sogou_s', zm_get_option( 'search_mode' ) ) ) { ?><div class="search-item searchsogou"><span class="search-sogou"><?php _e( '搜狗', 'begin' ); ?></span></div><?php } ?>
					<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( '360_s', zm_get_option( 'search_mode' ) ) ) { ?><div class="search-item search360"><span class="search-360"><?php _e( '360', 'begin' ); ?></span></div><?php } ?>
				</div>
				<div class="clear"></div>
				<?php if ( zm_get_option( 'wp_s' ) ) { ?>
					<div class="search-wp tab-search searchbar">
						<form method="get" id="searchform-so" autocomplete="off" action="<?php echo esc_url( home_url() ); ?>/">
							<span class="search-input">
								<input type="text" value="<?php the_search_query(); ?>" name="s" id="so" class="search-focus wp-input" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" required />
								<button type="submit" id="searchsubmit-so" class="sbtu<?php echo cur(); ?>"><i class="be be-search"></i></button>
							</span>
							<?php if ( zm_get_option( 'search_option' ) == 'search_cat' ) { ?><?php search_cat_args( ); ?><?php } ?>
							<div class="clear"></div>
						</form>
					</div>
				<?php } ?>

				<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'baidu_s', zm_get_option( 'search_mode' ) ) ) { ?>
				<div class="search-baidu tab-search searchbar">
					<script>
					function g(formname) {
						var url = "https://www.baidu.com/baidu";
						if (formname.s[1].checked) {
							formname.ct.value = "2097152";
						} else {
							formname.ct.value = "0";
						}
						formname.action = url;
						return true;
					}
					</script>
					<form name="f1" onsubmit="return g(this)" target="_blank" autocomplete="off">
						<span class="search-input">
							<input name=word class="swap_value search-focus baidu-input" placeholder="<?php _e( '百度一下', 'begin' ); ?>" />
							<input name=tn type=hidden value="bds" />
							<input name=cl type=hidden value="3" />
							<input name=ct type=hidden />
							<input name=si type=hidden value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
							<button type="submit" id="searchbaidu" class="search-close<?php echo cur(); ?>"><i class="be be-baidu"></i></button>
							<input name=s class="choose" type=radio />
							<input name=s class="choose" type=radio checked />
						</span>
					</form>
				</div>
				<?php } ?>

				<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'google_s', zm_get_option( 'search_mode' ) ) ) { ?>
					<div class="search-google tab-search searchbar">
						<form method="get" id="googleform" action="https://cse.google.com/cse" target="_blank" autocomplete="off">
							<span class="search-input">
								<input type="text" value="<?php the_search_query(); ?>" name="q" id="google" class="search-focus google-input" placeholder="Google" />
								<input type="hidden" name="cx" value="<?php echo zm_get_option('google_id'); ?>" />
								<input type="hidden" name="ie" value="UTF-8" />
								<button type="submit" id="googlesubmit" class="search-close<?php echo cur(); ?>"><i class="cx cx-google"></i></button>
							</span>
						</form>
					</div>
				<?php } ?>

				<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'bing_s', zm_get_option( 'search_mode' ) ) ) { ?>
				<div class="search-bing tab-search searchbar">
					<form method="get" id="bingform" action="https://www.bing.com/search" target="_blank" autocomplete="off" >
						<span class="search-input">
							<input type="text" value="<?php the_search_query(); ?>" name="q" id="bing" class="search-focus bing-input" placeholder="Bing" />
							<input type="hidden" name="q1" value="site:<?php echo $_SERVER['SERVER_NAME']; ?>">
							<button type="submit" id="bingsubmit" class="sbtu<?php echo cur(); ?>"><i class="cx cx-bing"></i></button>
						</span>
					</form>
				</div>
				<?php } ?>

				<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'sogou_s', zm_get_option( 'search_mode' ) ) ) { ?>
				<div class="search-sogou tab-search searchbar">
					<form action="https://www.sogou.com/web" target="_blank" name="sogou_queryform" autocomplete="off">
						<span class="search-input">
							<input type="text" placeholder="<?php _e( '上网从搜狗开始', 'begin' ); ?>" name="query" class="search-focus sogou-input">
							<button type="submit" id="sogou_submit" class="search-close<?php echo cur(); ?>" onclick="check_insite_input(document.sogou_queryform, 1)"><i class="cx cx-Sougou"></i></button>
							<input type="hidden" name="insite" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
						</span>
					</form>
				</div>
				<?php } ?>

				<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( '360_s', zm_get_option( 'search_mode' ) ) ) { ?>
				<div class="search-360 tab-search searchbar">
					<form action="https://www.so.com/s" target="_blank" id="so360form" autocomplete="off">
						<span class="search-input">
							<input type="text" placeholder="<?php _e( '360搜索', 'begin' ); ?>" name="q" id="so360_keyword" class="search-focus input-360">
							<button type="submit" id="so360_submit" class="search-close<?php echo cur(); ?>"><i class="cx cx-liu"></i></button>
							<input type="hidden" name="ie" value="utf-8">
							<input type="hidden" name="src" value="zz_<?php echo $_SERVER['SERVER_NAME']; ?>">
							<input type="hidden" name="site" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
							<input type="hidden" name="rg" value="1">
							<input type="hidden" name="inurl" value="">
						</span>
					</form>
				</div>
				<?php } ?>

				<div class="clear"></div>

				<?php if ( zm_get_option( 'search_history' ) && zm_get_option( 'search_hot' ) ) { ?>
					<div class="search-hot-main">
						<?php echo be_hot_search( zm_get_option( 'search_hot_n' ), '' . sprintf(__( '热门搜索', 'begin' ) ) . '' ); ?>
					</div>
				<?php } ?>
				<?php if ( zm_get_option( 'search_nav' ) ) { ?>
				<nav class="search-nav betip">
					<div class="clear"></div>
					<?php
						wp_nav_menu( array(
							'theme_location' => 'search',
							'menu_class'     => 'search-menu',
							'fallback_cb'    => 'search_menu'
						) );
					?>
					<?php be_help( $text = '主题选项 → 搜索设置 → 搜索推荐' ); ?>
				</nav>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="off-search<?php echo cur(); ?>"></div>
</div>