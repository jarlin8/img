<?php
add_action( 'phpmailer_init', 'mail_smtp' );
function mail_smtp( $phpmailer ) {
	$phpmailer->FromName   = zm_get_option( 'email_name' );
	$phpmailer->Host       = zm_get_option( 'email_smtp' );
	$phpmailer->Port       = zm_get_option( 'email_port' );
	$phpmailer->Username   = zm_get_option( 'email_account' );
	$phpmailer->Password   = zm_get_option( 'email_authorize' );
	$phpmailer->From       = zm_get_option( 'email_account' );
	$phpmailer->SMTPAuth   = true;
	$phpmailer->SMTPSecure = zm_get_option( 'email_secure' );
	$phpmailer->IsSMTP();

	if ( zm_get_option( 'all_ssl' ) ) {
		$phpmailer->SMTPOptions = array(
			'ssl' => array(
				'verify_peer'       => false,
				'verify_peer_name'  => false,
				'allow_self_signed' => true,
			)
		);
	}
}