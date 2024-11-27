<?php
// 信息提交
if ( ! defined( 'ABSPATH' ) ) exit;
function inf_start_output_buffers() {
	ob_start();
}
add_action( 'init', 'inf_start_output_buffers' );

function inf_messages() {
	$error_title = sprintf( __( '带星号的必填', 'begin' ) );
	$inf_messages = array(
		'unsaved_changes_warning'      => sprintf( __( '您有未保存的更改，继续吗?', 'begin' ) ),
		'confirmation_message'         => sprintf( __( '您确定吗?', 'begin' ) ),
		'required_field_error'         => $error_title,
		'general_form_error'           => '',
	);
	return $inf_messages;
}

function inf_enqueue_files( $posts ) {
	if ( ! is_main_query() || empty( $posts ) )
		return $posts;

	$found = false;
	foreach ( $posts as $post ) {
		if ( has_shortcode( $post->post_content, 'bet_article_list' ) || has_shortcode( $post->post_content, 'bet_submission_info' ) ) {
			$found = true;
			break;
		}
	}

	if ( $found ) {
		wp_enqueue_style( 'bet-style', get_template_directory_uri() . '/css/toug.css', array(), version );
		wp_enqueue_script( 'bet-profile', get_template_directory_uri() . '/js/bet-profile.js', array( 'jquery' ) );
		wp_localize_script( 'bet-profile', 'betajaxhandler', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		$bet_rules['check_required'] = true;
		wp_localize_script( 'bet-profile', 'bet_rules', $bet_rules );
		wp_localize_script( 'bet-profile', 'inf_messages', inf_messages() );
		wp_enqueue_media();
	}
	return $posts;
}
if ( ! is_admin() ) {
	add_action( 'the_posts', 'inf_enqueue_files' );
}

// shortcode
if ( ! function_exists( 'has_shortcode' ) ) {
	function has_shortcode( $content, $tag ) {
		if ( stripos($content, '[' . $tag . ']') !== false )
			return true;
		return false;
	}
}


// Ajax deleting post
function inf_delete_posts() {
	try {
		if ( ! wp_verify_nonce( $_POST['delete_nonce'], 'betnonce_delete_action') )
			throw new Exception( sprintf( __( '未通过安全检查', 'begin' ) ), 1 );

		if ( ! current_user_can( 'delete_post', $_POST['post_id'] ) )
			throw new Exception( sprintf( __( '无删除权限', 'begin' ) ), 1 );

		$result = wp_delete_post( $_POST['post_id'], true );
		if ( ! $result )
			throw new Exception(sprintf(__( '未删除', 'begin' ) ), 1);

		$data['success'] = true;
		$data['message'] = sprintf( __( '已成功删除!', 'begin' ) );
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = $ex->getMessage();
	}
	die( json_encode( $data ) );
}

add_action( 'wp_ajax_inf_delete_posts', 'inf_delete_posts' );
add_action( 'wp_ajax_nopriv_inf_delete_posts', 'inf_delete_posts' );

// Ajax new post
function inf_process_form_input() {
	$inf_messages = inf_messages();
	try {
		if ( ! wp_verify_nonce( $_POST['post_nonce'], 'betnonce_action' ) )
			throw new Exception(
				sprintf( __( '未通过安全检查', 'begin' ) ),
				1
			);

		if ( $_POST['post_id'] != -1 && !current_user_can( 'edit_post', $_POST['post_id'] ) )
			throw new Exception(
				sprintf( __( '无编辑权限', 'begin' ) ),
				1
			);

		if ( ! empty( $errors ) ) {
			throw new Exception( $errors, 1 );
		}

		$post_content = $_POST['post_content'];

		$current_post = empty( $_POST['post_id'] ) ? null : get_post( $_POST['post_id'] );
		$current_post_date = is_a( $current_post, 'WP_Post' ) ? $current_post->post_date : '';


			$post_type = 'bulletin';
			$post_category = array( cx_get_option( 'info_cat' ) );


		$new_post = array(
			'post_title'     => sanitize_text_field( $_POST['post_title'] ),
			'post_category'  => $post_category,
			'tags_input'     => sanitize_text_field( $_POST['post_tags'] ),
			'post_content'   => wp_kses_post( $post_content ),
			'post_date'      => $current_post_date,
			'post_type'      => $post_type,
			'tax_input'      => array(
					'notice' => array( cx_get_option( 'info_cat' ) )
			),
			'comment_status' => get_option( 'default_comment_status' ),
		);


		if ( ! cx_get_option( 'info_pending' ) ) {
			$post_action = __( 'published', 'frontend-publishing' );
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __( 'submitted', 'frontend-publishing' );
			$new_post['post_status'] = 'pending';
		}

		if ( $_POST['post_id'] != -1 ) {
			$new_post['ID'] = $_POST['post_id'];
			$post_action = __( 'updated', 'frontend-publishing' );
		}

		$new_post_id = wp_insert_post( $new_post, true );
		if ( is_wp_error( $new_post_id ) )
			throw new Exception( $new_post_id->get_error_message(), 1 );

		if ( isset( $_POST['featured_img'] ) && $_POST['featured_img'] != -1 ) {
			set_post_thumbnail( $new_post_id, $_POST['featured_img'] );
		}

		// 添加字段
		add_post_meta( $new_post_id, 'profile_name', $_POST['post_title'], true );

		$form = ( array ) cx_get_option( 'message_form_add' );
		foreach ( $form as $items ) {
			add_post_meta( $new_post_id, 'profile_' . $items['message_number'], $_POST['info_' . $items['message_number']], true );
		}

		// 添加作者
		global $user_identity;
		add_post_meta( $new_post_id, 'postauthor', $user_identity, true );

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf( '<div class="tou-success"><strong>%s</strong></div>', sprintf( __( '提交成功!', 'begin' ) ) );
		$data['message'] .= sprintf( '<div class="tou-success">');
		if ( cx_get_option( 'info_pending' ) || user_can( get_current_user_id(), 'administrator' ) ) {
			$data['message'] .= sprintf( '<a href="#" id="bet-continue-editing">%s</a>', sprintf( __( '编辑', 'begin' ) ) );
		}
		$data['message'] .= sprintf( '<button id="bet-submit-post" class="btn-continue btn-continue-quit" onclick="closetou()">%s</button>', sprintf( __( '关闭', 'begin' ) ) );
		$data['message'] .= sprintf( '<button id="bet-submit-post" class="btn-continue closeto" type="button" onclick="renovates()">%s</button> ', sprintf( __( '继续', 'begin' ) ) );
		$data['message'] .= sprintf( '</div><div class="clear-vh"></div>' );
	}
	catch ( Exception $ex ) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong>%s',
			$inf_messages['general_form_error'],
			$ex-> getMessage()
		);
	}
	die( json_encode( $data ) );
}

add_action( 'wp_ajax_inf_process_form_input', 'inf_process_form_input' );
add_action( 'wp_ajax_nopriv_inf_process_form_input', 'inf_process_form_input' );

function info_form() {
$current_user = wp_get_current_user();
$post = false;
$post_id = -1;
$featured_img_html = '';
if ( isset( $_GET['bet_id'] ) && isset( $_GET['bet_action'] ) && $_GET['bet_action'] == 'edit' ) {
	$post_id = $_GET['bet_id'];
	$p = get_post( $post_id, 'ARRAY_A' );
	if ( $p['post_author'] != $current_user->ID ) return __( "无编辑权限", 'begin' );
	$category = get_the_category( $post_id );
	$tags = wp_get_post_tags( $post_id, array( 'fields' => 'names' ) );
	$featured_img = get_post_thumbnail_id( $post_id );
	$featured_img_html = ( ! empty( $featured_img ) ) ? wp_get_attachment_image( $featured_img, array( 200, 200 ) ) : '';
	$post = array(
		'title'            => $p['post_title'],
		'content'          => $p['post_content'],
		'about_the_author' => get_post_meta( $post_id, 'about_the_author', true )
	);
	if ( isset( $category[0] ) && is_array( $category ) )
		$post['category'] = $category[0]->cat_ID;
	if ( isset( $tags ) && is_array( $tags ) )
		$post['tags'] = implode( ', ', $tags );
}
?>

<div id="bet-new-post" class="bet-tougao">
	<form id="bet-submission-form">
		<div class="info-submit">
			<p class="p-info">
				<label for="bet-post-title"><?php echo cx_get_option( 'message_name' ); ?><i class="be be-star"></i></label>
				<input type="text" name="post_title" id="bet-post-title" class="info-type" value="<?php echo ( $post ) ? $post['title'] : ''; ?>">
			</p>

			<?php 
				$form = ( array ) cx_get_option( 'message_form_add' );
				foreach ( $form as $items ) {
			?>
			
				<?php if ( empty( $items['message_select'] ) ) { ?>
					<p class="p-info">
						<label for="bet-info-<?php echo $items['message_number']; ?>"></i><?php echo $items['message_title']; ?></label>
						<input type="text" name="info_<?php echo $items['message_number']; ?>" id="bet-info-<?php echo $items['message_number']; ?>" class="info-type" value="">
					</p>
				<?php } else { ?>
					<p class="p-info">
						<label for="bet-info-<?php echo $items['message_number']; ?>" class="info-s"><?php echo $items['message_select']; ?></label>
						<select class="of-input s-veil" name="info_<?php echo $items['message_number']; ?>" id="bet-info-<?php echo $items['message_number']; ?>" class="info-type">
							<option value=""><?php if ( empty( $items['message_text'] ) ) { ?><?php _e( '未选择', 'begin' ); ?><?php } else { ?><?php echo $items['message_text']; ?><?php } ?></option>
							<?php foreach ( $items['message_option'] as $select ) { ?>
								<option value="<?php echo $select['select_value']; ?>"><?php echo $select['select_value']; ?></option>
							<?php } ?>
						</select>
					</p>
					<div class="clear"></div>
				<?php } ?>
	
			<?php } ?>

			<label for="bet-post-conten"><?php _e( '备注', 'begin' ); ?></label><br />
			<?php
				$toolbar1 = apply_filters( 'info_tinymce_toolbar1', 'fontsizeselect, bold, forecolor, undo,redo, link,unlink, image, code, spellchecker, fullscreen, dwqaCodeEmbed' );
				wp_editor( '', 'bet-post-content',
					$settings = array(
						'textarea_name' => 'post_content',
						'editor_class'  => 'toug',
						'media_buttons' => 0, 
						'textarea_rows' => 5,
						'tinymce'       => array(
							'toolbar1'  => $toolbar1,
							'toolbar2'  => ''
						),
						'quicktags'     => true
					) 
				);
				wp_nonce_field( 'betnonce_action', 'betnonce' );
			?>

			<div class="tou-cat bet-category">

				<select name="post_category" id="bet-category" style="display: none;"></select>
			</div>

			<div class="tou-cat bet-tags">
				<input type="text" name="post_tags" id="bet-tags" value="<?php echo ( $post ) ? $post['tags'] : ' '; ?>" hidden>
			</div>
		</div>
		<input type="hidden" name="post_id" id="bet-post-id" value="<?php echo $post_id ?>">
		<button type="button" id="bet-submit-post" class="active-btn bet-btn<?php echo cur(); ?>"><?php _e( '提 交', 'begin' ); ?></button>
		<div class="clear"></div>
	</form>
	<div class="clear"></div>
	<div id="bet-message" class="warning"></div>
</div>
<script type="text/javascript">function renovates(){ document.location.reload();}</script>
<?php }


function bet_add_new_info() {
	if ( ! cx_get_option( 'no_logged_in' ) ) {
		if ( ! is_user_logged_in() ) {
			return sprintf(
				'<div class="tou-login-tip">' . sprintf( __( '注册登录后方可提交信息', 'begin' ) ) . '</div>
				%s ',
				// '<a href="' . wp_login_url(get_permalink()) . '" title="登录">登录</a>'
				'<div class="toubtn show-layer">'.sprintf( __( '立即登录', 'begin' ) ) . '</div>'
			);
		}
	}

	ob_start();
	info_form();
	return ob_get_clean();
}

add_shortcode( 'bet_submission_info', 'bet_add_new_info' );