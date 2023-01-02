<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="search-main" class="da">
	<div class="off-search-a<?php echo cur(); ?>"></div>
	<div class="search-wrap bgt fadeInDown animated">
		<ul class="search-tabs"> 
			<?php if ( zm_get_option( 'wp_s' ) ) { ?><li class="searchwp"><a href=".search-wp"><?php _e( '站内', 'begin' ); ?></a></li><?php } ?>
			<?php if ( zm_get_option( 'baidu_s' ) ) { ?><li class="searchbaidu"><a href=".search-baidu"><?php _e( '百度', 'begin' ); ?></a></li><?php } ?>
			<?php if ( zm_get_option( 'google_s' ) ) { ?><li class="searchgoogle"><a href=".search-google"><?php _e( '谷歌', 'begin' ); ?></a></li><?php } ?>
			<?php if ( zm_get_option( 'bing_s' ) ) { ?><li class="searchbing"><a href=".search-bing"><?php _e( '必应', 'begin' ); ?></a></li><?php } ?>
			<?php if ( zm_get_option( 'sogou_s' ) ) { ?><li class="searchsogou"><a href=".search-sogou"><?php _e( '搜狗', 'begin' ); ?></a></li><?php } ?>
			<?php if ( zm_get_option( '360_s' ) ) { ?><li class="search360"><a href=".search-360"><?php _e( '360', 'begin' ); ?></a></li><?php } ?>
		</ul>
		<div class="clear"></div>
		<?php if ( zm_get_option( 'wp_s' ) ) { ?>
			<div class="search-wp tab-search searchbar da">
			<?php if ( zm_get_option( 'ajax_search' ) ) { ?>
				<form class="ajax-search-input da" autocomplete="off">
					<div class="search-input">
						<input class="bk dah search-focus wp-input" type="text" value="<?php echo get_search_query(); ?>" name="s" id="wpsearchInput" onkeyup="ajax_search_s()" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" />
						<script type="text/javascript">document.getElementById('wpsearchInput').addEventListener('input', function(e) {var value = ajax_search_s();});</script>
						<div class="ajax-button da"><i class="be be-loader ajax-button-loader"></i></div>
					</div>
				</form>
				<?php if ( !zm_get_option( 'search_the' ) || ( zm_get_option( "search_the" ) == 'search_list' ) ){ ?>
					<div id="wpsearchdata" class="da be-search-list"></div>
				<?php } ?>
				<?php if ( zm_get_option( 'search_the' ) == 'search_img' ){ ?>
					<div id="wpsearchdata" class="da be-search-img"></div>
				<?php } ?>
				<?php if ( zm_get_option( 'search_the' ) == 'search_normal' ){ ?>
					<div id="wpsearchdata" class="da be-search-normal"></div>
				<?php } ?>
				<div class="clear"></div>
			<?php } else { ?>
				<form method="get" id="searchform-so" autocomplete="off" action="<?php echo esc_url( home_url() ); ?>/">
					<span class="search-input">
						<input type="text" value="<?php the_search_query(); ?>" name="s" id="so" class="bk dah search-focus wp-input" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" required />
						<button type="submit" id="searchsubmit-so" class="bk da<?php echo cur(); ?>"><i class="be be-search"></i></button>
					</span>
					<?php if ( zm_get_option( 'search_option' ) == 'search_cat' ) { ?><?php search_cat_args( ); ?><?php } ?>
					<div class="clear"></div>
				</form>
			<?php } ?>
			</div>
		<?php } ?>

		<?php if ( zm_get_option( 'baidu_s' ) ) { ?>
		<div class="search-baidu tab-search searchbar da">
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
					<input name=word class="swap_value bk dah search-focus baidu-input" placeholder="<?php _e( '百度一下', 'begin' ); ?>" name="q" />
					<input name=tn type=hidden value="bds" />
					<input name=cl type=hidden value="3" />
					<input name=ct type=hidden />
					<input name=si type=hidden value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
					<button type="submit" id="searchbaidu" class="search-close bk da<?php echo cur(); ?>"><i class="be be-baidu"></i></button>
					<input name=s class="choose" type=radio />
					<input name=s class="choose" type=radio checked />
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if ( zm_get_option( 'google_s' ) ) { ?>
			<div class="search-google tab-search searchbar da">
				<form method="get" id="searchform" action="https://cse.google.com/cse" target="_blank" autocomplete="off">
					<span class="search-input">
						<input type="text" value="<?php the_search_query(); ?>" name="q" id="s" class="bk dah search-focus google-input" placeholder="Google" />
						<input type="hidden" name="cx" value="<?php echo zm_get_option('google_id'); ?>" />
						<input type="hidden" name="ie" value="UTF-8" />
						<button type="submit" id="searchsubmit" class="search-close bk da<?php echo cur(); ?>"><i class="cx cx-google"></i></button>
					</span>
				</form>
			</div>
		<?php } ?>

		<?php if ( zm_get_option( 'bing_s' ) ) { ?>
		<div class="search-bing tab-search searchbar da">
			<form method="get" id="searchform" action="https://www.bing.com/search" target="_blank" autocomplete="off" >
				<span class="search-input">
					<input type="text" value="<?php the_search_query(); ?>" name="q" id="s" class="bk dah search-focus bing-input" placeholder="Bing" />
					<input type="hidden" name="q1" value="site:<?php echo $_SERVER['SERVER_NAME']; ?>">
					<button type="submit" id="searchsubmit" class="bk da<?php echo cur(); ?>"><i class="cx cx-bing"></i></button>
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if ( zm_get_option( '360_s' ) ) { ?>
		<div class="search-360 tab-search searchbar da">
			<form action="https://www.so.com/s" target="_blank" id="so360form" autocomplete="off">
				<span class="search-input">
					<input type="text" placeholder="<?php _e( '360搜索', 'begin' ); ?>" name="q" id="so360_keyword" class="bk dah search-focus input-360">
					<button type="submit" id="so360_submit" class="search-close bk da<?php echo cur(); ?>"><i class="cx cx-liu"></i></button>
					<input type="hidden" name="ie" value="utf-8">
					<input type="hidden" name="src" value="zz_<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="site" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="rg" value="1">
					<input type="hidden" name="inurl" value="">
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if ( zm_get_option( 'sogou_s' ) ) { ?>
		<div class="search-sogou tab-search searchbar da">
			<form action="https://www.sogou.com/web" target="_blank" name="sogou_queryform" autocomplete="off">
				<span class="search-input">
					<input type="text" placeholder="<?php _e( '上网从搜狗开始', 'begin' ); ?>" name="query" class="bk dah search-focus sogou-input">
					<button type="submit" id="sogou_submit" class="search-close bk da<?php echo cur(); ?>" onclick="check_insite_input(document.sogou_queryform, 1)"><i class="cx cx-Sougou"></i></button>
					<input type="hidden" name="insite" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
				</span>
			</form>
		</div>
		<?php } ?>
		<div class="clear"></div>

		<?php if ( zm_get_option( 'search_nav' ) ) { ?>
		<nav class="search-nav hz">
			<h4 class="hz"><i class="be be-star"></i><?php _e( '搜索热点', 'begin' ); ?></h4>
			<div class="clear"></div>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'search',
					'menu_class'     => 'search-menu',
					'fallback_cb'    => 'search_menu'
				) );
			?>
		</nav>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<div class="off-search-b<?php echo cur(); ?>">
		<div class="clear"></div>
	</div>
	<div class="off-search dah fadeInDown animated<?php echo cur(); ?>"></div>
</div>