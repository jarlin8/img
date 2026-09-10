<?php

namespace ContentEgg;

/*
  Plugin Name: Content Egg Pro
  Plugin URI: https://www.keywordrush.com/contentegg
  Description: Multi-network affiliate plugin — imports, price comparison, automation, and structured blocks for affiliate content.
  Version: 21.11.1
  Requires at least: 6.9
  Requires PHP: 8.0
  Tested up to: 7.1
  Author: keywordrush.com
  Author URI: https://www.keywordrush.com
  Text Domain: content-egg
  Domain Path: /languages
 */

/*
 * Copyright (c)  www.keywordrush.com  (email: support@keywordrush.com)
 */

defined('\ABSPATH') || die('No direct script access allowed!');

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');
define(NS . 'PLUGIN_PATH', \plugin_dir_path(__FILE__));
define(NS . 'PLUGIN_FILE', __FILE__);
define(NS . 'PLUGIN_DIR_URL', \plugins_url('', __FILE__));
define(NS . 'PLUGIN_RES', \plugins_url('res', __FILE__));
define(NS . 'CUSTOM_MODULES_DIR', 'content-egg-modules');

require_once PLUGIN_PATH . 'loader.php';

if (empty(\get_option('content-egg_lic')['license_key']))
{
  \update_option('content-egg_lic', array('license_key' => 'local-dev-B5E0B5F8DD8689E6ACA49DD6E6E1A930'));
}

if (!is_array(\get_option('cegg_status')))
{
  \update_option('cegg_status', array('license' => array('status' => 'active', 'is_lifetime' => true)), false);
}

\add_action('plugins_loaded', array('\ContentEgg\application\Plugin', 'registerComponents'));
\add_action('init', array('\ContentEgg\application\Plugin', 'getInstance'));

if (\is_admin())
{
  \register_activation_hook(__FILE__, array(\ContentEgg\application\Installer::getInstance(), 'activate'));
  \register_deactivation_hook(__FILE__, array(\ContentEgg\application\Installer::getInstance(), 'deactivate'));
  \register_uninstall_hook(__FILE__, array('\ContentEgg\application\Installer', 'uninstall'));

  \add_action('init', array('\ContentEgg\application\admin\PluginAdmin', 'getInstance'));
}
