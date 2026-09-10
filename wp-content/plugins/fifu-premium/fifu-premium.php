<?php

/*
 * Plugin Name: Featured Image from URL (FIFU) Premium
 * Plugin URI: https://fifu.app/
 * Description: Use an external image/video/audio as featured image of a post or WooCommerce product.
 * Version: 6.2.2
 * Author: fifu.app
 * Author URI: https://fifu.app/
 * WC requires at least: 4.0
 * WC tested up to: 8.0.2
 * Text Domain: fifu-premium
 * License: Proprietary License
 */
update_option('fifu_key', '***************');
update_option('fifu_email', 'mail@gmail.com');
delete_option('fifu_expired');
delete_option('fifu_ck');
delete_option('fifu_lock');
define('FIFU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FIFU_INCLUDES_DIR', FIFU_PLUGIN_DIR . 'includes');
define('FIFU_ADMIN_DIR', FIFU_PLUGIN_DIR . 'admin');
define('FIFU_ELEMENTOR_DIR', FIFU_PLUGIN_DIR . 'elementor');
define('FIFU_GRAVITY_DIR', FIFU_PLUGIN_DIR . 'gravity-forms');
define('FIFU_LANGUAGES_DIR', WP_CONTENT_DIR . '/uploads/fifu/languages/');
define('FIFU_PUC_DIR', FIFU_PLUGIN_DIR . 'plugin-update-checker');
define('FIFU_DELETE_ALL_URLS', false);
define('FIFU_CLOUD_DEBUG', false);

$FIFU_SESSION = array();

require_once (FIFU_INCLUDES_DIR . '/attachment.php');
require_once (FIFU_INCLUDES_DIR . '/bbpress.php');
require_once (FIFU_INCLUDES_DIR . '/convert-url.php');
require_once (FIFU_INCLUDES_DIR . '/external-post.php');
require_once (FIFU_INCLUDES_DIR . '/local.php');
require_once (FIFU_INCLUDES_DIR . '/jetpack.php');
require_once (FIFU_INCLUDES_DIR . '/rest.php');
require_once (FIFU_INCLUDES_DIR . '/shortcode.php');
require_once (FIFU_INCLUDES_DIR . '/speedup.php');
require_once (FIFU_INCLUDES_DIR . '/thumbnail.php');
require_once (FIFU_INCLUDES_DIR . '/thumbnail-category.php');
require_once (FIFU_INCLUDES_DIR . '/util.php');
require_once (FIFU_INCLUDES_DIR . '/video.php');
require_once (FIFU_INCLUDES_DIR . '/woo.php');

require_once (FIFU_ADMIN_DIR . '/amazon.php');
require_once (FIFU_ADMIN_DIR . '/api.php');
require_once (FIFU_ADMIN_DIR . '/books.php');
require_once (FIFU_ADMIN_DIR . '/category.php');
require_once (FIFU_ADMIN_DIR . '/column.php');
require_once (FIFU_ADMIN_DIR . '/cron.php');
require_once (FIFU_ADMIN_DIR . '/db.php');
require_once (FIFU_ADMIN_DIR . '/ddg.php');
require_once (FIFU_ADMIN_DIR . '/finder.php');
require_once (FIFU_ADMIN_DIR . '/lightbox.php');
require_once (FIFU_ADMIN_DIR . '/log.php');
require_once (FIFU_ADMIN_DIR . '/media-library.php');
require_once (FIFU_ADMIN_DIR . '/menu.php');
require_once (FIFU_ADMIN_DIR . '/meta-box.php');
require_once (FIFU_ADMIN_DIR . '/proxy.php');
require_once (FIFU_ADMIN_DIR . '/rsa.php');
require_once (FIFU_ADMIN_DIR . '/strings.php');
require_once (FIFU_ADMIN_DIR . '/widgets.php');
require_once (FIFU_ADMIN_DIR . '/wai-addon.php');

require_once (FIFU_ELEMENTOR_DIR . '/elementor-fifu-extension.php');

if (fifu_is_gravity_forms_active()) {
    require_once (WP_PLUGIN_DIR . '/gravityforms/gravityforms.php');
    if (class_exists('GFForms'))
        require_once (FIFU_GRAVITY_DIR . '/fifufieldaddon.php');
}

require(FIFU_PUC_DIR . '/Puc/v4p11/Autoloader.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Factory.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/InstalledPackage.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Metadata.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/OAuthSignature.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Scheduler.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/StateStore.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Update.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/UpdateChecker.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/UpgraderStatus.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Utils.php');

require(FIFU_PUC_DIR . '/Puc/v4p11/Plugin/Info.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Plugin/Package.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Plugin/Ui.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Plugin/Update.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Plugin/UpdateChecker.php');

require(FIFU_PUC_DIR . '/Puc/v4p11/Theme/Package.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Theme/UpdateChecker.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Theme/Update.php');

require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/Api.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/BaseChecker.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/BitBucketApi.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/GitHubApi.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/GitLabApi.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/PluginUpdateChecker.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/Reference.php');
require(FIFU_PUC_DIR . '/Puc/v4p11/Vcs/ThemeUpdateChecker.php');

if (class_exists('Debug_Bar', false)) {
    require(FIFU_PUC_DIR . '/Puc/v4p11/DebugBar/Panel.php');
    require(FIFU_PUC_DIR . '/Puc/v4p11/DebugBar/PluginPanel.php');
    require(FIFU_PUC_DIR . '/Puc/v4p11/DebugBar/ThemePanel.php');
    require(FIFU_PUC_DIR . '/Puc/v4p11/DebugBar/Extension.php');
    require(FIFU_PUC_DIR . '/Puc/v4p11/DebugBar/PluginExtension.php');
}

if (defined('WP_CLI') && WP_CLI)
    require_once (FIFU_ADMIN_DIR . '/cli-commands.php');

register_activation_hook(__FILE__, 'fifu_activate');

function fifu_activate($network_wide) {
    // https://multilingualpress.org/docs/how-to-install-wordpress-multisite/
    if (is_multisite() && $network_wide) {
        global $wpdb;
        foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs") as $blog_id) {
            switch_to_blog($blog_id);
            fifu_activate_actions();
        }
    } else {
        fifu_activate_actions();
    }
    update_option('fifu_activation_time', time(), 'no');
    fifu_set_author();
    fifu_propagate_key($network_wide);
}

function fifu_activate_actions() {
    update_option('fifu_update_all', 'toggleoff', 'no');
    update_option('fifu_getimagesize', 1, 'no');
    fifu_db_change_url_length();
    fifu_db_create_table_video_oembed();

    if (!get_option('fifu_install'))
        update_option('fifu_install', date('Y-m-d H:i:s'), 'no');

    delete_site_option('external_updates-fifu-premium');
}

register_deactivation_hook(__FILE__, 'fifu_deactivation');

function fifu_deactivation() {
    delete_site_option('external_updates-fifu-premium');

    if (fifu_is_on('fifu_cron_metadata'))
        wp_clear_scheduled_hook('fifu_create_metadata_event');

    wp_clear_scheduled_hook('fifu_create_auto_set_event');

    wp_clear_scheduled_hook('fifu_create_isbn_event');

    wp_clear_scheduled_hook('fifu_create_finder_event');

    wp_clear_scheduled_hook('fifu_create_tags_event');

    wp_clear_scheduled_hook('fifu_create_upload_event');

    wp_clear_scheduled_hook('fifu_create_cloud_upload_auto_event');
}

add_action('upgrader_process_complete', 'fifu_upgrade', 10, 2);

function fifu_upgrade($upgrader_object, $options) {
    $current_plugin_path_name = plugin_basename(__FILE__);
    if ($options['action'] == 'update' && $options['type'] == 'plugin') {
        if (isset($options['plugins'])) {
            foreach ((array) $options['plugins'] as $each_plugin) {
                if ($each_plugin == $current_plugin_path_name) {
                    fifu_db_change_url_length();
                    fifu_db_create_table_video_oembed();
                    fifu_db_update_autoload();
                    fifu_db_delete_deprecated_data();
                }
            }
        }
    }
    if ($options['type'] == 'core') {
        fifu_db_change_url_length();
        fifu_db_fix_guid();
    }
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'fifu_action_links');
add_filter('network_admin_plugin_action_links_' . plugin_basename(__FILE__), 'fifu_action_links');

function fifu_action_links($links) {
    $strings = fifu_get_strings_plugins();
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=fifu-premium')) . '">' . $strings['settings']() . '</a>';
    return $links;
}

add_action('fifu_event', 'fifu_event_function');

function fifu_event_function() {
    require_once (FIFU_ADMIN_DIR . '/menu.php');
    fifu_db_update_all();
}

require 'plugin-update-checker/plugin-update-checker.php';
$fifuUpdateChecker = Puc_v4_Fifu_Factory::buildUpdateChecker(
                'https://update.fifu.app/details',
                __FILE__,
                'fifu-premium'
);

// add the license key to query arguments
$fifuUpdateChecker->addQueryArgFilter('fifu_filter_update_checks');

function fifu_filter_update_checks($queryArgs) {
    $queryArgs['license_key'] = get_option('fifu_key');
    $queryArgs['domain'] = parse_url(get_site_url())['host'];
    return $queryArgs;
}

function fifu_check_updates($should_check, $arg1 = null, $arg2 = null) {
    if (get_option('fifu_activation_time')) {
        delete_option('fifu_activation_time');
        wp_cache_flush();
        return true;
    }
    return $should_check;
}

add_filter('puc_check_now-fifu-premium', 'fifu_check_updates', 10, 3);

function fifu_expired_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {
    $strings = fifu_get_strings_plugins();

    if (strpos($plugin_file, 'fifu-premium.php') !== false) {
        if (get_option('fifu_expired'))
            $tag = '<a href="https://ws.featuredimagefromurl.com/keys/" target="_blank" style="color:#d63638"><b>' . $strings['expired']() . '</b></a>';
        else {
            $tag = '<a href="https://ws.featuredimagefromurl.com/keys/" target="_blank">' . $strings['manager']() . '</a>';
        }
        $new_links = array(
            'email' => '<a style="width:184px;padding:5px;color:white;background-color:#02a0d2"><b>support</b>@featuredimagefromurl.com</a>',
            'renew' => $tag,
            'affiliate' => '<a href="https://referral.fifu.app" target="_blank">' . $strings['affiliate']() . '</a>',
        );
        $plugin_meta = array_merge($plugin_meta, $new_links);
    }
    return $plugin_meta;
}

add_filter('plugin_row_meta', 'fifu_expired_row_meta', 10, 4);

function fifu_uninstall() {
    // buddyboss app
    if (isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'bbapp') !== false)
        return;

    $strings = fifu_get_strings_uninstall();

    wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');
    wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
    wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
    wp_enqueue_style('fifu-uninstall-css', plugins_url('includes/html/css/uninstall.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-uninstall-js', plugins_url('includes/html/js/uninstall.js', __FILE__), array('jquery'), fifu_version_number());
    wp_localize_script('fifu-uninstall-js', 'fifuUninstallVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'buttonTextClean' => $strings['button']['text']['clean'](),
        'buttonTextDeactivate' => $strings['button']['text']['deactivate'](),
        'buttonDescriptionClean' => $strings['button']['description']['clean'](),
        'buttonDescriptionDeactivate' => $strings['button']['description']['deactivate'](),
    ]);
}

add_action('admin_footer', 'fifu_uninstall');

// https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
add_action('before_woocommerce_init', function () {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// languages

/*
add_action('plugins_loaded', 'fifu_languages');

function fifu_languages() {
    load_plugin_textdomain(FIFU_SLUG, false, FIFU_LANGUAGES_DIR);
}

add_filter('load_textdomain_mofile', 'fifu_override_mo_files', 10, 2 );

function fifu_override_mo_files( $mofile, $domain ) {
    if (FIFU_SLUG === $domain )
        $mofile = FIFU_LANGUAGES_DIR . '/' . basename( $mofile );
    return $mofile;
}
*/

function fifu_custom_action_after_site_initialization($new_site) {
    fifu_propagate_key(false);
}

add_action('wp_initialize_site', 'fifu_custom_action_after_site_initialization');
