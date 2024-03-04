<?php

namespace FlyingPress;

class License
{
  private static $surecart_key = 'pt_ZFDsoFWUW6hPMLqpQskUYDcz';
  private static $client;

  public static function init()
  {
    // Initialize the SureCart client
    if (!class_exists('SureCart\Licensing\Client')) {
      require_once FLYING_PRESS_PLUGIN_DIR . 'licensing/src/Client.php';
    }
    self::$client = new \SureCart\Licensing\Client(
      'FlyingPress',
      self::$surecart_key,
      FLYING_PRESS_FILE
    );

    add_action('admin_notices', [__CLASS__, 'license_notice']);

    // Simulate weekly license check as always valid
    add_action('flying_press_license_reactivation', [__CLASS__, 'update_license_status']);
    if (!wp_next_scheduled('flying_press_license_reactivation')) {
      wp_schedule_event(time(), 'weekly', 'flying_press_license_reactivation');
    }

    register_activation_hook(FLYING_PRESS_FILE_NAME, [__CLASS__, 'activate_license']);
    add_action('flying_press_upgraded', [__CLASS__, 'check_activation']);
  }

  public static function activate_license($license_key = 'simulated_license_key')
  {
    // Simulate successful license activation
    Config::update_config([
      'license_key' => $license_key,
      'license_active' => true,
      'license_status' => 'active',
    ]);

    return true;
  }

  public static function check_activation()
  {
    // Ensure license is always considered active
    self::activate_license(Config::$config['license_key']);
  }

  public static function update_license_status()
  {
    // Simulate a valid license status update
    Config::update_config([
      'license_status' => 'active',
    ]);
  }

  public static function license_notice()
  {
    // Optional: Adjust or remove the admin notice logic if always valid
    $license_page = admin_url('admin.php?page=flying-press#/settings');

    // Notice logic can remain unchanged or be adjusted as needed
    $config = Config::$config;
    if (!$config['license_active']) {
      echo "<div class='notice notice-error'>
              <p><b>FlyingPress</b>: Your license key is not activated. Please <a href='$license_page'>activate</a> your license key.</p>
            </div>";
      return;
    }

    $status = $config['license_status'];
    if (!in_array($status, ['valid', 'active'])) {
      echo "<div class='notice notice-error'>
              <p><b>FlyingPress</b>: Your license key is $status. Please <a href='$license_page'>activate</a> your license key.</p>
            </div>";
    }
  }
}