<?php

namespace FlyingPress;

class License
{
  private static $surecart_key = 'pt_ZFDsoFWUW6hPMLqpQskUYDcz';
  private static $client;

  public static function init()
  {

    // Check if license key is set
    add_action('admin_notices', [__CLASS__, 'license_notice']);

    // Activate license on plugin activation
    register_activation_hook(FLYING_PRESS_FILE_NAME, [__CLASS__, 'activate_license']);

    // Check activation after upgrade
    add_action('flying_press_upgraded', [__CLASS__, 'check_activation']);
  }

  public static function activate_license($license_key)
  {
    if (!$license_key) {
      return;
    }
    Config::update_config([
      'license_key' => $license_key,
      'license_active' => true,
      'license_status' => 'active'
    ]);

    return true;
  }

  public static function check_activation()
  {
    $config = Config::$config;

    if (!$config['license_key']) {
      return;
    }

    if ($config['license_active']) {
      return;
    }

    self::activate_license($config['license_key']);
  }

  public static function update_license_status()
  {
    Config::update_config([
      'license_status' => 'active',
    ]);
  }

  public static function license_notice()
  {
    $config = Config::$config;

    $license_page = admin_url('admin.php?page=flying-press#/settings');

    // Add notice if the license is not activated
    if (!$config['license_active']) {
      echo "<div class='notice notice-error'>
              <p><b>FlyingPress</b>: Your license key is not activated. Please <a href='$license_page'>activate</a> your license key.</p>
            </div>";
      return;
    }

    // Add notice if the license is invalid
    $status = $config['license_status'];
    if (!in_array($status, ['valid', 'active'])) {
      echo "<div class='notice notice-error'>
              <p><b>FlyingPress</b>: Your license key is $status. Please <a href='$license_page'>activate</a> your license key.</p>
            </div>";
    }
  }
}