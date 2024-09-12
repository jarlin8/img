<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require get_template_directory() . '/inc/token.php';
function be_theme() {
	global $pagenow;
	if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		wp_redirect( admin_url( 'themes.php?page=begin-options' ) );
	}
}
add_action( 'after_switch_theme', 'be_theme' );

require get_template_directory() . '/inc/inc.php';
if ( !zm_get_option( 'meta_delete' ) ) {
	require get_template_directory() . '/inc/meta-delete.php';
}
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/show-meta.php';
require get_template_directory() . '/inc/links-xfn.php';

if ( zm_get_option( 'root_file_move' ) ) {
	function be_root_file_move() {
		$file = get_template_directory() . '/inc/download.php';
		if ( file_exists( $file ) ) {
			$downFile = ABSPATH . '/download.php';
			copy( $file, $downFile );
		}
	}
	add_action( 'optionsframework_after_validate', 'be_root_file_move' );
}

if ( function_exists( 'is_shop' ) ) {
	if ( file_exists( get_theme_file_path( '/woocommerce/woo.php' ) ) ) {
		require get_template_directory() . '/woocommerce/woo.php';
	}
}