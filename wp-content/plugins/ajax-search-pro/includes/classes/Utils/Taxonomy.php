<?php
namespace WPDRMS\ASP\Utils;

defined('ABSPATH') or die("You can't access this file directly.");

class Taxonomy {
	/**
	 * Gets a list of taxonomy terms, separated by a comma (or as defined)
	 *
	 * @param $taxonomy
	 * @param int $count
	 * @param string $separator
	 * @param array $args arguments passed to get_terms() or wp_get_post_terms() functions
	 * @return string
	 */
	public static function getTermsList($taxonomy, int $count = 5, string $separator = ', ', array $args = array()): string {
		// Additional keys
		$args = array_merge($args, array(
			'taxonomy' => $taxonomy,
			'fields' => 'names',
			'number' => $count
		));
		$terms = wpd_get_terms($args);
		if ( !is_wp_error($terms) && !empty($terms) ) {
			return implode($separator, $terms);
		} else {
			return '';
		}
	}

	public static function isTaxonomyArchive(): bool {
		return is_archive() && ( is_tax() || is_category() || is_tag() );
	}

	public static function getCurrentArchiveURL(): string {
		$return = '';
		if ( self::isTaxonomyArchive() ) {
			$term_id = get_queried_object_id();
			if ( !empty($term_id) && !is_wp_error($term_id) ) {
				$return = get_term_link( $term_id );
				if ( defined('ICL_LANGUAGE_CODE') ) {
					$return = apply_filters( 'wpml_permalink', $return, ICL_LANGUAGE_CODE);
				}
			}
		}
		return $return;
	}
}