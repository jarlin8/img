<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 文章始终保持在文本编辑模式
// 添加添加面板和保存选项
if ( is_admin() ) {
	add_action( 'admin_init', 'be_edit_in_html_create_options_box' );
	add_action( 'admin_head', 'be_edit_in_html_handler' );
	add_action( 'save_post', 'be_edit_in_html_save_postdata', $post );
}

// 关闭可视化
function be_edit_in_html_handler() {
	global $post;

	// 检查这里是否有post对象，否则返回
	if ( $post == null ) return;

	// 获取meta值并检查它是否已打开文本模式
	$editInHTML = be_edit_in_html_get_html_edit_status( $post->ID );
	if ( $editInHTML ){
		// 隐藏“可视化”选项卡
		echo '<style type="text/css">';
		echo '#content-tmce.wp-switch-editor.switch-tmce{display:none;}';
		echo '</style>';

		// 将编辑器设置为文本模式
		add_filter( 'wp_default_editor', function () {
			return 'html';
		});
	}
}

// 将选项面板添加到所有编辑页面中
function be_edit_in_html_create_options_box() {
	// 添加到页面
	add_meta_box( 'be-edit-in-html', '文本模式', 'be_edit_in_html_custom_box', 'page', 'side' );

	// 添加到文章
	add_meta_box( 'be-edit-in-html', '文本模式', 'be_edit_in_html_custom_box', 'post', 'side' );

	// 添加到所有文章类型
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$post_types = get_post_types( $args, 'names' );

	foreach ( $post_types as $post_type ) {
		add_meta_box( 'be-edit-in-html', '文本模式', 'be_edit_in_html_custom_box', $post_type,'side' );
	}
}

//创建选项框
function be_edit_in_html_custom_box( $post ){
	// 检查数据是否来自当前文章
	wp_nonce_field( basename( __FILE__ ), 'be_edit_in_html_noncename' );

	// 获取当前文章的当前状态
	$editInHTML = be_edit_in_html_get_html_edit_status( $post->ID );

	// 功能说明
	echo '<p>当前文章始终保持在文本编辑模式</p>';
	echo '<label class="selectit for="be_edit_in_html">';
	echo '<input class="checkbox" type="checkbox" id="be_edit_in_html" name="be_edit_in_html" value="on" ';

	if ( $editInHTML ){
		echo 'checked="checked"';
	}
	echo ' />';
	echo '文本模式</label>';
}

// 获取总是在HTML中编辑选项字段，并检查它是否已设置
function be_edit_in_html_get_html_edit_status( $id ) {
	$editInHTML=get_post_meta( $id, 'editInHTML', true );

	if ( $editInHTML === "on" ) {
		return true;
	} else {
		return false;
	}
}

// 保存选项
function be_edit_in_html_save_postdata( $post_id ) {
	// 快速检查以确保数据属于当前文章
	if ( !isset($_POST['be_edit_in_html_noncename']) || !wp_verify_nonce( $_POST['be_edit_in_html_noncename'], basename(__FILE__) ) ) {
		return $post_id;
	}

	// 对于自动保存操作不执行任何操作
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// 检测有更新文章的权限
	if ( 'page' === $_POST['post_type'] ){
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	// 所有检查完成，保存选项。
	if ( isset( $_POST['be_edit_in_html'] ) ) {
		update_post_meta( $post_id, 'editInHTML', 'on' );
		} else {
		//update_post_meta( $post_id, 'editInHTML', 'off' );
		delete_post_meta( $post_id, 'editInHTML' );
	}

	// 返回 $post_id 以保留其他过滤器。
	return $post_id;
}