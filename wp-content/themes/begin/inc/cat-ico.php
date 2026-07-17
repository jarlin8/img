<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 单色
add_action('admin_init', 'zm_icon_init');
function zm_icon_init() {
	$zm_icon_taxonomies = get_taxonomies();
	if (is_array($zm_icon_taxonomies)) {
		foreach ($zm_icon_taxonomies as $zm_icon_taxonomy) {
			add_action($zm_icon_taxonomy.'_add_form_fields', 'zm_add_icon_texonomy_field');
			add_action($zm_icon_taxonomy.'_edit_form_fields', 'zm_icon_edit_texonomy_field');
		}
	}
}

function zm_add_icon_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	echo '<div class="form-field">
		<label for="taxonomy_icon">单色图标</label>
		<input type="text" name="taxonomy_icon" id="taxonomy_icon" value="" />
		<br/>
		<span class="cat-words">输入单色图标字体代码</span><br />
	</div>';
}

function zm_icon_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	$icon_code = zm_taxonomy_icon_code( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_icon">单色图标</label></th>
		<td>' . zm_taxonomy_icon_code( $taxonomy->term_id, 'medium', TRUE ) . '<br/><input type="text" name="taxonomy_icon" id="taxonomy_icon" value="'.$icon_code.'" /><br />
		<span class="cat-words">输入单色图标字体代码</span><br />
		</td><br />
	</tr>';
}

add_action('edit_term','zm_save_taxonomy_icon');
add_action('create_term','zm_save_taxonomy_icon');
function zm_save_taxonomy_icon($term_id) {
	if (isset($_POST['taxonomy_icon']))
		update_option('zm_taxonomy_icon'.$term_id, $_POST['taxonomy_icon'], NULL);
}

function zm_icon_get_attachment_id_by_code($icon_name) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $icon_name);
	$id = $wpdb->get_var($query);
	return (!empty($id)) ? $id : NULL;
}

function zm_taxonomy_icon_code($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}

	$taxonomy_icon_code = get_option('zm_taxonomy_icon'.$term_id);
	if (!empty($taxonomy_icon_code)) {
		$attachment_id = zm_icon_get_attachment_id_by_code($taxonomy_icon_code);
		if (!empty($attachment_id)) {
			$taxonomy_icon_code = $taxonomy_icon_code[0];
		}
	}
	return $taxonomy_icon_code;
}

// 彩色
add_action('admin_init', 'zm_svg_init');
function zm_svg_init() {
	$zm_svg_taxonomies = get_taxonomies();
	if (is_array($zm_svg_taxonomies)) {
		foreach ($zm_svg_taxonomies as $zm_svg_taxonomy) {
			add_action($zm_svg_taxonomy.'_add_form_fields', 'zm_add_svg_texonomy_field');
			add_action($zm_svg_taxonomy.'_edit_form_fields', 'zm_svg_edit_texonomy_field');
		}
	}
}

function zm_add_svg_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	echo '<div class="form-field">
		<label for="taxonomy_svg">彩色图标</label>
		<input type="text" name="taxonomy_svg" id="taxonomy_svg" value="" />
		<br/>
		<span class="cat-words">输入彩色图标字体代码</span><br />
	</div>';
}

function zm_svg_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	$svg_code = zm_taxonomy_svg_code( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_svg">彩色图标</label></th>
		<td>' . zm_taxonomy_svg_code( $taxonomy->term_id, 'medium', TRUE ) . '<br/><input type="text" name="taxonomy_svg" id="taxonomy_svg" value="'.$svg_code.'" /><br />
		<span class="cat-words">输入彩色图标字体代码</span><br />
		</td><br />
	</tr>';
}

add_action('edit_term','zm_save_taxonomy_svg');
add_action('create_term','zm_save_taxonomy_svg');
function zm_save_taxonomy_svg($term_id) {
	if (isset($_POST['taxonomy_svg']))
		update_option('zm_taxonomy_svg'.$term_id, $_POST['taxonomy_svg'], NULL);
}

function zm_svg_get_attachment_id_by_code($svg_name) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $svg_name);
	$id = $wpdb->get_var($query);
	return (!empty($id)) ? $id : NULL;
}

function zm_taxonomy_svg_code($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}

	$taxonomy_svg_code = get_option('zm_taxonomy_svg'.$term_id);
	if (!empty($taxonomy_svg_code)) {
		$attachment_id = zm_svg_get_attachment_id_by_code($taxonomy_svg_code);
		if (!empty($attachment_id)) {
			$taxonomy_svg_code = $taxonomy_svg_code[0];
		}
	}
	return $taxonomy_svg_code;
}