<?php
namespace WPDRMS\ASP\Core;

use WPDRMS\ASP\Patterns\SingletonTrait;

defined('ABSPATH') or die("You can't access this file directly.");

/**
 * @deprecated Will be removed @2022 Q1
 */
class ScriptsLegacy  {
	use SingletonTrait;

	public function enqueue() {
		$comp_settings = wd_asp()->o['asp_compatibility'];
		$load_in_footer = true;
		$media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_site_option("asp_media_query", "defn");

		$js_source = w_isset_def($comp_settings['js_source'], 'min');
		$load_mcustom = 0;
		$load_lazy = w_isset_def($comp_settings['load_lazy_js'], 0);

		$load_noui = asp_is_asset_required('noui');
		$load_isotope = asp_is_asset_required('isotope');
		$load_chosen = asp_is_asset_required('select2');
		$load_polaroid = asp_is_asset_required('polaroid');

		$minify_string = (($load_noui == 1) ? '-noui' : '') . (($load_isotope == 1) ? '-isotope' : '') . (($load_mcustom == 1) ? '-sb' : '');

		if (ASP_DEBUG) $js_source = 'nomin';

		if ( $load_polaroid ) {
			wp_register_script('wd-asp-photostack', ASP_URL . 'js/legacy/nomin/photostack.js', array("jquery"), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-photostack');
		}

		if ( $load_chosen ) {
			if ( ASP_DEBUG == 1 || defined('WP_ASP_TEST_ENV') ) {
				wp_register_script('wd-asp-select2', ASP_URL . 'js/legacy/nomin/jquery.select2.js', array('jquery'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-select2');
			} else if ( strpos($js_source, 'scoped') !== false ) {
				wp_register_script('wd-asp-select2', ASP_URL . 'js/legacy/min-scoped/jquery.select2.min.js', array('wd-asp-ajaxsearchpro'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-select2');
			} else {
				wp_register_script('wd-asp-select2', ASP_URL . 'js/legacy/min/jquery.select2.min.js', array('jquery'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-select2');
			}
		}

		if ( $load_lazy ) {
			if ( ASP_DEBUG == 1 || defined('WP_ASP_TEST_ENV') ) {
				wp_register_script('wd-asp-lazy', ASP_URL . 'js/legacy/nomin/jquery.lazy.js', array('jquery'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-lazy');
			} else if ( strpos($js_source, 'scoped') !== false ) {
				wp_register_script('wd-asp-lazy', ASP_URL . 'js/legacy/min-scoped/jquery.lazy.min.js', array('wd-asp-ajaxsearchpro'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-lazy');
			} else {
				wp_register_script('wd-asp-lazy', ASP_URL . 'js/legacy/min/jquery.lazy.min.js', array('jquery'), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-lazy');
			}
		}

		if ($js_source == 'nomin' || $js_source == 'nomin-scoped') {
			$prereq = "jquery";
			if ($js_source == "nomin-scoped") {
				$prereq = "wd-asp-aspjquery";
				wp_register_script('wd-asp-aspjquery', ASP_URL . 'js/legacy/' . $js_source . '/aspjquery.js', array(), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-aspjquery');
			}

			wp_register_script('wd-asp-gestures', ASP_URL . 'js/legacy/' . $js_source . '/jquery.gestures.js', array($prereq), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-gestures');
			wp_register_script('wd-asp-mousewheel', ASP_URL . 'js/legacy/' . $js_source . '/jquery.mousewheel.js', array($prereq), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-mousewheel');

			wp_register_script('wd-asp-highlight', ASP_URL . 'js/legacy/' . $js_source . '/jquery.highlight.js', array($prereq), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-highlight');
			if ($load_noui) {
				wp_register_script('wd-asp-nouislider', ASP_URL . 'js/legacy/' . $js_source . '/jquery.nouislider.all.js', array($prereq), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-nouislider');
			}
			if ($load_isotope) {
				wp_register_script('wd-asp-rpp-isotope', ASP_URL . 'js/legacy/' . $js_source . '/rpp_isotope.js', array($prereq), $media_query, $load_in_footer);
				wp_enqueue_script('wd-asp-rpp-isotope');
			}
			wp_register_script('wd-asp-inviewport', ASP_URL . 'js/legacy/' . $js_source . '/jquery.inviewport.js', array($prereq), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-inviewport');

			wp_register_script('wd-asp-ajaxsearchpro', ASP_URL . 'js/legacy/' . $js_source . '/jquery.ajaxsearchpro.js', array($prereq), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-ajaxsearchpro');

			wp_register_script('wd-asp-ajaxsearchpro-widgets', ASP_URL . 'js/legacy/' . $js_source . '/asp_widgets.js', array($prereq, "wd-asp-ajaxsearchpro"), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-ajaxsearchpro-widgets');

			wp_register_script('wd-asp-ajaxsearchpro-wrapper', ASP_URL . 'js/legacy/' . $js_source . '/asp_wrapper.js', array($prereq, "wd-asp-ajaxsearchpro"), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-ajaxsearchpro-wrapper');
		} else {
			wp_enqueue_script('jquery');
			wp_register_script('wd-asp-ajaxsearchpro', ASP_URL . "js/legacy/" . $js_source . "/jquery.ajaxsearchpro" . $minify_string . ".min.js", array('jquery'), $media_query, $load_in_footer);
			wp_enqueue_script('wd-asp-ajaxsearchpro');
		}
	}
}