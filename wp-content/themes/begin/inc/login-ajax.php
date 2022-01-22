<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class LoginAjax {
	public static $current_user;
	public static $footer_loc;
	public static $url_login;
	public static $url_remember;
	public static $url_register;

	public static function init(){
		self::$current_user = wp_get_current_user();
		if ( !empty($_REQUEST["zml"]) ) {
		    self::ajax();
		}elseif ( isset($_REQUEST["login-widget"]) ) {
			$instance['profile_link'] = ( !empty($_REQUEST["zml_profile_link"]) ) ? $_REQUEST['zml_profile_link']:0;
			self::widget( $instance );
			exit();
		}else{
			if( !is_admin() ) {
				$schema = is_ssl() ? 'https':'http';
				$js_vars = array('ajaxurl' => admin_url('admin-ajax.php', $schema));
				wp_localize_script('login-ajax', 'ZML', apply_filters('zml_js_vars', $js_vars));
			}
			// add_action('wp_logout', 'LoginAjax::logoutRedirect');
			add_filter('logout_url', 'LoginAjax::logoutUrl');
			add_shortcode('login-ajax', 'LoginAjax::shortcode');
			add_shortcode('zml', 'LoginAjax::shortcode');
		}
	}
	// 登录操作
	public static function ajax(){
		$return = array('result'=>false, 'error'=>'Unknown command requested');
		switch ( $_REQUEST["login-ajax"] ) {
			case 'login': 
				add_filter('ws_plugin__s2member_login_redirect', '__return_false');
			    $return = self::login();
				break;
			case 'remember':
				$return = self::remember();
				break;
			case 'register': 
			default: 
			    $return = self::register();
			    break;
		}
		@header( 'Content-Type: application/javascript; charset=UTF-8', true );
		echo self::json_encode(apply_filters('zml_ajax_'.$_REQUEST["login-ajax"], $return));
		exit();
	}

	// 登录提示信息
	public static function login(){
		$return = array();
		if( !empty($_REQUEST['log']) && !empty($_REQUEST['pwd']) && trim($_REQUEST['log']) != '' && trim($_REQUEST['pwd'] != '') ){
			$loginResult = wp_signon();
			if ( strtolower(get_class($loginResult)) == 'wp_user' ) {
				self::$current_user = $loginResult;
				$return['result'] = true;
				$return['message'] = '<div class="message-tips message-ok log-wait">' . __('登录成功，请稍候...','begin') . '</div>';
			} elseif ( strtolower(get_class($loginResult)) == 'wp_error' ) {
				$return['result'] = false;
				$return['error'] = '<div class="message-tips">' . $loginResult->get_error_message() . '</div>';
			} else {
				$return['result'] = false;
				$return['error'] = '<div class="message-tips">' . __('出错了','begin') . '</div>';
			}
		}else{
			$return['result'] = false;
			$return['error'] = '<div class="message-tips">' . __('请输入用户名和密码','begin') . '</div>';
		}
		$return['action'] = 'login';
		return $return;
	}

	// 注册提示信息
	public static function register(){
		$return = array();
		if( get_option('users_can_register') ){
			$errors = register_new_user($_REQUEST['user_login'], $_REQUEST['user_email']);
			if ( !is_wp_error($errors) ) {
				$return['result'] = true;
				$return['message'] = '<div class="message-tips message-ok">' . __('注册成功！请查看您的邮箱','begin') . '</div>';
				if( is_multisite() ){
				add_user_to_blog(get_current_blog_id(), $errors, get_option('default_role'));
				}
			}else{
				$return['result'] = false;
				$return['error'] = '<div class="message-tips">' . $errors->get_error_message() . '</div>';
			}
			$return['action'] = 'register';
		}else{
			$return['result'] = false;
			$return['error'] = '<div class="message-tips">' . __('注册已被禁用','begin') . '</div>';
		}
		return $return;
	}

	// 读取登录
	public static function remember(){
		$return = array();
		if( !function_exists('retrieve_password') ){
			ob_start();
			include_once(ABSPATH.'wp-login.php');
			ob_clean();
		}
		$result = retrieve_password();
		if ( $result === true ) {
			$return['result'] = true;
			$return['message'] = '<div class="message-tips message-ok">' . __('已经向您发出一封电子邮件','begin') . '</div>';
		} elseif ( strtolower(get_class($result)) == 'wp_error' ) {
			$return['result'] = false;
			$return['error'] = '<div class="message-tips">' . $result->get_error_message() . '</div>';
		} else {
			$return['result'] = false;
			$return['error'] = '<div class="message-tips">' . __('发生未知的错误','begin') . '</div>';
		}
		$return['action'] = 'remember';
		return $return;
	}

	public static function logoutUrl( $logout_url ){
		return $logout_url;
	}

	public static function widget($instance = array() ){
		require get_template_directory() . '/inc/login-class.php';
	}

	public static function shortcode($atts){
		ob_start();
		$defaults = array(
			'profile_link' => true,
			'registration' => true,
			'redirect' => false,
			'remember' => true
		);
		self::widget(shortcode_atts($defaults, $atts));
		return ob_get_clean();
	}

	public static function json_encode($array){
		$return = json_encode($array);
		if( isset($_REQUEST['callback']) && preg_match("/^jQuery[_a-zA-Z0-9]+$/", $_REQUEST['callback']) ){
			$return = $_REQUEST['callback']."($return)";
		}
		return $return;
	}
}

add_action( 'init', 'LoginAjax::init' );

function login_ajax($atts = ''){
	if( !array($atts) ) $atts = shortcode_parse_atts($atts);
	echo LoginAjax::shortcode($atts);
}