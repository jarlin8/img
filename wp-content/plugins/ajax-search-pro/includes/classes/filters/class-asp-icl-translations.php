<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASP_IclTranslations_Filter")) {
	/**
	 * Class WD_ASP_IclTranslations_Filter
	 *
	 * Elementor related filters
	 *
	 * @class         WD_ASP_IclTranslations_Filter
	 * @version       1.1
	 * @package       AjaxSearchPro/Classes/Filters
	 * @category      Class
	 * @author        Ernest Marcinko
	 */
	class WD_ASP_IclTranslations_Filter extends WD_ASP_Filter_Abstract {
		/**
		 * Static instance storage
		 *
		 * @var self
		 */
		protected static $_instance;

		/**
		 * Registered to: apply_filters("asp_query_args", $args, $search_id, $options);
		 */
		public function aspQueryArgsTranslations($args, $search_id) {
			WD_ASP_PLL_Strings::init();
			if ( $args['_ajax_search'] && isset($args["_sd"]['advtitlefield'] ) ) {
				$args["_sd"]['advtitlefield'] = asp_icl_t("Advanced Title Field for Post Type ($search_id)", $args["_sd"]['advtitlefield']);
				$args["_sd"]['user_search_advanced_title_field'] = asp_icl_t("Advanced Title Field for Users ($search_id)", $args["_sd"]['user_search_advanced_title_field']);
				$args["_sd"]['advdescriptionfield'] = asp_icl_t("Advanced Content Field for Post Type ($search_id)", $args["_sd"]['advdescriptionfield']);
				$args["_sd"]['user_search_advanced_description_field'] = asp_icl_t("Advanced Content Field for Users ($search_id)", $args["_sd"]['user_search_advanced_description_field']);
			}
			WD_ASP_PLL_Strings::save();
			return $args;
		}

		// ------------------------------------------------------------
		//   ---------------- SINGLETON SPECIFIC --------------------
		// ------------------------------------------------------------
		public static function getInstance() {
			if ( ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}