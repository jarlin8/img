<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 信息表单
if ( cx_get_option( 'ext_inf_meta' ) ) {
	if ( ! cx_get_option( 'ext_inf_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_init', 'add_ext_inf_meta_boxes', 1 );
	}
}

function add_ext_inf_meta_boxes() {
	add_meta_box( 'ext-inf', '附加信息', 'ext_inf_meta_box_display', array( 'post', 'page', 'show' ), 'normal', 'high' );
}

function ext_inf_meta_box_display() {
	global $post;
	$be_inf_ext = get_post_meta( get_the_ID(), 'be_inf_ext', true );
	wp_nonce_field( 'ext_inf_meta_box_nonce', 'ext_inf_meta_box_nonce' );
?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.metabox_submit').click(function(e) {
			e.preventDefault();
			$('#publish').click();
		});
		$('#add-row').on('click', function() {
			var row = $('.empty-row.screen-reader-text').clone(true);
			row.removeClass('empty-row screen-reader-text');
			row.insertBefore('#repea-fieldset-one tbody>tr:last');
			return false;
		});
		$('.remove-row').on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});

		$('#repea-fieldset-one tbody').sortable({
			opacity: 0.6,
			revert: true,
			cursor: 'move',
			handle: '.sort'
		});
	});
	</script>

	<table id="repea-fieldset-one" width="97.5%">
		<thead>
			<tr>
				<th width="2%"></th>
				<th width="30%">名称</th>
				<th width="60%">信息</th>
				<th width="2%"></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( $be_inf_ext ) : ?>
				<?php foreach ( $be_inf_ext as $field ) { ?>
					<tr>
						<td><a class="button remove-row" href="#" style="padding: 0 6px;"><span class="dashicons dashicons-minus"></span></a></td>
						<td><input type="text" class="widefat" name="name[]" value="<?php if ( $field['name'] != '' ) echo esc_attr( $field['name'] ); ?>" /></td>
						<td><input type="text" class="widefat" name="inf[]" value="<?php if ( $field['inf'] != '' ) echo esc_attr( $field['inf'] ); else echo ''; ?>" /></td>
						<td><a class="sort"style="cursor: move;color: #999;"><span class="dashicons dashicons-move"></span></a></td>
					</tr>
				<?php } ?>
			<?php else : ?>
				<tr>
					<td><a class="button remove-row" href="#" style="padding: 0 6px;"><span class="dashicons dashicons-minus"></span></a></td>
					<td><input type="text" class="widefat" name="name[]" /></td>
					<td><input type="text" class="widefat" name="inf[]" value="" /></td>
					<td><a class="sort"style="cursor: move;color: #999;"><span class="dashicons dashicons-move"></span></a></td>
				</tr>
			<?php endif; ?>
			<tr class="empty-row screen-reader-text">
				<td><a class="button remove-row" href="#" style="padding: 0 6px;"><span class="dashicons dashicons-minus"></span></a></td>
				<td><input type="text" class="widefat" name="name[]" /></td>
				<td><input type="text" class="widefat" name="inf[]" value="" /></td>
				<td><a class="sort"style="cursor: move;color: #999;"><span class="dashicons dashicons-move"></span></a></td>
			</tr>
		</tbody>
	</table>
	<p>
		<a id="add-row" class="button" href="#" style="margin: 0 3px;padding: 0 6px;"><span class="dashicons dashicons-plus-alt2"></span></a>
		<input type="submit" class="metabox_submit button" value="更新" />
	</p>
	<p></p>

<?php 
	// 图片
	global $ext_inf_img_meta_boxes;
	foreach ( $ext_inf_img_meta_boxes as $meta_box ) {
		$meta_box_value = get_post_meta( get_the_ID(), $meta_box['name'] . '', true );
		if ( $meta_box_value != "" )
	
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
	
		switch ( $meta_box['type'] ) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /><a href="javascript:;" class="begin_file button">选择图片</a></span>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}

	// 按钮
	global $ext_inf_btn_meta_boxes;
	foreach ( $ext_inf_btn_meta_boxes as $meta_box ) {
		$meta_box_value = get_post_meta( get_the_ID(), $meta_box['name'] . '', true );
		if ( $meta_box_value != "" )
	
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
		switch ( $meta_box['type'] ) {
			case 'text':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span>';
				echo '</span>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function ext_inf_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['ext_inf_meta_box_nonce'] ) ||
		! wp_verify_nonce( $_POST['ext_inf_meta_box_nonce'], 'ext_inf_meta_box_nonce' ) )
		return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return;

	$old = get_post_meta( $post_id, 'be_inf_ext', true );
	$new = array();

	$names = isset( $_POST['name'] ) ? $_POST['name'] : array();
	$infs = isset( $_POST['inf'] ) ? $_POST['inf'] : array();

	// 如果 $_POST['name'] 和 $_POST['inf'] 中有任意一个存在，则添加数据
	if ( ! empty( $names ) || ! empty( $infs ) ) {
		$count = max( count( $names ), count( $infs ) );

		for ( $i = 0; $i < $count; $i++ ) {
			$name = isset( $names[$i] ) ? stripslashes( strip_tags( $names[$i] ) ) : '';
			$inf = isset( $infs[$i] ) ? stripslashes( $infs[$i] ) : '';

			if ( $name != '' || $inf != '' ) {
				$new[$i]['name'] = $name;
				$new[$i]['inf'] = $inf;
			}
		}
	}

	if ( ! empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'be_inf_ext', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'be_inf_ext', $old );


	global $ext_inf_img_meta_boxes;
	foreach ($ext_inf_img_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if ( get_post_meta( $post_id, $meta_box['name'] . '' ) == "" ) add_post_meta( $post_id, $meta_box['name'] . '', $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_box['name'] . '', true ) ) update_post_meta( $post_id, $meta_box['name'] . '', $data );
		elseif ( $data == "") delete_post_meta( $post_id, $meta_box['name'] . '', get_post_meta( $post_id, $meta_box['name'] . '', true ) );
	}

	global $ext_inf_btn_meta_boxes;
	foreach ($ext_inf_btn_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if ( get_post_meta( $post_id, $meta_box['name'] . '' ) == "" ) add_post_meta( $post_id, $meta_box['name'] . '', $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_box['name'] . '', true ) ) update_post_meta( $post_id, $meta_box['name'] . '', $data );
		elseif ( $data == "") delete_post_meta( $post_id, $meta_box['name'] . '', get_post_meta( $post_id, $meta_box['name'] . '', true ) );
	}
}

add_action( 'save_post', 'ext_inf_meta_box_save' );

// 附加信息缩略图
$ext_inf_img_meta_boxes =
array(
	"ext_inf_img" => array(
		"name"    => "ext_inf_img",
		"std"     => "",
		"title"   => "图片",
		"type"    => "upload"
	),
);

function save_ext_inf_data($post_id) {
	global $post, $ext_inf_img_meta_boxes;
	foreach ($ext_inf_img_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if ( get_post_meta( $post_id, $meta_box['name'] . '' ) == "" ) add_post_meta( $post_id, $meta_box['name'] . '', $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_box['name'] . '', true ) ) update_post_meta( $post_id, $meta_box['name'] . '', $data );
		elseif ( $data == "") delete_post_meta( $post_id, $meta_box['name'] . '', get_post_meta( $post_id, $meta_box['name'] . '', true ) );
	}
}

add_action( 'save_post', 'save_ext_inf_data' );

// 附加信息按钮
$ext_inf_btn_meta_boxes =
array(
	"inf_btn" => array(
		"name"    => "inf_btn",
		"std"     => "",
		"title"   => "按钮名称",
		"type"    => "text"
	),

	"inf_btn_url" => array(
		"name"  => "inf_btn_url",
		"std"   => "",
		"title" => "按钮链接",
		"type"  => "text"
	),
	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),

);

function save_btn_inf_data($post_id) {
	global $post, $ext_inf_btn_meta_boxes;
	foreach ($ext_inf_btn_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if ( get_post_meta( $post_id, $meta_box['name'] . '' ) == "" ) add_post_meta( $post_id, $meta_box['name'] . '', $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_box['name'] . '', true ) ) update_post_meta( $post_id, $meta_box['name'] . '', $data );
		elseif ( $data == "") delete_post_meta( $post_id, $meta_box['name'] . '', get_post_meta( $post_id, $meta_box['name'] . '', true ) );
	}
}

add_action( 'save_post', 'save_btn_inf_data' );

// 信息
function inf_ext() {
	global $post;
	$be_inf_ext = get_post_meta( get_the_ID(), 'be_inf_ext', true );
	if ( $be_inf_ext ) {
		echo '<div class="inf-ext-box">';
		if ( get_post_meta( get_the_ID(), 'ext_inf_img', true ) ) {
			$image = get_post_meta( get_the_ID(), 'ext_inf_img', true );
			echo '<div class="inf-ext-img-box">';
			echo '<img class="inf-x" src="' . $image . '" alt="' . $post->post_title . '">';
			echo '</div>';
			echo '<div class="inf-ext-content-img">';
		} else {
			echo '<div class="inf-ext-content">';
		}
		foreach ( $be_inf_ext as $field ) {
			echo '<div class="inf-ext-list">';
			if ( $field['name'] != '' ) echo '<span class="ext-name">'. esc_attr( $field['name'] ) . '</span>';
			if ( $field['inf'] != '' ) echo '<span class="ext-inf">'. esc_attr( $field['inf'] ) . '</span>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
	}
}

// 正文调用
add_filter( 'the_content', 'be_ext_inf_content_beforde' );
function be_ext_inf_content_beforde( $content ) {
	if ( ! is_home() && is_main_query() ) {
		if ( ! is_feed() ) {
			$before_content = inf_ext();
		} else {
			$before_content = '';
		}
		return $before_content . $content;
	}
}

// 简介信息
function inf_bio() {
	global $post;
	$be_inf_ext = get_post_meta( get_the_ID(), 'be_inf_ext', true );
	if ( $be_inf_ext ) {
		echo '<div class="inf-bio-box">';
			if ( get_post_meta( get_the_ID(), 'ext_inf_img', true ) ) {
				$image = get_post_meta( get_the_ID(), 'ext_inf_img', true );
				echo '<div class="inf-bio-content-img bio-content">';
					echo '<img class="inf-x" src="' . $image . '" alt="' . $post->post_title . '">';
					echo '</div>';
			}
			echo '<div class="inf-bio-content bio-content">';
				echo '<h1 class="bio-entry-title">' . get_the_title(  ) . '</h1>';
				begin_single_meta();
				echo '<div class="inf-bio-list-content">';
					foreach ( $be_inf_ext as $field ) {
						echo '<div class="inf-bio-list">';
							if ( $field['name'] != '' ) echo '<span class="bio-name">'. esc_attr( $field['name'] ) . '</span>';
							if ( $field['inf'] != '' ) echo '<span class="bio-inf">'. esc_attr( $field['inf'] ) . '</span>';
						echo '</div>';
					}
				echo '</div>';
				if ( get_post_meta( get_the_ID(), 'inf_btn', true ) ) {
					$inf_btn = get_post_meta( get_the_ID(), 'inf_btn', true );
					$inf_btn_url = get_post_meta( get_the_ID(), 'inf_btn_url', true );
					echo '<div class="inf-bio-btn">';
					echo '<a href="' . $inf_btn_url . '" rel="external nofollow" target="_blank">' . $inf_btn . '</a>';
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	}
}