<?php 
global $wpdb;
if(!is_admin() && !isset($_SESSION)) session_start();
function beginlogin_install(){
	global $wpdb;
	$var = $wpdb->query("SELECT qqid FROM $wpdb->users");
	if(!$var){
		$wpdb->query("ALTER TABLE $wpdb->users ADD qqid varchar(100)");
	}
	$var1 = $wpdb->query("SELECT sinaid FROM $wpdb->users");
	if(!$var1){
	 $wpdb->query("ALTER TABLE $wpdb->users ADD sinaid varchar(100)");
	}
	$var2 = $wpdb->query("SELECT weixinid FROM $wpdb->users");
	if(!$var2){
	 $wpdb->query("ALTER TABLE $wpdb->users ADD weixinid varchar(100)");
	}
}

// add_action( 'after_switch_theme', 'beginlogin_install' );
add_action( 'optionsframework_after_validate', 'beginlogin_install' );

function beginlogin_selfURL(){
	global $wp;
	$current_url = home_url(add_query_arg(array(),$wp->request));
	return $current_url;
}

function beginlogin_post($url, $data) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$ret = curl_exec ( $ch );
	curl_close ( $ch );
	return $ret;
}

function beginlogin_get_url_contents($url) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt ( $ch, CURLOPT_URL, $url );
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	return $result;
}

function beginloginFormButton(){
	$beginlogin_qqid = zm_get_option('qq_app_id');
	$beginlogin_sinaid = zm_get_option('weibo_key');
	$beginlogin_weixinid = zm_get_option('weixin_id');
	echo '<div class="clear"></div><div id="beginlogin-box" class="beginlogin-box"><div class="beginlogin-main"><div class="social-t">'.sprintf(__( '社交登录', 'begin' )).'</div>';
	if($beginlogin_qqid) echo '<i id="qqbtn" class="soc beginlogin-qq-a ms da be be-qq"></i><script type="text/javascript">var btn = document.getElementById("qqbtn"); btn.onclick = function () {window.open("'.get_template_directory_uri().'/inc/social/qq.php?beginloginurl='.zm_get_option('social_login_url').'")}</script>';
	if($beginlogin_sinaid) echo '<i id="sinabtn" class="soc beginlogin-weibo-a ms da be be-stsina"></i><script type="text/javascript">var btn = document.getElementById("sinabtn"); btn.onclick = function () {window.open("'.get_template_directory_uri().'/inc/social/sina.php?beginloginurl='.zm_get_option('social_login_url').'")}</script>';
	if($beginlogin_weixinid) echo '<i id="weibtn" class="soc beginlogin-weixin-a ms da be be-weixin"></i><script type="text/javascript">var btn = document.getElementById("weibtn"); btn.onclick = function () {window.open("https://open.weixin.qq.com/connect/qrconnect?appid='.$beginlogin_weixinid.'&redirect_uri='.get_template_directory_uri().'/inc/social/weixin.php&response_type=code&scope=snsapi_login&state=beginlogin_weixin#wechat_redirect")}</script>';
	echo '</div></div>';
?>
<?php
}
if ( !is_user_logged_in() ) {
add_filter('login_form', 'beginloginFormButton');
add_filter('register_form', 'beginloginFormButton');
}

function fontsCSS(){
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts/fonts.css', array(), version );
?>
<?php
}
add_filter('login_head', 'fontsCSS');

function get_beginlogin(){
	$begin = '';
	if(!is_user_logged_in()){
		$beginlogin_qqid = zm_get_option('qq_app_id');
		$beginlogin_sinaid = zm_get_option('weibo_key');
		$beginlogin_weixinid = zm_get_option('weixin_id');
		$begin .= '<div id="beginlogin-box" class="beginlogin-box"><div class="beginlogin-main">';
		if($beginlogin_qqid) $begin .= '<a href="'.get_template_directory_uri().'/inc/social/qq.php?beginloginurl='.zm_get_option('social_login_url').'" title="QQ快速登录" rel="nofollow" class="beginlogin-qq-a ms da"><i class="be be-qq"></i></a>';
		if($beginlogin_sinaid) $begin .= '<a href="'.get_template_directory_uri().'/inc/social/sina.php?beginloginurl='.zm_get_option('social_login_url').'" title="微博快速登录" rel="nofollow" class="beginlogin-weibo-a ms da"><i class="be be-stsina"></i></a>';
		if($beginlogin_weixinid) $begin .= '<a href="https://open.weixin.qq.com/connect/qrconnect?appid='.$beginlogin_weixinid.'&redirect_uri='.get_template_directory_uri().'/inc/social/weixin.php&response_type=code&scope=snsapi_login&state=beginlogin_weixin#wechat_redirect" title="微信快速登录" rel="nofollow" class="beginlogin-weixin-a ms da"><i class="be be-weixin"></i></a>';
		$begin .= '</div></div>';
	}
	return $begin;
}