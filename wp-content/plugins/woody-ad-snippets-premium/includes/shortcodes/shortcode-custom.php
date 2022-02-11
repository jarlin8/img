<?php
/**
 * Custom Shortcode
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 14.02.2019, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Name Shortcode
 */
class WASP_Snippet_Shortcode_Custom extends WINP_SnippetShortcode {

	public $shortcode_name = array();

	/**
	 * WINP_SnippetShortcodeCustom constructor.
	 *
	 * @param WINP_Plugin $plugin
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );
		$this->set_custom_names();
	}

	/**
	 * Параметр shortcode_name должен содержать все кастомные имена шорткодов. Зададим их.
	 */
	public function set_custom_names() {
		$snippets = get_posts(
			array(
				'post_type'   => WINP_SNIPPETS_POST_TYPE,
				'meta_key'    => WINP_Plugin::app()->getPrefix() . 'snippet_scope',
				'meta_value'  => 'shortcode',
				'post_status' => 'publish',
				'numberposts' => - 1,
			)
		);

		foreach ( (array) $snippets as $snippet ) {
			$snippet_custom_name = WINP_Helper::getMetaOption( $snippet->ID, 'snippet_custom_name', '' );
			if ( $snippet_custom_name ) {
				$this->shortcode_name[] = $snippet_custom_name;
			}
		}
	}

	/**
	 * Content render
	 *
	 * @param array $attr
	 * @param string $content
	 * @param string $tag
	 */
	public function html( $attr, $content, $tag ) {
		global $wpdb;

		$meta_key = WINP_Plugin::app()->getPrefix() . 'snippet_custom_name';
		$id       = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '$meta_key' AND meta_value = '$tag' LIMIT 1" );

		if ( ! $id ) {
			echo '<span style="color:red">' . __( 'Custom shortcode: PHP snippets error (not found the snippet ID)', 'insert-php' ) . '</span>';

			return;
		}

		$snippet_meta = get_post_meta( $id, '' );
		if ( empty( $snippet_meta ) ) {
			return;
		}

		$attr = $this->filterAttributes( $attr, $id );

		// Let users pass arbitrary variables, through shortcode attributes.
		// @since 2.0.5
		if ( is_array( $attr ) ) {
			extract( $attr, EXTR_SKIP );
		}

		$snippet_type = WINP_Helper::get_snippet_type( $id );

		$snippet = get_post( $id );

		$is_activate = $this->getSnippetActivate( $snippet_meta );

		$snippet_content = WINP_SNIPPET_TYPE_TEXT == $snippet_type
			? $snippet->post_content
			: $this->getSnippetContent( $snippet, $snippet_meta, $id );

		$snippet_scope = $this->getSnippetScope( $snippet_meta );

		$is_condition = WINP_SNIPPET_TYPE_PHP == $snippet_type
			? true
			: WINP_Plugin::app()->getExecuteObject()->checkCondition( $id );

		if ( ! $is_activate || empty( $snippet_content ) || 'shortcode' != $snippet_scope || ! $is_condition ) {
			return;
		}

		$code = do_shortcode( $snippet_content );
		$code = WINP_SNIPPET_TYPE_TEXT == $snippet_type
			? str_replace( '{{SNIPPET_CONTENT}}', $content, $code )
			: $code;

		if ( WINP_SNIPPET_TYPE_TEXT == $snippet_type ) {
			echo( $code );
		} elseif ( WINP_SNIPPET_TYPE_PHP == $snippet_type ) {
			eval( $code );
		} else {
			eval( "?> " . $code . " <?php " );
		}
	}

}