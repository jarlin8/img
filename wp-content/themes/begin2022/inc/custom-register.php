<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (!is_admin() && !isset($_SESSION)) {
	session_start();
	session_regenerate_id();
}

function begin_show_password_field() {
?>
<div class="pass-input zml-ico">
	<div class="togglepass"><i class="be be-eye"></i></div>
	<?php password_iocn(); ?>
	<input class="user_pwd1 input dah bk" type="password" size="25" value="<?php if (isset($_POST['user_pass'])) {$_POST['user_pass'];} ?>" name="user_pass" placeholder="<?php _e( '密码', 'begin' ); ?><?php _e( '(至少6位)', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '密码', 'begin' ); ?><?php _e( '(至少6位)', 'begin' ); ?>'" autocomplete="off" />
</div>
<div class="pass-input zml-ico">
	<div class="togglepass"><i class="be be-eye"></i></div>
	<?php password_iocn(); ?>
	<input class="user_pwd2 input dah bk" type="password" size="25" value="<?php if (isset($_POST['user_pass2'])) {$_POST['user_pass2'];} ?>" name="user_pass2" placeholder="<?php _e( '重复密码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '重复密码', 'begin' ); ?>'" autocomplete="off" />
</div>
<input type="hidden" name="spam_check" value="<?php global $token; echo $token; ?>" />
<?php
}

function begin_check_fields($login, $email, $errors) {
	global $wpdb;
	$last_reg = $wpdb->get_var("SELECT `user_registered` FROM `$wpdb->users` ORDER BY `user_registered` DESC LIMIT 1");

	if ( (time() - strtotime($last_reg)) < 60 )
		$errors->add('anti_spam', ''.sprintf(__( '休息一会', 'begin' )).'');

	if(strlen($_POST['user_pass']) < 6)
		$errors->add('password_length', ''.sprintf(__( '错误：密码长度至少6位', 'begin' )).'');
	elseif($_POST['user_pass'] != $_POST['user_pass2'])
		$errors->add('password_error', ''.sprintf(__( '错误：密码必须一致', 'begin' )).'');
}

function begin_register_extra_fields($user_id, $password="", $meta=array()) {
	$userdata = array();
	$userdata['ID'] = $user_id;
	$userdata['user_pass'] = $_POST['user_pass'];
	wp_new_user_notification( $user_id, $_POST['user_pass'], 1 );
	wp_update_user($userdata);
}

function remove_default_password_wp() {
	global $user_ID;
	delete_user_setting('default_password_nag', $user_ID);
	update_user_option($user_ID, 'default_password_nag', false, true);
}

add_filter( 'send_password_change_email', '__return_false' );
add_action('admin_init', 'remove_default_password_wp');
add_action('register_form','begin_show_password_field');
add_action('register_post','begin_check_fields',10,3);
add_action('user_register', 'begin_register_extra_fields');