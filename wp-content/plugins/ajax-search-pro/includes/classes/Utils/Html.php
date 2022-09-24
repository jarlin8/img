<?php
namespace WPDRMS\ASP\Utils;

defined('ABSPATH') or die("You can't access this file directly.");

if ( !class_exists(__NAMESPACE__ . '\Html') ) {
	class Html {
		public static function toTxt( string $document ) {
			$search = array(
				'@<script[^>]*?>.*?</script>@si', // Strip out javascript
				'@<style[^>]*?>.*?</style>@si', // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments including CDATA
				'@<[\/\!]*?[^<>]*?>@si' // Strip out HTML tags
			);
			return preg_replace( $search, ' ', $document );
		}

		/** @noinspection HtmlRequiredLangAttribute */
		public static function extractIframeContent(string $str ): string {
			/** @noinspection All */
			preg_match_all('/\<iframe.+?src=[\'"]([^"\']+)["\']/', $str, $match);
			if ( isset($match[1]) ) {
				$ret = '';
				foreach($match[1] as $link) {
					$s = wp_remote_get($link);
					if ( !is_wp_error($s) ) {
						$xs = explode('<body', $s['body']);
						$final = $s['body'];
						if ( isset($xs[1]) ) {
							$final = '<html><body ' . $xs[1];
						}
						$ret .= ' ' . Str::stripTagsWithContent($final, array('head','script', 'style', 'img', 'input'));
					}
				}
				return $ret;
			}
			return '';
		}
	}
}