<?php
class Options_Framework_Admin {
	protected $options_screen = null;
	public function init() {
		$options = & Options_Framework::_optionsframework_options();
		if ( $options ) {
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ) );
		}
	}

	function settings_init() {
		$optionsframework_settings = get_option( 'optionsframework' );
		register_setting( 'optionsframework', $optionsframework_settings['id'],  array ( $this, 'validate_options' ) );
		add_action( 'optionsframework_after_validate', array( $this, 'save_options_notice' ) );
	}

	static function menu_settings() {
		$menu = array(
			'mode' => 'submenu',
			'page_title' => __( 'Begin主题选项', 'textdomain'),
			'menu_title' => '<span class="menu-icon"> '. __('主题选项', 'textdomain'). '</span>',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'begin-options',
			'parent_slug' => 'themes.php',

			'icon_url' => 'dashicons-admin-generic',
			'position' => '61'
		);
		return apply_filters( 'optionsframework_menu', $menu );
	}


	function add_custom_options_page() {
		$menu = $this->menu_settings();
		$this->options_screen = add_theme_page(
			$menu['page_title'],
			$menu['menu_title'],
			$menu['capability'],
			$menu['menu_slug'],
			array( $this, 'options_page' )
		);
	}

	function enqueue_admin_styles( $hook ) {
		if ( $this->options_screen != $hook )
			return;
		// wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(),  Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	function enqueue_admin_scripts( $hook ) {
		if ( $this->options_screen != $hook )
			return;
		wp_enqueue_script( 'options-custom', OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js', array( 'jquery','wp-color-picker' ), Options_Framework::VERSION );
		add_action( 'admin_head', array( $this, 'of_admin_head' ) );
	}

	function of_admin_head() {
		do_action( 'optionsframework_custom_scripts' );
	}

	 function options_page() { ?>
		<div id="optionsframework-wrap" class="wrap">
		<?php $menu = $this->menu_settings(); ?>

		<div class="be-box">
			<div class="be-inf">
				<div class="begin-ico"><i class="cx cx-begin"></i></div>
				<div class="be-themes">
					<h2 class="themes-name"><?php echo esc_html( $menu['page_title'] ); ?></h2>
					<div class="themes-inf">WordPress&nbsp;多功能主题</div>
				</div>
			</div>
			<div class="be-wrapper">
				<div class="be-nav-menu"></div>
				<div class="be-nav-tab">
					<h2 class="nav-tab-wrapper">
						<?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
					</h2>
				</div>

				<?php settings_errors( 'begin-options' ); ?>
				<div class="be-metabox">
					<div id="optionsframework-metabox" class="metabox-holder">
						<div id="optionsframework" class="postbox">
							<form action="options.php" method="post" class="options-form">
								<?php settings_fields( 'optionsframework' ); ?>
								<?php Options_Framework_Interface::optionsframework_fields(); /* Settings */ ?>
								<div id="optionsframework-submit">
									<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( '保存设置', 'textdomain' ); ?>" />
									<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( '重置所有设置', 'textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( '警告：点击确定，之前所有设置修改都将丢失！', 'textdomain' ) ); ?>' );" />
									<div class="clear"></div>
								</div>
								<div class="be-options-submit" title="保存"><input type="submit" class="be-button-primary" name="update" value="" /></div>
							</form>
						</div> <!-- / #container -->
					</div>
				</div>
			</div>
			<?php do_action( 'optionsframework_after' ); ?>
			<div class="opid">
				<div class="options-caid op-caid" title="分类ID"><span class="dashicons dashicons-editor-ol"></span></div>
				<div class="catid-list op-id-list"><div class="catid-site-box"><span class="arrow-right"></div><div class="catid-site"><div class="catid-t">分类ID</div><?php show_id();?><?php type_show_id();?></div></div>
			</div>
			<div class="opid">
				<div class="special-id op-caid" title="专题ID"><span class="dashicons dashicons-editor-ol"></span></div>
				<div class="special-id-list op-id-list"><div class="catid-site-box"><span class="arrow-right"></div><div class="catid-site"><div class="catid-t">专题ID</div><?php special_show_id();?></div></div>
			</div>
			<div class="themes-help"><a href="http://zmingcx.com/begin-guide.html" title="帮助" target="_blank" rel="external nofollow"><i class="mce-i-wp_help"></i></a></div>
			<div class="all_expand_all" title="展开" ></div>
			<div class="to-top" title="顶部"><span class="dashicons dashicons-location"></span></div>
		</div>
	</div> <!-- / .wrap -->

	<?php
	}

	function validate_options( $input ) {
		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( '已重置所有设置', 'textdomain' ), 'updated fade' );
			return $this->get_default_values();
		}

		$clean = array();
		$options = & Options_Framework::_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], @$input[$id], $option );
			}
		}

		do_action( 'optionsframework_after_validate', $clean );

		return $clean;
	}

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( '设置已保存', 'textdomain' ), 'updated fade' );
		flush_rewrite_rules();
	}

	function get_default_values() {
		$output = array();
		$config = & Options_Framework::_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function optionsframework_admin_bar() {
		$menu = $this->menu_settings();
		global $wp_admin_bar;
		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}
		$args = array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);
		$wp_admin_bar->add_menu( apply_filters( 'optionsframework_admin_bar', $args ) );
	}
}