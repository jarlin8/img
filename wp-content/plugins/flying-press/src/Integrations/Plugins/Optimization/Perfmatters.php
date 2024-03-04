<?php

namespace FlyingPress\Integrations\Plugins\Optimization;

// Plugin: Perfmatters

class Perfmatters
{
  public static function init()
  {
    add_action('flying_press_update_config:after', [__CLASS__, 'disable_conflicting_settings']);
  }

  public static function disable_conflicting_settings()
  {
    if (!defined('PERFMATTERS_VERSION')) {
      return;
    }

    $options = [
      'disable_emojis' => false,
      'disable_dashicons' => false,
      'disable_embeds' => false,
      'disable_xmlrpc' => false,
      'disable_rss_feeds' => false,
      'disable_rest_api' => false,
      'remove_jquery_migrate' => false,
      'disable_heartbeat' => false,
      'remove_global_styles' => false,
      'heartbeat_frequency' => false,
      'limit_post_revisions' => false,
      'assets' => [
        'defer_js' => false,
        'delay_js' => false,
        'delay_timeout' => false,
        'remove_unused_css' => false,
      ],
      'lazyload' => [
        'lazy_loading' => false,
        'lazy_loading_iframes' => false,
        'youtube_preview_thumbnails' => false,
        'image_dimensions' => false,
        'css_background_images' => false,
      ],
      'preload' => [
        'instant_page' => false,
        'critical_images' => 0,
      ],
      'fonts' => [
        'local_google_fonts' => false,
        'display_swap' => false,
        'disable_google_fonts' => false,
      ],
      'cdn' => [
        'enable_cdn' => false,
        'cdn_url' => '',
      ],
    ];

    // Get current perfmatters settings
    $current_options = get_option('perfmatters_options');
    update_option('perfmatters_options', array_replace_recursive($current_options, $options));
  }
}
