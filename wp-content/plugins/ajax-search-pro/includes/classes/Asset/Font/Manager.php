<?php
namespace WPDRMS\ASP\Asset\Font;

/* Prevent direct access */

use WPDRMS\ASP\Patterns\SingletonTrait;

defined('ABSPATH') or die("You can't access this file directly.");

if ( !class_exists(__NAMESPACE__ . '\Manager') ) {
	class Manager {
		use SingletonTrait;

		public function print() {
			$generator = new Generator();
			$fonts = $generator->generate();
			if ( count($fonts) > 0 ) {
				$stored_fonts = get_site_option('asp_fonts', array());
				$key = md5(implode('|', $fonts));
				$fonts_css = '';
				if ( isset($stored_fonts[$key]) ) {
					$fonts_css = $stored_fonts[$key];
				} else {
					$fonts_request = wp_safe_remote_get( 'https://fonts.googleapis.com/css?family=' . implode('|', $fonts) . "&display=swap");
					if ( !is_wp_error($fonts_request) ) {
						$fonts_css = wp_remote_retrieve_body($fonts_request);
						if ( $fonts_css != '' ) {
							$stored_fonts[$key] = $fonts_css;
							update_site_option('asp_fonts', $stored_fonts);
						}
					}
				}
				if ( !is_wp_error($fonts_css) && $fonts_css != '' ) {
					// Do NOT preload the fonts - it will give worst PagesPeed score. Preconnect is sufficient.
					?>
					<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
					<style>
						<?php echo $fonts_css; ?>
					</style>
					<?php
				} else {
					?>
					<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
					<link rel="preload" as="style" href="//fonts.googleapis.com/css?family=<?php echo implode('|', $fonts); ?>&display=swap" />
					<link rel="stylesheet" href="//fonts.googleapis.com/css?family=<?php echo implode('|', $fonts); ?>&display=swap" media="all" />
					<?php
				}
			}
		}
	}
}