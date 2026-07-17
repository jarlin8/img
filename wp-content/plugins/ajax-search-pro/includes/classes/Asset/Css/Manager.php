<?php
namespace WPDRMS\ASP\Asset\Css;

use WPDRMS\ASP\Patterns\SingletonTrait;
use WPDRMS\ASP\Utils\Html;

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

class Manager {
	use SingletonTrait;

	private
		$instance_inline_queue = array(),
		$method,	// file, optimized, inline
		$ob_level = -1,
		$media_query,
		$injected = false,
		$minify;
	public
		$generator;

	function __construct() {
		$comp_settings = wd_asp()->o['asp_compatibility'];
		$this->method = $comp_settings['css_loading_method']; // optimized, inline, file
		$this->minify = $comp_settings['css_minify'];
		$this->media_query = get_site_option("asp_media_query", "defncss");
		$this->generator = new Generator( $this->minify );

		/**
		 * Call order:
		 *  wp_enqueue_scripts 			-> enqueue()
		 *  wp_head 					-> headerStartBuffer()  -> start buffer
		 *  wp_print_footer_scripts 	-> print()				-> end buffer trigger
		 */
	}

	/**
	 * Called at wp_enqueue_scripts
	 */
	function enqueue() {
		if ( $this->method == 'file' ) {
			if ( !$this->generator->verifyFiles() ) {
				$this->generator->generate();
				if ( !$this->generator->verifyFiles() ) {
					$this->method = 'inline';
					return;
				}
			}
			wp_enqueue_style('asp-instances', $this->url('instances'), array(), $this->media_query);
		}
	}

	/**
	 * Called at wp_head
	 * @noinspection PhpUnused
	 */
	function headerStartBuffer() {
		if ( $this->method != 'file' ) {
			ob_start(array($this, 'injectFromBuffer'));
			$this->ob_level = ob_get_level();
		}
	}

	/**
	 * Called at wp_print_footer_scripts
	 */
	function print() {
		if ( $this->method != 'file' ) {
			// Trigger $this->injectFromBuffer()
			if( ob_get_length() > 0 ) {
				ob_end_flush();
			}

			// At this point the scripts should be injected, but just in case check and print if needed
			if ( count($this->instance_inline_queue) > 0 && !$this->injected ) {
				echo $this->getBasic();
				echo $this->getInstances();
			}
		}
	}

	function queueInlineIfNeeded($search_id) {
		$search_id = (int) $search_id;
		if ( $this->method != 'file' && !in_array($search_id, $this->instance_inline_queue) ) {
			if ( $search_id !== 0 ) {
				$this->instance_inline_queue[] = $search_id;
				$this->instance_inline_queue = array_unique($this->instance_inline_queue);
				sort($this->instance_inline_queue);
			}
		}
	}

	private function getBasic(): string {
		$output = '';
		if ( $this->method == 'inline' ) {
			$css = get_site_option('asp_css', array('basic' => '', 'instances' => array()));
			if ( $css['basic'] != '' ) {
				$output .= "<style id='asp-basic'>" . $css['basic'] . "</style>";
			}
		} else if ( $this->method == 'optimized' ) {
			$output = '<link rel="stylesheet" id="asp-basic" href="' . $this->url('basic') . '?mq='.$this->media_query.'" media="all" />';
		}
		return $output;
	}

	private function getInstances(): string {
		$css = get_site_option('asp_css', array('basic' => '', 'instances' => array()));
		$output = '';
		foreach ($this->instance_inline_queue as $search_id) {
			if ( isset($css['instances'][$search_id]) && $css['instances'][$search_id] != '' ) {
				$output .= "<style id='asp-instance-$search_id'>" . $css['instances'][$search_id] . "</style>";
			}
		}
		return $output;
	}

	private function injectFromBuffer($buffer, $phase) {
		if ($phase & PHP_OUTPUT_HANDLER_FINAL || $phase & PHP_OUTPUT_HANDLER_END) {
			$this->getInstancesFromString($buffer);
			if ( !empty($this->instance_inline_queue) ) {
				$output = $this->getBasic();
				$output .= $this->getInstances();
				$this->injected = Html::inject($output, $buffer);
			}
		}
		return $buffer;
	}

	private function getInstancesFromString($out): array {
		if ( $out !== false && $out !== '' ) {
			if ( preg_match_all('/data-asp-id=["\'](\d+)[\'"]\s/', $out, $matches) > 0 ) {
				foreach ( $matches[1] as $search_id ) {
					$search_id = (int) $search_id;
					if ( $search_id !== 0 ) {
						$this->instance_inline_queue[] = $search_id;
						$this->queueInlineIfNeeded( $search_id );
					}
				}
			}
		}
		$this->instance_inline_queue = array_unique($this->instance_inline_queue);
		sort($this->instance_inline_queue);
		return $this->instance_inline_queue;
	}

	private function url( $handle ): string {
		if ( '' != $file = $this->generator->filename($handle) ) {
			return wd_asp()->upload_url . $file;
		} else {
			return '';
		}
	}
}