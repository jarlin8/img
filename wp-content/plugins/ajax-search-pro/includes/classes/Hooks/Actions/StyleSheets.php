<?php  /** @noinspection PhpUnused */
namespace WPDRMS\ASP\Hooks\Actions;

use WPDRMS\ASP\Asset as Asset;

if (!defined('ABSPATH')) die('-1');

class StyleSheets extends AbstractAction {
	/** @noinspection PhpMissingReturnTypeInspection */
	public function handle() {
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if (isset($screen) && isset($screen->id) && $screen->id == 'widgets') {
				return false;
			}
		}

		// If no instances exist, no need to load any of the stylesheets
		if (
			wd_asp()->instances->exists() &&
			!apply_filters('asp_load_css_js', false) &&
			!apply_filters('asp_load_css', false)
		) {
			Asset\Css\Manager::getInstance()->enqueue();
		}
		return true;
	}

	public function handleHeader() {
		\WPDRMS\ASP\Asset\Css\Manager::getInstance()->headerStartBuffer();
	}

	public function handleFooter() {
		\WPDRMS\ASP\Asset\Css\Manager::getInstance()->print();
	}

	public function fonts() {
		// If custom font loading is disabled, exit
		$comp_options = wd_asp()->o['asp_compatibility'];
		if ( $comp_options['load_google_fonts'] == 1 ) {
			Asset\Font\Manager::getInstance()->print();
		}
	}

	/** @noinspection PhpMissingReturnTypeInspection */
	public function shouldLoadAssets() {
		$comp_settings = wd_asp()->o['asp_compatibility'];

		$exit = false;

		if ( $comp_settings['selective_enabled'] ) {
			if ( is_front_page() ) {
				if ( $comp_settings['selective_front'] == 0 ) {
					$exit = true;
				}
			} else if ( is_archive() ) {
				if ( $comp_settings['selective_archive'] == 0 ) {
					$exit = true;
				}
			} else if ( is_singular() ) {
				if ( $comp_settings['selective_exin'] != '' ) {
					global $post;
					if ( isset($post, $post->ID) ) {
						$_ids = wpd_comma_separated_to_array($comp_settings['selective_exin']);
						if ( !empty($_ids) ) {
							if ( $comp_settings['selective_exin_logic'] == 'exclude' && in_array($post->ID, $_ids) ) {
								$exit = true;
							} else if ( $comp_settings['selective_exin_logic'] == 'include' && !in_array($post->ID, $_ids) ) {
								$exit = true;
							}
						}
					}
				}
			}
		}

		return $exit;
	}
}