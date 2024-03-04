<?php

namespace FlyingPress\Integrations;

use FlyingPress\Purge;
use FlyingPress\Preload;
use FlyingPress\AutoPurge;

class WooCommerce
{
  public static function init()
  {
    // Exclude cart, checkout, and account pages from cache
    add_filter('flying_press_is_cacheable', [__CLASS__, 'is_cacheable']);

    // Add URLs to purge when a product is updated
    add_filter('flying_press_auto_purge_urls', [__CLASS__, 'auto_purge_urls'], 10, 2);

    // Stock updated
    add_action('woocommerce_product_set_stock', [__CLASS__, 'purge_product']);
    add_action('woocommerce_variation_set_stock', [__CLASS__, 'purge_product']);

    // Product updated via batch rest API
    add_action('woocommerce_rest_insert_product_object', [__CLASS__, 'purge_product']);

    // Include Queries
    add_filter('flying_press_cache_include_queries', [__CLASS__, 'cache_include_queries']);
  }

  public static function is_cacheable($is_cacheable)
  {
    if (!class_exists('woocommerce')) {
      return $is_cacheable;
    }

    // If the current page is a WooCommerce cart, checkout, or account page, return false
    if (is_cart() || is_checkout() || is_account_page()) {
      return false;
    }

    return $is_cacheable;
  }

  public static function auto_purge_urls($urls_to_purge, $post_id)
  {
    if (!class_exists('woocommerce')) {
      return $urls_to_purge;
    }

    // Check if post is a product
    $post_type = get_post_type($post_id);
    if ($post_type !== 'product') {
      return $urls_to_purge;
    }

    // Get product related URLs to purge
    $related_urls_to_purge = self::get_product_related_urls($post_id);

    // Merge product related URLs to purge with existing URLs to purge
    $urls_to_purge = [...$urls_to_purge, ...$related_urls_to_purge];

    return $urls_to_purge;
  }

  public static function purge_product($product)
  {
    // Get product URL
    $product_id = $product->get_id();
    $product_url = get_permalink($product_id);

    // Get related URLs to purge
    $urls_to_purge = self::get_product_related_urls($product_id);

    $urls_to_purge[] = $product_url;

    Purge::purge_urls($urls_to_purge);
    Preload::preload_urls($urls_to_purge);
  }

  protected static function get_product_related_urls($product_id)
  {
    $urls = [];

    // Add shop page URL
    $urls[] = get_permalink(wc_get_page_id('shop'));

    // Add product category URLs
    $product_categories = get_the_terms($product_id, 'product_cat') || [];
    foreach ($product_categories as $product_category) {
      $urls[] = get_term_link($product_category);
      $parent_categories = get_ancestors($product_category->term_id, 'product_cat');
      foreach ($parent_categories as $parent_category) {
        $urls[] = get_term_link($parent_category);
      }
    }

    return $urls;
  }

  public static function cache_include_queries($queries)
  {
    if (!class_exists('woocommerce')) {
      return $queries;
    }

    $attribute_filters = [];

    // Get all product attributes
    $product_attributes = wc_get_attribute_taxonomies();

    // Build the available query parameters
    foreach ($product_attributes as $product_attribute) {
      $attribute_filters[] = 'filter_' . $product_attribute->attribute_name;
    }
    // Append to existing queries
    $queries = [...$queries, ...$attribute_filters];

    return $queries;
  }
}
