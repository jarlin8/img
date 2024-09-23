<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 信息表单
add_action( 'admin_init', 'add_ext_inf_meta_boxes', 1 );
function add_ext_inf_meta_boxes() {
	add_meta_box( 'ext-inf', '附加信息', 'ext_inf_meta_box_display', 'post', 'normal', 'high' );
	add_meta_box( 'ext-inf', '附加信息', 'ext_inf_meta_box_display', 'page', 'normal', 'high' );
	add_meta_box( 'ext-inf', '附加信息', 'ext_inf_meta_box_display', 'show', 'normal', 'high' );
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

	<table id="repea-fieldset-one" width="100%">
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
		<input type="submit" class="metabox_submit button" value="保存" />
	</p>
<?php }

add_action( 'save_post', 'ext_inf_meta_box_save' );
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

	$names = $_POST['name'];
	$infs = $_POST['inf'];

	$count = count( $names );

	for ( $i = 0; $i < $count; $i++ ) {
		if ( $names[$i] != '' ) :
			$new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );

		if ( $infs[$i] == '' )
			$new[$i]['inf'] = '';
		else
			$new[$i]['inf'] = stripslashes( $infs[$i] );
		endif;
	}

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'be_inf_ext', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'be_inf_ext', $old );
}


function inf_ext() {
	global $post;
	$be_inf_ext = get_post_meta( get_the_ID(), 'be_inf_ext', true );
	if ( $be_inf_ext ) {
		echo '<div class="inf-ext-box">';
		if ( get_post_meta( get_the_ID(), 'ext_inf_img', true ) ) {
			$image = get_post_meta( get_the_ID(), 'ext_inf_img', true );
			echo '<div class="inf-ext-img-box">';
			echo '<img class="inf-x" src="' . $image . '" alt="' . $post->post_title . '" />';
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

// 附加信息缩略图
$ext_inf_img_meta_boxes =
array(
	"ext_inf_img" => array(
		"name"    => "ext_inf_img",
		"std"     => "",
		"title"   => "仅用于附加信息",
		"type"    => "upload"
	)
);


function ext_inf_img_meta_boxes() {
	global $post, $ext_inf_img_meta_boxes;

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
		}
	}
}

function ext_inf_meta_box() {
	global $theme_name;
	if ( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'ext_inf_meta_box', '附加信息缩略图', 'ext_inf_img_meta_boxes', 'post', 'normal', 'high' );
		add_meta_box( 'ext_inf_meta_box', '附加信息缩略图', 'ext_inf_img_meta_boxes', 'page', 'normal', 'high' );
		add_meta_box( 'ext_inf_meta_box', '附加信息缩略图', 'ext_inf_img_meta_boxes', 'show', 'normal', 'high' );
	}
}

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

add_action( 'admin_menu', 'ext_inf_meta_box' );
add_action( 'save_post', 'save_ext_inf_data' );

// 正文
add_filter( 'the_content', 'be_ext_inf_content_beforde' );
function be_ext_inf_content_beforde( $content ) {
	global $ext;
	if ( ! is_feed() && ! is_home() && is_main_query() ) {
		$before_content = inf_ext();
		$ext = $before_content . $content;
	}
	return $ext;
}