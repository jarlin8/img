<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 菜单搜索
function menu_search() { ?>
	<div class="menu-search-button menu-search-open"><i class="be be-search"></i></div>
	<div class="menu-search-box">
		<div class="menu-search-button menu-search-close"><i class="be be-cross"></i></div>
		<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'baidu_s', zm_get_option( 'search_mode' ) ) ) { ?>
			<div class="menu-search-choose" title="<?php _e( '切换搜索', 'begin' ); ?>"><span class="search-choose-ico"><i class="be be-more"></i></span></div>
		<?php } ?>
		<div class="menu-search-item menu-search-wp">
			<form method="get" id="be-menu-search" autocomplete="off" action="<?php echo esc_url( home_url() ); ?>/">
				<span class="menu-search-input">
					<input type="text" value="<?php the_search_query(); ?>" name="s" id="so" class="search-focus" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" required />
					<button type="submit" id="be-menu-search" class="be-menu-search<?php echo cur(); ?>"><i class="be be-search"></i></button>
				</span>
				<?php if ( zm_get_option( 'search_option' ) == 'search_cat' ) { ?><?php search_cat_args( ); ?><?php } ?>
				<div class="clear"></div>
			</form>
		</div>

		<?php if ( is_array( zm_get_option( 'search_mode' ) ) && in_array( 'baidu_s', zm_get_option( 'search_mode' ) ) ) { ?>
		<div class="menu-search-item menu-search-baidu conceal">
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
				<span class="menu-search-input">
					<input name=word class="swap_value search-focus-baidu" placeholder="<?php _e( '百度一下', 'begin' ); ?>" name="q" />
					<input name=tn type=hidden value="bds" />
					<input name=cl type=hidden value="3" />
					<input name=ct type=hidden />
					<input name=si type=hidden value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
					<button type="submit" id="searchbaidu" class="be-menu-search<?php echo cur(); ?>"><i class="be be-baidu"></i></button>
					<input name=s class="choose" type=radio />
					<input name=s class="choose" type=radio checked />
				</span>
			</form>
		</div>
		<?php } ?>
	</div>
<?php }