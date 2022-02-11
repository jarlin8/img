<?php
if (in_array($pagenow, array('post.php', 'post-new.php', 'page.php', 'page-new.php'))) {
	add_action('init', 'becode_addbuttons');
}

function becode_addbuttons() {
	if (get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_becode_tinymce_plugin", 5);
		add_filter('mce_buttons', 'register_becode_button', 5);
	}
}

function register_becode_button($buttons) {
	array_push($buttons, "separator", "becode");
	return $buttons;
}

function add_becode_tinymce_plugin($plugin_array){
	$plugin_array['becode'] = get_template_directory_uri() . '/inc/tinymce/editor-code.js';
	return $plugin_array;
}