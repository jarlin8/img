<?php

namespace FlyingPress;

use FilesystemIterator;

class Purge
{
  // Purge a list of HTML pages
  public static function purge_urls($urls)
  {
    do_action('flying_press_purge_urls:before', $urls);

    foreach ($urls as $url) {
      self::purge_url($url);
    }

    do_action('flying_press_purge_urls:after', $urls);
  }

  // Purge a single HTML page
  public static function purge_url($url)
  {
    do_action('flying_press_purge_url:before', $url);

    // Get directory path for the URL
    $host = parse_url($url, PHP_URL_HOST);
    $path = parse_url($url, PHP_URL_PATH);
    $page_cache_dir = FLYING_PRESS_CACHE_DIR . $host . $path;

    // Find all HTML pages in the directory (NOT recursive)
    $pages = glob($page_cache_dir . '/*.html');

    // Delete all HTML pages
    array_map(function ($file) {
      is_file($file) && file_exists($file) && unlink($file);
    }, $pages);

    do_action('flying_press_purge_url:after', $url);
  }

  // Purge all HTML pages
  public static function purge_pages()
  {
    do_action('flying_press_purge_pages:before');

    if (file_exists(FLYING_PRESS_CACHE_DIR . '/preload.txt')) {
      unlink(FLYING_PRESS_CACHE_DIR . '/preload.txt');
    }

    // Delete all HTML pages including subdirectories
    self::delete_all_pages(FLYING_PRESS_CACHE_DIR);

    do_action('flying_press_purge_pages:after');
  }

  // Purge entire cache
  public static function purge_everything()
  {
    do_action('flying_press_purge_everything:before');

    if (file_exists(FLYING_PRESS_CACHE_DIR . '/preload.txt')) {
      unlink(FLYING_PRESS_CACHE_DIR . '/preload.txt');
    }

    // Delete all files and subdirectories
    self::delete_directory(FLYING_PRESS_CACHE_DIR);

    // Create cache directory
    mkdir(FLYING_PRESS_CACHE_DIR, 0755, true);

    do_action('flying_press_purge_everything:after');
  }

  private static function delete_all_pages($path)
  {
    if (!file_exists($path)) {
      return;
    }

    $it = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
    foreach ($it as $fileinfo) {
      if ($fileinfo->isDir()) {
        self::delete_all_pages($fileinfo->getRealPath());
      } else {
        if ($fileinfo->getExtension() === 'html') {
          unlink($fileinfo->getRealPath());
        }
      }
    }

    // Check if directory is empty before removing
    if (iterator_count($it) === 0) {
      rmdir($path);
    }
  }

  private static function delete_directory($path)
  {
    if (!file_exists($path)) {
      return;
    }

    $it = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
    foreach ($it as $fileinfo) {
      if ($fileinfo->isDir()) {
        self::delete_directory($fileinfo->getRealPath());
      } else {
        unlink($fileinfo->getRealPath());
      }
    }

    rmdir($path);
  }
}
