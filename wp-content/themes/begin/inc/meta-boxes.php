<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 文章SEO
$seo_post_meta_boxes =
array(
	"seo_help"  => array(
		"name"  => "seo_help",
		"std"   => "默认自动生成文章描述和关键字，仅在需要自定义SEO时使用",
		"title" => "",
		"type"  => "help"
	),

	"custom_title" => array(
		"name"  => "custom_title",
		"std"   => "",
		"title" => "自定义标题",
		"type"  => "text"
	),

	"description" => array(
		"name"  => "description",
		"std"   => "",
		"title" => "自定义描述",
		"after" => "留空则自动截取首段一定字数作为文章描述",
		"type"  => "textarea"
	),

	"keywords"  => array(
		"name"  => "keywords",
		"std"   => "",
		"title" => "自定义关键词",
		"after" => "多个关键词用半角逗号隔开，留空则自动将文章标签做为关键词",
		"type"  => "text"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function seo_post_meta_boxes() {
	global $post, $seo_post_meta_boxes;

	foreach ($seo_post_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'help':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field">' . $meta_box['std'] . '</span><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function seo_post_meta_box() {
	global $theme_name;
	$be_post_types = array( 'post', 'page', 'bulletin', 'picture', 'video', 'tao', 'show', 'other_custom_post_type' );
	foreach ( $be_post_types as $post_type ) {
		if (function_exists('add_meta_box')) {
			add_meta_box( 'seo_post_meta_box', 'SEO设置', 'seo_post_meta_boxes', $post_type, 'normal', 'high' );
		}
	}
}

function save_seo_post_postdata($post_id) {
	global $post, $seo_post_meta_boxes;
	foreach ($seo_post_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
if ( zm_get_option( 'wp_title' ) ) {
	add_action('admin_menu', 'seo_post_meta_box');
	add_action('save_post', 'save_seo_post_postdata');
}

// 文章手动缩略图
$thumbnail_post_meta_boxes =
array(
	"thumbnail" => array(
		"name"  => "thumbnail",
		"std"   => "",
		"title" => "调用指定缩略图",
		"type"  => "upload"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function thumbnail_post_meta_boxes() {
	global $post, $thumbnail_post_meta_boxes;

	foreach ($thumbnail_post_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function thumbnail_post_meta_box() {
	global $theme_name;
	$be_post_types = array( 'post', 'tao', 'other_custom_post_type' );
	foreach ( $be_post_types as $post_type ) {
		if ( function_exists( 'add_meta_box' ) ) {
		add_meta_box('thumbnail_post_meta_box', '手动缩略图', 'thumbnail_post_meta_boxes', $post_type, 'normal', 'high');
		}
	}
}

function save_thumbnail_post_postdata($post_id) {
	global $post, $thumbnail_post_meta_boxes;
	foreach ($thumbnail_post_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

add_action( 'admin_menu', 'thumbnail_post_meta_box' );
add_action( 'save_post', 'save_thumbnail_post_postdata' );

// 文章其它设置
$other_post_meta_boxes =
array(
	"no_sidebar" => array(
		"name"   => "no_sidebar",
		"std"    => "",
		"title"  => "隐藏侧边栏",
		"type"   => "checkbox"
	),

	"sidebar_l" => array(
		"name"  => "sidebar_l",
		"std"   => "",
		"title" => "侧边栏居左",
		"type"  => "checkbox"
	),

	"no_abstract" => array(
		"name"  => "no_abstract",
		"std"   => "",
		"title" => "隐藏摘要",
		"type"  => "checkbox"
	),

	"user_only" => array(
		"name"  => "user_only",
		"std"   => "",
		"title" => "登录查看",
		"type"  => "checkbox"
	),

	"not_more" => array(
		"name"  => "not_more",
		"std"   => "",
		"title" => "不显示展开全文",
		"type"  => "checkbox"
	),

	"no_toc" => array(
		"name"  => "no_toc",
		"std"   => "",
		"title" => "不显示目录",
		"type"  => "checkbox"),

	"allow_copy" => array(
		"name"  => "allow_copy",
		"std"   => "",
		"title" => "允许复制",
		"type"  => "checkbox"
	),

	"toc_four" => array(
		"name"  => "toc_four",
		"std"   => "",
		"title" => "仅四级目录",
		"type"  => "checkbox"
	),

	"sub_section" => array(
		"name"  => "sub_section",
		"std"   => "",
		"title" => "三、四级章节",
		"type"  => "checkbox"
	),

	"show_line" => array(
		"name"  => "show_line",
		"std"   => "",
		"title" => "时间轴不隐藏",
		"type"  => "checkbox"
	),

	"no_today" => array(
		"name"  => "no_today",
		"std"   => "",
		"title" => "不显示历史文章",
		"type"  => "checkbox"
	),

	"go_direct" => array(
		"name"  => "go_direct",
		"std"   => "",
		"title" => "文章标题直达链接（用于“链接”文章形式）",
		"type"  => "checkbox"
	),

	"mark" => array(
		"name"  => "mark",
		"std"   => "",
		"title" => "标题后缀说明",
		"type"  => "text"
	),

	"post_info" => array(
		"name"  => "post_info",
		"std"   => "",
		"title" => "固定信息",
		"after" => "（自定义通用固定信息）",
		"type"  => "textarea"
	),

	"meta_sub" => array(
		"name"  => "meta_sub",
		"std"   => "",
		"title" => "附加标注",
		"after" => "（目前仅显示在部分模块中）",
		"type"  => "textarea"
	),

	"direct_btn" => array(
		"name"  => "direct_btn",
		"std"   => "",
		"title" => "直达链接按钮名称",
		"type"  => "text2"
	),

	"direct" => array(
		"name"  => "direct",
		"std"   => "",
		"title" => "直达链接地址",
		"type"  => "text2"
	),

	"link_inf" => array(
		"name"  => "link_inf",
		"std"   => "",
		"title" => "自定义文章信息",
		"after" => "（用于“链接”文章形式）",
		"type"  => "text"
	),

	"doc_name" => array(
		"name"  => "doc_name",
		"std"   => "",
		"title" => "固定下载按钮名称",
		"type"  => "text2"
	),


	"down_doc" => array(
		"name"  => "down_doc",
		"std"   => "",
		"title" => "固定下载按钮链接",
		"type"  => "text2"
	),

	"button1" => array(
		"name"  => "button1",
		"std"   => "",
		"title" => "弹窗按钮名称",
		"type"  => "text2"
	),

	"url1" => array(
		"name"  => "url1",
		"std"   => "",
		"title" => "弹窗下载链接",
		"type"  => "text2"
	),

	"from" => array(
		"name"  => "from",
		"std"   => "",
		"title" => "文章来源",
		"type"  => "text2"
	),

	"copyright" => array(
		"name"  => "copyright",
		"std"   => "",
		"title" => "原文链接",
		"type"  => "text2"
	),

	"slider_gallery_n" => array(
		"name"  => "slider_gallery_n",
		"std"   => "",
		"title" => "幻灯短代码每页显示图片数",
		"type"  => "text"
	),

	"fancy_box" => array(
		"name"  => "fancy_box",
		"std"   => "",
		"title" => "添加图片，用于点击缩略图查看原图",
		"type"  => "upload"
	),

	"poster_img" => array(
		"name"  => "poster_img",
		"std"   => "",
		"title" => "自定义海报图片",
		"type"  => "upload"
	),

	"be_img_fill" => array(
		"name"   => "be_img_fill",
		"std"    => "",
		"before" => "背景图片",
		"title"  => "在图片布局中，以图片替换背景色",
		"type"   => "becheckbox"
	),

	"be_bg_img" => array(
		"name"  => "be_bg_img",
		"std"   => "",
		"title" => "自定义背景图片",
		"type"  => "upload"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function other_post_meta_boxes() {
	global $post, $other_post_meta_boxes;

	foreach ($other_post_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'text2':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				$data = isset($_POST[$meta_box['name']]) ? $_POST[$meta_box['name']] : $meta_box['std'];
				// 在输出之前应用 wpautop 函数
				$data = wpautop( $data );
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $data . '</textarea><br />';
			break;
			case 'checkbox':
				echo '<span class="be-field" style="display: inline-block;width:50%">';
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
				echo '</span>';
			break;
			case 'becheckbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				if ( isset( $meta_box['before'] ) ) {
					echo '<h4 class="form-field">' . $meta_box['before'] . '</h4>';
				}
				echo '<label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
			case 'help':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				if ( isset( $meta_box['std'] ) ) {
					echo '<span class="form-field">' . $meta_box['std'] . '</span>';
				}
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function other_post_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('other_post-meta-boxes', '其它设置', 'other_post_meta_boxes', 'post', 'normal', 'high');
	}
}

function save_other_post_postdata($post_id) {
	global $post, $other_post_meta_boxes;
	foreach ($other_post_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;

		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'other_meta' ) ) {
	if ( ! cx_get_option( 'other_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'other_post_meta_box' );
		add_action( 'save_post', 'save_other_post_postdata' );
	}
}

// 文章添加到
$added_post_meta_boxes =
array(
	"cat_top"   => array(
		"name"  => "cat_top",
		"std"   => "",
		"title" => "分类推荐文章",
		"type"  => "checkbox"
	),

	"hot" => array(
		"name"  => "hot",
		"std"   => "",
		"title" => "本站推荐小工具中",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function added_post_meta_boxes() {
	global $post, $added_post_meta_boxes;

	foreach ($added_post_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function added_post_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('added_post_meta_box', '将文章添加到', 'added_post_meta_boxes', 'post', 'normal', 'high');
	}
}

function save_added_post_postdata($post_id) {
	global $post, $added_post_meta_boxes;
	foreach ($added_post_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'added_meta' ) ) {
	if ( ! cx_get_option( 'added_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'added_post_meta_box' );
		add_action( 'save_post', 'save_added_post_postdata' );
	}
}

// 文章标题图片
$header_bg_meta_boxes =
array(
	"header_img" => array(
		"name"   => "header_bg",
		"std"    => "",
		"title"  => "添加图片",
		"type"   => "text"
	),

	"header_img_wide" => array(
		"name"  => "header_bg_wide",
		"std"   => "",
		"title" => "通栏显示",
		"type"  => "checkbox"
	),

	"no_img_title" => array(
		"name"  => "img_title",
		"std"   => "",
		"title" => "标题在图片上",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function header_bg_meta_boxes() {
	global $post, $header_bg_meta_boxes;

	foreach ($header_bg_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function header_bg_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('header_bg_meta_box', '标题图片', 'header_bg_meta_boxes', array( 'post', 'page' ), 'normal', 'high');
	}
}

function save_header_bg_postdata($post_id) {
	global $post, $header_bg_meta_boxes;
	foreach ($header_bg_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'header_bg_meta' ) ) {
	if ( ! cx_get_option( 'header_bg_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'header_bg_meta_box' );
		add_action( 'save_post', 'save_header_bg_postdata' );
	}
}
// 标题幻灯
$header_show_meta_boxes =
array(
	"header_img" => array(
		"name"   => "header_img",
		"std"    => "",
		"title"  => "添加图片，一行一个不能有空行和空格，图片尺寸必须相同",
		"type"   => "textarea"
	),

	"header_img_wide" => array(
		"name"  => "header_img_wide",
		"std"   => "",
		"title" => "通栏显示",
		"type"  => "checkbox"
	),

	"no_show_title" => array(
		"name"  => "show_title",
		"std"   => "",
		"title" => "标题在幻灯上",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function header_show_meta_boxes() {
	global $post, $header_show_meta_boxes;

	foreach ($header_show_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" class="file-uploads" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><a href="javascript:;" class="show_file button">选择图片</a>';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function header_show_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('header_show_meta_box', '标题幻灯', 'header_show_meta_boxes', array( 'post', 'page' ), 'normal', 'high');
	}
}

function save_header_show_postdata($post_id) {
	global $post, $header_show_meta_boxes;
	foreach ($header_show_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'header_show_meta' ) ) {
	if ( ! cx_get_option( 'header_show_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'header_show_meta_box' );
		add_action( 'save_post', 'save_header_show_postdata' );
	}
}
// 页面相关自定义栏目
$new_meta_page_boxes =
array(
	"no_sidebar" => array(
		"name"  => "no_sidebar",
		"std"   => "",
		"title" => "隐藏侧边栏",
		"type"  => "checkbox"
	),

	"sidebar_l" => array(
		"name"  => "sidebar_l",
		"std"   => "",
		"title" => "侧边栏居左",
		"type"  => "checkbox"
	),

	"no_toc" => array(
		"name"  => "no_toc",
		"std"   => "",
		"title" => "不显示目录",
		"type"  => "checkbox"
	),

	"toc_four" => array(
		"name"  => "toc_four",
		"std"   => "",
		"title" => "仅四级目录",
		"type"  => "checkbox"
	),

	"sub_section" => array(
		"name"  => "sub_section",
		"std"   => "",
		"title" => "三、四级章节",
		"type"  => "checkbox"
	),

	"show_line" => array(
		"name"  => "show_line",
		"std"   => "",
		"title" => "时间轴不隐藏",
		"type"  => "checkbox"
	),

	"group_strong" => array(
		"name"  => "group_strong",
		"std"   => "",
		"title" => "添加到公司首页咨询模块",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);


function new_meta_page_boxes() {
	global $post, $new_meta_page_boxes;

	foreach ($new_meta_page_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function create_meta_page_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '页面设置', 'new_meta_page_boxes', 'page', 'normal', 'high');
	}
}
function save_page_postdata($post_id) {
	global $post, $new_meta_page_boxes;
	foreach ($new_meta_page_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

add_action( 'admin_menu', 'create_meta_page_box' );
add_action( 'save_post', 'save_page_postdata' );

// 专题
$special_page_meta_boxes =
array(
	"special" => array(
		"name"  => "special",
		"std"   => "",
		"title" => "与该专题关联的标签名称/别名",
		"type"  => "text"
	),

	"special_img" => array(
		"name"  => "special_img",
		"std"   => "",
		"title" => "图片布局",
		"type"  => "checkbox"
	),

	"thumbnail" => array(
		"name"  => "thumbnail",
		"std"   => "",
		"title" => "专题封面图片",
		"type"  => "upload"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function special_page_meta_boxes() {
	global $post, $special_page_meta_boxes;

	foreach ($special_page_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '（功能已弃用）</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function special_page_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('special_page_meta_box', '专题模板设置', 'special_page_meta_boxes', 'page', 'normal', 'high');
	}
}

function save_special_page_postdata($post_id) {
	global $post, $special_page_meta_boxes;
	foreach ($special_page_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'subject_meta' ) ) {
	if ( ! cx_get_option( 'subject_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'special_page_meta_box' );
		add_action( 'save_post', 'save_special_page_postdata' );
	}
}

// 产品页面
$cp_page_meta_boxes =
array(
	"cp_cat_id" => array(
		"name"  => "cp_cat_id",
		"std"   => "",
		"title" => "输入分类ID",
		"type"  =>"text2"
	),

	"cp_number" => array(
		"name"  => "cp_number",
		"std"   => "",
		"title" => "每页文章数量",
		"type"  => "text2"
	),

	"cp_style" => array(
		'type'       => 'radio',
		'title'      => '样式布局',
		'name'       => 'cp_style',
		'options'    => array(
			array( 'value' => 'default', 'label' => '标准样式' ),
			array( 'label' => '图片样式' ),
			array( 'value' => 'grid', 'label' => '卡片样式' ),
		),
		'std' => '',
	),

	"cp_column" => array(
		'type'       => 'radio',
		'title'      => '图片/卡片分栏',
		'name'       => 'cp_column',
		'options'    => array(
			array( 'value' => '2', 'label' => '2栏' ),
			array( 'value' => '3', 'label' => '3栏' ),
			array( 'label' => '4栏' ),
			array( 'value' => '5', 'label' => '5栏' ),
			array( 'value' => '6', 'label' => '6栏' ),
		),
		'std' => '',
	),

	"cp_column_t" => array(
		"name"    => "cp_column_t",
		"after"   => "图片样式选择&nbsp;4/5/6&nbsp;&nbsp;&nbsp;&nbsp;卡片样式选择&nbsp;2/3/4",
		"type"    => "be_before"
	),

	"cp_more"    => array(
		"name"   => "cp_more",
		"std"    => "",
		"title"  => "更多按钮",
		"type"   => "checkbox"
	),

	"cp_infinite" => array(
		"name"    => "cp_infinite",
		"std"     => "",
		"title"   => "滚动加载",
		"type"    => "checkbox"
	),

	"cp_sidebar_r" => array(
		"name"     => "cp_sidebar_r",
		"std"      => "",
		"title"    => "侧边栏居右",
		"type"     => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);


function cp_page_meta_boxes() {
	global $post, $cp_page_meta_boxes;

	foreach ($cp_page_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'text2':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
				echo '</span>';
			break;
			case 'be_after':
				echo '<span class="form-be-after">' . $meta_box['after'] . '</span>';
			break;
			case 'be_before':
				echo '<span class="form-be-before" style="margin: 0 0 20px 10px;">' . $meta_box['after'] . '</span>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;

			case 'radio':
				echo '<h4>' . htmlspecialchars($meta_box['title']) . '</h4>';
				$defaultValue = isset($meta_box['std']) ? $meta_box['std'] : '';
				foreach ($meta_box['options'] as $option) {
					// 检查是否存在 'value' 键，如果不存在则使用空字符串
					$value = isset($option['value']) ? $option['value'] : '';
					$checked = ($value == $defaultValue) ? 'checked' : '';
					echo '<input type="radio" class="kcheck" name="' . htmlspecialchars($meta_box['name']) . '" value="' . htmlspecialchars($value) . '" ' . $checked . ' />';
					// 检查是否存在 'label' 键
					echo isset($option['label']) ? '<label for="' . htmlspecialchars($meta_box['name']) . '_' . htmlspecialchars($value) . '">' . htmlspecialchars($option['label']) . '</label>&nbsp;&nbsp;&nbsp;&nbsp;' : '';
				}
			break;

			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function cp_page_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('cp_page_meta_box', '产品展示模板设置', 'cp_page_meta_boxes', 'page', 'normal', 'high');
	}
}

function save_cp_page_postdata($post_id) {
	global $post, $cp_page_meta_boxes;
	foreach ($cp_page_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'cp_page_meta' ) ) {
	if ( ! cx_get_option( 'cp_page_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'cp_page_meta_box' );
		add_action( 'save_post', 'save_cp_page_postdata' );
	}
}


// 图片日志
$new_meta_picture_boxes =
array(
	"thumbnail" => array(
		"name"  => "thumbnail",
		"std"   => "",
		"title" => "添加图片地址，调用指定缩略图，图片尺寸要求与主题选项中对应的缩略图大小相同",
		"type"  => "upload"
	),

	"fancy_box" => array(
		"name"  => "fancy_box",
		"std"   => "",
		"title" => "添加图片，用于点击缩略图查看原图",
		"type"  => "upload"
	),

	"poster_img" => array(
		"name"  => "poster_img",
		"std"   => "",
		"title" => "自定义海报图片",
		"type"  => "upload"
	),

	"slider_gallery_n" => array(
		"name"  => "slider_gallery_n",
		"std"   => "",
		"title" => "相册每页显示图片数",
		"type"  => "text"
	),

	"no_sidebar" => array(
		"name"  => "no_sidebar",
		"std"   => "",
		"title" => "隐藏侧边栏",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function new_meta_picture_boxes() {
	global $post, $new_meta_picture_boxes;

	foreach ($new_meta_picture_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_meta_picture_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '图片设置', 'new_meta_picture_boxes', 'picture', 'normal', 'high');
	}
}
function save_picture_postdata($post_id) {
	global $post, $new_meta_picture_boxes;
	foreach ($new_meta_picture_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_meta_picture_box');
add_action('save_post', 'save_picture_postdata');

// 视频日志
$new_meta_video_boxes =
array(
	"small" => array(
		"name"  => "small",
		"std"   => "",
		"title" => "添加图片地址，调用指定缩略图，图片尺寸要求与主题选项中缩略图大小相同",
		"type"  => "upload"
	),

	"poster_img" => array(
		"name"  => "poster_img",
		"std"   => "",
		"title" => "自定义海报图片",
		"type"  => "upload"
	),

	"slider_gallery_n" => array(
		"name"  => "slider_gallery_n",
		"std"   => "",
		"title" => "相册每页显示图片数",
		"type"  => "text"
	),

	"no_sidebar" => array(
		"name"  => "no_sidebar",
		"std"   => "",
		"title" => "隐藏侧边栏",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),

);

// 面板内容
function new_meta_video_boxes() {
	global $post, $new_meta_video_boxes;

	foreach ($new_meta_video_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_meta_video_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '视频设置', 'new_meta_video_boxes', 'video', 'normal', 'high');
	}
}
function save_video_postdata($post_id) {
	global $post, $new_meta_video_boxes;
	foreach ($new_meta_video_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_meta_video_box');
add_action('save_post', 'save_video_postdata');

// 淘客
$new_meta_tao_boxes =
array(
	"product" => array(
		"name"  => "product",
		"std"   => "",
		"title" => "商品描述",
		"type"  => "textarea"
	),

	"pricex" => array(
		"name"  => "pricex",
		"std"   => "",
		"title" => "商品现价",
		"type"  => "text2"
	),

	"pricey" => array(
		"name"  => "pricey",
		"std"   => "",
		"title" => "商品原价（可选）",
		"type"  => "text2"
	),

	"taourl" => array(
		"name"  => "taourl",
		"std"   => "",
		"title" => "商品购买链接",
		"type"  => "text2"
	),

	"m_taourl" => array(
		"name"  => "m_taourl",
		"std"   => "",
		"title" => "商品购买链接移动端（可选）",
		"type"  => "text2"
	),

	"taourl_t" => array(
		"name"  => "taourl_t",
		"std"   => "",
		"title" => "购买链接文字（可选）",
		"type"  => "text2"
	),

	"vip_url" => array(
		"name"  => "vip_url",
		"std"   => "",
		"title" => "升级VIP链接",
		"type"  => "text2"
	),

	"vip_login_text" => array(
		"name"  => "vip_login_text",
		"std"   => "",
		"title" => "会员免费文字",
		"type"  => "text2"
	),

	"vip_text" => array(
		"name"  => "vip_text",
		"std"   => "",
		"title" => "立即升级文字",
		"type"  => "text2"
	),

	"spare_t" => array(
		"name"  => "spare_t",
		"std"   => "",
		"title" => "备用文字（可选）",
		"type"  => "text2"
	),

	"tao_img_t" => array(
		"name"  => "tao_img_t",
		"std"   => "",
		"title" => "缩略图文字（可选）",
		"type"  => "text2"
	),

	"discount" => array(
		"name"  => "discount",
		"std"   => "",
		"title" => "添加优惠卷（可选）",
		"type"  => "text2"
	),

	"discounturl" => array(
		"name"  => "discounturl",
		"std"   => "",
		"title" => "优惠卷链接（可选）",
		"type"  => "text2"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function new_meta_tao_boxes() {
	global $post, $new_meta_tao_boxes;

	foreach ($new_meta_tao_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'text2':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'];
				if ( isset( $meta_box['after'] ) ) {
					echo '<span class="field-after">' . $meta_box['after'] . '</span>';
				}
				echo '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
				echo '</span>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function create_meta_tao_box() {
	global $theme_name;
	$be_post_types = array( 'post', 'tao', 'other_custom_post_type' );
	foreach ( $be_post_types as $post_type ) {
		if (function_exists('add_meta_box')) {
			add_meta_box( 'new-meta-boxes', '商品设置', 'new_meta_tao_boxes', $post_type, 'normal', 'high' );
		}
	}
}

function save_tao_postdata($post_id) {
	global $post, $new_meta_tao_boxes;
	foreach ($new_meta_tao_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'tao_meta' ) ) {
	if ( ! cx_get_option( 'tao_meta_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'create_meta_tao_box' );
		add_action( 'save_post', 'save_tao_postdata' );
	}
}
// 网址
$new_meta_sites_boxes =
array(
	"sites_link" => array(
		"name"  => "sites_link",
		"std"   => "",
		"title" => "输入网址链接，发表后点更新，可自动获取网站描述",
		"type"  => "text"
	),

	"sites_url" => array(
		"name"  => "sites_url",
		"std"   => "",
		"title" => "输入网址链接，适合无法获取描述的网站",
		"type"  => "text"
	),

	"sites_des" => array(
		"name"  => "sites_des",
		"std"   => "",
		"title" => "自定义网站描述",
		"type"  => "textarea"
	),

	"keywords" => array(
		"name"  => "keywords",
		"std"   => "",
		"title" => "自定义关键词，留空则自动将文章标题做为关键词",
		"type"  => "text"
	),

	"thumbnail" => array(
		"name"  => "thumbnail",
		"std"   => "",
		"title" => "网站截图",
		"type"  => "upload"
	),

	"sites_ico" => array(
		"name"  => "sites_ico",
		"std"   => "",
		"title" => "自定义图标",
		"type"  => "upload"
	),

	"order" => array(
		"name"  => "sites_order",
		"std"   => "0",
		"title" => "网址排序数值越大越靠前",
		"type"  => "text"
	),

	"no_sidebar" => array(
		"name"  => "no_sidebar",
		"std"   => "",
		"title" => "隐藏侧边栏",
		"type"  => "checkbox"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function new_meta_sites_boxes() {
	global $post, $new_meta_sites_boxes;

	foreach ($new_meta_sites_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_meta_sites_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '添加链接', 'new_meta_sites_boxes', 'sites', 'normal', 'high');
	}
}
function save_sites_postdata($post_id) {
	global $post, $new_meta_sites_boxes;
	foreach ($new_meta_sites_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_meta_sites_box');
add_action('save_post', 'save_sites_postdata');

// 下载链接（文章）
$down_post_meta_boxes =
array(
	"down_start" => array(
		"name"  => "down_start",
		"std"   => "",
		"title" => "启用下载",
		"type"  => "checkbox"
	),

	"down_name" => array(
		"name"  => "down_name",
		"std"   => "",
		"title" => "资源名称",
		"type"  => "be_text"
	),

	"be_down_name" => array(
		"name"  => "be_down_name",
		"std"   => "",
		"title" => "预设名称",
		"type"  => "be_checkbox"
	),

	"file_os" => array(
		"name"  => "file_os",
		"std"   => "",
		"title" => "应用平台",
		"type"  => "be_text"
	),

	"be_file_os" => array(
		"name"  => "be_file_os",
		"std"   => "",
		"title" => "预设名称",
		"type"  => "be_checkbox"
	),

	"file_inf"  => array(
		"name"  => "file_inf",
		"std"   => "",
		"title" => "资源版本",
		"type"  => "be_text"
	),

	"be_file_inf" => array(
		"name"  => "be_file_inf",
		"std"   => "",
		"title" => "预设名称",
		"type"  => "be_checkbox"
	),

	"down_size" => array(
		"name"  => "down_size",
		"std"   => "",
		"title" => "资源大小",
		"type"  => "be_text"
	),

	"be_down_size" => array(
		"name"  => "be_down_size",
		"std"   => "",
		"title" => "预设名称",
		"type"  => "be_checkbox"
	),

	"links_id"  => array(
		"name"  => "links_id",
		"std"   => "",
		"title" => "下载次数（输入短链接 ID）",
		"type"  => "text"
	),

	"password_down" => array(
		"name"  => "password_down",
		"std"   => "",
		"title" => "启用下载链接回复/登录可见",
		"type"  => "checkbox"
	),

	"down_demo" => array(
		"name"  => "down_demo",
		"std"   => "",
		"title" => "演示链接",
		"type"  => "text"
	),

	"baidu_pan_btn" => array(
		"name"  => "baidu_pan_btn",
		"std"   => "",
		"title" => "网盘按钮名称",
		"type"  => "text2"
	),


	"baidu_pan" => array(
		"name"  => "baidu_pan",
		"std"   => "",
		"title" => "网盘下载链接",
		"type"  => "text2"
	),

	"baidu_password" => array(
		"name"  => "baidu_password",
		"std"   => "",
		"title" => "网盘密码",
		"type"  => "text2"
	),

	"r_baidu_password" => array(
		"name"  => "r_baidu_password",
		"std"   => "",
		"title" => "网盘密码 ( 回复或登录可见 )",
		"type"  => "text2"
	),

	"vip_purview" => array(
		"name"  => "vip_purview",
		"std"   => "",
		"title" => "会员可见网盘密码",
		"type"  => "text"
	),

	"down_local_btn" => array(
		"name"  => "down_local_btn",
		"std"   => "",
		"title" => "本站下载按钮名称",
		"type"  => "text2"
	),

	"down_local" => array(
		"name"  => "down_local",
		"std"   => "",
		"title" => "本站下载链接",
		"type"  => "text2"
	),

	"wechat_follow" => array(
		"name"  => "wechat_follow",
		"std"   => "",
		"title" => "输入公众号自动回复“关键字”获取解压密码",
		"type"  => "text"
	),

	"rar_password" => array(
		"name"  => "rar_password",
		"std"   => "",
		"title" => "解压密码",
		"type"  => "text2"
	),

	"r_rar_password" => array(
		"name"  => "r_rar_password",
		"std"   => "",
		"title" => "解压密码 ( 回复或登录可见 )",
		"type"  => "text2"
	),

	"down_official_btn" => array(
		"name"  => "down_official_btn",
		"std"   => "",
		"title" => "官网下载按钮名称",
		"type"  => "text2"
	),

	"down_official" => array(
		"name"  => "down_official",
		"std"   => "",
		"title" => "官网下载链接",
		"type"  => "text2"
	),

	"down_img"  => array(
		"name"  => "down_img",
		"std"   => "",
		"title" => "输入演示图地址",
		"type"  => "upload"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function down_post_meta_boxes() {
	global $post, $down_post_meta_boxes;
	foreach ($down_post_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'text2':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
				echo '</span>';
			break;
			case 'be_text':
				echo '<span class="be-field" style="display: inline-block;width:48.7%">';
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span>';

			break;
			case 'be_checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label class="be-label"><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label>';
				echo '</span>';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function down_post_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('down_post_meta_box', '下载信息', 'down_post_meta_boxes', 'post', 'normal', 'high');
	}
}

function save_down_post_postdata($post_id) {
	global $post, $down_post_meta_boxes;
	foreach ($down_post_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

if ( cx_get_option( 'down_meta' ) ) {
	if ( ! cx_get_option( 'down_box' ) || current_user_can( 'manage_options' ) ) {
		add_action( 'admin_menu', 'down_post_meta_box' );
		add_action( 'save_post', 'save_down_post_postdata' );
	}
}
// 项目
$new_meta_show_boxes =
array(
	"slider_gallery_n" => array(
		"name"  => "slider_gallery_n",
		"std"   => "",
		"title" => "相册每页显示图片数",
		"type"  => "text"
	),

	"thumbnail" => array(
		"name"  => "thumbnail",
		"std"   => "",
		"title" => "调用指定缩略图",
		"type"  => "upload"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

// 面板内容
function new_meta_show_boxes() {
	global $post, $new_meta_show_boxes;

	foreach ($new_meta_show_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
				break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_meta_show_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '项目设置', 'new_meta_show_boxes', 'show', 'normal', 'high');
	}
}
function save_show_postdata($post_id) {
	global $post, $new_meta_show_boxes;
	foreach ($new_meta_show_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

add_action( 'admin_menu', 'create_meta_show_box' );
add_action( 'save_post', 'save_show_postdata' );

// 项目幻灯
$new_meta_show_h_a_boxes =
array(
	"s_a_img_d" => array(
		"name"  => "s_a_img_d",
		"std"   => "",
		"title" => "大背景图地址",
		"type"  => "upload"
	),

	"s_a_img_x" => array(
		"name"  => "s_a_img_x",
		"std"   => "",
		"title" => "浮动层小图地址",
		"type"  => "upload"
	),

	"s_a_t_a"   => array(
		"name"  => "s_a_t_a",
		"std"   => "",
		"title" => "第一行文字",
		"type"  => "text"
	),

	"s_a_t_b"   => array(
		"name"  => "s_a_t_b",
		"std"   => "",
		"title" => "第二行文字（大字）",
		"type"  => "text"
	),

	"s_a_t_c"   => array(
		"name"  => "s_a_t_c",
		"std"   => "",
		"title" => "第三行文字",
		"type"  => "text"),

	"s_a_n_b"   => array(
		"name"  => "s_a_n_b",
		"std"   => "",
		"title" => "按钮名称",
		"type"  => "text"
	),

	"s_a_n_b_l" => array(
		"name"  => "s_a_n_b_l",
		"std"   => "",
		"title" => "按钮链接",
		"type"  => "text"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

// 面板内容
function new_meta_show_h_a_boxes() {
	global $post, $new_meta_show_h_a_boxes;

	foreach ($new_meta_show_h_a_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'upload':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field file-uploads be-uploads-btn">';
				echo '<input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" />';
				echo '<a href="javascript:;" class="begin_file button">选择图片</a>';
				echo '<a href="javascript:;" class="delete_file button" style="display: none;">删除图片</a>';
				echo '</span>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_show_h_a_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_h_a_meta_box', '项目头部图片设置', 'new_meta_show_h_a_boxes', 'show', 'normal', 'high');
	}
}
function save_show_h_a_postdata($post_id) {
	global $post, $new_meta_show_h_a_boxes;
	foreach ($new_meta_show_h_a_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_h_a_meta_box');
add_action('save_post', 'save_show_h_a_postdata');

// 标题及其它
$new_meta_show_q_boxes =
array(
	"s_j_t"     => array(
		"name"  => "s_j_t",
		"std"   => "",
		"title" => "简介标题",
		"type"  => "text"
	),

	"s_j_e"     => array(
		"name"  => "s_j_e",
		"std"   => "",
		"title" => "简介描述",
		"type"  => "text"
	),

	"s_c_t"     => array(
		"name"  => "s_c_t",
		"std"   => "正文标题",
		"title" => "正文标题",
		"type"  => "text"
	),

	"s_f_t"     => array(
		"name"  => "s_f_t",
		"std"   => "附加模块标题",
		"title" => "附加模块标题",
		"type"  => "text"
	),

	"s_f_e"     => array(
		"name"  => "s_f_e",
		"std"   => "",
		"title" => "附加模块内容",
		"type"  => "textarea"
	),

	"s_f_n_a"   => array(
		"name"  => "s_f_n_a",
		"std"   => "<i class='be be-stack'></i> 详细查看",
		"title" => "附加模块按钮A文字",
		"type"  => "text"
	),

	"s_f_n_a_l" => array(
		"name"  => "s_f_n_a_l",
		"std"   => "",
		"title" => "附加模块按钮A链接",
		"type"  => "text"
	),

	"s_f_n_b"   => array(
		"name"  => "s_f_n_b",
		"std"   => "<i class='be be-phone'></i> 联系方式",
		"title" => "附加模块按钮b文字",
		"type"  => "text"
	),

	"s_f_n_b_l" => array(
		"name"  => "s_f_n_b_l",
		"std"   => "",
		"title" => "附加模块按钮B链接",
		"type"  => "text"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

// 面板内容
function new_meta_show_q_boxes() {
	global $post, $new_meta_show_q_boxes;

	foreach ($new_meta_show_q_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}
function create_show_q_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_q_meta_box', '标题及其它', 'new_meta_show_q_boxes', 'show', 'normal', 'high');
	}
}
function save_show_q_postdata($post_id) {
	global $post, $new_meta_show_q_boxes;
	foreach ($new_meta_show_q_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_q_meta_box');
add_action('save_post', 'save_show_q_postdata');

// 菜单短代码
$becode_meta_boxes =
array(
	"code_explain_1"  => array(
		"name"  => "code_explain_1",
		"std"   => '',
		"title" => '<span class="exp-title">顶级菜单选择</span><span class="exp-select">短代码封面</span>',
		"type"  => "explain"
	),

	"code_1"  => array(
		"name"  => "code_1",
		"std"   => '[menucodecover ids="分类ID"]',
		"title" => '分类封面',
		"type"  => "help"
	),

	"code_2"  => array(
		"name"  => "code_2",
		"std"   => '[menucodespecial ids="专题页面ID"]',
		"title" => '专题封面',
		"type"  => "help"
	),

	"code_explain_2"  => array(
		"name"  => "code_explain_2",
		"std"   => '',
		"title" => '<span class="exp-title">顶级菜单选择</span><span class="exp-select">短代码分类</span>',
		"type"  => "explain"
	),

	"code_3"  => array(
		"name"  => "code_3",
		"std"   => '[menucode image="图片链接" ids="分类ID" post="页面ID"]',
		"title" => '左侧图片，右侧分类和文章、页面链接',
		"type"  => "help"
	),

	"code_4"  => array(
		"name"  => "code_4",
		"std"   => '[menucatmod cat_ids="分类ID"]',
		"title" => '调用一个分类的文章列表',
		"type"  => "help"
	),

	"code_5"  => array(
		"name"  => "code_5",
		"std"   => '[menucatlist cat_ids="分类ID" number="分章数"]',
		"title" => '分类列表',
		"type"  => "help"
	),

	"code_6"  => array(
		"name"  => "code_6",
		"std"   => '[menurecmod post_ids="文章ID"]',
		"title" => '调用指定ID文章列表',
		"type"  => "help"
	),

	"code_7"  => array(
		"name"  => "code_7",
		"std"   => '[menuhotmod number="数量" cat_ids="分类ID" month="限定月数"]',
		"title" => '调用指定分类一定时间内热门文章',
		"type"  => "help"
	),

	"code_9"  => array(
		"name"  => "code_9",
		"std"   => '[menucodea image="图片地址" buturl="按钮链接" but="按钮标题"]',
		"title" => '左侧图片，右侧自定义链接按钮',
		"type"  => "help"
	),

	"code_9_1"  => array(
		"name"  => "code_9_1",
		"std"   => '[menucodea image="图片地址" url1="链接一" url2="链接二" url3="链接三" title1="标题一" title2="标题二" title3="标题三"]',
		"title" => '或者多个按钮',
		"type"  => "help"
	),

	"code_explain_3"  => array(
		"name"  => "code_explain_3",
		"std"   => '',
		"title" => '<span class="exp-title">顶级菜单选择</span><span class="exp-select">短代码混排</span>',
		"type"  => "explain"
	),

	"code_8"  => array(
		"name"  => "code_8",
		"std"   => '[menupost image="图片链接" cat_ids="左侧分类ID" post_ids="右侧文章/页面ID" des="1" words_n="文章摘要字数"]',
		"title" => '图片、分类、单篇文章混排',
		"type"  => "help"
	),

	"code_10"  => array(
		"name"  => "code_10",
		"std"   => '[superlist page_ids="页面ID" cat_ids="分类ID" post_ids="页面ID" column="分栏" number="数量" cat="图上按钮分类ID" image="图片链接"]',
		"title" => '左侧两个页面，中间分类文章，右侧图片加按钮，需要为两个页面添加自定义字段，名称：page_img，值：图片地址',
		"type"  => "help"
	),

	"code_11"  => array(
		"name"  => "code_11",
		"std"   => '[supercover image="图片链接" cat="图上按钮分类ID" cat_ids="分类ID，多个分类用英文","逗号隔开" column="分栏" number="数量"]',
		"title" => '，左侧图片加按钮，右侧分类文章列表',
		"type"  => "help"
	),

	"code_12"  => array(
		"name"  => "code_12",
		"std"   => '[supercatlist cat_id="左图按钮分类ID" image="左图片链接" cat_list_id="中间文章列表分类ID" number="文章数量" cat_a_id="右a按钮分类ID" cat_b_id="右b按钮分类ID" cat_a_img="右a图片链接" cat_b_img="右b图片链接"]',
		"title" => '左侧分类图片及链接，中间分类文章列表，右侧两张图和分类链接',
		"type"  => "help"
	),

	"empty"     => array(
		"name"  => "empty",
		"type"  => "empty"
	),
);

function becode_meta_boxes() {
	global $post, $becode_meta_boxes;

	foreach ($becode_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta(get_the_ID(), $meta_box['name'] . '', true);
		if ($meta_box_value != "")

		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		switch ($meta_box['type']) {
			case 'help':
				echo '<div class="field-code-box">';
				echo '<div class="field-code-title"><i class="dashicons dashicons-shortcode"></i>' . $meta_box['title'] . '</div>';
				echo '<span class="field-code short-link">' . $meta_box['std'] . '</span>';
				echo '<span class="copy-becode">';
				echo '<button type="button" class="button button-small copy-attachment-url">复制</button>';
				echo '<span class="success hidden" aria-hidden="true">已复制！</span>';
				echo '</span>';
				echo '</div>';
			break;
			case 'explain':
				echo '<div class="field-code-title explain-title">' . $meta_box['title'] . '</div>';
			break;
			case 'empty':
				echo '<p></p>';
			break;
		}
	}
}

function becode_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('becode_meta_box', '超级菜单短代码', 'becode_meta_boxes', 'becode', 'normal', 'high');
	}
}

function save_becode_postdata($post_id) {
	global $post, $becode_meta_boxes;
	foreach ($becode_meta_boxes as $meta_box) {
		if ( !isset($_POST[$meta_box['name'] . '_noncename']) || !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = isset($_POST[$meta_box['name'] . '']) ? $_POST[$meta_box['name'] . ''] : null;
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}

add_action('admin_menu', 'becode_meta_box');
add_action('save_post', 'save_becode_postdata');

function becodeclipboard() { ?>
<script>
	document.addEventListener('click', function(event) {
		if (event.target.classList.contains('copy-attachment-url')) {
			const button = event.target;
			const quoteElement = button.parentElement.previousElementSibling;
			const quote = quoteElement.textContent;
			const tempTextArea = document.createElement('textarea');
			tempTextArea.value = quote;
			document.body.appendChild(tempTextArea);
			tempTextArea.select();
			document.execCommand('copy');
			document.body.removeChild(tempTextArea);

			const successElement = button.nextElementSibling;
			successElement.classList.remove('hidden');
			setTimeout(() => {
				successElement.classList.add('hidden');
			}, 2000);
		}
	});
</script>
<?php }
add_action( 'admin_footer', 'becodeclipboard' );

function upload_js() { ?>
<script>
jQuery(document).ready(function($) {
	$(document).ready(function() {
		// 页面加载完成后检查每个输入框的值
		$('input[type="text"]').each(function() {
			var inputValue = $(this).val().trim();
			if (inputValue !== '') {
				$(this).next('.begin_file').next('.delete_file').show();
			}
		});
	});

	// 点击选择图片按钮
	$('body').on('click', '.begin_file',
	function(e) {
		e.preventDefault();

		var buon = $(this);
		var custom_uploader = wp.media({
			title: '添加图片',
			library: {
				type: 'image'
			},
			button: {
				text: '选择'
			},
			multiple: false
		}).on('select',
		function() {
			var inputField = buon.prev('input[type="text"]');
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$(inputField).val(attachment.url);

			// 检查输入框是否为空
			var inputValue = $(inputField).val().trim();
			if (inputValue !== '') {
				// 显示删除按钮
				buon.next('.delete_file').show();
			}
		}).open();
	});

	// 点击删除按钮
	$('body').on('click', '.delete_file',
	function(e) {
		e.preventDefault();
		var inputField = $(this).siblings('input[type="text"]');
		$(inputField).val('');
		$(this).hide();
	});


	var $ = jQuery;
	if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
		$(document).on('click', '.show_file',
		function(e) {
			e.preventDefault();
			var button = $(this);
			var id = button.prev();
			wp.media.editor.send.attachment = function(props, attachment) {
				if ($.trim(id.val()) != '') {
					id.val(id.val() + '\n' + attachment.url);
				} else {
					id.val(attachment.url);
				}
			};
			wp.media.editor.open(button);
			return false;
		});
	}
});
</script>
<?php };
add_action('admin_head', 'upload_js');