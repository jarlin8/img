<?php
function bet_start_output_buffers() {
	ob_start();
}
add_action('init', 'bet_start_output_buffers');

function bet_messages() {
	if (zm_get_option('tougao_mode') == 'info_mode') {
		$error_title = '必须输入姓名';
	} else {
		$error_title = '必须输入标题';
	}

	$bet_messages = array(
		'unsaved_changes_warning'      => '您有未保存的更改。继续吗?',
		'confirmation_message'         => '您确定吗?',
		'required_field_error'         => $error_title,
		'general_form_error'           => '提示：',
	);
	return $bet_messages;
}

function bet_enqueue_files($posts) {
	if (!is_main_query() || empty($posts))
		return $posts;

	$found = false;
	foreach ($posts as $post) {
		if (has_shortcode($post->post_content, 'bet_article_list') || has_shortcode($post->post_content, 'bet_submission_form')) {
			$found = true;
			break;
		}
	}

	if ($found) {
		wp_enqueue_style( 'bet-style', get_template_directory_uri() . '/css/toug.css', array(), version );
		wp_enqueue_script( 'bet-script', get_template_directory_uri() . '/js/publish-scripts.js', array( 'jquery' ) );
		wp_localize_script('bet-script', 'betajaxhandler', array('ajaxurl' => admin_url('admin-ajax.php')));
		$bet_rules['check_required'] = true;
		wp_localize_script('bet-script', 'bet_rules', $bet_rules);
		wp_localize_script('bet-script', 'bet_messages', bet_messages());
		wp_enqueue_media();
	}
	return $posts;
}

add_action('the_posts', 'bet_enqueue_files');

// shortcode
if (!function_exists('has_shortcode')) {
	function has_shortcode($content, $tag) {
		if (stripos($content, '[' . $tag . ']') !== false)
			return true;
		return false;
	}
}

// Ajax  featured image
function bet_fetch_featured_image() {
	$image_id = $_POST['img'];
	echo wp_get_attachment_image($image_id, array(200, 200));
	die();
}

add_action('wp_ajax_bet_fetch_featured_image', 'bet_fetch_featured_image');

// Ajax deleting post
function bet_delete_posts() {
	try {
		if (!wp_verify_nonce($_POST['delete_nonce'], 'betnonce_delete_action'))
			throw new Exception(sprintf(__( '您没有通过安全检查', 'begin' )), 1);

		if (!current_user_can('delete_post', $_POST['post_id']))
			throw new Exception(sprintf(__( '您没有权限删除这篇文章', 'begin' )), 1);

		$result = wp_delete_post($_POST['post_id'], true);
		if (!$result)
			throw new Exception(sprintf(__( '这篇文章不能被删除', 'begin' )), 1);

		$data['success'] = true;
		$data['message'] = sprintf(__( '这篇文章已被成功删除!', 'begin' ));
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = $ex->getMessage();
	}
	die(json_encode($data));
}

add_action('wp_ajax_bet_delete_posts', 'bet_delete_posts');
add_action('wp_ajax_nopriv_bet_delete_posts', 'bet_delete_posts');

// Ajax new post
function bet_process_form_input() {
	$bet_messages = bet_messages();
	try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'betnonce_action'))
			throw new Exception(
				sprintf(__( '您没有通过安全检查', 'begin' )),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', $_POST['post_id']))
			throw new Exception(
				sprintf(__( '您没有权限编辑这篇文章', 'begin' )),
				1
			);

		if ($errors)
			throw new Exception($errors, 1);

			$post_content = $_POST['post_content'];

		$current_post = empty($_POST['post_id']) ? null : get_post($_POST['post_id']);
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		if (zm_get_option('tougao_mode') == 'info_mode' && zm_get_option('submit_bulletin')) {
			$post_type = 'bulletin';
			$post_category = array(zm_get_option('info_cat'));
		} else {
			$post_type = 'post';
			$post_category = array(zm_get_option('info_cat'));
		}

		if (zm_get_option('tougao_mode') == 'info_mode') {
			$new_post = array(
				'post_title'     => sanitize_text_field($_POST['post_title']),
				'post_category'  => $post_category,
				'tags_input'     => sanitize_text_field($_POST['post_tags']),
				'post_content'   => wp_kses_post($post_content),
				'post_date'      => $current_post_date,
				'post_type'     => $post_type,
				'tax_input'     => array(
						'notice' => array( zm_get_option('info_cat') )
					),
				'comment_status' => get_option('default_comment_status'),
			);
		} else {
			$new_post = array(
				'post_title'     => sanitize_text_field($_POST['post_title']),
				'post_category'  => array($_POST['post_category']),
				'tags_input'     => sanitize_text_field($_POST['post_tags']),
				'post_content'   => wp_kses_post($post_content),
				'post_date'      => $current_post_date,
				'comment_status' => get_option('default_comment_status'),
			);
		}

		if (zm_get_option('instantly_publish')) {
			$post_action = __('published', 'frontend-publishing');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'frontend-publishing');
			$new_post['post_status'] = 'pending';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = $_POST['post_id'];
			$post_action = __('updated', 'frontend-publishing');
		}

		$new_post_id = wp_insert_post($new_post, true);
		if (is_wp_error($new_post_id))
			throw new Exception($new_post_id->get_error_message(), 1);

		if ($_POST['featured_img'] != -1)
			set_post_thumbnail($new_post_id, $_POST['featured_img']);

		if (zm_get_option('tougao_mode') == 'info_mode') {
			add_post_meta($new_post_id, 'message_a', $_POST['post_title'], true);
			if( $_POST['info_b'] )
			add_post_meta($new_post_id, 'message_b', $_POST['info_b'], true);
			if( $_POST['info_c'] )
			add_post_meta($new_post_id, 'message_c', $_POST['info_c'], true);
			if( $_POST['info_d'] )
			add_post_meta($new_post_id, 'message_d', $_POST['info_d'], true);
			if( $_POST['info_e'] )
			add_post_meta($new_post_id, 'message_e', $_POST['info_e'], true);
			if( $_POST['info_f'] )
			add_post_meta($new_post_id, 'message_f', $_POST['info_f'], true);
		}

		global $user_identity;
		add_post_meta($new_post_id, 'postauthor', $user_identity, true);

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="bet-continue-editing">%s</a><br/>
			<div class="clear"></div>
			<button id="bet-submit-post" class="btn-continue" onclick="closetou()">'. sprintf(__( '关闭', 'begin' )) .'</button>
			<button id="bet-submit-post" class="btn-continue closeto" type="button" onclick="renovates()">'. sprintf(__( '继续', 'begin' )) .'</button>
			<div class="clear-vh"></div>',
			'<div class="tou-success"><strong>'.sprintf(__('提交成功!', 'begin').'</strong>', $post_action), sprintf(__( '编辑', 'begin' )).'</div>'
		);
	}
	catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$bet_messages['general_form_error'],
			$ex-> getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_bet_process_form_input', 'bet_process_form_input');
add_action('wp_ajax_nopriv_bet_process_form_input', 'bet_process_form_input');

// shortcodes
function bet_add_new_post() {
	if (!is_user_logged_in()) {
		return sprintf(
			'<div class="tou-login-tip">'.sprintf(__( '注册登录后方可发表文章', 'begin' )).'</div>
			%s ',
			// '<a href="' . wp_login_url(get_permalink()) . '" title="登录">登录</a>'
			'<div class="toubtn show-layer" data-show-layer="login-layer" role="button">'.sprintf(__( '立即登录', 'begin' )).'</div>'
		);
	}

	ob_start();
	require get_template_directory() . '/inc/frontend-publish-form.php';
	return ob_get_clean();
}

add_shortcode('bet_submission_form', 'bet_add_new_post');