<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function be_styles(){
	$styles = '';
	// 颜色
	if (zm_get_option("all_color")) {
		$all_color = substr(zm_get_option("all_color"), 1);
		$styles .= ".top-menu a:hover,#user-profile a:hover,.nav-search:hover,.nav-search:hover:after,#navigation-toggle:hover,.entry-meta a,.entry-meta-no a,.nav-mobile:hover,.nav-mobile a:hover,.single-meta a:hover,.ajax-pagination a:hover,.comment-tool a:hover,.ias-next .be,.slider-home .owl-nav,.slider-group .owl-nav,.slider-single .owl-nav,.ajax-search-input:after,.tabs-more a:hover,.usshang .be,.single-content p a,.single-content table a,.single-content p a:visited,a:hover,.new-icon .be, #site-nav .down-menu > .current-menu-item > a, #site-nav .down-menu > .current-post-ancestor > a, #site-nav .down-menu > li > a:hover, #site-nav .down-menu > li.sfHover > a, .sf-arrows > li > .sf-with-ul:focus:after,.sf-arrows > li:hover > .sf-with-ul:after,.sf-arrows > .sfHover > .sf-with-ul:after, .nav-more:hover .nav-more-i, #site-nav .down-menu > .current-post-ancestor > .sf-with-ul:after, #site-nav .down-menu > .current-menu-item > .sf-with-ul:after, .toc-ul-box .sup a:hover, .toc-ul-box a.active, #all-series h4, .serial-number:before, .seat:before {color:#" . $all_color . ";}.menu_c #site-nav .down-menu > li > a:hover,.menu_c #site-nav .down-menu > li.sfHover > a,.menu_c #site-nav .down-menu > .current-menu-item > a,.menu_c #site-nav .down-menu > .current-post-ancestor > a,.but-i,.toc-zd,.sign input[type='submit'],.entry-more a,.menu-login .show-avatars,.read-pl a:hover,#wp-calendar a,.author-m a,.page-button:hover,.new-more a:hover,.new-more-img a:hover,.owl-dots .owl-dot.active span, .owl-dots .owl-dot:hover span,.owl-carousel .owl-nav button.owl-next,.owl-carousel .owl-nav button.owl-prev,.down-doc a,.down-button a,.wp-ajax-search-not,.begin-tabs-content .tab_title.selected a:after,.main-nav-o .down-menu > li > a:hover,.main-nav-o .down-menu > li.sfHover > a,.main-nav-o .down-menu > .current-menu-item > a,.main-nav-o .down-menu > .current-post-ancestor > a,.group-phone a,#series-letter li,.fancybox-navigation .fancybox-button div:hover,.night .begin-tabs-content .tab_title.selected a::after,.thumbnail .cat,.title-l,.cms-news-grid-container .marked-ico,.special-mark,.gw-ico i,.t-mark#site-nav .down-menu > .current-menu-item > a:hover:before, .main-nav .down-menu a:hover:before, .slide-progress, .btn-login:hover, .cms-picture-cat-title {background:#" . $all_color . ";}.pretty.success input:checked + label i:before{background:#" . $all_color . " !important;}.pretty.success input:checked + label i:after, .deanmove:hover .de-button a{border:#" . $all_color . ";background:#" . $all_color . " !important;}#respond input[type='text']:focus,#respond textarea:focus,.pagination a,.pagination a:visited,.input-number,.page-button,.ball-pulse > div,.new-more a,.tags-cat-img:hover,.new-tabs-all-img:hover,.tags-cat-img.current,.tags-cat-img.current,.new-tabs-all-img.current,.new-more-img a,.ajax-search-box,.searchbar .ajax-search-input input,.grid-cat-title:hover .title-i span,.cat-title:hover .title-i span,.cat-square-title:hover .title-i span,.widget-title:hover .title-i span,.cat-grid-title:hover .title-i span,.child-title:hover .title-i span,.night .filter-on,.filter-on,.sign input:focus,.add-link input:focus,.add-link textarea:focus,.search-input input,.max-num,.sign input:focus,.add-link input:focus,.add-link textarea:focus, .deanmove:hover .de-button a:before, .filter-tag:hover:before, .menu-login-box .nav-reg a, .menu-login-box .nav-login-l a, .menu-login-box .nav-login, #user-profile .userinfo a, #user-profile .userinfo a.user-logout, .mobile-login-l a, .mobile-login-reg a, .mobile-login, .mobile-login a, .add-img-but:hover, .owl-dots .owl-dot:hover span:before, .owl-dots .owl-dot.active span:before, .my-gravatar-apply a, .user-profile .submit, .update-avatar, .bet-btn {border:1px solid #" . $all_color . " !important;}.tab-product .tab-hd .current,.tab-area .current,.tab-title .selected{border-top:2px solid #" . $all_color . ";}.menu-container-o-full,.group-site .menu-container-o-full{border-top:1px solid #" . $all_color . ";}.single-content h3,.single-content h4,.single-content h6,.single-post .entry-header h1,.single-bulletin .entry-header h1,.single-video .entry-header h1,.single-tao .entry-header h1,.single-sites .entry-header h1,.single-picture .entry-header h1{border-left:5px solid #" . $all_color . ";}#search-main .search-cat{border-left:1px solid #" . $all_color . ";}.all-cat a:hover, .btn-login, .bet-btn:hover {color:#" . $all_color . " !important;border:1px solid #" . $all_color . " !important;}.resp-vtabs .resp-tab-active:before{border-left:3px solid #" . $all_color . " !important;}.post-password-form input[type='submit']:hover,.page-links > span,.page-links a:hover span,.meta-nav:hover,#respond #submit:hover,.widget_categories a:hover,.widget_links a:hover,#sidebar .widget_nav_menu a:hover,#sidebar-l .widget_nav_menu a:hover,.link-all a:hover,.pagination span.current,.pagination a:hover,a.fo:hover,.down a,.ajax-button,.tab-pagination a:hover,.widget-nav .widget_nav_menu li a:hover,.img-tab-hd .img-current,.tab-nav li a:hover,.add-link .add-link-btn:hover,.searchbar button, #get_verify_code_btn:hover, .sidebox .userinfo a:hover, #user-profile .userinfo a:hover, .mobile-login-l a:hover, .mobile-login:hover, .mobile-login-reg a:hover, .menu-login-box .nav-login:hover, .menu-login-box #user-profile a:hover, #user-profile .userinfo a.user-logout:hover {background:#" . $all_color . ";border:1px solid #" . $all_color . ";}.link-f a:hover {border:1px solid #" . $all_color . ";}";
		}
	if (zm_get_option("blogname_color")) {
		$blogname_color = substr(zm_get_option("blogname_color"), 1);
		$styles .= ".site-title a {color: #" . $blogname_color . ";}";
	}
	if (zm_get_option("blogdescription_color")) {
		$blogdescription_color = substr(zm_get_option("blogdescription_color"), 1);
		$styles .= ".site-description {color: #" . $blogdescription_color . ";}";
	}
	if (zm_get_option("link_color")) {
		$link_color = substr(zm_get_option("link_color"), 1);
		$styles .= "a:hover, .single-content p a, .single-content table a, .single-content p a:visited, .top-menu a:hover, #user-profile a:hover, .entry-meta a, .entry-meta-no a, .filter-tag:hover, .comment-tool a:hover, .toc-ul-box .sup a:hover, .toc-ul-box a.active {color: #" . $link_color . ";}.grid-cat-title:hover .title-i span, .cat-title:hover .title-i span, .cat-square-title:hover .title-i span, .widget-title:hover .title-i span, .cat-grid-title:hover .title-i span, .child-title:hover .title-i span, #respond input[type='text']:focus, #respond textarea:focus, .login-tab-product input:focus, .add-link input:focus, .add-link textarea:focus, .tags-cat-img:hover, .new-tabs-all-img:hover, .tags-cat-img.current, .tags-cat-img.current, .new-tabs-all-img.current {border: 1px solid #" . $link_color . "}.ball-pulse > div {border: 1px solid #" . $link_color . "}";
	}
	if (zm_get_option("menu_color")) {
		$menu_color = substr(zm_get_option("menu_color"), 1);
		$styles .= ".menu_c #site-nav .down-menu > .current-menu-item > a, .menu_c #site-nav .down-menu > .current-post-ancestor > a, .menu_c #site-nav .down-menu > li > a:hover, .menu_c #site-nav .down-menu > li.sfHover > a, .main-nav-o .down-menu > li > a:hover, .main-nav-o .down-menu > li.sfHover > a, .main-nav-o .down-menu > .current-menu-item > a, .main-nav-o .down-menu > .current-post-ancestor > a, .but-i, #site-nav .down-menu > .current-menu-item > a:hover:before, .main-nav .down-menu a:hover:before {background: #" . $menu_color . "}.menu-container-o-full {border-top: 1px solid #" . $menu_color . "}#site-nav .down-menu > .current-menu-item > a, #site-nav .down-menu > .current-post-ancestor > a, #site-nav .down-menu > li > a:hover, #site-nav .down-menu > li.sfHover > a, .sf-arrows > li > .sf-with-ul:focus:after,.sf-arrows > li:hover > .sf-with-ul:after,.sf-arrows > .sfHover > .sf-with-ul:after, .nav-more:hover .nav-more-i, #site-nav .down-menu > .current-post-ancestor > .sf-with-ul:after, #site-nav .down-menu > .current-menu-item > .sf-with-ul:after, .nav-search:hover:after, #navigation-toggle:hover {color:#" . $menu_color . ";}";
	}
	if (zm_get_option("button_color")) {
		$button_color = substr(zm_get_option("button_color"), 1);
		$styles .= ".pagination a,.pagination a:visited,.filter-on,.night .filter-on,.all-cat a:hover,.new-more a,.page-button,.input-number,.max-num, .sign input:focus, .add-link input:focus, .add-link textarea:focus, .deanmove:hover .de-button a:before, .filter-tag:hover:before, .menu-login-box .nav-reg a, .menu-login-box .nav-login-l a, .menu-login-box .nav-login, #user-profile .userinfo a, #user-profile .userinfo a.user-logout, .mobile-login-l a, .mobile-login-reg a, .mobile-login, .mobile-login a, .add-img-but:hover, .btn-login, .my-gravatar-apply a, .user-profile .submit, .update-avatar {border:1px solid #" . $button_color . ";}.bet-btn {border:1px solid #" . $button_color . " !important;}.bet-btn:hover {color:#" . $button_color . " !important;border:1px solid #" . $button_color . " !important;}.cat-con-section{border-bottom:3px solid #" . $button_color . ";}.tab-product .tab-hd .current,.tab-area .current,.tab-title .selected{border-top:2px solid #" . $button_color . " !important;}.tabs-more a:hover,.ias-next .be,.all-cat a:hover, #all-series h4, .serial-number:before,.seat:before, .btn-login {color:#" . $button_color . ";}.upfile inputk, .btn-login:hover{background:#" . $button_color . " !important;}.resp-vtabs .resp-tab-active:before{border-left:3px solid #" . $button_color . " !important;}.down a,.meta-nav:hover,#gallery .callbacks_here a,.orderby li a:hover,#respond #submit:hover,.login-respond,.widget_categories a:hover,.widget_links a:hover,#sidebar .widget_nav_menu a:hover,#sidebar-l .widget_nav_menu a:hover,#cms-widget-one .widget_nav_menu li a:hover,.tab-nav li a:hover,.pagination span.current,.pagination a:hover,.page-links > span,.page-links a:hover span,.group-tab-more a:hover,.tab-pagination a:hover,.page-button:hover, .deanmove:hover .de-button a, #get_verify_code_btn:hover, .sidebox .userinfo a:hover, #user-profile .userinfo a:hover, .mobile-login-l a:hover, .mobile-login:hover, .mobile-login-reg a:hover, .menu-login-box .nav-login:hover, .menu-login-box #user-profile a:hover, #user-profile .userinfo a.user-logout:hover {background:#" . $button_color . ";border:1px solid #" . $button_color . ";}.pretty.success input:checked + label i:before {background:#" . $button_color . " !important;}.pretty.success input:checked + label i:after{border:#" . $button_color . ";background:#" . $button_color . " !important;}.fo:hover {background:#" . $button_color . ";border:1px solid #" . $button_color . ";}.entry-more a,.down-doc a,#series-letter li,.login-tab-product input[type='submit'],#wp-calendar a,.author-m a,.group-phone a,.group-more a,.new-more a:hover, .toc-zd, .slide-progress {background:#" . $button_color . ";}.link-f a:hover {border:1px solid #" . $all_color . ";}";
	}
	if (zm_get_option("cat_color")) {
		$cat_color = substr(zm_get_option("cat_color"), 1);
		$styles .= ".thumbnail .cat, .full-cat, .format-img-cat, .title-l, .cms-news-grid .marked-ico, .cms-news-grid-container .marked-ico, .special-mark, .gw-ico i, .cms-picture-cat-title {background: #" . $cat_color . ";} .new-icon .be, .gw-main-b .gw-ico i {color: #" . $cat_color . ";}";
	}
	if (zm_get_option("slider_color")) {
		$slider_color = substr(zm_get_option("slider_color"), 1);
		$styles .= ".slider-home .slider-home-title, .owl-dots .owl-dot.active span, .owl-dots .owl-dot:hover span,.owl-carousel .owl-nav button.owl-next,.owl-carousel .owl-nav button.owl-prev {background: #" . $slider_color . "}.owl-dots .owl-dot:hover span:before, .owl-dots .owl-dot.active span:before{border: 1px solid #" . $slider_color . ";}";
	}
	if (zm_get_option("h_color")) {
		$h_color = substr(zm_get_option("h_color"), 1);
		$styles .= ".single-post .entry-header h1, .single-content h3, .single-content h4, .single-content h6, .single-content h2 {border-left: 5px solid #" . $h_color . ";}";
	}
	if (zm_get_option("s_color")) {
		$s_color = substr(zm_get_option("s_color"), 1);
		$styles .= ".ajax-button, .searchbar button, .widget_search .searchbar button {background: #" . $s_color . ";border: 1px solid #" . $s_color . ";}.wp-ajax-search-not {background: #" . $s_color . ";}.searchbar .ajax-search-input input, .search-input input, .widget_search .search-input input, .ajax-search-box {border: 1px solid #" . $s_color . " !important;}.ajax-search-input:after {color: #" . $s_color . "}#search-main .search-cat {border-left: 1px solid #" . $s_color . ";}";
	}

	if (zm_get_option("z_color")) {
		$z_color = substr(zm_get_option("z_color"), 1);
		$styles .= ".like-left .sharing-box .zmy-btn-beshare:hover, .sharing-box .zmy-btn-beshare:hover {background: #" . $z_color . ";border: 1px solid #" . $z_color . ";}";
	}

	// 宽度
	if (zm_get_option("custom_width")) {
		$width = substr(zm_get_option("custom_width"), 0);
		$styles .= "#content, .search-wrap, .header-sub, .nav-top, #top-menu, #navigation-top, #mobile-nav, #main-search, .bread, .footer-widget, .links-box, .g-col, .links-group #links, .logo-box, #menu-container-o {width: " . $width . "px;}@media screen and (max-width: " . $width . "px) {#content, .bread, .footer-widget, .links-box, #top-menu, #navigation-top, .nav-top, #main-search, #search-main, #mobile-nav, .header-sub, .bread, .g-col, .links-group #links, .logo-box, .search-wrap {width: 98%;} #menu-container-o {width: 100%;}}";
	}
	if (zm_get_option("adapt_width")) {
		$width = substr(zm_get_option("adapt_width"), 0);
		$styles .= "#content, .search-wrap, .header-sub, .nav-top, #top-menu, #navigation-top, #mobile-nav, #main-search, .bread, .footer-widget, .links-box, .g-col, .links-group #links, .logo-box, #menu-container-o {width: " . $width . "%;}@media screen and (max-width: 1025px) {#content, .bread, .footer-widget, .links-box, #top-menu, #navigation-top, .nav-top, #main-search, #search-main, #mobile-nav, .header-sub, .bread, .g-col, .links-group #links {width: 98%;}}";
	}
	// 缩略图
	if (zm_get_option("thumbnail_width")) {
		$thumbnail = substr(zm_get_option("thumbnail_width"), 0);
		$styles .= ".thumbnail {max-width: " . $thumbnail . "px;}@media screen and (max-width: 620px) {.thumbnail {max-width: 100px;}}";
	}
	// 比例
	if (zm_get_option("img_bl")) {
		$img_bl = substr(zm_get_option("img_bl"), 0);
		$styles .= ".thumbs-b {padding-top: " . $img_bl . "%;}";
	}
	if (zm_get_option("img_k_bl")) {
		$img_k_bl = substr(zm_get_option("img_k_bl"), 0);
		$styles .= ".thumbs-f {padding-top: " . $img_k_bl . "%;}";
	}
	if (zm_get_option("grid_bl")) {
		$grid_bl = substr(zm_get_option("grid_bl"), 0);
		$styles .= ".thumbs-h {padding-top: " . $grid_bl . "%;}";
	}
	if (zm_get_option("img_v_bl")) {
		$img_v_bl = substr(zm_get_option("img_v_bl"), 0);
		$styles .= ".thumbs-v {padding-top: " . $img_v_bl . "%;}";
	}
	if (zm_get_option("img_t_bl")) {
		$img_t_bl = substr(zm_get_option("img_t_bl"), 0);
		$styles .= ".thumbs-t {padding-top: " . $img_t_bl . "%;}";
	}
	if (zm_get_option("img_s_bl")) {
		$img_s_bl = substr(zm_get_option("img_s_bl"), 0);
		$styles .= ".thumbs-sw {padding-top: " . $img_s_bl . "%;}";
	}
	if (zm_get_option("img_l_bl")) {
		$img_l_bl = substr(zm_get_option("img_l_bl"), 0);
		$styles .= ".thumbs-sg {padding-top: " . $img_l_bl . "%;}";
	}
	if (zm_get_option("img_full_bl")) {
		$img_full_bl = substr(zm_get_option("img_full_bl"), 0);
		$styles .= ".thumbs-w {padding-top: " . $img_full_bl . "%;}";
	}
	if (zm_get_option("sites_bl")) {
		$sites_bl = substr(zm_get_option("sites_bl"), 0);
		$styles .= ".thumbs-s {padding-top: " . $sites_bl . "%;}";
	}

	if (zm_get_option("meta_left")) {
		$meta = substr(zm_get_option("meta_left"), 0);
		$styles .= ".entry-meta {left: " . $meta . "px;}@media screen and (max-width: 620px) {.entry-meta {left: 130px;}}";
	}
	if (zm_get_option("custom_css")) {
		$css = substr(zm_get_option("custom_css"), 0);
		$styles .= $css;
	}
	if (zm_get_option("print_no")) {
		$styles .= "@media print{body{display:none}}";
	}
	if (zm_get_option("nav_more")) {
		$nav = substr(zm_get_option("nav_n"), 0);
		$styles .= "@media screen and (min-width: 1025px) {.nav-menu li.menu-item:nth-child(n+" . $nav . "){display: none;}}";
	}
	if (zm_get_option("top_nav_more")) {
		$nav = substr(zm_get_option("top_nav_n"), 0);
		$styles .= "@media screen and (min-width: 1025px) {.top-menu li.menu-item:nth-child(n+" . $nav . "){display: none;}}";
	}

	if (zm_get_option("slide_progress")) {
		$time = substr(zm_get_option("owl_time"), 0);
		$styles .= ".planned {transition: width " . $time . "ms;}";
	}

	if (zm_get_option("big_back_img_m_h")) {
		$m_h = substr(zm_get_option("big_back_img_m_h"), 0);
		$styles .= "@media screen and (max-width: 670px) {.group-lazy-img, .big-back-img {height: " . $m_h . "px !important;}}";
	}

	if ($styles) {
		echo '<style type="text/css">' . $styles . '</style>';
	}
}

// 后台添加文章ID
function ssid_column($cols) {
	$cols['ssid'] = 'ID';
	return $cols;
}

function ssid_value($column_name, $id) {
	if ($column_name == 'ssid')
		echo $id;
}

function ssid_return_value($value, $column_name, $id) {
	if ($column_name == 'ssid')
		$value = $id;
	return $value;
}

function ssid_css() {
?>
<style type="text/css">
#ssid { width: 50px;}
</style>
<?php 
}

// no-referrer
function admin_referrer(){
	echo'<meta name="referrer" content="no-referrer" />';
}
if (zm_get_option('no_referrer')) {
	add_action('admin_head', 'admin_referrer');
	add_action('login_head', 'admin_referrer');
}

function be_ssid_add() {
	add_action('admin_head', 'ssid_css');

	add_filter('manage_posts_columns', 'ssid_column');
	add_action('manage_posts_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_pages_columns', 'ssid_column');
	add_action('manage_pages_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_media_columns', 'ssid_column');
	add_action('manage_media_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_link-manager_columns', 'ssid_column');
	add_action('manage_link_custom_column', 'ssid_value', 10, 2);

	add_action('manage_edit-link-categories_columns', 'ssid_column');
	add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);

	foreach ( get_taxonomies() as $taxonomy ) {
		add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
		add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
	}

	add_action('manage_users_columns', 'ssid_column');
	add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);

	add_action('manage_edit-comments_columns', 'ssid_column');
	add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
}

function be_icon() {
	wp_enqueue_style( 'follow', get_template_directory_uri() . '/css/icons/icons.css', array(), version );
?>
<?php 
}
if (is_admin()) :
add_action('init', 'be_icon');
endif;
// 登录
function custom_login_head(){
if (zm_get_option('bing_login')) {
	$imgurl=get_stylesheet_directory_uri() . '/template/bing.php';
} else {
	$imgurl=zm_get_option('login_img');
}

if (zm_get_option('logos')) {$logourl=zm_get_option('logo');} else {$logourl=zm_get_option('logo_small_b');}
echo'<style type="text/css">body.login{background:url('.$imgurl.') no-repeat;background-position:center center;background-size:cover;background-attachment:fixed;overflow:hidden;width:100%;}body.login #login{position:relative;width:100%;height:100%;margin:0;background:#fff;background:rgba(255,255,255,0.9);-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;}#login #login_error{position:absolute;top:0;left:0;width:90% !important;padding:10px 0 10px 10%;background:rgba(255,236,234,0.9);}.login-action-login form{margin-top:50px;}.login-action-register form{margin-top:0;}@media only screen and (min-width:520px){body.login #login{width:85%;margin:0;max-width:520px;padding:30px 0 0;border-left:1px solid #e7e7e7;}}.login h1 a{background:url('.$logourl.') no-repeat center;background-size:auto 50px;font-size:50px;text-align:center;width:220px;height:50px;padding:5px;margin:0 auto;}.login h1 a:focus,#loginform .button-primary:focus{box-shadow:none !important;}#login{padding:5% 0 0;}.login form{background:transparent !important;box-shadow:none !important;border:none;padding:0 24px 46px;}.login #nav{margin:-10px 0 0 0;}.login #login_error,.login .message,.login .success{text-align:left !important;border-left:none;background:transparent;color:#72777c;box-shadow:none;width:85%;margin:1px auto;}#login > p{text-align:center;color:#72777c;}.login label{color:#72777c;font-weight:bold;}.wp-core-ui .button-primary{background:#3690cf;border:none;box-shadow:none;color:#fff;text-decoration:none;text-shadow:none;}.wp-core-ui .button.button-large{padding:6px 14px;line-height:normal;font-size:14px;height:auto;margin-bottom:4px;}input[type="checkbox"],input[type="radio"]{width:16px;height:16px;}.login form .input,.login input[type=text]{font-size:16px;line-height:30px;}input[type="checkbox"]:checked:before{font:normal 21px/1 dashicons;margin:-2px -4px;}#login form .input{box-shadow:none;border-radius:3px;border:1px solid #d1d1d1;}#login form .input{background:#fff;}.invitation-box{position:relative;}.to-code{position:absolute;top:5px;right:5px;}.to-code a{background:#3690cf;color:#fff;width:90px;height:30px;line-height:30px;text-align:center;display:block;border-radius:2px;text-decoration:none;}.to-code a:hover{opacity:0.8;}.clear{clear:both;display:block;}.to-code a{margin:0 0 15px;}.to-code i{display:none;}input.captcha-input{float:left;width:48% !important;}.label-captcha img{padding:2px;border:1px solid #e7e7e7;}#reg_passmail{clear:both;}.beginlogin-box{margin:0 0 25px !important;}.pass-input{position:relative;}.togglepass{position:absolute;top:11px;right:5px;width:40px;color:#999;cursor:pointer;text-align:center;}.login-icon{width:1.48em;height:1.48em;vertical-align:-0.25em;fill:currentColor;overflow:hidden;}.zml-ico{position:relative;}.zml-ico svg{position:absolute;top:13px;left:8px;color:#666;}.zml-ico input{padding:0 0 0 30px !important;}</style>';
}

function wp_login_head(){
echo'<style type="text/css">.login form .input,.login input[type=text]{font-size:16px;line-height:30px;}.invitation-box{position:relative;}.to-code{position:absolute;top:5px;right:5px;}.to-code a{background:#3690cf;color:#fff;width:90px;height:30px;line-height:30px;text-align:center;display:block;border-radius:2px;text-decoration:none;}.to-code a:hover{opacity:0.8;}.to-code a{margin:0 0 15px;}.to-code i{display:none;}input.captcha-input{float:left;width:48% !important;}.label-captcha img{padding:2px;border:1px solid #e7e7e7;}.beginlogin-box{margin:0 0 25px !important;}.pass-input{position:relative;}.togglepass{position:absolute;top:11px;right:5px;width:40px;color:#999;cursor:pointer;text-align:center;}.login-icon{width:1.48em;height:1.48em;vertical-align:-0.25em;fill:currentColor;overflow:hidden;}.zml-ico{position:relative;}.zml-ico svg{position:absolute;top:13px;left:8px;color:#666;}.zml-ico input{padding:0 0 0 30px !important;}</style>';
}
if (zm_get_option('custom_login')) {
	add_action('login_head', 'custom_login_head');
} else {
	add_action('login_head', 'wp_login_head');
}