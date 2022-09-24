<?php
namespace WPDRMS\ASP\Asset\Css;

use WPDRMS\ASP\Patterns\SingletonTrait;

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if ( !class_exists(__NAMESPACE__ . '\Manager') ) {
	class Manager {
		use SingletonTrait;

		private
			$instance_inline_queue = array(),
			$all_inline_printed = false,
			$method,
			$minify;
		public
			$generator;

		function __construct() {
			$comp_settings = wd_asp()->o['asp_compatibility'];
			$this->method = $comp_settings['css_loading_method']; // optimized, inline, file
			$this->minify = $comp_settings['css_minify'];
			$this->generator = new Generator( $this->minify );
		}

		function enqueue() {
			if ( $this->method != 'inline' ) {
				if ( !$this->generator->verifyFiles() ) {
					$this->generator->generate();
					if ( !$this->generator->verifyFiles() ) {
						$this->method = 'inline';
						return false;
					}
				}
			}

			$media_query = get_site_option("asp_media_query", "defncss");
			if ( $this->method == 'optimized' ) {
				wp_enqueue_style('wpdreams-ajaxsearchpro-basic', $this->url('basic'), array(), $media_query);
			} elseif ( $this->method == 'file' ) {
				wp_enqueue_style('wpdreams-ajaxsearchpro-instances', $this->url('instances'), array(), $media_query);
			}
		}

		function queueInlineIfNeeded($search_id) {
			if ( $this->method != 'file' && !in_array($search_id, $this->instance_inline_queue) ) {
				$this->instance_inline_queue[] = $search_id;
			}
		}

		function printInline() {
			if ( count($this->instance_inline_queue) > 0 ) {
				$css = get_site_option('asp_css', array('basic' => '', 'instances' => array()));
				if ( $this->method == 'inline' ) {
					if ( $css['basic'] != '' ) {
						echo "<style id='asp-instance-style-basic'>" . $css['basic'] . "</style>";
					}
				}
				if ( $this->method != 'file' ) {
					foreach ($this->instance_inline_queue as $search_id) {
						if ( isset($css['instances'][$search_id]) && $css['instances'][$search_id] != '' ) {
							echo "<style id='asp-instance-style-$search_id'>" . $css['instances'][$search_id] . "</style>";
						}
					}
				}
			}
		}

		private function url( $handle ) {
			if ( '' != $file = $this->generator->filename($handle) ) {
				return wd_asp()->upload_url . $file;
			} else {
				return '';
			}
		}
	}
}