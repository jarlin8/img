<?php

namespace AAWP\Admin\ComparisonTable;

/**
 * Comparison Table Settings.
 */
class Settings extends \AAWP_Functions {

	/**
	 * Construct the plugin object
	 */
	public function __construct() {
		// Call parent constructor first
		parent::__construct();

		// Setup identifier
		$this->func_id       = 'table';
		$this->func_name     = __( 'Table Builder', 'aawp' );
		$this->func_listener = 'table';

		// Execute
		$this->hooks();
	}

	/**
	 * Add settings functions
	 */
	public function add_settings_functions_filter( $functions ) {

		$functions[] = $this->func_id;

		return $functions;
	}

	/**
	 * Settings: Register section and fields
	 */
	public function add_settings() {

		add_settings_section(
			'aawp_table_section',
			false,
			false,
			'aawp_functions'
		);

		add_settings_field(
			'aawp_table',
			__( 'Table Builder', 'aawp' ),
			[ &$this, 'settings_render' ],
			'aawp_functions',
			'aawp_table_section',
			[ 'label_for' => 'aawp_table_template' ]
		);
	}

	/**
	 * Settings callbacks
	 */
	public function settings_render() {

		$templates = [
			$this->template_default => __( 'Standard', 'aawp' ),
		];

		$styles = [
			'0' => __( 'Standard', 'aawp' ),
		];

		$template = ( ! empty( $this->options['functions'][ $this->func_id . '_template' ] ) ) ? $this->options['functions'][ $this->func_id . '_template' ] : '';
		$style    = ( ! empty( $this->options['functions'][ $this->func_id . '_style' ] ) ) ? $this->options['functions'][ $this->func_id . '_style' ] : '';

		$label_col_options = aawp_admin_table_get_label_col_options();
		$label_col         = ( ! empty( $this->options['functions'][ $this->func_id . '_labels' ] ) ) ? $this->options['functions'][ $this->func_id . '_labels' ] : 'show';

		$highlight_bg_color = ( ! empty( $this->options['functions'][ $this->func_id . '_highlight_bg_color' ] ) ) ? $this->options['functions'][ $this->func_id . '_highlight_bg_color' ] : aawp_get_default_highlight_bg_color();
		$highlight_color    = ( ! empty( $this->options['functions'][ $this->func_id . '_highlight_color' ] ) ) ? $this->options['functions'][ $this->func_id . '_highlight_color' ] : aawp_get_default_highlight_bg_color();
		?>

		<!-- Template -->
		<h4 class="first"><?php _e( 'Default Template', 'aawp' ); ?></h4>
		<p>
			<select id="aawp_<?php echo $this->func_id; ?>_template" name="aawp_functions[<?php echo $this->func_id; ?>_template]">
				<?php foreach ( $templates as $key => $label ) { ?>
					<option value="<?php echo $key; ?>" <?php selected( $template, $key ); ?>><?php echo $label; ?></option>
				<?php } ?>
			</select>
		</p>

		<!-- Labels -->
		<h4><?php _e( 'Labels', 'aawp' ); ?></h4>
		<p>
			<select id="aawp_<?php echo $this->func_id; ?>_labels" name="aawp_functions[<?php echo $this->func_id; ?>_labels]">
				<?php foreach ( $label_col_options as $key => $label ) { ?>
					<option value="<?php echo $key; ?>" <?php selected( $label_col, $key ); ?>><?php echo $label; ?></option>
				<?php } ?>
			</select>
		</p>

		<!-- Style -->
		<h4><?php _e( 'Default style', 'aawp' ); ?></h4>
		<p>
			<select id="aawp_<?php echo $this->func_id; ?>_style" name="aawp_functions[<?php echo $this->func_id; ?>_style]">
				<?php foreach ( $styles as $key => $label ) { ?>
					<option value="<?php echo $key; ?>" <?php selected( $style, $key ); ?>><?php echo $label; ?></option>
				<?php } ?>
			</select>
		</p>

		<!-- Highlight rows -->
		<h4><?php _e( 'Highlight rows', 'aawp' ); ?></h4>
		<div>
			<div class="aawp-color-picker-inline">
				<label for="aawp_<?php echo $this->func_id; ?>_highlight_bg_color"><?php _e( 'Background color', 'aawp' ); ?></label>
				<input id="aawp_<?php echo $this->func_id; ?>_highlight_bg_color" name="aawp_functions[<?php echo $this->func_id; ?>_highlight_bg_color]" type="text" class="aawp-input-colorpicker" value="<?php echo $highlight_bg_color; ?>" />
			</div>
			<div class="aawp-color-picker-inline">
				<label for="aawp_<?php echo $this->func_id; ?>_highlight_color"><?php _e( 'Font color', 'aawp' ); ?></label>
				<input id="aawp_<?php echo $this->func_id; ?>_highlight_color" name="aawp_functions[<?php echo $this->func_id; ?>_highlight_color]" type="text" class="aawp-input-colorpicker" value="<?php echo $highlight_color; ?>" />
			</div>
		</div>

		<?php
		do_action( 'aawp_settings_functions_table_render' );
	}

	/*
	* Hooks & Actions
	*/
	public function hooks() {

		// Settings functions
		add_filter( $this->settings_functions_filter, [ &$this, 'add_settings_functions_filter' ] );

		add_action( 'aawp_settings_functions_register', [ &$this, 'add_settings' ], 60 );
	}
}
