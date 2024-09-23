<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! is_admin() && ( ! session_id() || ! isset( $_SESSION ) ) ) {
	session_start();
	session_regenerate_id( TRUE );
}

if ( ! function_exists( 'wp_new_user_notification' ) ) :
	function wp_new_user_notification( $user_id, $plaintext_pass = '', $flag='' ) {
		if ( func_num_args() > 1 && $flag !== 1 )
			return;

		$user = new WP_User( $user_id );

		$user_login = stripslashes($user->user_login );
		$user_email = stripslashes( $user->user_email );

		$blogname = wp_specialchars_decode( get_option('blogname' ), ENT_QUOTES );

		$message  = sprintf( __( 'New user registration on your site %s:'), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= sprintf( __('E-mail: %s' ), $user_email ) . "\r\n";

		@wp_mail( get_option( 'admin_email'), sprintf( __('[%s] New User Registration' ), $blogname ), $message );

		if ( empty( $plaintext_pass ) )
			return;

		// 你可以在此修改发送给用户的注册通知Email
		$message  = sprintf( __( 'Username: %s'), $user_login ) . "\r\n";
		$message .= sprintf( __( 'Password: %s' ), $plaintext_pass ) . "\r\n";
		$message .= '登录网址: ' . wp_login_url() . "\r\n";

		// sprintf(__('[%s] Your username and password'), $blogname) 为邮件标题
		wp_mail( $user_email, sprintf( __('[%s] Your username and password' ), $blogname ), $message );
	}
endif;

function be_show_password_field() { ?>

<div class="pass-input zml-ico">
	<div class="togglepass"><i class="be be-eye"></i></div>
	<input type="text" style="display:none;">
	<input class="user_pwd1 input" type="password" size="25" value="<?php if (isset($_POST['user_pass'])) {$_POST['user_pass'];} ?>" name="user_pass" placeholder="<?php _e( '密码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '密码', 'begin' ); ?>'" autocomplete="off" />
</div>
<div class="pass-input pass2-input zml-ico">
	<div class="togglepass"><i class="be be-eye"></i></div>
	<input class="user_pwd2 input" type="password" size="25" value="<?php if (isset($_POST['user_pass2'])) {$_POST['user_pass2'];} ?>" name="user_pass2" placeholder="<?php _e( '再次输入密码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '重复密码', 'begin' ); ?>'" autocomplete="off" />
</div>

<?php if ( zm_get_option( 'reg_captcha' ) ) { ?>
	<p class="label-captcha zml-ico captcha-ico email-captcha-ico">
		<input id="captcha_code" type="text" name="captcha_code" class="input captcha-input" value="" placeholder="<?php _e( '邮件验证码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '邮件验证码', 'begin' ); ?>'">
		<a href="javascript:void(0)" onclick="javascript:;" class="be-email-code"><?php _e( '获取邮件验证码', 'begin' ); ?></a>
	</p>
	<div class="reg-captcha-message"></div>
<?php } ?>

<?php $module = mt_rand( 100000,999999 ); ?>
<input type="hidden" name="module" value="<?php echo $module; ?>"/>
<input type="hidden" name="timestamp" value="<?php echo time(); ?>"/>
<input type="hidden" name="spam_check" value="<?php echo md5( $module . '#$@%!^*' . time() ); ?>"/>
<?php
}

/* 处理表单提交的数据 */
function be_check_fields( $login, $email, $errors ) {
	$module = $_POST['module'];
	$timestamp = $_POST['timestamp'];
	$token = md5( $module . '#$@%!^*'.$timestamp );
	if ( $token != $_POST['spam_check'] ){
		$errors->add( 'spam_detect', '' . sprintf( __( '请勿恶意注册', 'begin' ) ) . '' );
	}

	global $wpdb;
	$last_reg = $wpdb->get_var("SELECT `user_registered` FROM `$wpdb->users` ORDER BY `user_registered` DESC LIMIT 1");

	if ( (time() - strtotime( $last_reg ) ) < 60 )
		$errors->add( 'anti_spam', '' . sprintf( __( '休息一会', 'begin' ) ) . '');

	if ( zm_get_option( 'reg_captcha' ) ) {
		if ( empty( $_POST['captcha_code'] ) || empty( $_SESSION['EER_captcha'] ) || ( trim( strtolower( $_POST['captcha_code'] ) ) != $_SESSION['EER_captcha'] ) || ( trim( strtolower( $_POST['user_email'] ) ) != $_SESSION['EER_captcha_email'] ) ) {
			$errors->add( 'captcha_spam', '' . sprintf( __( '邮件验证码错误', 'begin' ) ) . '' );
		}

		unset( $_SESSION['EER_captcha'] );
		unset( $_SESSION['EER_captcha_email'] );
	}

	if ( empty( $_POST['user_pass'] ) )
		$errors->add( 'password_empty', '' . sprintf( __( '密码不能为空', 'begin' ) ) . '' );
	elseif ( strlen($_POST['user_pass']) < 6 )
		$errors->add( 'password_length', '' . sprintf( __( '密码长度至少6位', 'begin' ) ) . '' );
	elseif ( empty( $_POST['user_pass2'] ) )
		$errors->add( 'password2_empty', '' . sprintf( __( '请再次输入密码', 'begin' ) ) . '' );
	elseif ( $_POST['user_pass'] != $_POST['user_pass2'] )
		$errors->add( 'password_error', '' . sprintf( __( '两次输入的密码必须一致', 'begin' ) ) . '' );
}

/* 保存表单提交的数据 */
function be_register_extra_fields( $user_id, $password="", $meta=array() ) {
	$userdata = array();
	$userdata['ID'] = $user_id;
	if( isset( $_POST['user_pass'] ) ) {
		$userdata['user_pass'] = $_POST['user_pass'];
	}

	$pattern = '/[一-龥]/u';
	if ( preg_match( $pattern, $_POST['user_login'] ) ) {
		$userdata['user_nicename'] = $user_id;
	}
	if( isset( $_POST['user_pass'] ) ) {
		wp_new_user_notification( $user_id, $_POST['user_pass'], 1 );
	}
	wp_update_user( $userdata );
}

function be_remove_default_password() {
	global $user_ID;
	delete_user_setting( 'default_password_nag', $user_ID );
	update_user_option( $user_ID, 'default_password_nag', false, true );
}

function be_register_change_translated_text( $translated_text, $untranslated_text, $domain ) {
	if ( $untranslated_text === 'A password will be e-mailed to you.' || $untranslated_text === 'Registration confirmation will be emailed to you.' )
		return '';
	else if ( $untranslated_text === 'Registration complete. Please check your e-mail.' || $untranslated_text === 'Registration complete. Please check your email.' )
		return '' . sprintf( __( '注册成功！', 'begin' ) ) . '';
	else
		return $translated_text;
}

function be_mail_captcha_js() {
	$url = get_template_directory_uri();
	wp_enqueue_script( 'jquery' );
	echo '<script>window._betip = { uri:"' . $url .'/" }</script>';
	wp_enqueue_script( 'login', get_template_directory_uri() . '/js/captcha-email.js', array(), version, true );
}
add_action( 'login_footer', 'be_mail_captcha_js' );
add_action( 'wp_footer', 'be_mail_captcha_js' );
add_filter( 'send_password_change_email', '__return_false' );
add_filter( 'gettext', 'be_register_change_translated_text', 20, 3 );
add_action( 'admin_init', 'be_remove_default_password' );
add_action( 'be_register_form','be_show_password_field' );
// add_action( 'register_form','be_show_password_field' );
add_action( 'register_post','be_check_fields',10,3 );
add_action( 'user_register', 'be_register_extra_fields' );