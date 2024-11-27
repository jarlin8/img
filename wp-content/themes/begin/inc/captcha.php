<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( zm_get_option( 'slider_captcha' ) || zm_get_option( 'verify_comment' ) ) {
	add_action( 'wp_enqueue_scripts', 'be_captcha_scripts' );
}

if ( zm_get_option( 'slider_captcha' ) ) {
	add_action( 'be_login_form', 'add_slidercaptcha_form');
	add_action( 'be_lostpassword_form', 'add_slidercaptcha_form');
	add_action( 'be_register_form', 'add_slidercaptcha_form' );
}

if ( zm_get_option( 'verify_comment' ) ) {
	add_filter( "be_comment_form", 'add_slidercaptcha_form', 10, 1 );
}

function be_captcha_scripts() {
	wp_enqueue_script( 'captcha', get_template_directory_uri() . '/js/captcha.js', array( 'jquery' ), version, true );
	$captcha_img_urls  = zm_get_option( 'captcha_img_url' );
	$captcha_img_array = $captcha_img_urls ? explode( ',', $captcha_img_urls ) : array();
	$default_image_url = get_template_directory_uri() . '/img/default/captcha/y1.jpg';
	$captcha_img_array = $captcha_img_array ?: array( $default_image_url );
	$captcha_img       = 'var captcha_images = ' . wp_json_encode( $captcha_img_array ) . ';';
	$captcha_ajax_data = array( 'ajax_url' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'captcha', 'verify_ajax', $captcha_ajax_data );
	wp_add_inline_script( 'captcha', $captcha_img, 'after' );
}

function add_slidercaptcha_form() {
	if ( ! session_id() ) {
		session_start();
	}

	$header_text    = __( '拖动滑块以完成验证', 'begin' );
	$slider_text    = __( '向右滑动完成拼图', 'begin' );
	$try_again_text = __( '请再试一次', 'begin' );

?>
	<div class="slidercaptcha-box">
		<div class="bec-slidercaptcha bec-card">
			<div class="becclose"></div>
			<div class="refreshimg"></div>
			<div class="bec-card-header">
				<span><?php echo $header_text; ?></span>
			</div>
			<div class="bec-card-body"><div data-heading="<?php echo $header_text; ?>" data-slider="<?php echo $slider_text; ?>" data-tryagain="<?php echo $try_again_text; ?>" data-form="login" class="bec-captcha"></div></div>
		</div>
	</div>
	<?php
}

function be_ajax_verify_callback() {
	if ( ! session_id() ) {
		session_start();
	}
	$form = $_POST["form"];
	unset( $_SESSION["bec_".$form."_form"] );
	echo 'verified';
	die;
}

add_action( 'wp_ajax_be_ajax_verify', 'be_ajax_verify_callback' );
add_action( 'wp_ajax_nopriv_be_ajax_verify', 'be_ajax_verify_callback' );