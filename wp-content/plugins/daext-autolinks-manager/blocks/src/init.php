<?php

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
    die();
}

/**
 * Enqueue the Gutenberg block assets for the backend.
 *
 * This function should be used for:
 *
 * - Hooks into editor only
 * - For main block JS
 * - For editor only block CSS overrides
 */
function daam_editor_assets() {

    $shared = daam_Shared::get_instance();

    //Do not enqueue the sidebar files if the user doesn't have the proper capability
	if(!current_user_can(get_option($shared->get('slug') . "_capabilities_editor_sidebar"))) {return;}

	//Styles -----------------------------------------------------------------------------------------------------------
	wp_enqueue_style(
		'dagp-editor-css',
		$shared->get('url') . 'blocks/dist/editor.build.css',
		array( 'wp-edit-blocks' ),//Dependency to include the CSS after it.
		filemtime( $shared->get('dir') . 'blocks/dist/editor.build.css')
	);

    //Scripts ----------------------------------------------------------------------------------------------------------
    wp_enqueue_script(
        'daam-editor-js', // Handle.
	    $shared->get('url') . 'blocks/dist/blocks.build.js', //We register the block here.
	    array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data' ),
	    filemtime( $shared->get('dir') . 'blocks/dist/blocks.build.js'),
        true //Enqueue the script in the footer.
    );

	/*
	 * Add the translations associated with this script in the JED/json format.
	 *
	 * Reference: https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
	 *
	 * Argument 1: Handler
	 * Argument 2: Domain
	 * Argument 3: Location where the JED/json file is located.
	 *
	 * Note that:
	 *
	 * - The JED/json file should be named [domain]-[locale]-[handle].json to be actually detected by WordPress.
	 * - The JED/json file is generated with https://github.com/mikeedwards/po2json from the .po file
	 */
	wp_set_script_translations( 'daam-editor-js', 'daam', $shared->get('dir') . 'blocks/lang' );

}
add_action( 'enqueue_block_editor_assets', 'daam_editor_assets' );