<?php
// remove markup
function remove_shortcode_markup( $string ) {
	$patterns = array(
		'#^\s*</p>#',
		'#<p>\s*$#'
	);
	return preg_replace($patterns, '', $string);
}

// Timeline
function time_line( $atts, $content = null ) {
	global $wpdb, $post;
	$can = get_post_meta(get_the_ID(), 'show_line', true);
	$clean = remove_shortcode_markup($content);
	return '<div class="timeline '.$can.'">'.do_shortcode( $clean ).'</div>';
}

// video
function my_videos( $atts, $content = null ) {
	extract( shortcode_atts( array (
		'src' => '""'
	), $atts ) );
	return '<div class="video-content"><video src="'.$src.'" controls="controls" width="100%"></video></div>';
}

// Comments visible
function reply_read($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '
	<div class="read-point-box bk">
		<div class="read-point-content">
			<div class="read-point-title"><i class="be be-info"></i>' . zm_get_option('reply_read_t') . '</div>
			<div class="read-point-explain">' . zm_get_option('reply_read_c') . '</div>
		</div>
		<div class="read-btn read-btn-reply"><a href="#respond" class="flatbtn"><i class="read-btn-ico"></i>' . sprintf(__( '发表评论', 'begin' )) . '</a></div>
	</div>
	'), $atts));

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
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode('<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>');
	} else {
		return $notice;
	}
}

// Login visible
function login_to_read( $atts, $content = null ) {
	if ( zm_get_option( 'user_l' ) ) {
		$readbutton = '<a href="' . zm_get_option( 'user_l' ) . '" rel="external nofollow" target="_blank"><div class="read-btn read-btn-login"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></a>';
	} else {
		$readbutton = '<div class="read-btn read-btn-login"><div class="flatbtn show-layer cur" data-show-layer="login-layer" role="button"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></div>';
	}
	extract(shortcode_atts(array("notice" =>'
	<div class="read-point-box bk">
		<div class="read-point-content">
			<div class="read-point-title"><i class="be be-info"></i>' . zm_get_option( 'login_read_t' ) . '</div>
			<div class="read-point-explain">' . zm_get_option( 'login_read_c' ) . '</div>
		</div>
		' . $readbutton . '
	</div>
	'), $atts));

	if (is_user_logged_in()) {
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">' . do_shortcode( $content ) . '</div><div class="secret-b"></div>';
	} else {
		return $notice;
	}
}

// user_role_visible
function be_user_role_visible( $atts, $content = null ) {
	if ( zm_get_option( 'user_l' ) ) {
		$readbutton = '<a href="' . zm_get_option( 'user_l' ) . '" rel="external nofollow" target="_blank"><div class="read-btn read-btn-login"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></a>';
	} else {
		$readbutton = '<div class="read-btn read-btn-login"><div class="flatbtn show-layer cur" data-show-layer="login-layer" role="button"><i class="read-btn-ico"></i>' . sprintf( __( '登录', 'begin' ) ) . '</div></div>';
	}
	extract(shortcode_atts(array("notice" =>'
	<div class="read-point-box bk">
		<div class="read-point-content">
			<div class="read-point-title"><i class="be be-info"></i>' . zm_get_option( 'role_visible_t' ) . '</div>
			<div class="read-point-explain">' . zm_get_option('role_visible_c') . '</div>
		</div>
		' . $readbutton . '
	</div>',
	'limits' => '
	<div class="hide-content">
		<div class="hide-ts  bk hide-tl">
			<div class="hide-point"><i class="be be-info"></i>' . zm_get_option( 'login_read_t' ) . '</div>
			<div class="hide-sm">' . zm_get_option( 'role_visible_w' ) . '</div>
		</div>
		<div class="clear"></div>
	</div>',
	), $atts));

	global $current_user;
	if ( in_array( zm_get_option( 'user_roles' ), $current_user->roles ) || in_array( 'administrator', $current_user->roles ) ) {
		return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		if ( !is_user_logged_in() ) {
			return $notice;
			} else {
			return $limits;
		}
	}
}

add_shortcode('hide', 'be_user_role_visible');

// Encrypted content
function secret($atts, $content=null){
extract(shortcode_atts(array('key'=>null), $atts));
if ( current_user_can('level_10') ) {
	return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
}
if (isset($_POST['secret_key']) && $_POST['secret_key']==$key){
	return '<div class="hide-t"><i class="be be-loader"></i></div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		return '
		<form class="post-password-form bk" action="'.get_permalink().'" method="post">
			<div class="post-secret"><i class="be be-info"></i>' . sprintf(__( '输入密码查看隐藏内容', 'begin' )) . '</div>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
			</p>
		</form>';
	}
}

// Follow password
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
		<form class="post-password-form bk wechat-key-form" action="'.get_permalink().'" method="post">
			<div class="wechat-box wechat-left">
				<div class="post-secret"><i class="be be-info"></i>' . sprintf(__( '输入验证码查看隐藏内容', 'begin' )) . '</div>
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
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>网盘密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>登录可见</span>', 'begin' )) . '</div>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>网盘密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
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
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>解压密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>登录可见</span>', 'begin' )) . '</div>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>解压密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
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
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>下载地址：</strong><span class="reply-prompt"><i class="be be-warning"></i>登录可见</div>', 'begin' )) . '</span>'), $atts));
	} else {
		extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>下载地址：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</div>', 'begin' )) . '</span>'), $atts));
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

// Slideshow
function gallery($atts, $content=null){
	$pure_content = str_replace( "<br />", "", $content );
	$pure_content = str_replace("</p>", "", $content);
	return '<div id="slider-single" class="owl-carousel slider-single be-wol">' . do_shortcode( do_shortcode( $pure_content ) ) . '</div>';
}

// Download button
function button_a($atts, $content = null) {
	return '<div class="down"><a class="d-popup" title="下载链接"><i class="be be-download"></i>下载地址</a><div class="clear"></div></div>';
}

// Pop-up download
function button_b($atts, $content = null) {
	return '<div class="down"><a class="d-popup" href="#"><i class="be be-download"></i>'.do_shortcode( do_shortcode( $content ) ).'</a><div class="clear"></div></div>';
}

// Download button
function button_url($atts,$content=null){
	global $wpdb, $post;
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	if ( get_post_meta(get_the_ID(), 'down_link_much', true) ) {
		return '<div class="down down-link down-much"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'.$content.'</a></div><div class="down-return"></div>';
	} else {
		return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'.do_shortcode( do_shortcode( $content ) ).'</a></div><div class="clear"></div>';
	}
}

// Link button
function button_link($atts,$content=null){
	global $wpdb, $post;
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	if ( get_post_meta(get_the_ID(), 'down_link_much', true) ) {
		return '<div class="down down-link down-much"><a href="'.$href.'" rel="external nofollow" target="_blank">'.$content.'</a></div><div class="down-return"></div>';
	} else {
		return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank">'.do_shortcode( do_shortcode( $content ) ).'</a></div><div class="clear"></div>';
	}
}

// but
function button_c ($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link down-link-but"><a href="'.$href.'" rel="external nofollow" target="_blank">'.do_shortcode( do_shortcode( $content ) ).'</a></div><div class="clear"></div>';
}

// fancy iframe
function fancy_iframe ($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link down-link-but"><a class="fancy-iframe" data-type="iframe" data-src="' . $href . '" href="javascript:;" rel="external nofollow" target="_blank">'.do_shortcode( do_shortcode( $content ) ).'</a></div><div class="clear"></div>';
}

// fieldset
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

// Chinese and English
function cn_en($atts, $content=null) {
	$return = '<p class="cnen">';
	$return .= do_shortcode( $content );
	$return .= '</p>';
	return $return;
}

// Hidden picture
function add_hide_img($atts, $content=null, $hide_img="") {
	$return = '<div class="hide-img">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// Icon font
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

// Two columns
function add_two_column($atts, $content=null, $two_column="") {
	$return = '<div class="two-column">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

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
		'ids' => '',
		'title' => '',
		'n' => ''
	),
	$atts ) );
	$content .=  '<div class="tags-posts"><h3>'.$title.'</h3><ul>';
	$recent = new WP_Query( array( 'posts_per_page' => $n, 'tag__in' => explode(',', $ids)) );
	while($recent->have_posts()) : $recent->the_post();
	$content .=  '<li><a target="_blank" href="' . get_permalink() . '"><i class="be be-arrowright"></i>' . get_the_title() . '</a></li>';
	endwhile;wp_reset_query();
	$content .=  '</ul></div>';
	return $content;
}

// Text expansion
function show_more($atts, $content = null) {
	return '<div class="show-more more-c sup' . cur() . '"><span class="show-more-tip"><span class="tip-k fd">' . sprintf(__( '展开', 'begin' )) . '</span></span><span class="show-more-tip"><span class="tip-s fd">' . sprintf(__( '收缩', 'begin' )) . '</span></span></div>';
}

function section_content($atts, $content = null) {
	$clean = remove_shortcode_markup($content);
	return '<div class="section-content show-area">'.do_shortcode( $clean ).'</p></div><p>';
}

// advertising
function post_ad(){
if ( wp_is_mobile() ) {
		return '<div class="post-tg"><div class="tg-m tg-site">'.stripslashes( zm_get_option('ad_s_z_m') ).'</div></div>';
	} else {
		return '<div class="post-tg"><div class="tg-pc tg-site">'.stripslashes( zm_get_option('ad_s_z') ).'</div></div>';
	}
}

// Direct link
function direct_btn(){
	global $post;
	if ( get_post_meta(get_the_ID(), 'direct', true) ) {
	$direct = get_post_meta(get_the_ID(), 'direct', true);
		if ( get_post_meta(get_the_ID(), 'direct_btn', true) ) {
			$direct_btn = get_post_meta(get_the_ID(), 'direct_btn', true);
			return '<div class="down-doc-box"><div class="down-doc down-doc-go bk"><a href="'.$direct.'" target="_blank" rel="external nofollow">'.$direct_btn.'</a><a href="'. $direct .'" rel="external nofollow" target="_blank"><i class="be be-thumbs-up-o hz"></i></a></div></div><div class="clear"></div>';
		} else {
			return '<div class="down-doc-box"><div class="down-doc down-doc-go bk"><a href="'.$direct.'" target="_blank" rel="external nofollow">'.zm_get_option('direct_w').'</a><a href="'. $direct .'" rel="external nofollow" target="_blank"><i class="be be-thumbs-up-o hz"></i></a></div></div><div class="clear"></div>';
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
		$content = $content . '<div class="down-doc-box"><div class="down-doc bk"><a href="'. $link_button .'" rel="external nofollow" target="_blank">' . $down_doc_name . '</a><a href="'. $link_button .'" rel="external nofollow" target="_blank"><i class="be be-download hz"></i></a></div></div><div class="clear"></div>';
	}
	return $content;
}

// prompt
function be_green($atts, $content=null){
	return '<div class="mark_a mark dah">'.do_shortcode( $content ).'</div>';
}

function be_red($atts, $content=null){
	return '<div class="mark_b mark dah">'.do_shortcode( $content ).'</div>';
}

function be_gray($atts, $content=null){
	return '<div class="mark_c mark dah">'.do_shortcode( $content ).'</div>';
}

function be_yellow($atts, $content=null){
	return '<div class="mark_d mark dah">'.do_shortcode( $content ).'</div>';
}

function be_blue($atts, $content=null){
	return '<div class="mark_e mark dah">'.do_shortcode( $content ).'</div>';
}

// Button
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
	$plugin_array['begin_mce_button'] = get_bloginfo( 'template_url' ) . '/js/mce-button.js';
	return $plugin_array;
}
function begin_register_mce_button( $buttons ) {
	array_push( $buttons, 'begin_mce_button' );
	return $buttons;
}

// List button
function lists_code_plugin() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'list_mce_external_plugins_filter');
		add_filter('mce_buttons', 'list_mce_buttons_filter');
	}
}

function list_mce_external_plugins_filter($plugin_array) {
	$plugin_array['list_code_plugin'] = get_template_directory_uri() . '/inc/tinymce/list-btn.js';
	return $plugin_array;
}

function list_mce_buttons_filter($buttons) {
	array_push($buttons, 'list_code_plugin');
	return $buttons;
}

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
	$out.= '<p class="wplist-des">' . $atts['content'] . '</p>';
	if (!empty($atts['price'])) {
		$out.= '<div class="wplist-oth"><div class="wplist-res wplist-price">' . $atts['price'] . '</div>';
		if (!empty($atts['oprice'])) {
			$out.= '<div class="wplist-res wplist-old-price"><del>' . $atts['oprice'] . '</del></div>';
		}
		$out.= '</div>';
	}
	if (!empty($atts['link'])) {
		$out.= '<a href="' . $atts['link'] . '" target="_blank" isconvert="1" rel="nofollow" ><div class="wplist-btn">' . $atts['btn'] . '</div></a><div class="clear"></div>';
	}
	$out.= '<div class="clear"></div></div>';
	return $out;
}

// TAB
function tab_code_plugin() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'tabs_mce_external_plugins_filter');
		add_filter('mce_buttons', 'tabs_mce_buttons_filter');
	}
}

function tabs_mce_external_plugins_filter($plugin_array) {
	$plugin_array['tabs_code_plugin'] = get_template_directory_uri() . '/inc/tinymce/tabs-btn.js';
	return $plugin_array;
}

function tabs_mce_buttons_filter($buttons) {
	array_push($buttons, 'tabs_code_plugin');
	return $buttons;
}

function start_tab_shortcode($atts, $content = '') {
	return '<div class="tab-single">';
}

function tabs_shortcode($atts, $content = '') {
	$atts['content'] =  do_shortcode($content);
	$out = '';
	$out.= '<section id="tab'.$atts['number'].'" betab="' . $atts['title'] . '">';
	$out.= $atts['content'];
	$out.= '</section>';
	return $out;
}

function end_tab_shortcode($atts, $content = '') {
	return '</div>';
}

// iframe
function iframe_add_shortcode( $atts ) {
	$defaults = array(
		'src' => '',
		'width' => '100%',
		'height' => '500',
		'scrolling' => 'yes',
		'class' => 'iframe-class',
		'frameborder' => '0'
	);

	foreach ( $defaults as $default => $value ) {
		if ( ! @array_key_exists( $default, $atts ) ) {
			$atts[$default] = $value;
		}
	}
	$html = '';
	$html .= '<iframe';
	foreach( $atts as $attr => $value ) {
		if ( strtolower($attr) != 'same_height_as' AND strtolower($attr) != 'onload'
			AND strtolower($attr) != 'onpageshow' AND strtolower($attr) != 'onclick') {
			if ( $value != '' ) {
				$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			} else {
				$html .= ' ' . esc_attr( $attr );
			}
		}
	}
	$html .= '></iframe>'."\n";

	if ( isset( $atts["same_height_as"] ) ) {
		$html .= '
			<script>
			document.addEventListener("DOMContentLoaded", function(){
				var target_element, iframe_element;
				iframe_element = document.querySelector("iframe.' . esc_attr( $atts["class"] ) . '");
				target_element = document.querySelector("' . esc_attr( $atts["same_height_as"] ) . '");
				iframe_element.style.height = target_element.offsetHeight + "px";
			});
			</script>
		';
	}
	return $html;
}

// serial number
function serial_number ( $atts ){
	extract( shortcode_atts(array(
		"text" => ''
	), $atts ) );
	return '<div class="serial-number"><div class="borde"></div><div class="borde"></div><span class="serial-txt">' . $text . '</span></div>';
}
add_shortcode('chapter', 'serial_number');

// quote post
add_shortcode( 'quote', 'quote_post' );
function quote_post( $atts, $content = null ){
	extract( shortcode_atts( array(
		'ids' => ''
	),
	$atts ) );
	$html = '';
	$quote = new WP_Query( array( 'post__in' => explode( ',', $ids ), 'post__not_in' => get_option( 'sticky_posts' ) ) );
	while( $quote->have_posts() ) : $quote->the_post();
		global $wpdb, $post;
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$html .= '<div class="quote-post bk sup">';
		$n = count( $strResult[1] );
		if ( $n > 0 ) {
			$html .= '<figure class="thumbnail"><div class="thumbs-b lazy">';
			$html .= '<a class="thumbs-back sc" target="_blank" rel="external nofollow" href="'.get_permalink().'" style="background-image: url('.$strResult[1][0].') !important;"></a>';
			$html .= '<div class="quote-cat cat ease">';
			foreach( ( get_the_category() ) as $category ){
				$html .= '<a target="_blank" href="' . get_category_link($category->cat_ID) . '">' . $category->cat_name. '</a>';
			}
			$html .= '</div>';
			$html .= '</div></figure>';
		}
		$html .= '<div class="quote-title over"><a target="_blank" href="' . get_permalink() . '">' . get_the_title() . '</a></div>';
		$html .= '<div class="quote-post-content">';
		$content = strip_shortcodes( $content );
		$html .= wp_trim_words( $content, 62, '...' );
		$html .= '</div>';
		if ( !$n > 0 ) {
			foreach( ( get_the_category() ) as $category ){
				$html .= '<a class="quote-inf-cat" target="_blank" href="' . get_category_link($category->cat_ID) . '"><i class="be be-sort"></i> ' . $category->cat_name. '</a>';
			}
		}
		$html .= '<div class="quote-inf fd">';
		$html .= '<span class="quote-views"><i class="be be-eye ri"></i>'. get_post_meta(get_the_ID(),'views',true) . '</span>';
		if ( get_comments_number() > 0) {
			$html .= '<span class="quote-comments"><i class="be be-speechbubble ri"></i>' . get_comments_number() . '</span>';
		}
		$html .= '</div>';
		$html .= '<div class="quote-more fd"><a href="' . get_permalink() . '" target="_blank" rel="external nofollow"><i class="be be-sort"></i></a></div>';
		$html .= '<div class="clear"></div>';
		$html .= '</div>';
	endwhile;
	wp_reset_query();
	return $html;
}

// random post
function random_post_shortcode() {
	ob_start();
	random_post();
	return ob_get_clean();
}

if (in_array($pagenow, array('post.php', 'post-new.php', 'page.php', 'page-new.php'))) {
	add_action('init', 'tab_code_plugin');
	add_action('init', 'lists_code_plugin');
	add_action('init', 'begin_add_mce_button');
}

// login reg
function login_reg( $atts, $content = null ) {
	extract( shortcode_atts( array('sup' => '' ), $atts ) );
	if ( !is_user_logged_in() ) {
		if ( strtolower( $sup ) === '1' ) {
			$hmtl = ' login-reg-btn';
		} else {
			$hmtl ='';
		}
		return '<span class="btn-login' . $hmtl . ' show-layer' . cur() . '" data-show-layer="login-layer" role="button">'.do_shortcode( do_shortcode( $content ) ).'</span>';
	}
}

// docs
function be_docs_point( $atts, $content = null ) {
	return '<div class="docs-point-box dah fd"><div class="docs-point-main dah">'.do_shortcode( $content ).'</div></div>';
}

// first drop
function be_first_drop( $atts, $content = null ) {
	return '<i class="be-first-drop"><p>'.do_shortcode( $content ).'</p></i>';
}

// menus custom [menucode image="图片链接" ids="分类ID"]
function be_menus_set_cat( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html' => '',
		'image' => '',
		'ids'   => ''
	), $atts ) );

	$args = array(
		'include'    => explode( ',',$ids ),
		'taxonomy'   => array('category','taobao', 'gallery', 'videos', 'products', 'notice'),
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
		'hide_empty' => 0
	);
	$cats = get_categories( $args );

	$html = '</a><span class="menus-set-cat-box"><span class="menus-set-cat-img menus-team-cat sc">';
	$html .= '<img alt="catimg" src="' . $image . '" />';
	$html .= '</span><span class="menus-set-cat-name-box menus-team-cat"><span class="menus-set-cat-name">';

	foreach ( $cats as $cat ) {
		query_posts( 'cat=' . $cat->cat_ID );
		$html .= '<a href="' . get_category_link( $cat->cat_ID ) . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $cat->cat_name . '</a>';
	}
	wp_reset_query();

	$html .= '<span class="rec-adorn-s"></span><span class="rec-adorn-x"></span></span></span><span class="clear"></span></span><a class="menus-set-name-hide">';
	return $html;
}

// menus cover [menucodecover ids="分类ID"]
function be_menus_set_cover( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html' => '',
		'image' => cat_cover_url(),
		'ids'   => ''
	), $atts ) );

	$args = array(
		'include'    => explode( ',', $ids ),
		'taxonomy'   => array('category','taobao', 'gallery', 'videos', 'products', 'notice'),
		'hide_empty' => 0
	);
	$cats = get_categories( $args );

	$html = '</a></li>';
	$html .= '</a>';

	foreach ( $cats as $cat ) {
		query_posts( 'cat=' . $cat->cat_ID );

		$html .= '<li class="be-menu-custom-img be-menu-custom-explain">';
		$html .= '<a href="' . get_category_link( $cat->cat_ID ) . '" rel="bookmark">';
		$html .= '<span class="be-menu-custom-title"><span class="be-menu-custom-title-ico"></span>' . $cat->cat_name . '</span>';
		$html .= '<span class="be-menu-img sc"><img alt="catimg" src="' . cat_cover_url() . '" /></span>';
		$html .= '<span class="be-menu-explain">' . category_description() . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	wp_reset_query();

	$html .= '<li class="menus-set-cover-hide"><a>';
	return $html;
}

// menus special [menucodespecial ids="专题页面ID"]
function be_menus_set_special( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html' => '',
		'image' => cat_cover_url(),
		'ids'   => ''
	), $atts ) );


	$posts = get_posts( array(
		'post_type' => 'any',
		'orderby' => 'menu_order',
		'order'      => 'ASC',
		'include' => $ids,
		'ignore_sticky_posts' => 1
	) ); 

	$html = '</a></li>';
	$html .= '</a>';

	foreach( $posts as $post ) : setup_postdata( $post );
	$description = get_post_meta( get_the_ID(), 'description', true );
	$image = get_post_meta( get_the_ID(), 'thumbnail', true );
	$html .= '<li class="be-menu-custom-img be-menu-custom-explain">';
	$html .= '<a href="' . get_permalink() . '" rel="bookmark">';
	$html .= '<span class="be-menu-custom-title"><span class="be-menu-custom-title-ico"></span>' . get_the_title() . '</span>';

	$html .= '<span class="be-menu-img sc"><img alt="catimg" src="' . $image . '" /></span>';
	$html .= '<span class="be-menu-explain">' . $description . '</span>';
	$html .= '</a>';
	$html .= '</li>';

	endforeach;
	wp_reset_query();

	$html .= '<li class="menus-set-cover-hide"><a>';
	return $html;
}

// menus custom 
// [menupost image="图片链接" cat_ids="左侧分类ID" post_ids="右侧文章/页面ID" des="1" words_n="文章摘要字数"]
function be_menus_set_post( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'image'     => '',
		'cat_ids'   => '',
		'post_ids'  => '',
		'des'       => '',
		'words_n'   => ''
	), $atts ) );

	$args = array(
		'include'    => explode( ',',$cat_ids ),
		'taxonomy'   => array('category','taobao', 'gallery', 'videos', 'products', 'notice'),
		'orderby'    => 'menu_order', 
		'order'      => 'ASC',
		'hide_empty' => 0
	);
	$cats = get_categories( $args );

	$args = array(
		'post__in'            => explode( ',', $post_ids ),
		'post_type'           => 'any',
		'orderby'             => 'menu_order', 
		'order'               => 'DESC',
		'ignore_sticky_posts' => 1
	);
	$the_query = new WP_Query( $args );

	$html = '</a>';
	$html .= '<span class="menu-mix-box">';

	$html .= '<span class="menu-mix-cat-box menu-mix-team">';
	$html .= '<span class="menus-mix-img menus-team-cat sc">';
	$html .= '<img alt="catimg" src="' . $image . '" />';
	$html .= '</span>';

	$html .= '<span class="menus-mix-cat-name">';
		foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
			$html .= '<a href="' . get_category_link( $cat->cat_ID ) . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $cat->cat_name . '</a>';
		}
		wp_reset_query();
	$html .= '</span>';
	$html .= '</span>';

	$html .= '<span class="menu-mix-post-box  menu-mix-team">';

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$html .= '<span class="menu-mix-post">';
			$html .= '<a class="menu-mix-post-main" href="' . get_permalink() . '">';
			$html .= '<span class="be-menu-custom-title-ico"></span>';
			$html .= '<span class="menu-mix-post-title">' . get_the_title() . '<span class="clear"></span></span>';

			$html .= be_menu_thumbnail();

			if ( strtolower( $des ) === '1' ) {
				$content = $post->post_content;
				preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
				$n = count( $strResult[1] );

				if ( get_post_meta( get_the_ID(), 'thumbnail', true ) || $n > 0 ) {
					$html .= '<span class="menu-mix-post-content menu-post-content-img">';
				} else {
					$html .= '<span class="menu-mix-post-content">';
				}
				$content = get_the_content();
				$content = strip_shortcodes( $content );
				$html .= wp_trim_words( $content, $words_n, '...' );
				$html .= '</span>';
			}
			$html .= '</a></span>';

		endwhile;
	endif;

	wp_reset_postdata();

	$html .= '</span>';
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';

	return $html;
}

// code custom 
// [menucodea image="图片地址" buturl="链接一" url1="链接一" url2="链接二" url3="链接三" url4="链接四" url5="链接五"  but="" title1="标题一" title2="标题二" title3="标题三" title4="标题四" title5=""]
function be_menus_custom_a( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html'   => '',
		'image'  => '',
		'url1'   => '',
		'url2'   => '',
		'url3'   => '',
		'url4'   => '',
		'url5'   => '',
		'buturl' => '',
		'title1' => '',
		'title2' => '',
		'title3' => '',
		'title4' => '',
		'title5' => '',
		'but' => ''

	), $atts ) );


	$html = '</a>';
	$html .= '<span class="menus-set-cat-box">';
	$html .= '<span class="menus-set-cat-img sc menus-team-cat">';
	$html .= '<img alt="catimg" src="' . $image . '" />';
	$html .= '</span>';

	$html .= '<span class="menus-set-cat-name-box menus-team-cat">';
	$html .= '<span class="menus-set-cat-name">';

	if ( !strtolower( $but ) == '' ) {
		$html .= '<span class="be-menu-custom-title-but">';
		$html .= '<a href="' . $buturl . '" rel="bookmark">' . $but . '</a>';
		$html .= '</span>';
	}
	if ( !strtolower( $title1 ) == '' ) {
		$html .= '<a href="' . $url1 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title1 . '</a>';
	}
	if ( !strtolower( $title2 ) == '' ) {
		$html .= '<a href="' . $url2 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title2 . '</a>';
	}
	if ( !strtolower( $title3 ) == '' ) {
		$html .= '<a href="' . $url3 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title3 . '</a>';
	}
	if ( !strtolower( $title4 ) == '' ) {
		$html .= '<a href="' . $url4 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title4 . '</a>';
	}
	if ( !strtolower( $title5 ) == '' ) {
		$html .= '<a href="' . $url5 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title5 . '</a>';
	}

	$html .= '</span>';
	$html .= '</span>';
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'menucodea', 'be_menus_custom_a' );

// menus cat mod
// [menucatmod cat_ids="分类ID"]
function be_menus_cat_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'cat_ids'   => ''

	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = new WP_Query(array(
		'post__not_in' => array($post->ID),
		'ignore_sticky_posts' => 1,
		'showposts' => '1',
		'category__and' => $cat_ids
	));

	while ($args->have_posts()) : $args->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	$html .= '<a href="' . get_category_link( $cat_ids ) . '">';
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile;
	wp_reset_postdata();

	$html .= '<span class="menus-cat-mod-name">';
	$html .= '<a class="menus-mod-ico" href="' . get_category_link( $cat_ids ) . '"><i class="dashicons dashicons-image-filter"></i>';
	$html .= get_cat_name( $cat_ids );
	$html .= '</a>';
	$html .= '</span>';

	$args = new WP_Query(array(
	'post__not_in' => array($post->ID),
		'ignore_sticky_posts' => 1,
		'showposts' => 5,
		'category__and' => $cat_ids
	));

	while ($args->have_posts()) : $args->the_post();
	$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'menucatmod', 'be_menus_cat_mod' );

// menus hot mod
// [menuhotmod number="数量" cat_ids="分类ID" month="限定月数"]
function be_menus_hot_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'    => '',
		'month'   => '',
		'number'  => '',
		'cat_ids' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = array(
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'showposts'           => 1,
		'category__and'       => $cat_ids,
		'meta_key'            => 'views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'date_query'          => array(
			array(
				'after'       => $month . 'month ago',
				'before'      => 'today',
				'inclusive'   => true
			)
		)
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	if ( strtolower( $cat_ids ) !== '0' ) {
		$html .= '<a href="' . get_category_link( $cat_ids ) . '">';
	} else {
		$html .= '<a href="' . esc_url( get_permalink() ) . '">';
	}
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile; endif;
	wp_reset_postdata();

	$html .= '<span class="menus-hot-mod-clear"></span>';
	$args = array(
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'showposts'           => $number,
		'category__and'       => $cat_ids,
		'meta_key'            => 'views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'date_query'          => array(
			array(
				'after'       => $month . 'month ago',
				'before'      => 'today',
				'inclusive'   => true
			)
		)
	);

	$the_query = new WP_Query( $args );
	$i = 1;
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$html .= '<a class="be-menus-cat-mod-list srm" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= '<span class="list-num list-num-' . ( $i ) . '">';
	$html .= ($i++);
	$html .= '</span>';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile; endif;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'menuhotmod', 'be_menus_hot_mod' );

// menus rec 
// [menurecmod post_ids="文章ID"]
function be_menus_rec_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'     => '',
		'post_ids' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = array(
		'post__in'  => explode( ',', $post_ids ),
		'ignore_sticky_posts' => 1,
		'showposts'           => 1,
		'orderby'   => 'menu_order',
		'order'     => 'ASC'
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	$html .= '<a href="' . esc_url( get_permalink() ) . '">';
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile; endif;
	wp_reset_postdata();

	$html .= '<span class="menus-hot-mod-clear"></span>';
	$args = array(
		'post__in'  => explode( ',', $post_ids ),
		'ignore_sticky_posts' => 1,
		'orderby'   => 'menu_order',
		'order'     => 'ASC'
	);

	$the_query = new WP_Query( $args );
	$i = 1;
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$html .= '<a class="be-menus-cat-mod-list srm" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= '<span class="list-num list-num-' . ( $i ) . '">';
	$html .= ($i++);
	$html .= '</span>';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile; endif;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'menurecmod', 'be_menus_rec_mod' );

// columns button
function btn_columns( $atts = array(), $content=null ) {
	extract( shortcode_atts( array(), $atts ) );
	return '<div class="btn-columns-box">' . do_shortcode( do_shortcode( $content ) ) . '</div>';
}

add_shortcode( 'bebtn', 'btn_columns' );

// 菜单分类列表
// [menucatlist cat_ids="分类ID" number="分章数"]
function be_menus_cat_list( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'cat_ids' => '', 
		'number' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="menu-cat-list-box">';
	$menu_cat = explode( ',', $cat_ids ); foreach ( $menu_cat as $category ) {
		query_posts( array( 'showposts' => $number, 'cat' => $category ) );
		$html .= '<span class="menu-list-item">';
		$html .= '<h3 class="menu-list-cat-title">';
		$html .= '<a href="';
		$html .= get_category_link( $category );
		$html .= '">';
		$html .= get_cat_name( $category );
		$html .= '</a>';
		$html .= '</h3>';
		$html .= '<span class="menu-list-title">';
		while ( have_posts() ) : the_post();
		$html .= '<a class="srm" href="';
		$html .= get_permalink();
		$html .= '" rel="bookmark">';
		$html .= '<span class="be-menu-custom-title-ico"></span>';
		$html .= get_the_title();
		$html .= '</a>';
		endwhile;
		wp_reset_query();
		$html .= '</span>';
		$html .= '</span>';
	}
	$html .= '</span>';
	return $html;
}
add_shortcode( 'menucatlist', 'be_menus_cat_list' );