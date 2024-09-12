<?php

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/options/' );

if ( ! defined( 'WPINC' ) ) {
	die;
}

if (is_admin() && ! function_exists( 'optionsframework_init' ) ) :

function optionsframework_init() {

	if ( ! current_user_can( 'edit_theme_options' ) )
		return;

	require plugin_dir_path( __FILE__ ) . 'framework.php';
	require plugin_dir_path( __FILE__ ) . 'framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'interface.php';
	require plugin_dir_path( __FILE__ ) . 'media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'sanitization.php';
	require plugin_dir_path( __FILE__ ) . 'options-backup.php';
	$of_backup = new OptionsFramework_Backup;
	$of_backup->init();

	$options_framework = new Options_Framework;
	$options_framework->init();

	$options_framework_admin = new Options_Framework_Admin;
	$options_framework_admin->init();

	$options_framework_media_uploader = new Options_Framework_Media_Uploader;
	$options_framework_media_uploader->init();
}

add_action( 'init', 'optionsframework_init', 20 );

endif;

if ( ! function_exists( 'zm_get_option' ) ) :

function zm_get_option( $name, $default = false ) {
	$config = get_option( 'optionsframework' );

	if ( ! isset( $config['id'] ) ) {
		return $default;
	}

	$options = get_option( $config['id'] );

	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}

endif;

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');
add_action('optionsframework_after','exampletheme_options_after', 100);
}

function exampletheme_options_after() { ?>

<?php
}

add_action('admin_init','optionscheck_change_santiziation', 100);
// Allow HTML
function optionscheck_change_santiziation() {
	remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
	add_filter( 'of_sanitize_textarea', function($input) {return $input;} );
	remove_filter( 'of_sanitize_text', 'of_sanitize_text' );
	add_filter( 'of_sanitize_text', function($input) {return $input;} );
}

add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>
<?php
}