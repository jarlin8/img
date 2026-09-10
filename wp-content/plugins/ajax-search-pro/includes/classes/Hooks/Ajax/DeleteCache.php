<?php
/** @noinspection PhpMissingParamTypeInspection */
/** @noinspection PhpMissingReturnTypeInspection */

namespace WPDRMS\ASP\Hooks\Ajax;

use WPDRMS\ASP\Cache\TextCache;
use WPDRMS\ASP\Utils\Ajax;

if (!defined('ABSPATH')) die('-1');


class DeleteCache extends AbstractAjax {
	/**
	 * Deletes the Ajax Search Pro directory
	 */
	public function handle( $exit = true ) {
		$count = 0;
		if ( !empty(wd_asp()->upload_path) && wd_asp()->upload_path !== '' )
			$count = $this->delFiles(wd_asp()->upload_path, '*.wpd');
		if ( !empty(wd_asp()->bfi_path) && wd_asp()->bfi_path !== '' ) {
			$count = $count + $this->delFiles(wd_asp()->bfi_path, '*.jpg');
			$count = $count + $this->delFiles(wd_asp()->bfi_path, '*.jpeg');
			$count = $count + $this->delFiles(wd_asp()->bfi_path, '*.png');
		}

		// Clear database cache
		$count = $count + TextCache::clearDBCache();

		if ( $exit !== false ) {
			Ajax::prepareHeaders();
			print $count;
			die();
		}
	}

	/**
	 * Delete *.wpd files in directory
	 *
	 * @param $dir string
	 * @param $file_arg string
	 * @return int files and directories deleted
	 */
	private function delFiles($dir, $file_arg = '*.*') {
		$count = 0;
		$files = @glob($dir . $file_arg, GLOB_MARK);
		// Glob can return FALSE on error
		if ( is_array($files) ) {
			foreach ($files as $file) {
				wpd_del_file($file);
				$count++;
			}
		}
		return $count;
	}
}