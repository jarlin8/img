<?php
if (zm_get_option('register_captcha')) {
add_action('register_form', 'captcha_form');
}
if (zm_get_option('login_captcha')) {
add_action('login_form', 'captcha_form');
}
if (zm_get_option('lost_captcha')) {
add_action('lostpassword_form', 'captcha_form');
}

add_action('contact_form', 'captcha_form');

function label_captcha() { ?>
<?php 
	$url = get_template_directory_uri() . '/inc/captcha/';
	$be_captcha = new BECaptchaCode();
	$be_code = be_str_encrypt($be_captcha->generateCode(6));
?>

<div class="clear"></div>
<p class="label-captcha zml-ico captcha-ico">
	<img class="bk" src="<?php echo $url; ?>captcha_images.php?width=120&height=35&code=<?php echo $be_code; ?>" />
	<input type="text" name="be_security_code" class="input captcha-input dah bk" autocomplete="off" value="" placeholder="<?php _e( '验证码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '验证码', 'begin' ); ?>'"><br/>
	<input type="hidden" name="be_security_check" value="<?php echo $be_code; ?>">
	<label id="be_hp_label" style="display: none;">HP<br/>
		<input type="text" name="be_hp" value="" class="input" size="20" tabindex="23" />
	</label>
</p>
<div class="clear"></div>
<?php }

function captcha_form() {
	include_once("captcha/shared.php");
	include_once("captcha/captcha_code.php");
	echo label_captcha();
}

if (zm_get_option('register_captcha')) {
add_action('register_post', 'register_check_code', 10, 3);
}
function register_check_code($login, $email, $errors) {
	include_once("captcha/shared.php");
	$be_code = isset( $_POST['be_security_check'] ) ? be_str_decrypt( $_POST['be_security_check'] ) : '';
	if (($be_code != $_POST['be_security_code']) && (!empty($be_code)))
		$errors->add('crror', sprintf(__( '请输入正确的验证码', 'begin' )) );

	if (!isset($_POST['be_hp']) || !empty($_POST['be_hp']))
		$errors->add('be_error2', __('出错了，请重试'));
}

if (zm_get_option('login_captcha')) {
add_action('authenticate', 'login_check_code', 21, 1);
}
function login_check_code($errors) {
	include_once("captcha/shared.php");
	$be_code = isset( $_POST['be_security_check'] ) ? be_str_decrypt( $_POST['be_security_check'] ) : '';
	if (isset($_POST['be_security_code']) && $_POST['be_security_code'] != $be_code && (!empty($be_code)))
		$errors = new WP_Error( 'crror', sprintf(__( '请输入正确的验证码', 'begin' )) );
		return $errors;
}

add_action('lostpassword_post', 'lost_check_code', 10, 2);
function lost_check_code($errors, $user_data) {
	include_once("captcha/shared.php");
	$be_code = isset( $_POST['be_security_check'] ) ? be_str_decrypt( $_POST['be_security_check'] ) : '';
	if (($be_code != $_POST['be_security_code']) && (!empty($be_code)))
		$errors->add('crror', sprintf(__( '请输入正确的验证码', 'begin' )) );
		return $errors;
}