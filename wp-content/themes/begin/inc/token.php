<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function be_license_notice() {
	$message = '主题尚未激活！';
	$class = 'be-license-error';

	printf( '<div class="%1$s"><p class="bem">%2$s <a style="text-decoration: none;" href="%3$s" target="_blank">%4$s</a> 或 <a style="text-decoration: none;" href="%5$s" rel="external nofollow" target="_blank">%6$s</a></p></div>',
		esc_attr( $class ),
		esc_html( $message ),
		admin_url( 'tools.php?page=be-themes-license' ),
		esc_html__( '激活主题' ),
		esc_url( 'https://zmingcx.com/begin.html' ),
		esc_html__( '购买授权' )
	);
}