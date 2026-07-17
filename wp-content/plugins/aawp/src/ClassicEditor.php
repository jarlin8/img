<?php

namespace AAWP;

/**
 * ClassicEditor.
 */
class ClassicEditor {

	/**
	 * Contains ids of the editors that contains the AAWP button.
	 *
	 * @var array
	 */
	private $editors_with_buttons = [];

	/**
	 * Initialize
	 */
	public function init() {
		add_action( 'admin_init', [ $this, 'initialize' ], 18 );
	}

	/**
	 * Initialize the TinyMCE button.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function initialize() {

		// Check user capabalities.
		if ( ! apply_filters( 'aawp_tinymce_init', true ) && ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// Check if WYSIWYG is enabled.
		if ( 'true' !== get_user_option( 'rich_editing' ) ) {
			return;
		}

		add_filter( 'mce_buttons', [ $this, 'register_aawp_button' ], 10, 2 );
		add_filter( 'tiny_mce_plugins', [ $this, 'add_tinymce_plugin' ] );
		add_filter( 'tiny_mce_before_init', [ $this, 'tiny_mce_before_init' ], 10, 2 );
		add_action( 'wp_tiny_mce_init', [ $this, 'print_js' ] );
		add_action( 'print_default_editor_scripts', [ $this, 'print_js' ] );

		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_add_inline_script(
					'wp-tinymce',
					'var aawp_classic_editor_data = ' . wp_json_encode(
						[
							'icon_url'      => plugins_url( 'assets/img/icon.svg', AAWP_PLUGIN_FILE ),
							'shortcode'     => \aawp_get_shortcode(),
							'default_items' => [
								'bestseller'   => \aawp_get_option( 'bestseller_default_items', 'functions' ),
								'new_releases' => \aawp_get_option( 'new_releases_default_items', 'functions' ),
							],
						]
					),
					'before'
				);
			}
		);

		add_action(
			'media_buttons',
			function() {
				add_action( 'admin_footer', [ $this, 'shortcode_modal' ] );
			}
		);
	}

	/**
	 * Check if needed actions and filters exists or aren't removed.
	 *
	 * @since 3.18.2
	 *
	 * @return bool
	 */
	private function hooks_exist() {
		if (
			has_action( 'wp_tiny_mce_init', [ $this, 'print_js' ] )
			|| has_action( 'print_default_editor_scripts', [ $this, 'print_js' ] )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Register the AAWP TinyMCE button.
	 *
	 * @param  array  $buttons TinyMCE buttons.
	 * @param  string $editor_id Editor ID.
	 *
	 * @since 3.18
	 *
	 * @return array TinyMCE buttons including AAWP button.
	 */
	public function register_aawp_button( $buttons, $editor_id ) {

		if ( ! $this->hooks_exist() ) {
			return $buttons;
		}
		if ( ! is_array( $buttons ) ) {
			$buttons = [];
		}

		$this->editors_with_buttons[] = $editor_id;
		$buttons                   [] = 'aawp';
		return $buttons;
	}

	/**
	 * Add the AAWP TinyMCE plugin.
	 *
	 * @param array $plugins The TinyMCE Plugins.
	 *
	 * @see https://codex.wordpress.org/TinyMCE_Custom_Buttons
	 *
	 * @since 3.18
	 */
	public function add_tinymce_plugin( $plugins ) {

		if ( ! $this->hooks_exist() ) {
			return $plugins;
		}

		$plugins[] = 'aawp';
		return $plugins;
	}

	/**
	 * Enqueue required styles for TinyMCE editor button.
	 *
	 * @since 3.18
	 *
	 * @return void.
	 */
	public function editor_style() {

		// Load the CSS.
		wp_enqueue_style(
			'aawp-classic-editor',
			plugins_url( 'assets/dist/css/classic-editor.css', AAWP_PLUGIN_FILE ),
			[],
			AAWP_VERSION
		);
	}

	/**
	 * Delete the plugin added by the {@see `tiny_mce_plugins`} method when necessary hooks do not exist.
	 *
	 * This is needed because a plugin may call `wp_editor` (which will permanently add our `aawp` plugin,
	 * because the `tiny_mce_plugins` hooks is called only once) and after that another plugin may call
	 * `remove_all_filters( 'mce_buttons') function that will remove our hook.
	 *
	 * @param array  $mce_init   An array with TinyMCE config.
	 * @param string $editor_id Unique editor identifier.
	 *
	 * @since 3.18.2
	 *
	 * @return array the TinyMCE config.
	 */
	public function tiny_mce_before_init( $mce_init, $editor_id = '' ) {
		if (
			! isset( $mce_init['plugins'] )
			|| ! is_string( $mce_init['plugins'] )
		) {
			return $mce_init;
		}

		$plugins = explode( ',', $mce_init['plugins'] );
		$found   = array_search( 'aawp', $plugins, true );

		if ( ! $found || ( $editor_id !== '' && in_array( $editor_id, $this->editors_with_buttons, true ) ) ) { //phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
			return $mce_init;
		}

		unset( $plugins[ $found ] );
		$mce_init['plugins'] = implode( ',', $plugins );

		return $mce_init;
	}

	/**
	 * Print JS inline.
	 *
	 * @param array|null $mce_settings TinyMCE settings array.
	 */
	public function print_js( $mce_settings = [] ) {
		static $printed = null;

		if ( null !== $printed ) {
			return;
		}

		$printed = true;

		// The `tinymce` argument of the `wp_editor()` function is set  to `false`.
		if ( empty( $mce_settings ) && ! ( doing_action( 'print_default_editor_scripts' ) && user_can_richedit() ) ) {
			return;
		}

		if ( empty( $this->editors_with_buttons ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		echo "<script>\n"
			. file_get_contents( AAWP_PLUGIN_DIR . 'assets/dist/js/classic-editor.js' ) . "\n"
			. "</script>\n";
		// phpcs:enable
	}

	/**
	 * Modal window for inserting the aawp shortcode into TinyMCE.
	 *
	 * @since 3.18
	 */
	public function shortcode_modal() {

		$this->editor_style();

		?>
		<div id="aawp-modal-backdrop" style="display: none"></div>
		<div id="aawp-modal-wrap" style="display: none">
			<form id="aawp-modal" tabindex="-1">
				<div id="aawp-modal-title">
					<?php esc_html_e( 'AAWP', 'aawp' ); ?>
					<button type="button" id="aawp-modal-close"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'aawp' ); ?></span></button>
				</div>
				<div id="aawp-modal-inner">

					<div id="aawp-modal-options">
						<?php
						$looks = [
							''           => '-- Select An Option --',
							'box'        => 'Product Boxes',
							'bestseller' => 'Bestseller (Lists)',
							'new'        => 'New Releases (Lists)',
							'fields'     => 'Fields (Single product data)',
							'link'       => 'Text Links',
							'table'      => 'Comparison Table',
						];

						printf( '<p><label for="aawp-modal-display-variant">%s</label></p>', esc_html__( 'Choose your display variant', 'aawp' ) );
						echo '<select id="aawp-modal-display-variant">';
						foreach ( $looks as $value => $look ) {
							printf( '<option value="%s">%s</option>', $value, $look ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						echo '</select>';

						printf( '<div id="aawp-modal-asin-input-container"><label for="aawp-modal-asin-input">%s</label><br/><i>%s</i><br/><input type="input" id="aawp-modal-asin-input"></div>', esc_html__( 'ASIN', 'aawp' ), esc_html__( 'Multiple ASIN Values can be entered separated by comma.' ) );

						printf( '<div id="aawp-modal-keywords-input-container"><label for="aawp-modal-keywords-input">%s</label><br/><i>%s</i><br/><input type="input" id="aawp-modal-keywords-input"></div>', esc_html__( 'Keywords', 'aawp' ), esc_html__( 'E.g. "top 4k monitors"' ) );

						printf( '<div id="aawp-modal-value-select-container"><p><label for="aawp-modal-value-select">%s</label></p>', esc_html__( 'Choose the field value', 'aawp' ) );
						echo '<select id="aawp-modal-value-select">';

						$field_values = [
							'title'       => esc_html__( 'Title', 'aawp' ),
							'description' => esc_html__( 'Description', 'aawp' ),
							'thumb'       => esc_html__( 'Thumbnail', 'aawp' ),
							'star_rating' => esc_html__( 'Star Rating', 'aawp' ),
							'price'       => esc_html__( 'Price', 'aawp' ),
							'button'      => esc_html__( 'Amazon Button', 'aawp' ),
						];

						foreach ( $field_values as $value => $label ) {
							printf( '<option value="%s">%s</option>', $value, $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						echo '</select></div>';

						printf( '<div id="aawp-modal-items-input-container"><label for="aawp-modal-items-input">%s</label><br/><i>%s</i><br/><input type="number" id="aawp-modal-items-input"></div>', esc_html__( 'Number of Items', 'aawp' ), esc_html__( 'Defines the maximum amount of products which will be shown.', 'aawp' ) );

						printf( '<div id="aawp-modal-comparison-table-container"><p><label for="aawp-modal-comparison-table">%s</label></p>', esc_html__( 'Choose your comparison table', 'aawp' ) );

						echo '<select id="aawp-modal-comparison-table">';

						$tables  = \aawp_get_comparison_tables();
						$options = [ '' => '-- Select A Table --' ] + $tables;

						foreach ( $options as $id => $title ) {
							printf( '<option value="%s">%s</option>', absint( $id ), esc_html( $title ) );
						}

						echo '</select></div>';

						?>
					</div>
				</div>
				<div class="submitbox">
					<div id="aawp-modal-cancel">
						<a class="submitdelete deletion" href="#"><?php esc_html_e( 'Cancel', 'aawp' ); ?></a>
					</div>
					<div id="aawp-modal-update">
						<button class="button button-primary" id="aawp-modal-submit"><?php esc_html_e( 'Insert', 'aawp' ); ?></button>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}
