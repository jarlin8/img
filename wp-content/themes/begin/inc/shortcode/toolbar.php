<?php
// 编辑器
// remove markup
function remove_shortcode_markup( $string ) {
	$patterns = array(
		'#^\s*</p>#',
		'#<p>\s*$#'
	);
	return preg_replace($patterns, '', $string);
}

// 过滤多余空格
if ( ! function_exists( 'filter_shortcode_text' ) ) {
	function filter_shortcode_text( $content ) {
		$array = array (
			'<p>&nbsp;</p>' => ''
		);
		$content = strtr( $content, $array );
		return $content;
	}
	// add_filter( 'the_content', 'filter_shortcode_text' );
}

// 时间轴
function time_line( $atts, $content = null ) {
	global $wpdb, $post;
	$can = get_post_meta(get_the_ID(), 'show_line', true);
	$clean = remove_shortcode_markup($content);
	return '<div class="timeline '.$can.'">'.do_shortcode( $clean ).'</div>';
}

// 视频
function my_videos( $atts, $content = null ) {
	extract( shortcode_atts( array (
		'src' => '""'
	), $atts ) );
	return '<div class="video-content"><video src="'.$src.'" controls="controls" width="100%"></video></div>';
}

// 评论查看
function reply_read($atts, $content=null) {
	extract( shortcode_atts( array(
		'title'   => '',
		'explain' => '',
	),
	$atts ) );

	$html = '<div class="read-point-box">';
	$html .= '<div class="read-point-content">';

	if ( ! empty( $atts['title'] ) ) {
		$html .= '<div class="read-point-title"><i class="be be-edit"></i>' . $title . '</div>';
	} else {
		$html .= '<div class="read-point-title"><i class="be be-edit"></i>' . zm_get_option('reply_read_t') . '</div>';
	}

	if ( ! empty( $atts['explain'] ) ) {
		$html .= '<div class="read-point-explain">' . $explain . '</div>';
	} else {
		$html .= '<div class="read-point-explain">' . zm_get_option('reply_read_c') . '</div>';
	}

	$html .= '</div>';
	$html .= '<div class="read-btn read-btn-reply"><a href="#respond" class="flatbtn"><i class="read-btn-ico"></i>' . sprintf(__( '发表评论', 'begin' )) . '</a></div>';
	$html .= '</div>';

	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {
			return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
		}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $html;
	}
	if (empty($email)) {
		return $html;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode('<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>');
	} else {
		return $html;
	}
}

// 登录查看
function login_to_read( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title'   => '',
		'explain' => '',
	),
	$atts ) );

	$html = '<div class="read-point-box">';
	$html .= '<div class="read-point-content">';

	if ( ! empty( $atts['title'] ) ) {
		$html .= '<div class="read-point-title"><i class="be be-timerauto"></i>' . $title . '</div>';
	} else {
		$html .= '<div class="read-point-title"><i class="be be-timerauto"></i>' . zm_get_option('login_read_t') . '</div>';
	}

	if ( ! empty( $atts['explain'] ) ) {
		$html .= '<div class="read-point-explain">' . $explain . '</div>';
	} else {
		$html .= '<div class="read-point-explain">' . zm_get_option('login_read_c') . '</div>';
	}

	$html .= '</div>';
	if ( zm_get_option( 'user_l' ) ) {
		$html .= '<a href="' . zm_get_option( 'user_l' ) . '" rel="external nofollow" target="_blank"><div class="read-btn read-btn-login"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></a>';
	} else {
		$html .= '<div class="read-btn read-btn-login"><div class="flatbtn show-layer cur"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></div>';
	}
	$html .= '</div>';

	if ( is_user_logged_in() ) {
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">' . do_shortcode( $content ) . '</div><div class="secret-b"></div>';
	} else {
		return $html;
	}
}

// 会员查看
function be_user_role_visible( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title'   => '',
		'explain' => '',
		'tip'   => '',
		'role' => '',
	),
	$atts ) );
	$html = '<div class="read-point-box">';
	$html .= '<div class="read-point-content">';

	if ( ! empty( $atts['title'] ) ) {
		$html .= '<div class="read-point-title"><i class="be be-personoutline"></i>' . $title . '</div>';
	} else {
		$html .= '<div class="read-point-title"><i class="be be-personoutline"></i>' . zm_get_option( 'role_visible_t' ) . '</div>';
	}

	if ( ! empty( $atts['explain'] ) ) {
		$html .= '<div class="read-point-explain">' . $explain . '</div>';
	} else {
		$html .= '<div class="read-point-explain">' . zm_get_option('role_visible_c') . '</div>';
	}

	$html .= '</div>';
	if ( zm_get_option( 'user_l' ) ) {
		$html .= '<a href="' . zm_get_option( 'user_l' ) . '" rel="external nofollow" target="_blank"><div class="read-btn read-btn-login"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></a>';
	} else {
		$html .= '<div class="read-btn read-btn-login"><div class="flatbtn show-layer cur"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></div>';
	}
	$html .= '</div>';


	$output = '<div class="hide-content">';
	$output .= '<div class="hide-ts hide-tl">';
	if ( ! empty( $atts['role'] ) ) {
		$output .= '<div class="hide-point"><i class="be be-personoutline"></i>' . $role . '</div>';
	} else {
		$output .= '<div class="hide-point"><i class="be be-personoutline"></i>' . zm_get_option( 'login_read_t' ) . '</div>';
	}

	if ( ! empty( $atts['tip'] ) ) {
		$output .= '<div class="hide-sm">' . $tip . '</div>';
	} else {
		$output .= '<div class="hide-sm">' . zm_get_option( 'role_visible_w' ) . '</div>';
	}

	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	$output .= '</div>';


	global $current_user;
	if ( in_array( zm_get_option( 'user_roles' ), $current_user->roles ) || in_array( 'administrator', $current_user->roles ) ) {
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		if ( ! is_user_logged_in() ) {
			return $html;
			} else {
			return $output;
		}
	}
}

// 密码保护
function secret( $atts, $content=null ){
extract( shortcode_atts( array( 'key'=>null ), $atts ) );
if ( current_user_can( 'level_10' ) ) {
	return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">' . do_shortcode( $content ) . '</div><div class="secret-b"></div>';
}

if ( isset($_POST['secret_key'] ) && $_POST['secret_key']==$key ){
	return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">' . do_shortcode( $content ) . '</div><div class="secret-b"></div>';
	} else {
		return '
		<form class="post-password-form" action="' . get_permalink() . '" method="post">
			<div class="post-secret"><i class="be be-edit"></i>' . sprintf(__( '输入密码查看隐藏内容', 'begin' )) . '</div>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
			</p>
		</form>';
	}
}

// 密码查看
function password_view( $atts, $content=null ){
extract( shortcode_atts( array( 'key'=>null ), $atts ) );

if ( isset($_POST['secret_key'] ) && $_POST['secret_key']==$key ){
	return '<div class="shide-content">' . do_shortcode( $content ) . '</div>';
	} else {
		return '
		<form class="password-view post-password-form" action="' . get_permalink() . '" method="post">
			<div class="post-secret-tip">' . sprintf(__( '输入密码', 'begin' )) . '</div>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
			</p>
		</form>';
	}
}

add_shortcode( 'bepassword', 'password_view');

// 关注公众号
function wechat_key($atts, $content=null) {
	extract(shortcode_atts( array (
		'key' => null,
		'reply' => null,
		), $atts));
	if ( current_user_can('level_10') ) {
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	}
	if (zm_get_option('wechat_unite')) {
		$keys = zm_get_option('weifans_pass');
		$replys = zm_get_option('weifans_key');
	} else {
		$keys = $key;
		$replys = $reply;
	}
	$cookie_name = 'wechat_key';
	$c = md5($cookie_name);
	$cookie_value = isset($_COOKIE[$cookie_name])?$_COOKIE[$cookie_name]:'';
	if ($cookie_value==$c || isset($_POST['wechat_key']) && $_POST['wechat_key']==$keys) {
		setcookie($cookie_name, $c ,time()+(int)30*86400, "/");
		$_COOKIE[$cookie_name] = $c;
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		return '
		<form class="post-password-form wechat-key-form" action="'.get_permalink().'" method="post">
			<div class="wechat-box wechat-left">
				<div class="post-secret"><i class="be be-edit"></i>' . sprintf(__( '输入验证码查看隐藏内容', 'begin' )) . '</div>
				<p>
					<input id="wpbox" type="password" size="20" name="wechat_key">
					<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
				</p>
				<div class="wechat-secret">
					<div class="wechat-follow">扫描二维码关注本站微信公众号 <span class="wechat-w">'.zm_get_option('wechat_fans').'</span></div>
					<div class="wechat-follow">或者在微信里搜索 <span class="wechat-w">'.zm_get_option('wechat_fans').'</span></div>
					<div class="wechat-follow">回复 <span class="wechat-w">' . $replys . '</span> 获取验证码</div>
				</div>
			</div>
			<div class="wechat-box wechat-right">
				<img src="'.zm_get_option('wechat_qr').'" alt="wechat">
				<span class="wechat-t">'.zm_get_option('wechat_fans').'</span>
			</div>
			<div class="clear"></div>
		</form>';
	}
}

// Download reply view
function pan_password($atts, $content=null) {
	if (zm_get_option('login_down_key')) {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>网盘密码</strong><span class="reply-prompt">登录可见</span>', 'begin' )) . '</div>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>网盘密码</strong><span class="reply-prompt">发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
	}
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return do_shortcode( $content );}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if (zm_get_option('login_down_key')) {
		if (is_user_logged_in()) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	} else {
		if ($wpdb->get_results($query)) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	}
}

function rar_password($atts, $content=null) {
	if (zm_get_option('login_down_key')) {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>解压密码</strong><span class="reply-prompt">登录可见</span>', 'begin' )) . '</div>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>解压密码</strong><span class="reply-prompt">发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
	}
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return do_shortcode( $content );}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if (zm_get_option('login_down_key')) {
		if (is_user_logged_in()) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	} else {
		if ($wpdb->get_results($query)) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	}
}

function down_password($atts, $content=null) {
	if (zm_get_option('login_down_key')) {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>下载地址</strong><span class="reply-prompt">登录可见</div>', 'begin' )) . '</span>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>下载地址</strong><span class="reply-prompt">发表评论并刷新可见</div>', 'begin' )) . '</span>'), $atts));
	}
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return do_shortcode( $content );}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if (zm_get_option('login_down_key')) {
		if (is_user_logged_in()) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	} else {
		if ($wpdb->get_results($query)) {
			return do_shortcode( do_shortcode( $content ) );
		} else {
			return $notice;
		}
	}
}

// 幻灯
function gallery( $atts, $content=null ){
	$pure_content = str_replace( array( "<br />", "</p>", "\n", "\r" ), "", $content );
	return '<div id="slider-single" class="owl-carousel slider-single be-wol">' . do_shortcode( do_shortcode( remove_shortcode_markup( $pure_content ) ) ) . '</div>';
}

// 下载按钮
function button_a( $atts, $content = null ) {
	return '<div class="down"><a class="d-popup" title="下载链接"><i class="be be-download"></i>下载地址</a><div class="clear"></div></div>';
}

// 弹窗按钮
function button_b( $atts, $content = null ) {
	return '<div class="down"><a class="d-popup" href="#"><div class="btnico"><i class="be be-clouddownload"></i></div><div class="btntxt">' . do_shortcode( do_shortcode( $content ) ) . '</div></a><div class="clear"></div></div>';
}

// 下载按钮
function button_url( $atts, $content=null ){
	global $wpdb, $post;
	extract( shortcode_atts( array( "href"=>'http://' ), $atts ) );
	return '<div class="down down-link"><a href="' . $href . '" rel="external nofollow" target="_blank"><div class="btnico"><i class="be be-download"></i></div><div class="btntxt">' . do_shortcode( do_shortcode( $content ) ) . '</div></a></div><div class="clear"></div>';
}

// 链接按钮
function button_link($atts,$content=null){
	global $wpdb, $post;
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank"><div class="btnico"><i class="be be-edit"></i></div><div class="btntxt">' . do_shortcode( do_shortcode( $content ) ) . '</div></a></div><div class="clear"></div>';
}

// but
function button_c ($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link down-link-but"><a href="'.$href.'" rel="external nofollow" target="_blank"><div class="btnico"><i class="be be-download"></i></div><div class="btntxt">' . do_shortcode( do_shortcode( $content ) ) . '</div></a></div><div class="clear"></div>';
}

// iframe标签
function fancy_iframe ($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link down-link-but"><a class="fancy-iframe" data-type="iframe" data-src="' . $href . '" href="javascript:;" rel="external nofollow" target="_blank"><div class="btnico"><i class="be be-star"></i></div><div class="btntxt">' . do_shortcode( do_shortcode( $content ) ) . '</div></a></div><div class="clear"></div>';
}

// fieldset标签
function fieldset_label($atts, $content = null) {
	return do_shortcode( $content );
}

// Wide picture
function add_full_img($atts, $content=null, $full_img="") {
	$return = '<div class="full-img">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 中英文混排
function cn_en($atts, $content=null) {
	$return = '<p class="cnen">';
	$return .= do_shortcode( $content );
	$return .= '</p>';
	return $return;
}

// 隐藏图片
function add_hide_img($atts, $content=null, $hide_img="") {
	$return = '<div class="hide-img">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 字体图标
function be_font_shortcode( $atts ) {
	extract( shortcode_atts( array( 'icon' => 'home', 'size' => '', 'color' => '', 'sup' => '' ), $atts ) );
	if ( $size ) { $size = ' font-size: '. $size .'px !important'; }
	else{ $size = ''; }
	if ( $color ) { $color = ' style="padding: 1px 2px;color: #'. $color . ';'; }
	else{ $color = ''; }

	if ( strtolower($sup) === '1' ) {
		return '<sup><i class="zm zm-'.str_replace('zm-','',$icon) .'"' . $color . $size . '"></i></sup>';
	} else{
		return '<i class="zm zm-'.str_replace('zm-','',$icon) .'"' . $color . $size . '"></i>';
	}
}

// 两栏
function add_two_column($atts, $content=null, $two_column="") {
	$return = '<div class="two-column">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 无缩进居中
function add_center_align($atts, $content=null, $two_column="") {
	extract( shortcode_atts( array(
		'center' => '',
	),
	$atts ) );
	if ( strtolower( $center ) === '1' ) {
		$center = ' align-center';
	} else {
		$center = '';
	}
	$return = '<div class="center-align' . $center . '">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

add_shortcode( 'align' , 'add_center_align' );

// bec
function add_bec($atts, $content=null, $bec="") {
	$return = '<div class="bec"><span class="dashicons dashicons-admin-site"></span>';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	$return .= '<div class="clear"></div>';
	return $return;
}

// Same label
function tags_posts( $atts, $content = null ){
	extract( shortcode_atts( array(
		'ids'   => '',
		'title' => '',
		'n'     => ''
	),
	$atts ) );
	$content .=  '<div class="tags-posts"><h3>'.$title.'</h3><ul>';
	$recent = new WP_Query( array( 'posts_per_page' => $n, 'tag__in' => explode(',', $ids)) );
	while($recent->have_posts()) : $recent->the_post();
	$content .=  '<li><a target="_blank" href="' . get_permalink() . '"><i class="be be-arrowright"></i>' . get_the_title() . '</a></li>';
	endwhile;wp_reset_postdata();
	$content .=  '</ul></div>';
	return $content;
}

// 文字折叠
function show_more( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => '',
	),
	$atts ) );
	if ( strtolower( $title ) === '' ) {
		$title = '<span class="show-more-tip"><span class="tip-k fd">' . sprintf( __( '展开', 'begin' ) ) . '</span></span><span class="show-more-tip"><span class="tip-s fd">' . sprintf( __( '收缩', 'begin' ) ) . '</span></span>';
	} else {
		$title = $title;
	}
	$html = '<div class="show-more more-c sup' . cur() . '">';
	$html .= $title;
	$html .= '</div>';
	return $html;
}

function section_content( $atts, $content = null ) {
	$clean = remove_shortcode_markup( $content );
	return '<div class="section-content show-area">' . do_shortcode( $clean ) . '</p></div><p>';
}

// advertising
function post_ad(){
if ( wp_is_mobile() ) {
		return '<div class="post-tg"><div class="tg-m tg-site">'.stripslashes( zm_get_option('ad_s_z_m') ).'</div></div>';
	} else {
		return '<div class="post-tg"><div class="tg-pc tg-site">'.stripslashes( zm_get_option('ad_s_z') ).'</div></div>';
	}
}

// 直达按钮
function direct_btn(){
	global $post;
	if ( get_post_meta(get_the_ID(), 'direct', true) ) {
	$direct = get_post_meta(get_the_ID(), 'direct', true);
		if ( get_post_meta(get_the_ID(), 'direct_btn', true) ) {
			$direct_btn = get_post_meta(get_the_ID(), 'direct_btn', true);
			return '<div class="down-doc-box"><div class="down-doc down-doc-go"><a href="'.$direct.'" target="_blank" rel="external nofollow">'.$direct_btn.'</a><a href="'. $direct .'" rel="external nofollow" target="_blank"><i class="be be-skyatlas"></i></a></div></div><div class="clear"></div>';
		} else {
			return '<div class="down-doc-box"><div class="down-doc down-doc-go"><a href="'.$direct.'" target="_blank" rel="external nofollow">'.zm_get_option('direct_w').'</a><a href="'. $direct .'" rel="external nofollow" target="_blank"><i class="be be-skyatlas"></i></a></div></div><div class="clear"></div>';
		}
	}
}

// Fixed button
function down_doc_box( $content ) {
	global $post;
	$link_button = get_post_meta(get_the_ID(), 'down_doc', true);
	$doc_name = get_post_meta(get_the_ID(), 'doc_name', true);
	if ( get_post_meta(get_the_ID(), 'down_doc', true) ) { 
		if ( get_post_meta(get_the_ID(), 'doc_name', true) ) { 
			$down_doc_name = $doc_name;
		} else {
			$down_doc_name = sprintf(__( '下载地址', 'begin' ));
		}
		$content = $content . '<div class="down-doc-box"><div class="down-doc"><a href="'. $link_button .'" rel="external nofollow" target="_blank">' . $down_doc_name . '</a><a href="'. $link_button .'" rel="external nofollow" target="_blank"><i class="be be-download"></i></a></div></div><div class="clear"></div>';
	}
	return $content;
}

// prompt
function be_green($atts, $content=null){
	return '<div class="mark_a mark">'.do_shortcode( $content ).'</div>';
}

function be_red($atts, $content=null){
	return '<div class="mark_b mark">'.do_shortcode( $content ).'</div>';
}

function be_gray($atts, $content=null){
	return '<div class="mark_c mark">'.do_shortcode( $content ).'</div>';
}

function be_yellow($atts, $content=null){
	return '<div class="mark_d mark">'.do_shortcode( $content ).'</div>';
}

function be_blue($atts, $content=null){
	return '<div class="mark_e mark">'.do_shortcode( $content ).'</div>';
}

// 短代码按钮
function begin_add_mce_button() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'begin_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'begin_register_mce_button' );
	}
}

function begin_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['begin_mce_button'] = get_bloginfo( 'template_url' ) . '/inc/tinymce/mce-button.js';
	return $plugin_array;
}
function begin_register_mce_button( $buttons ) {
	array_push( $buttons, 'begin_mce_button' );
	return $buttons;
}

// 图文模块
function wplist_shortcode($atts, $content = '') {
	$atts['content'] = $content;
	$out = '<div class="wplist-item">';
	if (!empty($atts['link'])) {
		$out.= '<figure class="thumbnail"><div class="thumbs-b lazy"><a class="thumbs-back sc" rel="external nofollow" href="' . $atts['link'] . '" style="background-image: url(' . $atts['img'] . ');"></a></div></figure>';
		$out.= '<a href="' . $atts['link'] . '" target="_blank" isconvert="1" rel="nofollow" ><div class="wplist-title">' . $atts['title'] . '</div></a>';
	} else {
		$out.= '<figure class="thumbnail"><div class="thumbs-b lazy thumbs-back sc"><a class="thumbs-back sc" rel="external nofollow" href="" style="background-image: url(' . $atts['img'] . ');"></a></div></figure>';
		$out.= '<div class="wplist-title">' . $atts['title'] . '</div>';
	}
	$out.= '<div class="wplist-des">' . $atts['content'] . '</div>';
	$out.= '<div class="wplist-link-btn">';
	if (!empty($atts['price'])) {
		$out.= '<div class="wplist-oth"><div class="wplist-res wplist-price">' . $atts['price'] . '</div>';
		if (!empty($atts['oprice'])) {
			$out.= '<div class="wplist-res wplist-old-price"><del>' . $atts['oprice'] . '</del></div>';
		}
		$out.= '</div>';
	}
	if ( ! empty( $atts['link'] ) ) {
		$out.= '<a href="' . $atts['link'] . '" target="_blank" isconvert="1" rel="nofollow" ><div class="wplist-btn">' . $atts['btn'] . '</div></a><div class="clear"></div>';
	}
	$out.= '</div>';
	$out.= '<div class="clear"></div></div>';
	return $out;
}

// TAB
function start_tab_shortcode( $atts, $content = '' ) {
	return '<div class="tab-single-wrap">';
}

function tabs_shortcode( $atts, $content = '' ) {
	global $tab_count, $tab_nav, $tab_content;
	static $tab_count = 1; 
	if( ! isset( $tab_nav ) ) {
		$tab_nav = '<div class="tab-single-menu">';
	}

	$active_class = ( $tab_count == 1 ) ? ' active' : '';
	$tab_nav .= '<div class="tab-single-menu-item"><a href="#tab' . $tab_count . '" class="tab-single-btn' . $active_class . '">' . $atts['title'] . '</a></div>';
	$tab_content .= '<div id="tab' . $tab_count++ . '" class="tab-single-item' . $active_class . '">' . do_shortcode( $content ) . '</div>';
	return '';
}

function end_tab_shortcode( $atts, $content = '' ) {
	global $tab_nav, $tab_content;
	$out = '';
	$out .= $tab_nav . '</div><div class="clear"></div>';
	$out .= '<div class="tab-single-content">';
	$out .= $tab_content;
	$out .= '</div>';
	$tab_nav = '';
	$tab_content = '';
	$tab_nav = '<div class="tab-single-menu">';
	$tab_content = '';
	return $out . '</div>';
}

// iframe
function iframe_add_shortcode( $atts ) {
	extract( shortcode_atts(array(
		"src" => ''
	), $atts ) );

	return '<div class="iframe-class"><iframe src="' . $src . '" security="restricted" allowfullscreen></iframe></div>';
}

// serial number
function serial_number ( $atts ){
	extract( shortcode_atts(array(
		"text" => ''
	), $atts ) );
	return '<div class="serial-number"><div class="borde"></div><div class="borde"></div><span class="serial-txt">' . $text . '</span></div>';
}

// subhead number
function subhead_number ( $atts, $content = null ) { 
	return '<div class="subhead-area"><div class="subhead-number-bg"><div class="subhead-number"></div></div><span class="subhead-txt">' . do_shortcode( $content ) . '</span></div>';
}

// 嵌入文章
function quote_post( $atts, $content = null ){
	extract( shortcode_atts( array(
		'ids' => ''
	),
	$atts ) );
	$html = '';
	$quote = new WP_Query( array( 'post_type' => 'any', 'post__in' => explode( ',', $ids ), 'post__not_in' => get_option( 'sticky_posts' ) ) );
	while( $quote->have_posts() ) : $quote->the_post();
		global $wpdb, $post;
		$content = $post->post_content;
		preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
		$n = count( $strResult[1] );
		$html .= '<div class="quote-post sup">';
		if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
			$html .= '<figure class="thumbnail">';
			$html .= zm_thumbnail();
			$html .= '<div class="quote-cat cat ease">';
			foreach( ( get_the_category() ) as $category ){
				$html .= '<a target="_blank" href="' . get_category_link( $category->cat_ID ) . '">' . $category->cat_name . '</a>';
			}
			$html .= '</div>';
			$html .= '</figure>';
		} else {
			if ( $n > 0 ) {
				$html .= '<figure class="thumbnail">';
				$html .= zm_thumbnail();
				$html .= '<div class="quote-cat cat ease">';

				$post_id = $ids;
				$post_type = get_post_type( $post_id );
				$custom_post_types = get_post_types( ['public' => true, '_builtin' => false] );
				if ( in_array( $post_type, $custom_post_types ) ) {
					$taxonomies = get_object_taxonomies( $post_type );

					foreach ( $taxonomies as $taxonomy ) {
						if ( is_taxonomy_hierarchical( $taxonomy ) ) { 
							$terms = get_the_terms( $post_id, $taxonomy );

							if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {
								$first_term = $terms[0];
								$term_link = get_term_link( $first_term, $taxonomy );

								if ( ! is_wp_error( $term_link ) ) {
									$html .= '<a href="' . $term_link . '" target="_blank">' . $first_term->name . '</a>';
								}
							}
						}
					}

				} else {
					$terms = get_the_terms( $post_id, 'category' );

					if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {

						$first_term = $terms[0];
						$term_link = get_term_link( $first_term );

						if ( ! is_wp_error( $term_link ) ) {
							$html .= '<a href="' . $term_link . '" target="_blank">' . $first_term->name . '</a>';
						}
					}
				}

				$html .= '</div>';
				$html .= '</figure>';
			}
		}
		$html .= '<div class="quote-title over"><a target="_blank" href="' . get_permalink() . '">' . get_the_title() . '</a></div>';
		$html .= '<div class="quote-post-content">';
		$content = strip_shortcodes( $content );
		$html .= wp_trim_words( $content, 62, '...' );
		$html .= '</div>';
		if ( ! $n > 0 && ! get_post_meta( get_the_ID(), 'thumbnail', true ) ) {
			$post_id = $ids;
			$post_type = get_post_type( $post_id );
			$custom_post_types = get_post_types( ['public' => true, '_builtin' => false] );
			if ( in_array( $post_type, $custom_post_types ) ) {
				$taxonomies = get_object_taxonomies( $post_type );

				foreach ( $taxonomies as $taxonomy ) {

					if ( is_taxonomy_hierarchical( $taxonomy ) ) { 

						$terms = get_the_terms( $post_id, $taxonomy );

						if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {
							$first_term = $terms[0];
							$term_link = get_term_link( $first_term, $taxonomy );

							if ( ! is_wp_error( $term_link ) ) {
								$html .= '<a href="' . $term_link . '" target="_blank">' . $first_term->name . '</a>';
							}
						}
					}
				}

			} else {
				$terms = get_the_terms( $post_id, 'category' );

				if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {

					$first_term = $terms[0];
					$term_link = get_term_link( $first_term );

					if ( ! is_wp_error( $term_link ) ) {
						$html .= '<a  class="quote-inf-cat" target="_blank" href="' . $term_link . '"><i class="be be-sort"></i> ' . $first_term->name . '</a>';
					}
				}
			}
		}
		$html .= '<div class="quote-inf fd">';
		$html .= '<span class="quote-views"><i class="be be-eye ri"></i>'. get_post_meta( get_the_ID(),'views',true ) . '</span>';
		if ( get_comments_number() > 0 ) {
			$html .= '<span class="quote-comments"><i class="be be-speechbubble ri"></i>' . get_comments_number() . '</span>';
		}
		$html .= '</div>';
		$html .= '<div class="quote-more fd"><a href="' . get_permalink() . '" target="_blank" rel="external nofollow"><i class="be be-sort"></i></a></div>';
		$html .= '<div class="clear"></div>';
		$html .= '</div>';
	endwhile;
	wp_reset_postdata();
	return $html;
}

// 随机文章
function random_post_shortcode() {
	ob_start();
	random_post();
	return ob_get_clean();
}

global $pagenow;
if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'page.php', 'page-new.php' ) ) ) {
	add_action( 'init', 'begin_add_mce_button' );
}

// 登录按钮
function login_reg( $atts, $content = null ) {
	extract( shortcode_atts( array('sup' => '' ), $atts ) );
	if ( !is_user_logged_in() ) {
		if ( strtolower( $sup ) === '1' ) {
			$hmtl = ' login-reg-btn';
		} else {
			$hmtl ='';
		}
		return '<span class="btn-login' . $hmtl . ' show-layer' . cur() . '">' . do_shortcode( do_shortcode( $content ) ) . '</span>';
	}
}

// docs
function be_docs_point( $atts, $content = null ) {
	return '<div class="docs-point-box fd"><div class="docs-point-main">' . do_shortcode( $content ) . '</div></div>';
}

// first drop
function be_first_drop( $atts, $content = null ) {
	return '<i class="be-first-drop"><p>' . do_shortcode( $content ) . '</p></i>';
}

// details标签
function be_details_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title' => '',
		'open'  => '',
	),
	$atts ) );
	if ( strtolower( $open ) === '1' ) {
		$open = ' open=""';
	} else {
		$open = '';
	}
	$html = '<div class="be-details">';
	$html .= '<details' . $open . '>';
	$html .= '<summary>';
	$html .= $title;
	$html .= '</summary>';
	$html .= do_shortcode( do_shortcode( $content ) );
	$html .= '</details>';
	$html .= '</div>';
	return $html;
}

add_shortcode( 'details', 'be_details_shortcode' );

// 计数器
function be_counter( $atts, $content = null ){
	extract( shortcode_atts( array(
		'id'    => '1',
		'title' => '网站访问量',
		'value' => '123456',
		'n'     => all_view(),
		'speed' => '40000',
		'ico'   => 'be be-eye'
	),
	$atts ) );

	if ( ! empty( $atts['ico'] ) ) {
		$c = 'be-counter-main-l';
	} else {
		$c = 'be-counter-main-c';
	}
	$html = '<div class="be-counter-main be_count_' . $id . '">';
	if ( ! empty( $atts['ico'] ) ) {
		$html .= '<div class="counters-icon">';
		$html .= '<i class="' . $ico . '"></i>';
		$html .= '</div>';
	}
	$html .= '<div class="counters-item">';
	$html .= '<div class="counters">';
	if ( empty( $atts['n'] ) ) {
		$html .= '<div class="counter" data-TargetNum="' . $n . '" data-Speed="' . $speed . '">0</div><span class="counter-unit">+</span>';
	} else {
		$html .= '<div class="counter" data-TargetNum="' . $value . '" data-Speed="' . $speed . '">0</div><span class="counter-unit">+</span>';
	}
	$html .= '</div>';
	$html .= '<div class="counter-title">' . $title . '</div>';
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}
add_shortcode( 'counter', 'be_counter' );

// 复制按钮
function btn_copytext( $atts = array(), $content=null ) {
	extract( shortcode_atts( array(
		'tip' => '',
	),
	$atts ) );

	if ( strtolower( $tip ) === '1' ) {
		$html = '';
	} else {
		$html = ' textbox-no-tip';
	}

	return '<span class="textbox'.$html.'"><span class="btn-copy">' . sprintf( __( '点击复制', 'begin' ) ) . '</span><span class="copied-text">' . do_shortcode( do_shortcode( $content ) ) . '</span></span>';
}

add_shortcode( 'copy', 'btn_copytext' );

// 图文
function be_text_img_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title' => '',
		'text'  => '',
		'img'   => '',
		'align' => 'left',
	),
	$atts ) );

	if ( isset( $atts['align'] ) && $atts['align'] === 'left' ) {
		$html = '<div class="mixed-item mixed-r">';
			$html .= '<div class="mixed-img-area mixed-box">';
				$html .= '<img alt="' . $title . '" class="mixed-img" src="' . $img . '">';
			$html .= '</div>';
		
			$html .= '<div class="mixed-area mixed-box">';
			if ( ! empty( $atts['title'] ) ) {
				$html .= '<div class="mixed-title">' . $title . '</div>';
			}
				$html .= '<div class="mixed-text">' . $text . '</div>';
			$html .= '</div>';

		$html .= '</div>';
	} else {
		$html = '<div class="mixed-item mixed-l">';
				$html .= '<div class="mixed-area mixed-box">';
				if ( ! empty( $atts['title'] ) ) {
					$html .= '<div class="mixed-title">' . $title . '</div>';
				}
					$html .= '<div class="mixed-text">' . $text . '</div>';
				$html .= '</div>';
				$html .= '<div class="mixed-img-area mixed-box">';
					$html .= '<img alt="' . $title . '" class="mixed-img" src="' . $img . '">';
				$html .= '</div>';
		$html .= '</div>';
	}
	return $html;
}
add_shortcode( 'be_text_img', 'be_text_img_shortcode' );

/**
* 分栏盒子
* [colstart col="分栏数2/3/4"][colitem]这是第一个内容块[/colitem][colitem]这是第二个内容块[/colitem][colend]
*/

function be_start_col_shortcode( $atts,$content = null ) {
	extract( shortcode_atts( array(
		'col' => '2',
	), $atts ) );

	global $colvalue;
	$colvalue = $col;

	$html = '<div class="col-short-main">';
	$html .= do_shortcode( $content );
	return $html;
}

function be_item_shortcode( $atts,$content = null ) {
	global $colvalue;

	extract( shortcode_atts( array(
		'col' => $colvalue,
	), $atts ) );

	$html = '<div class="col-short-item col-short-' . $col . '">';
	$html .= do_shortcode( $content );
	$html .= '</div>';

	return $html;
}

function be_end_col_shortcode() {
	$html = '</div>';
	return $html;
}

add_shortcode( 'colstart', 'be_start_col_shortcode' );
add_shortcode( 'colitem', 'be_item_shortcode' );
add_shortcode( 'colend', 'be_end_col_shortcode' );

// 禁止解析
function shortcode_demo( $atts, $content = null ) {
	return $content;
}
add_shortcode('ban', 'shortcode_demo');

// 图片滑块
function baslider_zb_sanitize_xss_offset( $input ) {
	$output = str_replace( '})});alert(/XSS-offset/)//', '',$input );
	return $output;
}

function baslider_shortcode_init( $atts ) {
	$atts = shortcode_atts(
		array(
			'img1' => '',
			'img2' => '',
			'offset' => '0.5',
			'direction' => 'horizontal',
			'width' => '',
			'align' => '',
			'before' => '',
			'after' => '',
			'hover' => 'false',
			'external' => 'false',
		), $atts, 'be-baslider'
	);

	static $i = 1;

	$beID = "be-baslider-" .$i;

	$isVertical = "";
	$data_vertical = "";
	$isCenter = "";
	$isLeft = "";
	$isRight = "";

	if ( esc_attr( $atts['align'] ) == "center" ) {
		$isCenter = " margin: 0 auto;";
		if ( empty( $atts['width'] ) ) {$atts['width'] = "width: 50%;"; }
	}

	if ( esc_attr( $atts['align'] ) == "right" ) {
		$isRight = " float: right; margin-left: 20px;";
		if ( empty( $atts['width'] ) ) {$atts['width'] = "width: 50%;"; }
	}

	if ( esc_attr( $atts['align'] ) == "left" ) {
		$isLeft = " float: left; margin-right: 20px;";
		if ( empty( $atts['width'] ) ) {$atts['width'] = "width: 50%;"; }
	}

	if ( is_numeric( $atts['width'] ) ) {
		if ( empty( $atts['width'] ) ) {
			$atts['width'] = "width: 100% !important; clear: both;";
		} else {
			$atts['width'] = "width: " .$atts['width'] . '%;';
		}
	} else {
		$atts['width'] = "width: 100% !important; clear: both;";
	}

	if ( $atts['direction'] == "vertical" ) {
		$isVertical = ' data-orientation=vertical';
		$data_vertical = ", orientation: 'vertical'";
	}
	if ( $atts['hover'] === "true" ) {
		$isHover = ',move_slider_on_hover: true';
		$yesHover = "ba-hover";
	} else {
		$isHover = '';
		$yesHover = '';
	}

	$script = "";
	if ( ! empty( $atts['img1'] ) && ! empty($atts['img2'] ) ) {
		$img1_alt = get_post_meta($atts['img1'], '_wp_attachment_image_alt', true );
		$img2_alt = get_post_meta($atts['img2'], '_wp_attachment_image_alt', true );

		$img1_alt_attr =$img1_alt ? ' alt="' . esc_attr( $img1_alt ) . '" title="' . esc_attr($img1_alt ) . '"' : '';
		$img2_alt_attr =$img2_alt ? ' alt="' . esc_attr( $img2_alt ) . '" title="' . esc_attr($img2_alt ) . '"' : '';

		$output = '<div id="' . esc_attr($beID ) . '" class="be-baslider" style="' . esc_attr( $atts['width'] .$isLeft . $isRight . $isCenter ) . '">';
		$output .= '<div class="baslider-container ' . esc_attr($beID . ' ' . $yesHover ) . '"' . esc_attr($isVertical ) . '>';
		if ( $atts['external'] === "true" ) {
			$output .= '<img src="' .$atts['img1'] . '" />';
			$output .= '<img src="' . $atts['img2'] . '" />';
		} else {
			$output .= '<img src="' . esc_url( wp_get_attachment_url($atts['img1'] ) ) . '" alt="' . esc_attr( $img1_alt ) . '" />';
			$output .= '<img src="' . esc_url( wp_get_attachment_url($atts['img2'] ) ) . '" alt="' . esc_attr( $img2_alt ) . '" />';
		}
		$output .= '</div></div>';
		$script .= '<script>jQuery( document ).ready(function($ ) {';


		if ( $atts['direction'] == "vertical" ) {
			$direc = "[data-orientation='vertical']";
			$script .= '$(".baslider-container.' . esc_js( $beID ) .$direc . '").baslider({default_offset_pct: ' . esc_js( $atts['offset'] .$isHover ) . $data_vertical . '});';
		} else {
			$direc = "[data-orientation!='vertical']";
			$script .= '$(".baslider-container.' . esc_js( $beID ) .$direc . '").baslider({default_offset_pct: ' . esc_js( $atts['offset'] .$isHover ) . '});';
		}

		if ( $atts['before'] ) {
			$script .= '$(".' . baslider_zb_sanitize_xss_offset( esc_js( $beID ) ) . ' .baslider-before-label").html("' . baslider_zb_sanitize_xss_offset( esc_js($atts['before'] ) ) . '");';
		} else {
			$script .= '$(".' . baslider_zb_sanitize_xss_offset( esc_js( $beID ) ) . ' .baslider-overlay").hide();';
		}
		if ( $atts['after'] ) {
			$script .= '$(".' . baslider_zb_sanitize_xss_offset( esc_js( $beID ) ) . ' .baslider-after-label").html("' . baslider_zb_sanitize_xss_offset( esc_js($atts['after'] ) ) . '");';
		} else {
			$script .= '$(".' . baslider_zb_sanitize_xss_offset( esc_js( $beID ) ) . ' .baslider-overlay").hide();';
		}
		$script .= '});</script>';
		
	} else {
		$output = '<div class="be-baslider" style="background: var(--be-bg-pink-fd);color: #685545;margin: 15px 0;padding: 15px;' . esc_attr( $atts['width'] .$isLeft . $isRight . $isCenter ) . '">必须选择两张图片，才能显示图片滑块！</div>';
	}

	$i++;
	add_action( 'wp_footer', function() use ( $script ) { echo$script; }, 20 );
	return $output;
}
add_shortcode( 'moveslider', 'baslider_shortcode_init' );