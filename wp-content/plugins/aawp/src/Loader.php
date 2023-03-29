<?php

namespace AAWP;

/**
 * The Loader Class.
 */
class Loader {

	/**
	 * Initialize. All the classes that should be initiated loads from here on.
	 *
	 * @return void.
	 */
	public function init() {

		$this->prepare_classes();
	}

	/**
	 * Prepare Classes to load. Ideally the classes should run from respective modules. Example: ActivityLogs/init.
	 * All other classes for ActivityLogs are loaded from inside the ActivityLogs module.
	 *
	 * @return void.
	 */
	public function prepare_classes() {

		$classes = [

			// Block.
			'Block',

			// Shortener.
			'ShortenLinks\\Process',
			'ShortenLinks\\DB',
			'ShortenLinks\\BitlyAPI',

			// ActivityLogs.
			'ActivityLogs\\Init',

			// Comparison Tables Shortcode Handler.
			'Admin\\ComparisonTable\\ShortcodeHandler',

			// Click Tracking.
			'ClickTracking\\Init',

			// Advanced Ads.
			'Admin\\AdvancedAds\\Init',
			'Elementor\\Elementor',
			'API\\UsageTracking',
			'API\\Notifications',
		];

		if ( is_admin() ) {

			$admin_classes = [
				'Admin\\ClassicEditor',
				'Admin\\MetaBox',

				// Admin Pages.
				'Admin\\Menu',
				'Admin\\Tools',
				'Admin\\Tools\\Support', // Only required because of the register settings field.

				// Comparison Table.
				'Admin\\ComparisonTable\\Table',
				'Admin\\ComparisonTable\\DuplicateTable',
				'Admin\\ComparisonTable\\Settings',

				// Shortener Settings.
				'ShortenLinks\\Settings',

				// Admin Settings.
				'Admin\\Settings\\API',
				'Admin\\Settings\\Functions',
				'Admin\\Settings\\General',
				'Admin\\Settings\\License',
				'Admin\\Settings\\Output',

				// Welcome.
				'Admin\\Welcome',

				// ProductsTable.
				'Admin\\ProductsTable\\Init',

				// Flyout.
				'Admin\\Flyout',
			];

			$classes = array_merge( $classes, $admin_classes );
		}//end if

		$this->load_classes( $classes );
	}

	/**
	 * Now load the classes from their init() method if exists.
	 *
	 * @param array $classes An array of classes to load.
	 *
	 * @since 3.18
	 */
	public function load_classes( $classes ) {

		foreach ( $classes as $class ) {
			if ( \class_exists( __NAMESPACE__ . '\\' . $class ) ) {
				$class = __NAMESPACE__ . '\\' . $class;
				$obj   = new $class();

				if ( method_exists( $obj, 'init' ) ) {
					$obj->init();
				}
			}
		}
	}
}
