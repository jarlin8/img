<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="search-main" class="da">
	<div class="off-search-a"></div>
	<div class="search-wrap bgt fadeInDown animated">
		<?php if (zm_get_option('wp_s')) { ?>
			<div class="searchbar da">
			<?php if (zm_get_option('ajax_search')) { ?>
				<form class="ajax-search-input da">
					<div class="search-input">
						<input class="bk dah" type="text" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s" id="wpsearchInput" onkeyup="ajax_search_s()" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" />
						<script type="text/javascript">document.getElementById('wpsearchInput').addEventListener('input', function(e) {var value = ajax_search_s();});</script>
						<div class="ajax-button da"><i class="be be-loader ajax-button-loader"></i></div>
					</div>
				</form>
				<?php if (!zm_get_option('search_the') || (zm_get_option("search_the") == 'search_list')){ ?>
					<div id="wpsearchdata" class="da be-search-list"></div>
				<?php } ?>
				<?php if (zm_get_option('search_the') == 'search_img'){ ?>
					<div id="wpsearchdata" class="da be-search-img"></div>
				<?php } ?>
				<?php if (zm_get_option('search_the') == 'search_normal'){ ?>
					<div id="wpsearchdata" class="da be-search-normal"></div>
				<?php } ?>
				<div class="clear"></div>
			<?php } else { ?>
				<form method="get" id="searchform-so" action="<?php echo esc_url( home_url() ); ?>/">
					<span class="search-input">
						<input type="text" value="<?php the_search_query(); ?>" name="s" id="so" class="bk dah" placeholder="<?php _e( '输入关键字', 'begin' ); ?>" required />
						<button type="submit" id="searchsubmit-so" class="bk da"><i class="be be-search"></i></button>
					</span>
					<?php if (zm_get_option('search_option') == 'search_cat') { ?><?php search_cat_args( ); ?><?php } ?>
					<div class="clear"></div>
				</form>
			<?php } ?>
			</div>
		<?php } ?>

		<?php if (zm_get_option('baidu_s')) { ?>
		<div class="searchbar da">
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
			<form name="f1" onsubmit="return g(this)" target="_blank">
				<span class="search-input">
					<input name=word class="swap_value bk dah" placeholder="百度一下" name="q" />
					<input name=tn type=hidden value="bds" />
					<input name=cl type=hidden value="3" />
					<input name=ct type=hidden />
					<input name=si type=hidden value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
					<button type="submit" id="searchbaidu" class="search-close bk da"><i class="be be-baidu"></i></button>
					<input name=s class="choose" type=radio />
					<input name=s class="choose" type=radio checked />
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if (zm_get_option('google_s')) { ?>
			<div class="searchbar da">
				<form method="get" id="searchform" action="https://cse.google.com/cse" target="_blank">
					<span class="search-input">
						<input type="text" value="<?php the_search_query(); ?>" name="q" id="s" class="bk dah" placeholder="Google" />
						<input type="hidden" name="cx" value="<?php echo zm_get_option('google_id'); ?>" />
						<input type="hidden" name="ie" value="UTF-8" />
						<button type="submit" id="searchsubmit" class="search-close bk da"><svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1785" width="200" height="200"><path d="M522.016 438.016v176h290.016c-12 76-88 222.016-290.016 222.016-174.016 0-318.016-146.016-318.016-324s144-324 318.016-324c100 0 166.016 44 204 80l140-134.016C776 50.016 660 0 522.016 0c-282.016 0-512 230.016-512 512 0 284 230.016 512 512 512 296 0 492-208 492-500 0-34.016-4-60-8-84z" p-id="1786"></path></svg></button>
					</span>
				</form>
			</div>
		<?php } ?>

		<?php if (zm_get_option('bing_s')) { ?>
		<div class="searchbar da">
			<form method="get" id="searchform" action="https://www.bing.com/search" target="_blank">
				<span class="search-input">
					<input type="text" value="<?php the_search_query(); ?>" name="q" id="s" class="bk dah" placeholder="Bing" />
					<input type="hidden" name="q1" value="site:<?php echo $_SERVER['SERVER_NAME']; ?>">
					<button type="submit" id="searchsubmit" class="bk da"><svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2107" width="200" height="200"><path d="M340.5824 70.109867L102.536533 0.682667v851.217066L340.650667 643.345067V70.109867zM102.536533 851.7632l238.045867 171.6224 580.881067-340.923733V411.784533L102.536533 851.831467z" p-id="2108"></path><path d="M409.463467 255.3856l113.732266 238.933333 138.8544 56.866134 259.413334-139.400534-506.0608-156.330666z" p-id="2109"></path></svg></button>
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if (zm_get_option('360_s')) { ?>
		<div class="searchbar da">
			<form action="https://www.so.com/s" target="_blank" id="so360form">
				<span class="search-input">
					<input type="text" autocomplete="off"  placeholder="360搜索" name="q" id="so360_keyword" class="bk dah">
					<button type="submit" id="so360_submit" class="search-close bk da"><svg class="icon soico" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3349" width="200" height="200"><path d="M457.8816 954.2656C214.016 954.2656 15.616 755.8656 15.616 512S214.016 69.7344 457.8816 69.7344s442.2656 198.4 442.2656 442.2656-198.41024 442.2656-442.2656 442.2656z m0-742.25664c-165.41696 0-299.99104 134.57408-299.99104 299.99104S292.4544 811.99104 457.8816 811.99104 757.87264 677.41696 757.87264 512 623.29856 212.00896 457.8816 212.00896z" p-id="3350"></path><path d="M937.24672 849.11104m-71.13728 0a71.13728 71.13728 0 1 0 142.27456 0 71.13728 71.13728 0 1 0-142.27456 0Z" p-id="3351"></path><path d="M457.8816 954.2656c-175.33952 0-334.2848-103.71072-404.9408-264.21248l-1.95584-4.5056c-15.4112-36.13696 1.39264-77.9264 37.51936-93.3376 36.12672-15.4112 77.9264 1.3824 93.3376 37.51936l1.29024 2.9696c47.95392 108.93312 155.79136 179.29216 274.7392 179.29216 117.5552 0 224.88064-69.21216 273.41824-176.32256 0.65536-1.44384 1.31072-2.9184 1.9456-4.39296 15.60576-36.05504 57.47712-52.6336 93.53216-37.02784 36.05504 15.60576 52.6336 57.47712 37.02784 93.53216-0.95232 2.21184-1.93536 4.4032-2.92864 6.59456-71.53664 157.88032-229.72416 259.8912-402.98496 259.8912z" p-id="3352"></path></svg></button>
					<input type="hidden" name="ie" value="utf-8">
					<input type="hidden" name="src" value="zz_<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="site" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="rg" value="1">
					<input type="hidden" name="inurl" value="">
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if (zm_get_option('sogou_s')) { ?>
		<div class="searchbar da">
			<form action="https://www.sogou.com/web" target="_blank" name="sogou_queryform">
				<span class="search-input">
					<input type="text" placeholder="上网从搜狗开始" name="query" class="bk dah">
					<button type="submit" id="sogou_submit" class="search-close bk da" onclick="check_insite_input(document.sogou_queryform, 1)"><svg class="icon soico" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2771" width="200" height="200"><path d="M975.3088 822.10816l-53.80096-52.57216-0.02048 0.01024c-33.6896 59.16672-81.5616 109.2096-139.02848 145.4592-0.06144 0.04096-0.12288 0.07168-0.18432 0.11264l50.50368 53.62688a486.668288 486.668288 0 0 0 142.53056-146.6368z" p-id="2772"></path><path d="M509.83936 924.75392C281.87648 924.75392 97.08544 739.96288 97.08544 512c0-227.96288 184.80128-412.75392 412.75392-412.75392S922.59328 284.03712 922.59328 512c0 74.16832-19.57888 143.75936-53.83168 203.90912l0.02048-0.01024 53.80096 52.57216C969.13408 694.09792 996.07552 606.208 996.07552 512c0-267.34592-216.7296-484.07552-484.07552-484.07552S27.92448 244.65408 27.92448 512 244.65408 996.07552 512 996.07552c99.14368 0 191.31392-29.82912 268.06272-80.96768l-50.50368-53.62688c-63.62112 40.07936-138.96704 63.27296-219.71968 63.27296z" p-id="2773"></path><path d="M785.05984 403.10784l4.46464-105.96352s-126.03392-60.78464-310.6304-60.23168c-184.59648 0.55296-255.97952 65.24928-253.7472 153.36448 2.23232 88.1152 132.73088 142.76608 221.96224 170.6496 89.23136 27.88352 157.26592 45.73184 156.14976 81.98144-1.11616 36.2496-45.86496 48.51712-91.24864 47.96416s-204.31872-30.6688-282.95168-92.57984l-3.95264 120.46336s155.648 75.84768 324.06528 70.83008c168.41728-5.0176 255.97952-73.05216 254.30016-156.14976S700.29312 497.92 593.7664 464.45568s-173.44512-53.53472-172.88192-91.46368 127.71328-72.4992 364.17536 30.11584z" p-id="2774"></path></svg></button>
					<input type="hidden" name="insite" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
				</span>
			</form>
		</div>
		<?php } ?>
		<div class="clear"></div>

		<?php if (zm_get_option('search_nav')) { ?>
		<nav class="search-nav hz">
			<h4 class="hz"><?php _e( '搜索热点', 'begin' ); ?></h4>
			<div class="clear"></div>
			<?php
				wp_nav_menu( array(
					'theme_location'=> 'search',
					'menu_class'    => 'search-menu',
					'fallback_cb'   => 'search_menu'
				) );
			?>
		</nav>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<div class="off-search-b">
		<div class="clear"></div>
	</div>
	<div class="off-search dah fadeInDown animated"></div>
</div>