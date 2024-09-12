<?php
class Options_Framework {
	const VERSION = '1.8.0';
	public function init() {
		add_action( 'admin_init', array( $this, 'set_theme_option' ) );
	}

	function set_theme_option() {
		$optionsframework_settings = get_option( 'optionsframework' );
		if ( function_exists( 'optionsframework_option_name' ) ) {
			optionsframework_option_name();
		}
		elseif ( has_action( 'optionsframework_option_name' ) ) {
			do_action( 'optionsframework_option_name' );
		}
		else {
			$default_themename = get_option( 'stylesheet' );
			$default_themename = preg_replace( "/\W/", "_", strtolower($default_themename ) );
			$default_themename = 'optionsframework_' . $default_themename;
			if ( isset( $optionsframework_settings['id'] ) ) {
				if ( $optionsframework_settings['id'] == $default_themename ) {
				} else {
					$optionsframework_settings['id'] = $default_themename;
					update_option( 'optionsframework', $optionsframework_settings );
				}
			} else {
				$optionsframework_settings['id'] = $default_themename;
				update_option( 'optionsframework', $optionsframework_settings );
			}
		}
	}

	static function &_optionsframework_options() {
		static $options = null;

		if ( !$options ) {
			$location = apply_filters( 'options_framework_location', array( '/inc/options/begin-options.php' ) );
			if ( $optionsfile = locate_template( $location ) ) {
				$maybe_options = require_once $optionsfile;
				if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
				} else if ( function_exists( 'optionsframework_options' ) ) {
					$options = optionsframework_options();
				}
			}

			$options = apply_filters( 'of_options', $options );
		}
		return $options;
	}
}