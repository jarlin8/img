<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//添加页面函数
function begin_add_page( $title, $slug, $page_template = '' ){
	$allPages = get_pages();
	$exists = false;
	foreach( $allPages as $page ) {
		if ( strtolower( $page->post_name ) == strtolower( $slug ) ){
			$exists = true;
		}
	}

	if ( ! $exists ) {
		$new_page_id = wp_insert_post(
			array(
				'post_title'     => $title,
				'post_type'      => 'page',
				'post_name'      => $slug,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'menu_order'     => 0
			)
		);

		if ( $new_page_id && $page_template != '' ) {
			update_post_meta( $new_page_id, '_wp_page_template', $page_template );
		}
	}
}

function begin_add_template() {
	global $pagenow;
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		begin_add_page( '用户中心', 'user-center', 'pages/template-user.php' );
		begin_add_page( '用户注册', 'registered', 'pages/template-reg.php' );
		begin_add_page( '友情链接', 'link', 'pages/template-links.php' );
		begin_add_page( '链接跳转', 'go', 'pages/template-go.php' );
	}
}
add_action( 'load-themes.php','begin_add_template' );
// add_action( 'admin_init','begin_add_template' );

//添加注册需知
function begin_add_page_reg_notice( $title, $slug, $page_template = '' ){
	$allPages = get_pages();
	$exists = false;
	foreach( $allPages as $page ) {
		if ( strtolower( $page->post_name ) == strtolower( $slug ) ){
			$exists = true;
		}
	}

	$notice = '<p>您在自愿注册使用本网站服务前，必须仔细阅读并充分理解知悉本协议所有条款。</p><p>只要用户仍然使用本网站服务，即表示用户仍然同意本协议。</p><p>如有违反而导致任何法律后果的发生，您将以自己的名义独立承担相应的法律责任。</p>';
	if ( ! $exists ) {
		$new_page_id = wp_insert_post(
			array(
				'post_title'     => $title,
				'post_type'      => 'page',
				'post_name'      => $slug,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_content'   => $notice,
				'post_status'    => 'publish',
				'post_author'    => 1,
				'menu_order'     => 0,
				'meta_input'     => array(
					'no_sidebar' => 'true',
					'header_bg'  => get_template_directory_uri() . '/img/default/options/1200.jpg',
				),
			)
		);

		if ( $new_page_id && $page_template != '' ) {
			update_post_meta( $new_page_id, '_wp_page_template', $page_template );
		}
	}
}

function begin_add_template_reg_notice() {
	global $pagenow;
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		begin_add_page_reg_notice( '注册需知','notice','pages.php' );
	}
}
add_action( 'load-themes.php','begin_add_template_reg_notice' );

//添加投稿/信息提交
function be_add_publish_page( $title, $slug, $shortcode, $page_template = '' ) {
	$allPages = get_pages();
	$exists = false;
	foreach( $allPages as $page ) {
		if ( strtolower( $page->post_name ) == strtolower( $slug ) ){
			$exists = true;
			break;
		}
	}

	if ( ! $exists ) {
		$new_page_id = wp_insert_post(
			array(
				'post_title'     => $title,
				'post_type'      => 'page',
				'post_name'      => $slug,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_content'   => '[' . $shortcode . ']',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'menu_order'     => 0,
				'meta_input'     => array(
					'no_sidebar' => 'true',
					'header_bg'  => get_template_directory_uri() . '/img/default/options/1200.jpg',
				),
			)
		);

		if ( $new_page_id && $page_template != '' ) {
			update_post_meta( $new_page_id, '_wp_page_template', $page_template );
		}
	}
}

function begin_add_templates() {
	global $pagenow;
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		be_add_publish_page( '我要投稿', 'publish', 'bet_submission_form', 'pages.php' );
		be_add_publish_page( '信息提交', 'profile', 'bet_submission_info', 'pages.php' );
	}
}
add_action( 'load-themes.php', 'begin_add_templates' );