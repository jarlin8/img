<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function bet_start_output_buffers() {
	ob_start();
}
add_action( 'init', 'bet_start_output_buffers' );

function bet_messages() {
	$error_title = sprintf( __( '必须填写标题', 'begin' ) );
	$bet_messages = array(
		'unsaved_changes_warning'      => sprintf( __( '您有未保存的更改，继续吗?', 'begin' ) ),
		'confirmation_message'         => sprintf( __( '您确定吗?', 'begin' ) ),
		'required_field_error'         => $error_title,
		'general_form_error'           => '',
	);
	return $bet_messages;
}

function bet_enqueue_files( $posts ) {
	if ( ! is_main_query() || empty( $posts ) )
		return $posts;

	$found = false;
	foreach ( $posts as $post ) {
		if ( has_shortcode( $post->post_content, 'bet_article_list' ) || has_shortcode( $post->post_content, 'bet_submission_form' ) ) {
			$found = true;
			break;
		}
	}

	if ( $found ) {
		wp_enqueue_style( 'bet-style', get_template_directory_uri() . '/css/toug.css', array(), version );
		wp_enqueue_script( 'bet-publish', get_template_directory_uri() . '/js/bet-publish.js', array( 'jquery' ) );
		wp_localize_script( 'bet-publish', 'betajaxhandler', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		$bet_rules['check_required'] = true;
		wp_localize_script( 'bet-publish', 'bet_rules', $bet_rules );
		wp_localize_script( 'bet-publish', 'bet_messages', bet_messages() );
		wp_enqueue_media();
	}
	return $posts;
}
if ( ! is_admin() ) {
	add_action( 'the_posts', 'bet_enqueue_files' );
}

// shortcode
if ( ! function_exists( 'has_shortcode' ) ) {
	function has_shortcode( $content, $tag ) {
		if ( stripos($content, '[' . $tag . ']') !== false )
			return true;
		return false;
	}
}

// Ajax  featured image
function bet_fetch_featured_image() {
	$image_id = $_POST['img'];
	echo wp_get_attachment_image( $image_id, array( 200, 200 ) );
	die();
}

add_action( 'wp_ajax_bet_fetch_featured_image', 'bet_fetch_featured_image' );

// Ajax deleting post
function bet_delete_posts() {
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

add_action( 'wp_ajax_bet_delete_posts', 'bet_delete_posts' );
add_action( 'wp_ajax_nopriv_bet_delete_posts', 'bet_delete_posts' );

// Ajax new post
function bet_process_form_input() {
	$bet_messages = bet_messages();
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

		$new_post = array(
			'post_title'     => sanitize_text_field( $_POST['post_title'] ),
			'post_category'  => array( $_POST['post_category'] ),
			'tags_input'     => sanitize_text_field( $_POST['post_tags'] ),
			'post_content'   => wp_kses_post( $post_content ),
			'post_date'      => $current_post_date,
			'comment_status' => get_option( 'default_comment_status' ),
		);

		if ( zm_get_option( 'no_pending' ) ) {
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

		global $user_identity;
		add_post_meta( $new_post_id, 'postauthor', $user_identity, true );

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf( '<div class="tou-success"><strong>%s</strong></div>', sprintf( __( '提交成功!', 'begin' ) ) );
		$data['message'] .= sprintf( '<div class="tou-success">');
		if ( ! zm_get_option( 'no_pending' ) || user_can( get_current_user_id(), 'administrator' ) ) {
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
			$bet_messages['general_form_error'],
			$ex-> getMessage()
		);
	}
	die( json_encode( $data ) );
}

add_action( 'wp_ajax_bet_process_form_input', 'bet_process_form_input' );
add_action( 'wp_ajax_nopriv_bet_process_form_input', 'bet_process_form_input' );

// 表单
function post_form() {
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
		<div class="be-tou-editor">
			<label for="bet-post-title" class="tougao-title"><?php _e( '标题', 'begin' ); ?></label>
			<input type="text" name="post_title" id="bet-post-title" class="wyc" value="<?php echo ($post) ? $post['title'] : ''; ?>">
			<?php
				wp_editor( '', 'bet-post-content', $settings = array('textarea_name' => 'post_content', 'editor_class' => 'toug', 'textarea_rows' => 15, 'media_buttons' => 1 ) );
				wp_nonce_field( 'betnonce_action', 'betnonce' );
			?>
		</div>

		<div class="be-tou-inf">
			<div class="tou-cat bet-category" <?php aos_a(); ?>>
				<label for="bet-category"><?php _e( '分类', 'begin' ); ?></label>
				<?php 
					if ( zm_get_option( 'not_front_cat' ) ) {
						$notcat = implode( ',', zm_get_option( 'not_front_cat' ) );
					} else {
						$notcat = '';
					}

					wp_dropdown_categories( array(
						'id' => 'bet-category',
						'class' => 's-veil',
						'hide_empty' => 0,
						'name' => 'post_category',
						'orderby' => 'name',
						'selected' => 0,
						'hierarchical' => true,
						'exclude' => explode( ',', $notcat )
					) );
				?>
				<div class="clear"></div>
			</div>

			<div class="tou-cat bet-tags">
				<label for="bet-tags"><?php _e( '标签', 'begin' ); ?></label>
				<input type="text" name="post_tags" id="bet-tags" class="wyc" value="<?php echo ( $post ) ? $post['tags'] : ' '; ?>">
				<div class="clear"></div>
			</div>
	
			<div class="clear"></div>
			<?php if ( zm_get_option( 'thumbnail_required' ) ) { ?>
				<div id="bet-featured-image">
					<div id="bet-featured-image-container"><?php echo $featured_img_html; ?></div>
					<div class="clear"></div>
					<a id="bet-featured-image-link" href="#"><?php _e( '特色图像', 'begin' ); ?></a>
					<input type="hidden" id="bet-featured-image-id" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
				</div>
			<?php } ?>
			<input type="hidden" name="post_id" id="bet-post-id" value="<?php echo $post_id ?>">
			<button type="button" id="bet-submit-post" class="active-btn bet-btn<?php echo cur(); ?>"><?php _e( '发表', 'begin' ); ?></button>
		</div>
		<div class="clear"></div>
	</form>

	<div id="bet-message" class="warning"></div>
</div>
<script type="text/javascript">function renovates(){ document.location.reload();}</script>
<?php }

// shortcodes
function bet_add_new_post() {
	if ( ! is_user_logged_in() ) {
		return sprintf(
			'<div class="tou-login-tip">' . sprintf( __( '注册登录后方可发表文章', 'begin' ) ) . '</div>
			%s ',
			// '<a href="' . wp_login_url(get_permalink()) . '" title="登录">登录</a>'
			'<div class="toubtn show-layer">'.sprintf( __( '立即登录', 'begin' ) ) . '</div>'
		);
	}

	ob_start();
	post_form();
	return ob_get_clean();
}

add_shortcode( 'bet_submission_form', 'bet_add_new_post' );