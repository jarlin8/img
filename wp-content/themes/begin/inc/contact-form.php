<?php
if ( ! defined( 'ABSPATH' ) ) exit();

// 短代码
function be_mail_shortcode_init() {
	function be_mail_shortcode( $attr = [], $content = null ) {
		return '<div class="mail-main">' . be_display_contact_form() . '</div>';
	}
	add_shortcode( 'mail_contact_form', 'be_mail_shortcode' );
}
add_action( 'init', 'be_mail_shortcode_init' );

function bee_mail_shortcode_init() {
	function bee_mail_shortcode( $attr = [], $content = null ) {
		return be_display_contact_form();
	}
	add_shortcode( 'be_mail_contact_form', 'bee_mail_shortcode' );
}
add_action( 'init', 'bee_mail_shortcode_init' );

// 表单
function be_display_contact_form() {
	$content  = '<div id="be-contact-form" class="contact-form with-labels one-line">';
	$content .= '<form method="POST" autocomplete="off">';
	$content .= bec_input( 'name', zm_get_option( 'mail_name' ), 'text', true );
	$content .= bec_input( 'email', zm_get_option( 'mail_email' ), 'email', true );
	$content .= bec_input( 'address', __('Address', 'mcf'), 'text', false, false );
	if ( zm_get_option( 'mail_form_phone' ) ) { 
		$content .= bec_input( 'phone', zm_get_option( 'mail_phone' ), 'tel' );
	}
	if ( zm_get_option( 'mail_form_subject' ) ) { 
		$content .= bec_input( 'subject', zm_get_option( 'mail_subject' ) ) ;
	}
	$content .= bec_textarea( 'message', zm_get_option( 'mail_message' ), true );
	$content .= '<div class="notice fd">' . __( '带', 'begin' ) . '<i class="be be-star"></i>' . __( '号项必填', 'begin' ) . '</div>';
	$content .= bec_submit();
	$content .= '</form>';
	$content .= '<div class="clear"></div>';
	$content .= be_help( $text = '主题选项 → 辅助功能 → 联系我们' );
	$content .= '</div>';
	return $content;
}

// 输出表单
function bec_input( $name, $label, $type = 'text', $required = false, $has_label = true ) {
	$content  = "<div class='item item-$name'>";
	$content .= "<label class='" . ( $has_label ? 'label' : 'no-label' ) . "' for='$name'><span class='mail-label'>" .$label . ( $required ? '<span class="required"><i class="be be-star"></i></span>' : '' ) . "</span></label>";
	$content .= "<input type='$type' id='$name' class='wyc $name' name='$name' placeholder='' " . ( $required ? 'required' : '' ) . " />";
	$content .= "</div>";
	return $content;
}

// 输出内容表单
function bec_textarea( $name, $label, $required = false ) {
	$content  = "<div class='item item-$name'>";
	$content .= "<label class='label' for='$name'><span class='mail-label'>" .$label . ( $required ? '<span class="required"><i class="be be-star"></i></span>' : '' ) . "</span></label>";
	$content .= "<textarea id='$name' class='wyc $name' name='$name' placeholder='' rows='4' " . ( $required ? 'required' : '' ) . "></textarea>";
	$content .= "</div>";
	return $content;
}

// 提交按钮
function bec_submit() {
	$content  = '<div class="item item-submit">';
	$content .= '<button class="submit" type="button">' . __( '发送邮件', 'begin' ) . '</button>';
	$content .= '</div>';
	return $content;
}

// 提示消息
function be_ajax_mail_message() {
	$type = $_POST['type'];
	if ( $type !== '' ) {
		if ( $type === 'validation_error' ) echo __( '请正确填写所有必填项。', 'begin' );
		if ( $type === 'validation_phone_error' ) echo __( '请输入一个有效的电话号码。', 'begin' );
	}
	die();
}
add_action( 'wp_ajax_be_ajax_mail_message', 'be_ajax_mail_message' );
add_action( 'wp_ajax_nopriv_be_ajax_mail_message', 'be_ajax_mail_message' );

// AJAX发送电子邮件
function be_ajax_send_mail() {
	$data = $_POST['data'];
	// AJAX data
	$name    = sanitize_text_field( $data['name'] );
	$email   = sanitize_email( $data['email'] );
	$phone   = sanitize_text_field( $data['phone'] );
	$address = sanitize_text_field( $data['address'] ); // honeypot
	$subject = ( isset($data['subject'] ) && $data['subject'] !== '' ) ? sanitize_text_field( $data['subject'] ) : __( '来自网站的邮件', 'begin' );
	$msg     = sanitize_textarea_field( $data['message'] );
	$consent = ( int )$data['consent'];
	// Plugin data
	$charset = get_option( 'blog_charset', 'UTF-8' );
	$date    = get_date_from_gmt( current_time('mysql', true ), 'r' );

	// E-Mail Headers
	$emailto = '';
	if ( ! isset( $emailto ) || ( $emailto == '' ) ) {
		if ( ! zm_get_option( 'to_email' ) ) { 
			$emailto = get_option( 'admin_email' );
		} else {
			$emailto = zm_get_option( 'to_email' );
		}
	}

	$headers  = "From: $name <$email>\n";
	$headers .= "Sender: $name <$email>\n";
	$headers .= "Reply-To: $name <$email>\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html;\r\n";

	$message  = "";
	$message .= '<strong>' . __( '姓名', 'begin' ) . '：</strong>' . $name . "<br />";
	$message .= '<strong>' . __( '邮箱', 'begin' ) . '：</strong>' . $email . "<br />";
	if ( zm_get_option( 'mail_form_phone' ) ) { 
		$message .= '<strong>' . __( '电话', 'begin' ) . '：</strong>' . $phone . "<br />";
	}
	$message .= '<strong>' . __( '内容', 'begin' ) . '</strong><br />' . wp_strip_all_tags( $msg ) . "<br />";

	if ( $address === false || $address === '' ) {
		$success = wp_mail( $emailto, $subject, $message, $headers );

		if ( $success )
			echo '<p class="success">' . __( '邮件已发送！', 'begin' ) . '</p>';
		else
			echo '<p class="error">' . __( "无法发送，请稍后再试！", 'begin' ) . '</p>';
	} else {
		echo '<p class="warning">' . __( "error" ) . '</p>';
	}
	die();
}

add_action( 'wp_ajax_be_ajax_send_mail', 'be_ajax_send_mail' );
add_action( 'wp_ajax_nopriv_be_ajax_send_mail', 'be_ajax_send_mail' );