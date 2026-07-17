<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function be_styles(){
	$styles = '';
	$root = '';
	$glass = '';
	// 颜色
	if ( zm_get_option( "all_color" ) ) {
		$all_color = substr( zm_get_option( "all_color" ), 1 );
		$root .= "--be-hover: #" . $all_color . ";--be-bg-m: #" . $all_color . ";--be-m-bg: #" . $all_color . ";--be-bg-blue-r: #" . $all_color . ";--be-bg-btn: #" . $all_color . ";--be-shadow-h: #" . $all_color . ";--be-bg-blue: #" . $all_color . ";--be-bg-cat: #" . $all_color . ";--be-bg-hd: #" . $all_color . ";--be-bg-ht: #" . $all_color . ";--be-bg-htwo: #" . $all_color . ";--be-bg-btn-s: #" . $all_color . ";--be-blue-top: #" . $all_color . ";--be-bg-ico: #" . $all_color . ";";
	}

	if ( zm_get_option( "blogname_color" ) ) {
		$blogname_color = substr( zm_get_option( "blogname_color" ), 1 );
		$root .= "--be-site-n: #" . $blogname_color . ";";
	}

	if ( zm_get_option( "blogdescription_color" ) ) {
		$blogdescription_color = substr( zm_get_option("blogdescription_color" ), 1 );
		$root .= "--be-site-d: #" . $blogdescription_color . ";";
	}

	if ( zm_get_option( "link_color" ) ) {
		$link_color = substr( zm_get_option("link_color" ), 1 );
		$root .= "--be-hover: #" . $link_color . ";--be-blue-top: #" . $link_color . ";";
	}

	if ( zm_get_option( "menu_color" ) ) {
		$menu_color = substr( zm_get_option( "menu_color" ), 1 );
		$root .= "--be-bg-m: #" . $menu_color . ";--be-bg-m-text: #" . $menu_color . ";--be-m-bg: #" . $menu_color . ";--be-bg-blue-r: #" . $menu_color . ";";
	}

	if ( zm_get_option( "menu_bg_color" ) ) {
		$menu_bg_color = substr( zm_get_option( "menu_bg_color" ), 1 );
		$root .= "--be-bg-nav-white: #" . $menu_bg_color . ";";
	}

	if ( zm_get_option( "menu_text_color" ) ) {
		$menu_text_color = substr( zm_get_option( "menu_text_color" ), 1 );
		$root .= "--be-m-arrow: #" . $menu_text_color . ";--be-m-a: #" . $menu_text_color . ";--be-admin-btn: #" . $menu_text_color . ";--be-search: #" . $menu_text_color . ";--be-bg-m-text: #" . $menu_text_color . ";";
	}

	if ( zm_get_option( "menu_bg_glass_color" ) ) {
		$menu_bg_glass_color = substr( zm_get_option( "menu_bg_glass_color" ), 1 );
		$root .= "--be-bg-glass-m: r" . $menu_bg_glass_color . ";";
	}

	if ( zm_get_option( "menu_bg_border_color" ) ) {
		$menu_bg_border_color = substr( zm_get_option( "menu_bg_border_color" ), 1 );
		$root .= "--be-border-nav-b: #" . $menu_bg_border_color . ";--be-m-bg: #" . $menu_bg_border_color . ";";
	}

	if ( zm_get_option( "menu_real_color" ) ) {
		$menu_real_color = substr( zm_get_option( "menu_real_color" ), 1 );
		$root .= "--be-real: r" . $menu_real_color . ";";
	}

	if ( zm_get_option( "top_menu_bg_color" ) ) {
		$top_menu_bg_color = substr( zm_get_option( "top_menu_bg_color" ), 1 );
		$root .= "--be-bg-grey-top: #" . $top_menu_bg_color . ";";
	}

	if ( zm_get_option( "top_menu_text_color" ) ) {
		$top_menu_text_color = substr( zm_get_option( "top_menu_text_color" ), 1 );
		$root .= "--be-grey-top: #" . $top_menu_text_color . ";--be-admin: #" . $top_menu_text_color . ";";
	}

	if ( zm_get_option( "menu_o_color" ) ) {
		$menu_o_color = substr( zm_get_option( "menu_o_color" ), 1 );
		$root .= "--be-bg-nav-o: #" . $menu_o_color . ";";
	}

	if ( zm_get_option( "menu_color_f" ) ) {
		$menu_color_f = substr( zm_get_option( "menu_color_f" ), 1 );
		$root .= "--be-bg-glass-o: r" . $menu_color_f . ";";
	}

	if ( zm_get_option( "shadow_color" ) ) {
		$shadow_color = substr( zm_get_option( "shadow_color" ), 1 );
		$root .= "--be-shadow: r" . $shadow_color . ";";
	}

	if ( zm_get_option( "footer_widget_color" ) ) {
		$footer_widget_color = substr( zm_get_option( "footer_widget_color" ), 1 );
		$root .= "--be-footer-widget: r" . $footer_widget_color . ";";
	}

	if ( zm_get_option( 'bg_glass' ) ) {
		if ( zm_get_option( "glass_bg" ) ) {
			$glass_bg = substr( zm_get_option( "glass_bg" ), 1 );
			$glass .= "--be-glass-bg: r" . $glass_bg . ";";
		}

		if ( zm_get_option( "glass_h" ) ) {
			$glass_h = substr( zm_get_option( "glass_h" ), 1 );
			$glass .= "--be-bg-grey-f8: r" . $glass_h . ";";
		}
	}

	if ( zm_get_option( "button_color" ) ) {
		$button_color = substr(zm_get_option( "button_color"), 1 );
		$root .= "--be-bg-btn: #" . $button_color . ";--be-shadow-h: #" . $button_color . ";--be-bg-blue: #" . $button_color . ";";
	}

	if ( zm_get_option( "cat_color" ) ) {
		$cat_color = substr( zm_get_option( "cat_color" ), 1 );
		$root .= "--be-bg-cat: #" . $cat_color . ";--be-bg-ico: #" . $cat_color . ";--be-bg-red: #" . $cat_color . ";";
	}

	if ( zm_get_option( "slider_color" ) ) {
		$slider_color = substr( zm_get_option( "slider_color" ), 1 );
		$root .= "--be-bg-hd: #" . $slider_color . ";";
	}

	if ( zm_get_option( "h_color" ) ) {
		$h_color = substr( zm_get_option( "h_color" ), 1 );
		$root .= "--be-bg-ht: #" . $h_color . ";--be-bg-htwo: #" . $h_color . ";";
	}

	if ( zm_get_option( "z_color" ) ) {
		$z_color = substr( zm_get_option( "z_color"), 1 );
		$root .= "--be-bg-btn-s: #" . $z_color . ";";
	}

	if ( zm_get_option( "bread_color" ) ) {
		$bread_color = substr( zm_get_option( "bread_color"), 1 );
		$root .= "--be-bread: #" . $bread_color . ";";
	}

	if ( zm_get_option( "epd_color" ) ) {
		$epd_color = substr( zm_get_option( "epd_color" ), 1 );
		$root .= "--be-border-epd: #" . $epd_color . ";--be-bg-epd: #" . $epd_color . ";--be-epd-t: #" . $epd_color . ";";
	}

	if ( co_get_option( "line_odd_color" ) ) {
		$line_odd_color = substr( co_get_option( "line_odd_color" ), 1 );
		$root .= "--line-odd-bg: #" . $line_odd_color . ";";
	}

	if ( co_get_option( "line_even_color" ) ) {
		$line_even_color = substr( co_get_option( "line_even_color" ), 1 );
		$root .= "--line-even-bg: #" . $line_even_color . ";";
	}

	// 菜单高度
	if ( zm_get_option( "nav_height" ) ) {
		$height = substr( zm_get_option( "nav_height" ), 0 );
		$heights = $height + 30;
		$root .= "--be-m-li: " . $height . "px;--be-h: " . $height . "px;--be-h-s: " . $heights . "px;";
	}

	// 宽度
	if ( zm_get_option( "nav_width" ) ) {
		$width = substr( zm_get_option( "nav_width" ), 0 );
		$root .= "--be-nav-width: " . $width . "px;";
	}

	if ( zm_get_option( "nav_width" ) ) {
		$width = substr( zm_get_option( "nav_width" ), 0 );
		$styles .= "@media screen and (max-width: " . $width . "px) {.nav-extend .nav-top, .nav-extend #navigation-top {width: 97.5%;}}";
	}

	if ( zm_get_option( 'body_width' ) == 'given' ) {
		if ( zm_get_option( "custom_width" ) ) {
			$width = substr( zm_get_option( "custom_width" ), 0 );
			$root .= "--be-main-width: " . $width . "px;";
		}

		if ( zm_get_option( "custom_width" ) ) {
			$width = substr( zm_get_option( "custom_width" ), 0 );
			$styles .= "@media screen and (max-width: " . $width . "px) {#content, .search-wrap, .header-sub, .nav-top, #navigation-top, .bread, .footer-widget, .links-box, .g-col, .links-group #links, .logo-box {width: 97.5%;} #menu-container-o {width: 100%;}}";
		}
	}

	if ( zm_get_option( 'body_width' ) == 'percent' ) {
		if ( zm_get_option( "adapt_width" ) ) {
			$width = substr( zm_get_option( "adapt_width" ), 0 );
			$root .= "--be-main-percent: " . $width . "%;";
		}

		if ( zm_get_option( "adapt_width" ) ) {
			$width = substr( zm_get_option( "adapt_width" ), 0 );
			$styles .= "@media screen and (max-width: 1025px) {#content, .search-wrap, .header-sub, .nav-top, #navigation-top, .bread, .footer-widget, .links-box, .g-col, .links-group #links, .logo-box {width: 97.5% !important;}}";
		}
	}

	// 缩略图
	if ( zm_get_option( "thumbnail_width" ) ) {
		$thumbnail = substr( zm_get_option( "thumbnail_width" ), 0 );
		$styles .= ".thumbnail {max-width: " . $thumbnail . "px;}@media screen and (max-width: 620px) {.thumbnail {max-width: 100px;}}";
	}

	if ( zm_get_option( "nav_font_size" ) ) {
		$nav_font_size = substr( zm_get_option( "nav_font_size" ), 0 );
		$styles .= ".main-nav .down-menu a  {font-size: " . $nav_font_size . "px;}";
	}

	// 比例
	if ( zm_get_option( "img_bl" ) ) {
		$img_bl = substr( zm_get_option("img_bl"), 0 );
		$styles .= ".thumbs-b {padding-top: " . $img_bl . "%;}";
	}
	if ( zm_get_option("img_k_bl")) {
		$img_k_bl = substr(zm_get_option("img_k_bl"), 0);
		$styles .= ".thumbs-f {padding-top: " . $img_k_bl . "%;}";
	}
	if ( zm_get_option("grid_bl")) {
		$grid_bl = substr(zm_get_option("grid_bl"), 0);
		$styles .= ".thumbs-h {padding-top: " . $grid_bl . "%;}";
	}
	if ( zm_get_option("img_v_bl")) {
		$img_v_bl = substr(zm_get_option("img_v_bl"), 0);
		$styles .= ".thumbs-v {padding-top: " . $img_v_bl . "%;}";
	}
	if ( zm_get_option("img_t_bl")) {
		$img_t_bl = substr(zm_get_option("img_t_bl"), 0);
		$styles .= ".thumbs-t {padding-top: " . $img_t_bl . "%;}";
	}
	if ( zm_get_option("img_s_bl")) {
		$img_s_bl = substr(zm_get_option("img_s_bl"), 0);
		$styles .= ".thumbs-sw {padding-top: " . $img_s_bl . "%;}";
	}
	if ( zm_get_option("img_l_bl")) {
		$img_l_bl = substr(zm_get_option("img_l_bl"), 0);
		$styles .= ".thumbs-sg {padding-top: " . $img_l_bl . "%;}";
	}
	if ( zm_get_option( "img_full_bl" ) ) {
		$img_full_bl = substr( zm_get_option( "img_full_bl" ), 0 );
		$styles .= ".thumbs-w {padding-top: " . $img_full_bl . "%;}";
	}
	if ( zm_get_option( "sites_bl" ) ) {
		$sites_bl = substr( zm_get_option( "sites_bl" ), 0 );
		$styles .= ".thumbs-s {padding-top: " . $sites_bl . "%;}";
	}

	if ( zm_get_option( "custom_css" ) ) {
		$css = substr(zm_get_option( "custom_css" ), 0 );
		$styles .= $css;
	}

	if ( zm_get_option( "slide_progress" ) ) {
		$time = substr( zm_get_option( "owl_time" ), 0 );
		$styles .= ".planned {transition: width " . $time . "ms;}";
	}

	if ( co_get_option( "big_back_img_m_h" ) ) {
		$m_h = substr( co_get_option( "big_back_img_m_h" ), 0 );
		$styles .= "@media screen and (max-width: 670px) {.group-lazy-img, .big-back-img {height: " . $m_h . "px !important;}}";
	}

	if ( $root ) {
		echo '<style type="text/css">:root {' . $root . '}</style>';
	}

	if ( $glass ) {
		echo '<style type="text/css">.glass {' . $glass . '}</style>';
	}
	
	if ( $styles ) {
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
	wp_enqueue_style( 'follow', get_template_directory_uri() . '/css/fonts/fonts.css', array(), version );
?>
<?php 
}
if ( is_admin() ) :
add_action( 'init', 'be_icon' );
endif;

// 背景透明
function bg_glass( $classes ) {
	$classes[] = 'glass';
	return $classes;
}

if ( zm_get_option( 'bg_glass' ) ) {
	add_filter( 'body_class','bg_glass' );
}

// 毛玻璃
function bg_blur( $classes ) {
	$classes[] = 'blur';
	return $classes;
}

if ( zm_get_option( 'bg_blur' ) &&  zm_get_option( 'bg_glass' ) ) {
	add_filter( 'body_class','bg_blur' );
}

// 登录
function custom_login_head() {
if ( zm_get_option( 'bing_login' ) ) {
	$imgurl = get_template_directory_uri() . '/template/bing.php';
} else {
	$imgurl = zm_get_option( 'login_img' );
}

if ( ! zm_get_option( 'site_sign' ) || ( zm_get_option( 'site_sign' ) == 'logo_small' ) ) {
	$logourl = zm_get_option('logo_small_b');
}

if ( zm_get_option('site_sign') == 'logos' ) {
	$logourl = zm_get_option( 'logo' );
}

if ( zm_get_option( 'site_sign' ) == 'no_logo' ) {
	$logourl = zm_get_option( 'logo_small_b' );
}

echo'<style type="text/css">body.login{background:url('.$imgurl.') no-repeat;background-position:center center;background-size:cover;background-attachment:fixed;overflow:hidden;width:100%;}body.login #login{position:relative;width:100%;height:100%;margin:0;padding:10% 0 0 0;background:#fff;background:rgba(255,255,255,0.9);backdrop-filter: saturate(150%) blur(8px);-webkit-backdrop-filter: saturate(150%) blur(8px);-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;}#login #login_error{position:absolute;top:0;left:0;width:90% !important;padding:10px 0 10px 10%;background:rgba(255,236,234,0.9);}.login-action-login form{margin-top:50px;}.login-action-register form{margin-top:0;}#lostpasswordform,#resetpassform,#registerform,#loginform{max-width:300px;margin:0 auto;}.message.register{text-align:center;}@media only screen and (min-width:768px){body.login #login{width:50%;margin:0;padding:3% 0 0;border-left:1px solid #e7e7e7;}}.login h1 a{background:url('.$logourl.') no-repeat center;background-size:auto 50px;font-size:50px;text-align:center;width:220px;height:50px;padding:5px;margin:0 auto 20px;}.login h1 a:focus,#loginform .button-primary:focus{box-shadow:none !important;}#login{padding:5% 0 0;}.login form{background:transparent !important;box-shadow:none !important;border:none;padding:0 24px 46px;}.login #nav{margin:-10px 0 0 0;}.login #login_error,.login .message,.login .success{text-align:center;border-left:none;background:transparent;color:#72777c;box-shadow:none;width:85%;margin:1px auto;}#login > p{text-align:center;color:#72777c;}.login label{color:#72777c;font-weight:bold;}.wp-core-ui .button-primary{background:#4d8cb8;border:none;box-shadow:none;color:#fff;text-decoration:none;text-shadow:none;}.wp-core-ui .button.button-large{padding:6px 14px;line-height:normal;font-size:14px;height:auto;margin-bottom:4px;}input[type="checkbox"],input[type="radio"]{width:16px;height:16px;}.login form .input,.login input[type=text]{font-size:14px;line-height:20px;}input[type="checkbox"]:checked:before{font:normal 21px/1 dashicons;margin:-2px -4px;}#login form .input{background:#fff;padding:0 10px;box-shadow:none;border-radius:5px;border:1px solid #d1d1d1;}.invitation-box{position:relative;}.to-code{position:absolute;top:5px;right:5px;}.to-code a{background:#4d8cb8;color:#fff;width:90px;height:30px;line-height:30px;text-align:center;display:block;border-radius:3px;text-decoration:none;}.to-code a:hover{opacity:0.8;}.clear{clear:both;display:block;}.to-code a{margin:0 0 15px;}.to-code i{display:none;}input.captcha-input{width:48% !important;}.label-captcha img{float:right;padding:2px;border-radius:5px;border:1px solid #e7e7e7;}#reg_passmail{clear:both;}.beginlogin-box{margin:0 0 25px !important;}.pass-input{position:relative;}.togglepass{position:absolute;top:11px;right:5px;width:40px;color:#999;cursor:pointer;text-align:center;}.language-switcher{display:none;}.be-email-code{color:#fa9f7e;font-weight:700;line-height:38px;display:inline-block;text-decoration:none;}.be-email-code:hover{color:#83a599;}.be-email-code:focus{box-shadow:none;}.be-email-code:before{content:"\f465";font-size:22px;font-family:dashicons;font-weight:400;vertical-align:-16%;margin:0 5px;}.be-email-code.disabled{color:#999;}.reg-captcha-message{position:relative;width:99%;}.captcha-result{position:absolute;top:8px;left:0;width:100%;color:#fa9f7e;text-align:center;z-index:2;padding:15px 0;}.captcha-result-success{color:#83a599;}</style>';
}

function wp_login_head(){
echo'<style type="text/css">.login form .input,.login input[type=text]{font-size:16px;line-height:30px;}.invitation-box{position:relative;}.to-code{position:absolute;top:5px;right:5px;}.to-code a{background:#4d8cb8;color:#fff;width:90px;height:30px;line-height:30px;text-align:center;display:block;border-radius:2px;text-decoration:none;}.to-code a:hover{opacity:0.8;}.to-code a{margin:0 0 15px;}.to-code i{display:none;}input.captcha-input{float:left;width:48% !important;}.label-captcha img{float: right;padding:2px;border-radius: 5px;border:1px solid #e7e7e7;}.beginlogin-box{margin:0 0 25px !important;}.pass-input{position:relative;}.togglepass{position:absolute;top:11px;right:5px;width:40px;color:#999;cursor:pointer;text-align:center;}</style>';
}
if ( zm_get_option('custom_login' ) ) {
	add_action( 'login_head', 'custom_login_head' );
} else {
	add_action( 'login_head', 'wp_login_head' );
}