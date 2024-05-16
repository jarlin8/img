<?php

define('FIFU_SETTINGS', serialize(array('fifu_social', 'fifu_social_image_only', 'fifu_rss', 'fifu_block', 'fifu_sizes', 'fifu_popup', 'fifu_redirection', 'fifu_auto_set', 'fifu_auto_set_width', 'fifu_auto_set_height', 'fifu_auto_set_blocklist', 'fifu_auto_set_cpt', 'fifu_auto_set_source', 'fifu_auto_set_license', 'fifu_upload_domain', 'fifu_skip', 'fifu_html_cpt', 'fifu_isbn', 'fifu_isbn_custom_field', 'fifu_finder_custom_field', 'fifu_finder', 'fifu_video_finder', 'fifu_amazon_finder', 'fifu_tags', 'fifu_screenshot', 'fifu_lazy', 'fifu_audio', 'fifu_photon', 'fifu_cdn_social', 'fifu_cdn_crop', 'fifu_cdn_content', 'fifu_reset', 'fifu_content', 'fifu_content_page', 'fifu_content_cpt', 'fifu_fake', 'fifu_variation', 'fifu_order_email', 'fifu_gallery', 'fifu_adaptive_height', 'fifu_videos_before', 'fifu_buy', 'fifu_key', 'fifu_email', 'fifu_error_url', 'fifu_default_url', 'fifu_default_cpt', 'fifu_hide_format', 'fifu_enable_default_url', 'fifu_cron_metadata', 'fifu_spinner_nth', 'fifu_video_min_width', 'fifu_video_color', 'fifu_video_zindex', 'fifu_slider', 'fifu_slider_auto', 'fifu_slider_gallery', 'fifu_slider_thumb', 'fifu_slider_counter', 'fifu_slider_crop', 'fifu_slider_single', 'fifu_slider_vertical', 'fifu_slider_ctrl', 'fifu_slider_stop', 'fifu_slider_speed', 'fifu_slider_pause', 'fifu_crop_delay', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_hide_page', 'fifu_hide_post', 'fifu_hide_cpt', 'fifu_get_first', 'fifu_pop_first', 'fifu_ovw_first', 'fifu_query_strings', 'fifu_update_all', 'fifu_update_ignore', 'fifu_run_delete_all', 'fifu_mouse_youtube', 'fifu_loop', 'fifu_autoplay', 'fifu_autoplay_front', 'fifu_video_priority', 'fifu_decode', 'fifu_check', 'fifu_video_mute', 'fifu_video_mute_mobile', 'fifu_video_background', 'fifu_video_background_single', 'fifu_video_privacy', 'fifu_video_later', 'fifu_mouse_vimeo', 'fifu_video', 'fifu_video_thumb', 'fifu_video_thumb_page', 'fifu_video_thumb_post', 'fifu_video_thumb_cpt', 'fifu_video_play_button', 'fifu_video_play_hide_grid', 'fifu_video_play_hide_grid_wc', 'fifu_video_controls', 'fifu_same_size', 'fifu_auto_category', 'fifu_video_list_priority', 'fifu_auto_alt', 'fifu_dynamic_alt', 'fifu_data_clean', 'fifu_shortform', 'fifu_crop_ratio', 'fifu_crop_default', 'fifu_crop_ignore_parent', 'fifu_crop0', 'fifu_crop1', 'fifu_crop2', 'fifu_crop3', 'fifu_crop4', 'fifu_fit', 'fifu_play_type', 'fifu_upload_show', 'fifu_upload_proxy', 'fifu_upload_job', 'fifu_upload_private_proxy', 'fifu_slider_left', 'fifu_slider_right', 'fifu_buy_text', 'fifu_buy_disclaimer', 'fifu_buy_cf', 'fifu_bbpress_fields', 'fifu_bbpress_title', 'fifu_cloud_upload_auto', 'fifu_cloud_hotlink')));
define('FIFU_ACTION_SETTINGS', '/wp-admin/admin.php?page=fifu-premium');
define('FIFU_ACTION_CLOUD', '/wp-admin/admin.php?page=fifu-cloud');

define('FIFU_SLUG', 'featured-image-from-url');

add_action('admin_menu', 'fifu_insert_menu');

function fifu_insert_menu() {
    if (strpos($_SERVER['REQUEST_URI'], 'fifu') !== false) {
        wp_enqueue_script('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js');
        wp_enqueue_style('jquery-ui-style1', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui-style2', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css');
        wp_enqueue_style('jquery-ui-style3', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css');

        wp_enqueue_script('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
        wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');

        wp_enqueue_style('datatable-css', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
        wp_enqueue_style('datatable-select-css', '//cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css');
        wp_enqueue_style('datatable-buttons-css', '//cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css');
        wp_enqueue_script('datatable-js', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js');
        wp_enqueue_script('datatable-select', '//cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js');
        wp_enqueue_script('datatable-buttons', '//cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js');

        wp_enqueue_script('lazyload', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/jquery.lazyloadxt.min.js');
        wp_enqueue_style('lazyload-spinner', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/jquery.lazyloadxt.spinner.min.css');

        wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number());

        wp_enqueue_style('text-security', 'https://cdn.jsdelivr.net/gh/noppa/text-security@master/dist/text-security.min.css');

        // register custom variables for the AJAX script
        wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
            'restUrl' => esc_url_raw(rest_url()),
            'homeUrl' => esc_url_raw(home_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    $fifu = fifu_get_strings_settings();

    add_menu_page('Featured Image from URL', 'FIFU', 'manage_options', 'fifu-premium', 'fifu_get_menu_html', 'dashicons-camera', 57);
    add_submenu_page('fifu-premium', 'FIFU Settings', $fifu['options']['settings'](), 'manage_options', 'fifu-premium');
    add_submenu_page('fifu-premium', 'FIFU Cloud', $fifu['options']['cloud'](), 'manage_options', 'fifu-cloud', 'fifu_cloud');
    add_submenu_page('fifu-premium', 'FIFU Troubleshooting', $fifu['options']['troubleshooting'](), 'manage_options', 'fifu-troubleshooting', 'fifu_troubleshooting');
    add_submenu_page('fifu-premium', 'FIFU Status', $fifu['options']['status'](), 'manage_options', 'fifu-support-data', 'fifu_support_data');

    add_action('admin_init', 'fifu_get_menu_settings');

    fifu_check_integrity();

    if (!filter_var(get_option('fifu_email'), FILTER_VALIDATE_EMAIL))
        fifu_update_status();
}

function fifu_cloud() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_script('fifu-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js');
    wp_enqueue_style('fifu-menu-su-css', plugins_url('/html/css/menu-su.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-menu-su-js', plugins_url('/html/js/menu-su.js', __FILE__), array('jquery'), fifu_version_number());
    wp_enqueue_script('fifu-qrcode', plugins_url('/html/js/qrcode.js', __FILE__), array('jquery'), fifu_version_number());

    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-cloud-js', plugins_url('/html/js/cloud.js', __FILE__), array('jquery'), fifu_version_number());

    wp_localize_script('fifu-cloud-js', 'fifuScriptCloudVars', [
        'signUpComplete' => fifu_su_sign_up_complete(),
        'woocommerce' => class_exists('WooCommerce'),
        'availableImages' => fifu_db_count_available_images(),
        'down' => $fifucloud['ws']['down'](),
        'connected' => $fifucloud['ws']['connection']['ok'](),
        'notConnected' => $fifucloud['ws']['connection']['fail'](),
        'noImages' => $fifucloud['table']['no']['images'](),
        'noPosts' => $fifucloud['table']['no']['posts'](),
        'noData' => $fifucloud['table']['no']['data'](),
        'selectAll' => $fifucloud['table']['select']['all'](),
        'selectNone' => $fifucloud['table']['select']['none'](),
        'load' => $fifucloud['table']['load'](),
        'limit' => $fifucloud['table']['limit'](),
        'delete' => $fifucloud['table']['delete'](),
        'upload' => $fifucloud['table']['upload'](),
        'link' => $fifucloud['table']['link'](),
        'dialogDelete' => $fifucloud['table']['dialog']['delete'](),
        'dialogCancel' => $fifucloud['table']['dialog']['cancel'](),
        'dialogOk' => $fifucloud['table']['dialog']['ok'](),
        'dialogSure' => $fifucloud['table']['dialog']['sure'](),
        'dialogYes' => $fifucloud['table']['dialog']['yes'](),
        'dialogNo' => $fifucloud['table']['dialog']['no'](),
        'category' => $fifucloud['table']['category'](),
        'slider' => $fifucloud['table']['slider'](),
        'gallery' => $fifucloud['table']['gallery'](),
        'featured' => $fifucloud['table']['featured'](),
    ]);

    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');
    $enable_cloud_hotlink = get_option('fifu_cloud_hotlink');

    include 'html/cloud.html';

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_upload_auto', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_upload_auto', 'fifu_cloud_upload_auto');

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_hotlink', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_hotlink', 'fifu_cloud_hotlink');

    // schedule upload
    if (fifu_is_on('fifu_cloud_upload_auto')) {
        if (!wp_next_scheduled('fifu_create_cloud_upload_auto_event')) {
            wp_schedule_event(time(), 'fifu_schedule_cloud_upload_auto', 'fifu_create_cloud_upload_auto_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_cloud_upload_auto_event');
        delete_transient('fifu_cloud_upload_auto_semaphore');
        fifu_stop_job('fifu_cloud_upload_auto');
    }
}

function fifu_troubleshooting() {
    flush();

    $fifu = fifu_get_strings_settings();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-troubleshooting-js', plugins_url('/html/js/troubleshooting.js', __FILE__), array('jquery'), fifu_version_number());

    include 'html/troubleshooting.html';
}

function fifu_support_data() {
    $fifu = fifu_get_strings_settings();

    // css
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);

    $enable_social = get_option('fifu_social');
    $enable_social_image_only = get_option('fifu_social_image_only');
    $enable_rss = get_option('fifu_rss');
    $enable_block = get_option('fifu_block');
    $enable_sizes = get_option('fifu_sizes');
    $enable_popup = get_option('fifu_popup');
    $enable_redirection = get_option('fifu_redirection');
    $enable_auto_set = get_option('fifu_auto_set');
    $max_auto_set_width = get_option('fifu_auto_set_width');
    $max_auto_set_height = get_option('fifu_auto_set_height');
    $auto_set_blocklist = esc_textarea(get_option('fifu_auto_set_blocklist'));
    $auto_set_cpt = esc_attr(get_option('fifu_auto_set_cpt'));
    $auto_set_source = esc_attr(get_option('fifu_auto_set_source'));
    $auto_set_license = esc_attr(get_option('fifu_auto_set_license'));
    $upload_domain = esc_attr(get_option('fifu_upload_domain'));
    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $enable_isbn = get_option('fifu_isbn');
    $isbn_custom_field = esc_attr(get_option('fifu_isbn_custom_field'));
    $finder_custom_field = esc_attr(get_option('fifu_finder_custom_field'));
    $enable_finder = get_option('fifu_finder');
    $enable_video_finder = get_option('fifu_video_finder');
    $enable_amazon_finder = get_option('fifu_amazon_finder');
    $enable_tags = get_option('fifu_tags');
    $enable_screenshot = get_option('fifu_screenshot');
    $enable_lazy = get_option('fifu_lazy');
    $enable_audio = get_option('fifu_audio');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_social = get_option('fifu_cdn_social');
    $enable_cdn_crop = get_option('fifu_cdn_crop');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_content = get_option('fifu_content');
    $enable_content_page = get_option('fifu_content_page');
    $enable_content_cpt = get_option('fifu_content_cpt');
    $enable_fake = get_option('fifu_fake');
    $enable_variation = get_option('fifu_variation');
    $enable_order_email = get_option('fifu_order_email');
    $enable_gallery = get_option('fifu_gallery');
    $enable_adaptive_height = get_option('fifu_adaptive_height');
    $enable_videos_before = get_option('fifu_videos_before');
    $enable_buy = get_option('fifu_buy');
    $license_key = get_option('fifu_key');
    $email = get_option('fifu_email');
    $error_url = esc_url(get_option('fifu_error_url'));
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $hide_format = esc_attr(get_option('fifu_hide_format'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $enable_cron_metadata = get_option('fifu_cron_metadata');
    $nth_image = get_option('fifu_spinner_nth');
    $min_video_width = get_option('fifu_video_min_width');
    $video_color = esc_attr(get_option('fifu_video_color'));
    $video_zindex = get_option('fifu_video_zindex');
    $enable_slider = get_option('fifu_slider');
    $enable_slider_auto = get_option('fifu_slider_auto');
    $enable_slider_gallery = get_option('fifu_slider_gallery');
    $enable_slider_thumb = get_option('fifu_slider_thumb');
    $enable_slider_counter = get_option('fifu_slider_counter');
    $enable_slider_crop = get_option('fifu_slider_crop');
    $enable_slider_single = get_option('fifu_slider_single');
    $enable_slider_vertical = get_option('fifu_slider_vertical');
    $enable_slider_ctrl = get_option('fifu_slider_ctrl');
    $enable_slider_stop = get_option('fifu_slider_stop');
    $slider_speed = get_option('fifu_slider_speed');
    $slider_pause = get_option('fifu_slider_pause');
    $crop_delay = get_option('fifu_crop_delay');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide_page = get_option('fifu_hide_page');
    $enable_hide_post = get_option('fifu_hide_post');
    $enable_hide_cpt = get_option('fifu_hide_cpt');
    $enable_get_first = get_option('fifu_get_first');
    $enable_pop_first = get_option('fifu_pop_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_query_strings = get_option('fifu_query_strings');
    $enable_update_all = 'toggleoff';
    $enable_update_ignore = get_option('fifu_update_ignore');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_autoplay = get_option('fifu_autoplay');
    $enable_autoplay_front = get_option('fifu_autoplay_front');
    $enable_video_priority = get_option('fifu_video_priority');
    $enable_decode = get_option('fifu_decode');
    $enable_check = get_option('fifu_check');
    $enable_video_mute = get_option('fifu_video_mute');
    $enable_video_mute_mobile = get_option('fifu_video_mute_mobile');
    $enable_video_background = get_option('fifu_video_background');
    $enable_video_background_single = get_option('fifu_video_background_single');
    $enable_video_privacy = get_option('fifu_video_privacy');
    $enable_video_later = get_option('fifu_video_later');
    $enable_loop = get_option('fifu_loop');
    $enable_mouse_youtube = get_option('fifu_mouse_youtube');
    $enable_mouse_vimeo = get_option('fifu_mouse_vimeo');
    $enable_video = get_option('fifu_video');
    $enable_video_thumb = get_option('fifu_video_thumb');
    $enable_video_thumb_page = get_option('fifu_video_thumb_page');
    $enable_video_thumb_post = get_option('fifu_video_thumb_post');
    $enable_video_thumb_cpt = get_option('fifu_video_thumb_cpt');
    $enable_video_play_button = get_option('fifu_video_play_button');
    $enable_video_play_hide_grid = get_option('fifu_video_play_hide_grid');
    $enable_video_play_hide_grid_wc = get_option('fifu_video_play_hide_grid_wc');
    $enable_video_controls = get_option('fifu_video_controls');
    $enable_same_size = get_option('fifu_same_size');
    $enable_auto_category = get_option('fifu_auto_category');
    $enable_video_list_priority = get_option('fifu_video_list_priority');
    $enable_auto_alt = get_option('fifu_auto_alt');
    $enable_dynamic_alt = get_option('fifu_dynamic_alt');
    $enable_data_clean = 'toggleoff';
    $enable_shortform = get_option('fifu_shortform');
    $crop_ratio = get_option('fifu_crop_ratio');
    $crop_default = stripslashes(esc_attr(get_option('fifu_crop_default')));
    $crop_ignore_parent = stripslashes(esc_attr(get_option('fifu_crop_ignore_parent')));
    $fit_option = get_option('fifu_fit');
    $play_type_option = get_option('fifu_play_type');
    $enable_upload_show = get_option('fifu_upload_show');
    $enable_upload_proxy = get_option('fifu_upload_proxy');
    $enable_upload_job = get_option('fifu_upload_job');
    $upload_private_proxy = esc_attr(get_option('fifu_upload_private_proxy'));
    $slider_left = esc_url(get_option('fifu_slider_left'));
    $slider_right = esc_url(get_option('fifu_slider_right'));
    $buy_text = get_option('fifu_buy_text');
    $buy_disclaimer = get_option('fifu_buy_disclaimer');
    $buy_cf = get_option('fifu_buy_cf');
    $enable_bbpress_fields = get_option('fifu_bbpress_fields');
    $enable_bbpress_title = get_option('fifu_bbpress_title');
    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');
    $enable_cloud_hotlink = get_option('fifu_cloud_hotlink');

    $array_crop = array();
    for ($x = 0; $x <= 4; $x++)
        $array_crop[$x] = stripslashes(esc_attr(get_option('fifu_crop' . $x)));

    include 'html/support-data.html';
}

function fifu_get_menu_html() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-menu-js', plugins_url('/html/js/menu.js', __FILE__), array('jquery'), fifu_version_number());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-menu-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'wait' => $fifu['php']['message']['wait'](),
        'dimensionsSupport' => $fifu['dimensions']['support'](),
        'dimensionsWait' => $fifu['dimensions']['wait'](),
        'lock' => get_option('fifu_lock'),
        'install' => get_option('fifu_install'),
        'key' => get_option('fifu' . '_' . 'key'),
    ]);

    $enable_social = get_option('fifu_social');
    $enable_social_image_only = get_option('fifu_social_image_only');
    $enable_rss = get_option('fifu_rss');
    $enable_block = get_option('fifu_block');
    $enable_sizes = get_option('fifu_sizes');
    $enable_popup = get_option('fifu_popup');
    $enable_redirection = get_option('fifu_redirection');
    $enable_auto_set = get_option('fifu_auto_set');
    $max_auto_set_width = get_option('fifu_auto_set_width');
    $max_auto_set_height = get_option('fifu_auto_set_height');
    $auto_set_blocklist = esc_textarea(get_option('fifu_auto_set_blocklist'));
    $auto_set_cpt = esc_attr(get_option('fifu_auto_set_cpt'));
    $auto_set_source = esc_attr(get_option('fifu_auto_set_source'));
    $auto_set_license = esc_attr(get_option('fifu_auto_set_license'));
    $upload_domain = esc_attr(get_option('fifu_upload_domain'));
    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $enable_isbn = get_option('fifu_isbn');
    $isbn_custom_field = esc_attr(get_option('fifu_isbn_custom_field'));
    $finder_custom_field = esc_attr(get_option('fifu_finder_custom_field'));
    $enable_finder = get_option('fifu_finder');
    $enable_video_finder = get_option('fifu_video_finder');
    $enable_amazon_finder = get_option('fifu_amazon_finder');
    $enable_tags = get_option('fifu_tags');
    $enable_screenshot = get_option('fifu_screenshot');
    $enable_lazy = get_option('fifu_lazy');
    $enable_audio = get_option('fifu_audio');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_social = get_option('fifu_cdn_social');
    $enable_cdn_crop = get_option('fifu_cdn_crop');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_content = get_option('fifu_content');
    $enable_content_page = get_option('fifu_content_page');
    $enable_content_cpt = get_option('fifu_content_cpt');
    $enable_fake = get_option('fifu_fake');
    $enable_variation = get_option('fifu_variation');
    $enable_order_email = get_option('fifu_order_email');
    $enable_gallery = get_option('fifu_gallery');
    $enable_adaptive_height = get_option('fifu_adaptive_height');
    $enable_videos_before = get_option('fifu_videos_before');
    $enable_buy = get_option('fifu_buy');
    $license_key = get_option('fifu_key');
    $email = get_option('fifu_email');
    $error_url = esc_url(get_option('fifu_error_url'));
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $hide_format = esc_attr(get_option('fifu_hide_format'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $enable_cron_metadata = get_option('fifu_cron_metadata');
    $nth_image = get_option('fifu_spinner_nth');
    $min_video_width = get_option('fifu_video_min_width');
    $video_color = esc_attr(get_option('fifu_video_color'));
    $video_zindex = get_option('fifu_video_zindex');
    $enable_slider = get_option('fifu_slider');
    $enable_slider_auto = get_option('fifu_slider_auto');
    $enable_slider_gallery = get_option('fifu_slider_gallery');
    $enable_slider_thumb = get_option('fifu_slider_thumb');
    $enable_slider_counter = get_option('fifu_slider_counter');
    $enable_slider_crop = get_option('fifu_slider_crop');
    $enable_slider_single = get_option('fifu_slider_single');
    $enable_slider_vertical = get_option('fifu_slider_vertical');
    $enable_slider_ctrl = get_option('fifu_slider_ctrl');
    $enable_slider_stop = get_option('fifu_slider_stop');
    $slider_speed = get_option('fifu_slider_speed');
    $slider_pause = get_option('fifu_slider_pause');
    $crop_delay = get_option('fifu_crop_delay');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide_page = get_option('fifu_hide_page');
    $enable_hide_post = get_option('fifu_hide_post');
    $enable_hide_cpt = get_option('fifu_hide_cpt');
    $enable_get_first = get_option('fifu_get_first');
    $enable_pop_first = get_option('fifu_pop_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_query_strings = get_option('fifu_query_strings');
    $enable_update_all = 'toggleoff';
    $enable_update_ignore = get_option('fifu_update_ignore');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_autoplay = get_option('fifu_autoplay');
    $enable_autoplay_front = get_option('fifu_autoplay_front');
    $enable_video_priority = get_option('fifu_video_priority');
    $enable_decode = get_option('fifu_decode');
    $enable_check = get_option('fifu_check');
    $enable_video_mute = get_option('fifu_video_mute');
    $enable_video_mute_mobile = get_option('fifu_video_mute_mobile');
    $enable_video_background = get_option('fifu_video_background');
    $enable_video_background_single = get_option('fifu_video_background_single');
    $enable_video_privacy = get_option('fifu_video_privacy');
    $enable_video_later = get_option('fifu_video_later');
    $enable_loop = get_option('fifu_loop');
    $enable_mouse_youtube = get_option('fifu_mouse_youtube');
    $enable_mouse_vimeo = get_option('fifu_mouse_vimeo');
    $enable_video = get_option('fifu_video');
    $enable_video_thumb = get_option('fifu_video_thumb');
    $enable_video_thumb_page = get_option('fifu_video_thumb_page');
    $enable_video_thumb_post = get_option('fifu_video_thumb_post');
    $enable_video_thumb_cpt = get_option('fifu_video_thumb_cpt');
    $enable_video_play_button = get_option('fifu_video_play_button');
    $enable_video_play_hide_grid = get_option('fifu_video_play_hide_grid');
    $enable_video_play_hide_grid_wc = get_option('fifu_video_play_hide_grid_wc');
    $enable_video_controls = get_option('fifu_video_controls');
    $enable_same_size = get_option('fifu_same_size');
    $enable_auto_category = get_option('fifu_auto_category');
    $enable_video_list_priority = get_option('fifu_video_list_priority');
    $enable_auto_alt = get_option('fifu_auto_alt');
    $enable_dynamic_alt = get_option('fifu_dynamic_alt');
    $enable_data_clean = 'toggleoff';
    $enable_shortform = get_option('fifu_shortform');
    $crop_ratio = get_option('fifu_crop_ratio');
    $crop_default = stripslashes(esc_attr(get_option('fifu_crop_default')));
    $crop_ignore_parent = stripslashes(esc_attr(get_option('fifu_crop_ignore_parent')));
    $fit_option = get_option('fifu_fit');
    $play_type_option = get_option('fifu_play_type');
    $enable_upload_show = get_option('fifu_upload_show');
    $enable_upload_proxy = get_option('fifu_upload_proxy');
    $enable_upload_job = get_option('fifu_upload_job');
    $upload_private_proxy = esc_attr(get_option('fifu_upload_private_proxy'));
    $slider_left = esc_url(get_option('fifu_slider_left'));
    $slider_right = esc_url(get_option('fifu_slider_right'));
    $buy_text = get_option('fifu_buy_text');
    $buy_disclaimer = get_option('fifu_buy_disclaimer');
    $buy_cf = get_option('fifu_buy_cf');
    $enable_bbpress_fields = get_option('fifu_bbpress_fields');
    $enable_bbpress_title = get_option('fifu_bbpress_title');

    $array_crop = array();
    for ($x = 0; $x <= 4; $x++)
        $array_crop[$x] = stripslashes(esc_attr(get_option('fifu_crop' . $x)));

    include 'html/menu.html';

    $arr = fifu_update_menu_options();

    // category
    if (fifu_is_on('fifu_auto_category')) {
        if (!get_option('fifu_auto_category_created')) {
            fifu_db_insert_auto_category_image();
            update_option('fifu_auto_category_created', true, 'no');
        }
    } else
        update_option('fifu_auto_category_created', false, 'no');

    // default
    if (!$arr['fifu_default_cpt']) { # submit via post type form
        $default_url = $arr['fifu_default_url']; # submit via default url form
        if (!empty($default_url) && fifu_is_on('fifu_enable_default_url') && fifu_is_on('fifu_fake')) {
            if (!wp_get_attachment_url(get_option('fifu_default_attach_id'))) {
                $att_id = fifu_db_create_attachment($default_url);
                update_option('fifu_default_attach_id', $att_id);
                fifu_db_set_default_url();
            } else
                fifu_db_update_default_url($default_url);
        }
    }

    // reset
    if (fifu_is_on('fifu_reset')) {
        fifu_reset_settings();
        update_option('fifu_reset', 'toggleoff', 'no');
    }

    // gallery
    if (fifu_is_on('fifu_gallery')) {
        update_option('fifu_slider_thumb', 'toggleon', 'no');
    }

    // schedule metadata
    if (fifu_is_on('fifu_cron_metadata')) {
        if (!wp_next_scheduled('fifu_create_metadata_event'))
            wp_schedule_event(time(), 'fifu_schedule_metadata', 'fifu_create_metadata_event');
    } else {
        wp_clear_scheduled_hook('fifu_create_metadata_event');
        delete_transient('fifu_metadata_semaphore');
    }

    // schedule auto set
    if (fifu_is_on('fifu_auto_set')) {
        if (!wp_next_scheduled('fifu_create_auto_set_event'))
            wp_schedule_event(time(), 'fifu_schedule_auto_set', 'fifu_create_auto_set_event');
    } else {
        wp_clear_scheduled_hook('fifu_create_auto_set_event');
        delete_transient('fifu_auto_set_semaphore');
    }

    // schedule isbn
    if (fifu_is_on('fifu_isbn')) {
        if (!wp_next_scheduled('fifu_create_isbn_event'))
            wp_schedule_event(time(), 'fifu_schedule_isbn', 'fifu_create_isbn_event');
    } else {
        wp_clear_scheduled_hook('fifu_create_isbn_event');
        delete_transient('fifu_isbn_semaphore');
    }

    // schedule finder
    if (fifu_is_on('fifu_finder')) {
        if (!wp_next_scheduled('fifu_create_finder_event')) {
            wp_schedule_event(time(), 'fifu_schedule_finder', 'fifu_create_finder_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_finder_event');
        delete_transient('fifu_finder_semaphore');
        fifu_stop_job('fifu_finder');
    }

    // schedule tags
    if (fifu_is_on('fifu_tags')) {
        if (!wp_next_scheduled('fifu_create_tags_event'))
            wp_schedule_event(time(), 'fifu_schedule_tags', 'fifu_create_tags_event');
    } else {
        wp_clear_scheduled_hook('fifu_create_tags_event');
        delete_transient('fifu_tags_semaphore');
    }

    // schedule upload
    if (fifu_is_on('fifu_upload_job')) {
        if (!wp_next_scheduled('fifu_create_upload_event')) {
            wp_schedule_event(time(), 'fifu_schedule_upload', 'fifu_create_upload_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_upload_event');
        delete_transient('fifu_upload_semaphore');
        delete_option('fifu_cache_proxy');
        fifu_stop_job('fifu_upload_job');
    }
}

function fifu_get_menu_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i)
        fifu_get_setting($i);
}

function fifu_reset_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i) {
        if ($i != 'fifu_key' &&
                $i != 'fifu_email' &&
                $i != 'fifu_default_url' &&
                $i != 'fifu_enable_default_url')
            delete_option($i);
    }
}

function fifu_get_setting($type) {
    register_setting('settings-group', $type);

    $arrRatio = array('fifu_crop_ratio');
    $arrFit = array('fifu_fit');
    $arrPlayType = array('fifu_play_type');
    $arr0 = array('fifu_crop_delay', 'fifu_auto_set_width', 'fifu_auto_set_height');
    $arr1 = array('fifu_spinner_nth');
    $arrDefaultSelector = array('fifu_crop_default');
    $arrEmpty = array('fifu_default_url', 'fifu_crop4', 'fifu_crop3', 'fifu_crop2', 'fifu_crop1', 'fifu_crop0', 'fifu_crop_ignore_parent', 'fifu_upload_private_proxy', 'fifu_slider_left', 'fifu_slider_right', 'fifu_buy_text', 'fifu_buy_disclaimer', 'fifu_buy_cf', 'fifu_isbn_custom_field', 'fifu_finder_custom_field', 'fifu_auto_set_source', 'fifu_auto_set_license', 'fifu_upload_domain', 'fifu_skip', 'fifu_html_cpt', 'fifu_hide_format');
    $arrEmptyNo = array('fifu_error_url', 'fifu_key', 'fifu_email', 'fifu_auto_set_blocklist');
    $arrDefaultType = array('fifu_default_cpt');
    $arr100 = array('fifu_video_min_width');
    $arr1000 = array('fifu_slider_speed', 'fifu_video_zindex');
    $arr2000 = array('fifu_slider_pause');
    $arrRed = array('fifu_video_color');
    $arrPost = array('fifu_auto_set_cpt');
    $arrOn = array('fifu_wc_zoom', 'fifu_wc_lbox');
    $arrOnNo = array('fifu_fake', 'fifu_social', 'fifu_video_play_button', 'fifu_video_thumb', 'fifu_video_thumb_post', 'fifu_video_thumb_page', 'fifu_video_thumb_cpt', 'fifu_video_controls', 'fifu_slider_crop', 'fifu_adaptive_height', 'fifu_gallery');
    $arrOffNo = array('fifu_auto_category_created', 'fifu_data_clean', 'fifu_update_all', 'fifu_update_ignore', 'fifu_run_delete_all', 'fifu_reset', 'fifu_enable_cron_metadata', 'fifu_social_image_only', 'fifu_rss');

    if (get_option($type) === false) {
        if (in_array($type, $arrRatio))
            update_option($type, '4:3', 'no');
        else if (in_array($type, $arrFit))
            update_option($type, 'cover', 'no');
        else if (in_array($type, $arrPlayType))
            update_option($type, 'inline', 'no');
        else if (in_array($type, $arr0))
            update_option($type, 0);
        else if (in_array($type, $arr1))
            update_option($type, 1);
        else if (in_array($type, $arrDefaultSelector))
            update_option($type, "div[id^='post'],ul.products,div.products,div.product-thumbnails,ol.flex-control-nav.flex-control-thumbs");
        else if (in_array($type, $arrEmpty))
            update_option($type, '');
        else if (in_array($type, $arrEmptyNo))
            update_option($type, '', 'no');
        else if (in_array($type, $arrDefaultType))
            update_option($type, "post,page,product", 'no');
        else if (in_array($type, $arr100))
            update_option($type, 100, 'no');
        else if (in_array($type, $arr1000))
            update_option($type, 1000);
        else if (in_array($type, $arr2000))
            update_option($type, 2000);
        else if (in_array($type, $arrRed))
            update_option($type, 'red', 'no');
        else if (in_array($type, $arrPost))
            update_option($type, 'post', 'no');
        else if (in_array($type, $arrOn))
            update_option($type, 'toggleon');
        else if (in_array($type, $arrOnNo))
            update_option($type, 'toggleon', 'no');
        else if (in_array($type, $arrOffNo))
            update_option($type, 'toggleoff', 'no');
        else
            update_option($type, 'toggleoff');
    }
}

function fifu_update_menu_options() {
    if (fifu_is_valid_nonce('nonce_fifu_form_social'))
        fifu_update_option('fifu_input_social', 'fifu_social');

    if (fifu_is_valid_nonce('nonce_fifu_form_social_image_only'))
        fifu_update_option('fifu_input_social_image_only', 'fifu_social_image_only');

    if (fifu_is_valid_nonce('nonce_fifu_form_rss'))
        fifu_update_option('fifu_input_rss', 'fifu_rss');

    if (fifu_is_valid_nonce('nonce_fifu_form_block'))
        fifu_update_option('fifu_input_block', 'fifu_block');

    if (fifu_is_valid_nonce('nonce_fifu_form_sizes'))
        fifu_update_option('fifu_input_sizes', 'fifu_sizes');

    if (fifu_is_valid_nonce('nonce_fifu_form_popup'))
        fifu_update_option('fifu_input_popup', 'fifu_popup');

    if (fifu_is_valid_nonce('nonce_fifu_form_redirection'))
        fifu_update_option('fifu_input_redirection', 'fifu_redirection');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set'))
        fifu_update_option('fifu_input_auto_set', 'fifu_auto_set');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set_dimensions')) {
        fifu_update_option('fifu_input_auto_set_width', 'fifu_auto_set_width');
        fifu_update_option('fifu_input_auto_set_height', 'fifu_auto_set_height');
    }

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set_blocklist'))
        fifu_update_option('fifu_input_auto_set_blocklist', 'fifu_auto_set_blocklist');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set_cpt'))
        fifu_update_option('fifu_input_auto_set_cpt', 'fifu_auto_set_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set_source'))
        fifu_update_option('fifu_input_auto_set_source', 'fifu_auto_set_source');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_set_license'))
        fifu_update_option('fifu_input_auto_set_license', 'fifu_auto_set_license');

    if (fifu_is_valid_nonce('nonce_fifu_form_upload_domain'))
        fifu_update_option('fifu_input_upload_domain', 'fifu_upload_domain');

    if (fifu_is_valid_nonce('nonce_fifu_form_skip'))
        fifu_update_option('fifu_input_skip', 'fifu_skip');

    if (fifu_is_valid_nonce('nonce_fifu_form_html_cpt'))
        fifu_update_option('fifu_input_html_cpt', 'fifu_html_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_isbn'))
        fifu_update_option('fifu_input_isbn', 'fifu_isbn');

    if (fifu_is_valid_nonce('nonce_fifu_form_isbn_custom_field'))
        fifu_update_option('fifu_input_isbn_custom_field', 'fifu_isbn_custom_field');

    if (fifu_is_valid_nonce('nonce_fifu_form_finder_custom_field'))
        fifu_update_option('fifu_input_finder_custom_field', 'fifu_finder_custom_field');

    if (fifu_is_valid_nonce('nonce_fifu_form_finder'))
        fifu_update_option('fifu_input_finder', 'fifu_finder');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_finder'))
        fifu_update_option('fifu_input_video_finder', 'fifu_video_finder');

    if (fifu_is_valid_nonce('nonce_fifu_form_amazon_finder'))
        fifu_update_option('fifu_input_amazon_finder', 'fifu_amazon_finder');

    if (fifu_is_valid_nonce('nonce_fifu_form_tags'))
        fifu_update_option('fifu_input_tags', 'fifu_tags');

    if (fifu_is_valid_nonce('nonce_fifu_form_screenshot'))
        fifu_update_option('fifu_input_screenshot', 'fifu_screenshot');

    if (fifu_is_valid_nonce('nonce_fifu_form_lazy'))
        fifu_update_option('fifu_input_lazy', 'fifu_lazy');

    if (fifu_is_valid_nonce('nonce_fifu_form_audio'))
        fifu_update_option('fifu_input_audio', 'fifu_audio');

    if (fifu_is_valid_nonce('nonce_fifu_form_photon'))
        fifu_update_option('fifu_input_photon', 'fifu_photon');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_social'))
        fifu_update_option('fifu_input_cdn_social', 'fifu_cdn_social');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_crop'))
        fifu_update_option('fifu_input_cdn_crop', 'fifu_cdn_crop');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_content'))
        fifu_update_option('fifu_input_cdn_content', 'fifu_cdn_content');

    if (fifu_is_valid_nonce('nonce_fifu_form_reset'))
        fifu_update_option('fifu_input_reset', 'fifu_reset');

    if (fifu_is_valid_nonce('nonce_fifu_form_content'))
        fifu_update_option('fifu_input_content', 'fifu_content');

    if (fifu_is_valid_nonce('nonce_fifu_form_content_page'))
        fifu_update_option('fifu_input_content_page', 'fifu_content_page');

    if (fifu_is_valid_nonce('nonce_fifu_form_content_cpt'))
        fifu_update_option('fifu_input_content_cpt', 'fifu_content_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_fake'))
        fifu_update_option('fifu_input_fake', 'fifu_fake');

    if (fifu_is_valid_nonce('nonce_fifu_form_variation'))
        fifu_update_option('fifu_input_variation', 'fifu_variation');

    if (fifu_is_valid_nonce('nonce_fifu_form_order_email'))
        fifu_update_option('fifu_input_order_email', 'fifu_order_email');

    if (fifu_is_valid_nonce('nonce_fifu_form_gallery'))
        fifu_update_option('fifu_input_gallery', 'fifu_gallery');

    if (fifu_is_valid_nonce('nonce_fifu_form_adaptive_height'))
        fifu_update_option('fifu_input_adaptive_height', 'fifu_adaptive_height');

    if (fifu_is_valid_nonce('nonce_fifu_form_videos_before'))
        fifu_update_option('fifu_input_videos_before', 'fifu_videos_before');

    if (fifu_is_valid_nonce('nonce_fifu_form_buy'))
        fifu_update_option('fifu_input_buy', 'fifu_buy');

    if (fifu_is_valid_nonce('nonce_fifu_form_buy_text')) {
        fifu_update_option('fifu_input_buy_text', 'fifu_buy_text');
        fifu_update_option('fifu_input_buy_disclaimer', 'fifu_buy_disclaimer');
        fifu_update_option('fifu_input_buy_cf', 'fifu_buy_cf');
    }

    if (fifu_is_valid_nonce('nonce_fifu_form_key')) {
        fifu_update_option('fifu_input_key', 'fifu_key');
        fifu_update_option('fifu_input_email', 'fifu_email');
    }

    if (fifu_is_valid_nonce('nonce_fifu_form_error_url'))
        fifu_update_option('fifu_input_error_url', 'fifu_error_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_url'))
        fifu_update_option('fifu_input_default_url', 'fifu_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_cpt'))
        fifu_update_option('fifu_input_default_cpt', 'fifu_default_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_format'))
        fifu_update_option('fifu_input_hide_format', 'fifu_hide_format');

    if (fifu_is_valid_nonce('nonce_fifu_form_enable_default_url'))
        fifu_update_option('fifu_input_enable_default_url', 'fifu_enable_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_cron_metadata'))
        fifu_update_option('fifu_input_cron_metadata', 'fifu_cron_metadata');

    if (fifu_is_valid_nonce('nonce_fifu_form_spinner_nth'))
        fifu_update_option('fifu_input_spinner_nth', 'fifu_spinner_nth');

    if (fifu_is_valid_nonce('nonce_fifu_form_spinner')) {
        fifu_update_option('fifu_input_slider_pause', 'fifu_slider_pause');
        fifu_update_option('fifu_input_slider_speed', 'fifu_slider_speed');
        fifu_update_option('fifu_input_slider_left', 'fifu_slider_left');
        fifu_update_option('fifu_input_slider_right', 'fifu_slider_right');
    }

    if (fifu_is_valid_nonce('nonce_fifu_form_video_min_width'))
        fifu_update_option('fifu_input_video_min_width', 'fifu_video_min_width');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_color'))
        fifu_update_option('fifu_input_video_color', 'fifu_video_color');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_zindex'))
        fifu_update_option('fifu_input_video_zindex', 'fifu_video_zindex');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider'))
        fifu_update_option('fifu_input_slider', 'fifu_slider');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_auto'))
        fifu_update_option('fifu_input_slider_auto', 'fifu_slider_auto');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_gallery'))
        fifu_update_option('fifu_input_slider_gallery', 'fifu_slider_gallery');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_thumb'))
        fifu_update_option('fifu_input_slider_thumb', 'fifu_slider_thumb');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_counter'))
        fifu_update_option('fifu_input_slider_counter', 'fifu_slider_counter');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_crop'))
        fifu_update_option('fifu_input_slider_crop', 'fifu_slider_crop');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_single'))
        fifu_update_option('fifu_input_slider_single', 'fifu_slider_single');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_vertical'))
        fifu_update_option('fifu_input_slider_vertical', 'fifu_slider_vertical');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_ctrl'))
        fifu_update_option('fifu_input_slider_ctrl', 'fifu_slider_ctrl');

    if (fifu_is_valid_nonce('nonce_fifu_form_slider_stop'))
        fifu_update_option('fifu_input_slider_stop', 'fifu_slider_stop');

    if (fifu_is_valid_nonce('nonce_fifu_form_crop_delay'))
        fifu_update_option('fifu_input_crop_delay', 'fifu_crop_delay');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_lbox'))
        fifu_update_option('fifu_input_wc_lbox', 'fifu_wc_lbox');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_zoom'))
        fifu_update_option('fifu_input_wc_zoom', 'fifu_wc_zoom');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_page'))
        fifu_update_option('fifu_input_hide_page', 'fifu_hide_page');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_post'))
        fifu_update_option('fifu_input_hide_post', 'fifu_hide_post');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_cpt'))
        fifu_update_option('fifu_input_hide_cpt', 'fifu_hide_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_get_first'))
        fifu_update_option('fifu_input_get_first', 'fifu_get_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_pop_first'))
        fifu_update_option('fifu_input_pop_first', 'fifu_pop_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_ovw_first'))
        fifu_update_option('fifu_input_ovw_first', 'fifu_ovw_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_query_strings'))
        fifu_update_option('fifu_input_query_strings', 'fifu_query_strings');

    if (fifu_is_valid_nonce('nonce_fifu_form_update_all'))
        fifu_update_option('fifu_input_update_all', 'fifu_update_all');

    if (fifu_is_valid_nonce('nonce_fifu_form_update_ignore'))
        fifu_update_option('fifu_input_update_ignore', 'fifu_update_ignore');

    if (fifu_is_valid_nonce('nonce_fifu_form_run_delete_all'))
        fifu_update_option('fifu_input_run_delete_all', 'fifu_run_delete_all');

    if (fifu_is_valid_nonce('nonce_fifu_form_autoplay'))
        fifu_update_option('fifu_input_autoplay', 'fifu_autoplay');

    if (fifu_is_valid_nonce('nonce_fifu_form_autoplay_front'))
        fifu_update_option('fifu_input_autoplay_front', 'fifu_autoplay_front');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_priority'))
        fifu_update_option('fifu_input_video_priority', 'fifu_video_priority');

    if (fifu_is_valid_nonce('nonce_fifu_form_decode'))
        fifu_update_option('fifu_input_decode', 'fifu_decode');

    if (fifu_is_valid_nonce('nonce_fifu_form_check'))
        fifu_update_option('fifu_input_check', 'fifu_check');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_mute'))
        fifu_update_option('fifu_input_video_mute', 'fifu_video_mute');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_mute_mobile'))
        fifu_update_option('fifu_input_video_mute_mobile', 'fifu_video_mute_mobile');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_background'))
        fifu_update_option('fifu_input_video_background', 'fifu_video_background');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_background_single'))
        fifu_update_option('fifu_input_video_background_single', 'fifu_video_background_single');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_privacy'))
        fifu_update_option('fifu_input_video_privacy', 'fifu_video_privacy');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_later'))
        fifu_update_option('fifu_input_video_later', 'fifu_video_later');

    if (fifu_is_valid_nonce('nonce_fifu_form_loop'))
        fifu_update_option('fifu_input_loop', 'fifu_loop');

    if (fifu_is_valid_nonce('nonce_fifu_form_mouse_youtube'))
        fifu_update_option('fifu_input_mouse_youtube', 'fifu_mouse_youtube');

    if (fifu_is_valid_nonce('nonce_fifu_form_mouse_vimeo'))
        fifu_update_option('fifu_input_mouse_vimeo', 'fifu_mouse_vimeo');

    if (fifu_is_valid_nonce('nonce_fifu_form_video'))
        fifu_update_option('fifu_input_video', 'fifu_video');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_thumb'))
        fifu_update_option('fifu_input_video_thumb', 'fifu_video_thumb');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_thumb_page'))
        fifu_update_option('fifu_input_video_thumb_page', 'fifu_video_thumb_page');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_thumb_post'))
        fifu_update_option('fifu_input_video_thumb_post', 'fifu_video_thumb_post');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_thumb_cpt'))
        fifu_update_option('fifu_input_video_thumb_cpt', 'fifu_video_thumb_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_play_button'))
        fifu_update_option('fifu_input_video_play_button', 'fifu_video_play_button');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_play_hide_grid'))
        fifu_update_option('fifu_input_video_play_hide_grid', 'fifu_video_play_hide_grid');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_play_hide_grid_wc'))
        fifu_update_option('fifu_input_video_play_hide_grid_wc', 'fifu_video_play_hide_grid_wc');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_controls'))
        fifu_update_option('fifu_input_video_controls', 'fifu_video_controls');

    if (fifu_is_valid_nonce('nonce_fifu_form_same_size'))
        fifu_update_option('fifu_input_same_size', 'fifu_same_size');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_category'))
        fifu_update_option('fifu_input_auto_category', 'fifu_auto_category');

    if (fifu_is_valid_nonce('nonce_fifu_form_video_list_priority'))
        fifu_update_option('fifu_input_video_list_priority', 'fifu_video_list_priority');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_alt'))
        fifu_update_option('fifu_input_auto_alt', 'fifu_auto_alt');

    if (fifu_is_valid_nonce('nonce_fifu_form_dynamic_alt'))
        fifu_update_option('fifu_input_dynamic_alt', 'fifu_dynamic_alt');

    if (fifu_is_valid_nonce('nonce_fifu_form_data_clean'))
        fifu_update_option('fifu_input_data_clean', 'fifu_data_clean');

    if (fifu_is_valid_nonce('nonce_fifu_form_shortform'))
        fifu_update_option('fifu_input_shortform', 'fifu_shortform');

    if (fifu_is_valid_nonce('nonce_fifu_form_crop_ratio'))
        fifu_update_option('fifu_input_crop_ratio', 'fifu_crop_ratio');

    if (fifu_is_valid_nonce('nonce_fifu_form_crop_default'))
        fifu_update_option('fifu_input_crop_default', 'fifu_crop_default');

    if (fifu_is_valid_nonce('nonce_fifu_form_crop_ignore_parent'))
        fifu_update_option('fifu_input_crop_ignore_parent', 'fifu_crop_ignore_parent');

    if (fifu_is_valid_nonce('nonce_fifu_form_fit'))
        fifu_update_option('fifu_input_fit', 'fifu_fit');

    if (fifu_is_valid_nonce('nonce_fifu_form_play_type'))
        fifu_update_option('fifu_input_play_type', 'fifu_play_type');

    if (fifu_is_valid_nonce('nonce_fifu_form_upload_show'))
        fifu_update_option('fifu_input_upload_show', 'fifu_upload_show');

    if (fifu_is_valid_nonce('nonce_fifu_form_upload_proxy'))
        fifu_update_option('fifu_input_upload_proxy', 'fifu_upload_proxy');

    if (fifu_is_valid_nonce('nonce_fifu_form_upload_job'))
        fifu_update_option('fifu_input_upload_job', 'fifu_upload_job');

    if (fifu_is_valid_nonce('nonce_fifu_form_upload_private_proxy'))
        fifu_update_option('fifu_input_upload_private_proxy', 'fifu_upload_private_proxy');

    if (fifu_is_valid_nonce('nonce_fifu_form_bbpress_fields'))
        fifu_update_option('fifu_input_bbpress_fields', 'fifu_bbpress_fields');

    if (fifu_is_valid_nonce('nonce_fifu_form_bbpress_title'))
        fifu_update_option('fifu_input_bbpress_title', 'fifu_bbpress_title');

    if (fifu_is_valid_nonce('nonce_fifu_form_crop')) {
        for ($x = 0; $x <= 4; $x++)
            fifu_update_option('fifu_input_crop' . $x, 'fifu_crop' . $x);
    }

    // delete all run log
    if (fifu_is_on('fifu_run_delete_all'))
        update_option('fifu_run_delete_all_time', current_time('mysql'), 'no');

    // urgent updates
    $arr = array();
    if (isset($_POST['fifu_input_default_url'])) {
        $arr['fifu_default_url'] = wp_strip_all_tags($_POST['fifu_input_default_url']);
    } else {
        $default_url = get_option('fifu_default_url');
        $arr['fifu_default_url'] = $default_url ? $default_url : '';
    }

    if (isset($_POST['fifu_input_default_cpt'])) {
        $arr['fifu_default_cpt'] = wp_strip_all_tags($_POST['fifu_input_default_cpt']);
    } else
        $arr['fifu_default_cpt'] = null;

    if (isset($_POST['fifu_input_hide_format'])) {
        $arr['fifu_hide_format'] = wp_strip_all_tags($_POST['fifu_input_hide_format']);
    } else
        $arr['fifu_hide_format'] = null;

    return $arr;
}

function fifu_update_option($input, $field) {
    if (!isset($_POST[$input]))
        return;

    $value = $_POST[$input];

    $arr_boolean = array('fifu_adaptive_height', 'fifu_videos_before', 'fifu_auto_alt', 'fifu_auto_category', 'fifu_auto_set', 'fifu_autoplay', 'fifu_autoplay_front', 'fifu_bbpress_fields', 'fifu_bbpress_title', 'fifu_block', 'fifu_sizes', 'fifu_buy', 'fifu_cdn_content', 'fifu_cdn_crop', 'fifu_cdn_social', 'fifu_check', 'fifu_content', 'fifu_content_cpt', 'fifu_content_page', 'fifu_cron_metadata', 'fifu_data_clean', 'fifu_decode', 'fifu_dynamic_alt', 'fifu_enable_default_url', 'fifu_fake', 'fifu_finder', 'fifu_gallery', 'fifu_get_first', 'fifu_hide_cpt', 'fifu_hide_page', 'fifu_hide_post', 'fifu_isbn', 'fifu_lazy', 'fifu_audio', 'fifu_loop', 'fifu_mouse_vimeo', 'fifu_mouse_youtube', 'fifu_ovw_first', 'fifu_photon', 'fifu_pop_first', 'fifu_query_strings', 'fifu_popup', 'fifu_redirection', 'fifu_reset', 'fifu_rss', 'fifu_run_delete_all', 'fifu_same_size', 'fifu_shortform', 'fifu_slider', 'fifu_slider_auto', 'fifu_slider_counter', 'fifu_slider_crop', 'fifu_slider_single', 'fifu_slider_ctrl', 'fifu_slider_gallery', 'fifu_slider_stop', 'fifu_slider_thumb', 'fifu_slider_vertical', 'fifu_social', 'fifu_social_image_only', 'fifu_tags', 'fifu_screenshot', 'fifu_update_all', 'fifu_update_ignore', 'fifu_upload_job', 'fifu_upload_proxy', 'fifu_upload_show', 'fifu_variation', 'fifu_order_email', 'fifu_video', 'fifu_video_background', 'fifu_video_background_single', 'fifu_video_privacy', 'fifu_video_later', 'fifu_video_controls', 'fifu_video_finder', 'fifu_amazon_finder', 'fifu_video_list_priority', 'fifu_video_mute', 'fifu_video_mute_mobile', 'fifu_video_play_button', 'fifu_video_play_hide_grid', 'fifu_video_play_hide_grid_wc', 'fifu_video_priority', 'fifu_video_thumb', 'fifu_video_thumb_cpt', 'fifu_video_thumb_page', 'fifu_video_thumb_post', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_cloud_upload_auto', 'fifu_cloud_hotlink');
    if (in_array($field, $arr_boolean)) {
        if (in_array($value, array('on', 'off')))
            update_option($field, 'toggle' . $value);
        return;
    }

    $arr_int = array('fifu_auto_set_height', 'fifu_auto_set_width', 'fifu_crop_delay', 'fifu_fake_created', 'fifu_slider_pause', 'fifu_slider_speed', 'fifu_spinner_nth', 'fifu_video_min_width', 'fifu_video_zindex');
    if (in_array($field, $arr_int)) {
        if (filter_var($value, FILTER_VALIDATE_INT))
            update_option($field, $value);
        return;
    }

    $arr_hex = array('fifu_key');
    if (in_array($field, $arr_hex)) {
        if (filter_var(trim($value), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-z0-9-]+$/"))))
            update_option($field, trim($value));
        return;
    }

    $arr_email = array('fifu_email');
    if (in_array($field, $arr_email)) {
        if (filter_var($value, FILTER_VALIDATE_EMAIL))
            update_option($field, $value);
        return;
    }

    $arr_ratio = array('fifu_crop_ratio');
    if (in_array($field, $arr_ratio)) {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[0-9]+[:][0-9]+$/"))))
            update_option($field, $value);
        return;
    }

    $arr_fit = array('fifu_fit');
    if (in_array($field, $arr_fit)) {
        if (in_array($value, array('cover', 'contain', 'fill')))
            update_option($field, $value);
        return;
    }

    $arr_play_type = array('fifu_play_type');
    if (in_array($field, $arr_play_type)) {
        if (in_array($value, array('inline', 'lightbox')))
            update_option($field, $value);
        return;
    }

    $arr_url = array('fifu_default_url', 'fifu_error_url', 'fifu_slider_left', 'fifu_slider_right');
    if (in_array($field, $arr_url)) {
        if (empty($value) || filter_var($value, FILTER_VALIDATE_URL))
            update_option($field, esc_url_raw($value));
        return;
    }

    $arr_textarea = array('fifu_auto_set_blocklist');
    if (in_array($field, $arr_textarea)) {
        update_option($field, sanitize_textarea_field($value));
        return;
    }

    $arr_text = array('fifu_auto_set_cpt', 'fifu_auto_set_source', 'fifu_auto_set_license', 'fifu_upload_domain', 'fifu_crop0', 'fifu_crop1', 'fifu_crop2', 'fifu_crop3', 'fifu_crop4', 'fifu_crop_default', 'fifu_crop_ignore_parent', 'fifu_default_cpt', 'fifu_hide_format', 'fifu_finder_custom_field', 'fifu_isbn_custom_field', 'fifu_skip', 'fifu_html_cpt', 'fifu_upload_private_proxy', 'fifu_video_color', 'fifu_buy_text', 'fifu_buy_disclaimer', 'fifu_buy_cf');
    if (in_array($field, $arr_text))
        update_option($field, sanitize_text_field($value));
}

function fifu_enable_fake() {
    if ((get_option('fifu_fake_created') && get_option('fifu_fake_created') != null) || get_option('fifu_ck') || get_option('fifu_chk'))
        return;
    update_option('fifu_fake_created', true, 'no');

    fifu_db_change_url_length();
    fifu_db_insert_attachment();
    fifu_db_insert_attachment_gallery();
    fifu_db_insert_attachment_category();
}

function fifu_disable_fake() {
    if (!get_option('fifu_fake_created') && get_option('fifu_fake_created') != null)
        return;
    update_option('fifu_fake_created', false, 'no');

    fifu_db_delete_default_url();
    fifu_db_delete_attachment();
    fifu_db_delete_attachment_category();
}

function fifu_version() {
    $plugin_data = get_plugin_data(FIFU_PLUGIN_DIR . 'fifu-premium.php');
    return $plugin_data ? $plugin_data['Name'] . ':' . $plugin_data['Version'] : '';
}

function fifu_version_number() {
    return get_plugin_data(FIFU_PLUGIN_DIR . 'fifu-premium.php')['Version'];
}

function fifu_su_sign_up_complete() {
    return isset(get_option('fifu_su_privkey')[0]) ? true : false;
}

function fifu_su_get_email() {
    return base64_decode(get_option('fifu_su_email')[0]);
}

function fifu_get_last($meta_key) {
    $list = '';
    foreach (fifu_db_get_last($meta_key) as $key => $row) {
        $aux = $row->meta_value . ' &#10; |__ ' . get_permalink($row->id);
        $list .= '&#10; | ' . $aux;
    }
    return $list;
}

function fifu_get_plugins_list() {
    $list = '';
    foreach (get_plugins() as $key => $domain) {
        $name = $domain['Name'] . ' (' . $domain['TextDomain'] . ')';
        $list .= '&#10; - ' . $name;
    }
    return $list;
}

function fifu_get_active_plugins_list() {
    $list = '';
    foreach (get_option('active_plugins') as $key) {
        $name = explode('/', $key)[0];
        $list .= '&#10; - ' . $name;
    }
    return $list;
}

function fifu_has_curl() {
    return function_exists('curl_version');
}

function fifu_number_of_users() {
    return count_users()['total_users'];
}

function fifu_check_update_url() {
    return wp_nonce_url(
            add_query_arg(
                    array(
                        'puc_check_for_updates' => 1,
                        'puc_slug' => 'fifu-premium',
                    ),
                    is_network_admin() ? network_admin_url('plugins.php') : admin_url('plugins.php')
            ),
            'puc_check_for_updates'
    );
}

function fifu_is_valid_nonce($nonce, $action = FIFU_ACTION_SETTINGS) {
    return isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $action);
}

function fifu_check_integrity() {
    $found = false;
    $file = fopen(FIFU_PLUGIN_DIR . "fifu-premium.php", "r");
    while (!feof($file)) {
        $line = fgets($file);
        if (strpos($line, 'fifu_' . 'ck') !== false) {
            update_option('fifu_chk', 1, 'no');
            $found = true;
            break;
        }
    }
    if (!$found)
        delete_option('fifu_chk');
    fclose($file);
}

