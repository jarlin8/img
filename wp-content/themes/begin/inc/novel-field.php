<?php 
// 分类添加字段
if ( ! defined( 'ABSPATH' ) ) exit;
function add_novel_field() {
	echo '<div class="form-field">
			<label for="cat-author">作者</label>
			<input name="cat-author" id="cat-author" type="text" value="" size="40">
			<p>仅用于书籍信息</p>
		</div>';

	echo '<div class="form-field">
			<label for="cat-status">状态</label>
			<input name="cat-status" id="cat-status" type="text" value="" size="40">
			<p>仅用于书籍信息</p>
		</div>';
}
add_action( 'category_add_form_fields', 'add_novel_field', 10, 2 );

// 分类编辑字段
function edit_novel_field( $tag ) {
	echo '<tr class="form-field">
			<th scope="row"><label for="cat-author">作者</label></th>
			<td>
				<input name="cat-author" id="cat-author" type="text" value="';
				echo get_option( 'cat-author-' . $tag->term_id ) . '" size="40"/><br>
				<span class="cat-author">用于' . $tag->name . '书籍信息</span>
			</td>
		</tr>';

	echo '<tr class="form-field">
			<th scope="row"><label for="cat-status">状态</label></th>
			<td>
				<input name="cat-status" id="cat-status" type="text" value="';
				echo get_option( 'cat-status-' . $tag->term_id ) . '" size="40"/><br>
				<span class="cat-status">用于' . $tag->name . '书籍信息</span>
			</td>
		</tr>';
}
add_action( 'category_edit_form_fields', 'edit_novel_field', 10, 2 );

function novel_meta_date( $term_id ) {
	if ( isset( $_POST['cat-author'] ) && isset( $_POST['cat-status'] ) ) {

		if ( ! current_user_can( 'manage_categories' ) ) {
			return $term_id;
		}

		$author_key = 'cat-author-' . $term_id; // key
		$author_value = $_POST['cat-author']; // value

		$status_key = 'cat-status-' . $term_id;
		$status_value = $_POST['cat-status'];

		update_option( $author_key, $author_value );
		update_option( $status_key, $status_value );
	}
}

add_action( 'created_category', 'novel_meta_date', 10, 1 );
add_action( 'edited_category', 'novel_meta_date', 10, 1 );