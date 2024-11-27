<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 自助友链
if ( zm_get_option( 'add_link' ) ) {
	add_action( 'wp_ajax_nopriv_submit_link', 'add_link_ajax' );
	add_action( 'wp_ajax_submit_link', 'add_link_ajax' );

	function add_link_ajax() {
		// 处理 AJAX 请求的逻辑
		if ( isset( $_POST['begin_form'] ) && $_POST['begin_form'] == 'send' ) {
			global $wpdb;

			$link_name        = isset( $_POST['begin_name'] ) ? sanitize_text_field( $_POST['begin_name'] ) : '';
			$link_url         = isset( $_POST['begin_url'] ) ? esc_url_raw( $_POST['begin_url'] ) : '';
			$link_description = isset( $_POST['begin_description'] ) ? sanitize_textarea_field( $_POST['begin_description'] ) : '';
			$link_notes       = isset( $_POST['link_notes'] ) ? sanitize_text_field( $_POST['link_notes'] ) : '';
			$link_target      = "_blank";
			$link_visible     = "N";
			$errors = '';

			if ( empty( $link_name ) || mb_strlen( $link_name ) > 30 ){
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '请填写网站名称，不超过30字！', 'begin' ) ) . '</p>';
			}

			if ( empty( $link_url ) || strlen( $link_url ) > 100 || ! filter_var( $link_url, FILTER_VALIDATE_URL ) ){
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '请正确填写网站链接！', 'begin' ) ) . '</p>';
			}

			if ( empty( $link_notes ) ){
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '请填写QQ号，方便联系！', 'begin' ) ) . '</p>';
			} else {
				if ( ! is_numeric( $link_notes ) ){
					$errors .= '<p class="add-link-tip fd">' . sprintf( __( '貌似填写的不是QQ号！', 'begin' ) ) . '</p>';
				}
			}

			if ( empty( $link_description ) || mb_strlen( $link_description ) > 100 ){
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '请填写网站描述，不超过100字！', 'begin' ) ) . '</p>';
			}

			$lkname  = $link_name . ' — 待审核';
			$lk_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->links WHERE link_name = %s", $lkname ) );
			if ( $lk_name ) {
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '网站名称已经存在，请勿重复申请！', 'begin' ) ) . '</p>';
			}

			$lk_url =  $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->links WHERE link_url = %s", $link_url ) );
			if ( $lk_url ){
				$errors .= '<p class="add-link-tip fd">' . sprintf( __( '网站链接已经存在，请勿重复申请！', 'begin' ) ) . '</p>';
			}

			if ( empty( $errors ) ) {
				$sql_link = $wpdb->prepare( "INSERT INTO $wpdb->links ( link_name, link_url, link_target, link_description, link_notes, link_visible ) VALUES ( %s, %s, %s, %s, %s, %s )",
					$link_name.' — 待审核',
					$link_url,
					$link_target,
					$link_description,
					$link_notes,
					$link_visible
				);

				$result = $wpdb->get_results( $sql_link );
				$errors .= '<p class="add-link-tip add-link-succeed fd">' . sprintf( __( '提交成功，请等待站长审核通过！', 'begin' ) ) . '</p>';
			}

			// 返回错误消息
			if ( ! empty( $errors ) ) {
				wp_send_json_error( $errors );
			}
		}

		// 返回成功消息
		wp_send_json_success( '提交成功，请等待站长审核通过！' );
	}

}