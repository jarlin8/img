<?php

namespace AAWP\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin as ElementorPlugin;

/**
 * Elementor AAWP Widget.
 *
 * @since 3.19
 */
class Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve AAWP widget name.
	 *
	 * @since 3.19
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'aawp';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve AAWP widget title.
	 *
	 * @since 3.19
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'AAWP', 'aawp' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve AAWP widget icon.
	 *
	 * @since 3.19
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'icon-aawp';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 3.19
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://getaawp.com/docs/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the AAWP widget belongs to.
	 *
	 * @since 3.19
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the AAWP widget belongs to.
	 *
	 * @since 3.19
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'aawp', 'amazon', 'link' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.19
	 */
	protected function register_controls() {

		$this->content_controls();
	}
	/**
	 * Register content tab controls.
	 *
	 * @since 3.19
	 */
	protected function content_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'aawp' ),
			]
		);

		$looks = [
			''           => esc_html__( '-- Select An Option --', 'aawp' ),
			'box'        => 'Product Boxes',
			'bestseller' => 'Bestseller (Lists)',
			'new'        => 'New Releases (Lists)',
			'fields'     => 'Fields (Single product data)',
			'link'       => 'Text Links',
			'table'      => 'Comparison Table',
		];

		$this->add_control(
			'display_variant',
			[
				'label'       => esc_html__( 'Display Variant', 'aawp' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => $looks,
				'default'     => '',
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$this->add_control(
			'asin',
			[
				'label'       => esc_html__( 'ASIN', 'aawp' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '',
				'condition'   => [
					'display_variant' => [ 'box', 'fields', 'link' ],
				],
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$this->add_control(
			'keywords',
			[
				'label'       => esc_html__( 'Keywords', 'aawp' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '',
				'placeholder' => 'E.g. top 4k monitors',
				'condition'   => [
					'display_variant' => [ 'bestseller', 'new' ],
				],
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$field_values = [
			'title'       => esc_html__( 'Title', 'aawp' ),
			'description' => esc_html__( 'Description', 'aawp' ),
			'thumb'       => esc_html__( 'Thumbnail', 'aawp' ),
			'star_rating' => esc_html__( 'Star Rating', 'aawp' ),
			'price'       => esc_html__( 'Price', 'aawp' ),
			'button'      => esc_html__( 'Amazon Button', 'aawp' ),
		];

		$this->add_control(
			'field_value',
			[
				'label'       => esc_html__( 'Choose the field value', 'aawp' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => $field_values,
				'default'     => 'title',
				'condition'   => [
					'display_variant' => 'fields',
				],
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Number of items', 'aawp' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'default'     => '10',
				'condition'   => [
					'display_variant' => [ 'bestseller', 'new' ],
				],
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$tables = \aawp_get_comparison_tables();

		$this->add_control(
			'table',
			[
				'label'       => esc_html__( 'Choose your comparison table', 'aawp' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => $tables,
				'condition'   => [
					'display_variant' => 'table',
				],
				'default'     => '',
				'classes'     => 'aawp-el-widget-control',
			]
		);

		$this->add_control(
			'divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'documentation',
			[
				'show_label'      => false,
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					wp_kses( /* translators: %s - AAWP documentation link. */
						__( 'Heads up! The section below is intended for advanced users. Please <a href="%s" target="_blank" rel="noopener noreferrer">check out our complete guide</a> and adjust the shortcode generated below.', 'aawp' ),
						[
							'br' => [],
							'a'  => [
								'href'   => [],
								'rel'    => [],
								'target' => [],
							],
						]
					),
					'https://getaawp.com/docs/article/shortcodes/'
				),
				'condition'       => [
					'display_variant!' => '',
				],
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'generated_shortcode',
			[
				'label'     => esc_html__( 'Generated Shortcode', 'aawp' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => [
					'display_variant!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render aawp widget output.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 3.19
	 * @access protected
	 */
	protected function render() {

		if ( ElementorPlugin::$instance->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	/**
	 * Render widget output in edit mode.
	 *
	 * @since 3.19
	 */
	protected function render_edit_mode() {

		// Render in frontend anyway, because we don't render anything on edit mode yet.
		$this->render_frontend();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 3.19
	 */
	protected function render_frontend() {

		echo do_shortcode( $this->render_shortcode() );
	}

	/**
	 * Render widget as plain content.
	 *
	 * @since 3.19
	 */
	public function render_plain_content() {

		echo $this->render_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render shortcode.
	 *
	 * @since 3.19
	 */
	public function render_shortcode() {

		return sanitize_textarea_field( $this->get_settings_for_display( 'generated_shortcode' ) );
	}
}
