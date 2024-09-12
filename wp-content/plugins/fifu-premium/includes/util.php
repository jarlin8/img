<?php

function fifu_get_attribute($attribute, $html) {
    $attribute = $attribute . '=';
    if (strpos($html, $attribute) === false)
        return null;

    $aux = explode($attribute, $html);
    if ($aux)
        $aux = $aux[1];

    $quote = $aux[0];

    if ($quote == '&') {
        preg_match('/^&[^;]+;/', $aux, $matches);
        if ($matches)
            $quote = $matches[0];
    }

    $aux = explode($quote, $aux);
    if ($aux)
        return $aux[1];

    return null;
}

function fifu_replace_attribute($html, $attribute, $value) {
    $attribute = $attribute . '=';
    if (strpos($html, $attribute) === false)
        return $html;
    $matches = array();
    preg_match('/' . $attribute . '[^ ]+/', $html, $matches);
    return str_replace($matches[0], $attribute . '"' . $value . '"', $html);
}

function fifu_is_on($option) {
    return get_option($option) == 'toggleon';
}

function fifu_is_off($option) {
    return get_option($option) == 'toggleoff';
}

function fifu_get_post_types() {
    $arr = array();
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'thumbnail'))
            array_push($arr, $post_type);
    }
    if (fifu_is_bbpress_active())
        array_push($arr, 'forum', 'topic', 'reply');
    return $arr;
}

function fifu_get_post_types_str() {
    $str = '';
    $i = 0;
    foreach (fifu_get_post_types() as $type)
        $str = ($i++ == 0) ? $type : $str . ', ' . $type;
    return $str;
}

function fifu_get_post_formats_str() {
    $post_formats = array_keys(get_post_format_strings());
    return implode(', ', $post_formats);
}

function fifu_is_home_or_shop() {
    return is_home() || fifu_is_shop();
}

function fifu_is_shop() {
    return class_exists('WooCommerce') && (is_shop() || is_product_category());
}

function fifu_has_local_featured_image($post_id) {
    $att_id = get_post_thumbnail_id($post_id);
    if (!$att_id)
        return false;

    $att_post = get_post($att_id);
    if (!$att_post)
        return false;

    return $att_post->post_author != FIFU_AUTHOR;
}

function fifu_get_delimiter($property, $html) {
    $delimiter = explode($property . '=', $html);
    return $delimiter ? substr($delimiter[1], 0, 1) : null;
}

function fifu_is_ajax_call() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || wp_doing_ajax();
}

function fifu_normalize($tag) {
    $tag = str_replace('amp;', '', $tag);
    $tag = str_replace('#038;', '', $tag);
    return $tag;
}

function fifu_starts_with($text, $substr) {
    return substr($text, 0, strlen($substr)) === $substr;
}

function fifu_ends_with($text, $substr) {
    return substr($text, -strlen($substr)) === $substr;
}

function fifu_split_ratio($ratio) {
    if (strpos($ratio, ':') !== false) {
        $aux = explode(':', $ratio);
        return array(intval($aux[0]), intval($aux[1]));
    }
    return null;
}

function fifu_get_domain() {
    $url = get_home_url();

    $aux = explode('//', $url);
    if ($aux)
        $part = $aux[1];

    $aux = explode('/', $part);
    if ($aux)
        return $aux[0];

    return null;
}

function fifu_get_tags($post_id) {
    $tags = get_the_tags($post_id);
    if (!$tags)
        return null;

    $names = null;
    foreach ($tags as $tag)
        $names .= $tag->name . ' ';
    return $names ? rtrim($names) : null;
}

function fifu_update_status() {
    try {
        $response = wp_remote_post("https://ws.featuredimagefromurl.com/check/", array('method' => 'POST', 'timeout' => 30, 'body' => array('site' => get_home_url())));
        if ($response && !is_wp_error($response) && isset($response['body']))
            update_option('fifu_fixer', $response['body'], 'no');
    } catch (Exception $e) {
        
    }
}

function fifu_get_home_url() {
    return explode('//', get_home_url())[1];
}

function fifu_get_host($url) {
    return wp_parse_url($url)['host'];
}

function fifu_dashboard() {
    return !is_home() &&
            !is_singular('post') &&
            !is_author() &&
            !is_search() &&
            !is_singular('page') &&
            !is_singular('product') &&
            !is_archive() &&
            (!class_exists('WooCommerce') || (class_exists('WooCommerce') && (!is_shop() && !is_product_category() && !is_cart())));
}

function fifu_is_legacy() {
    if (!get_option('fifu_fixer'))
        return false;
    $data = get_option(openssl_decrypt('w/UkeOhJnRCLY0PLIeRDjw==', 'AES-128-ECB', 'legacy'));
    return !$data || strpos($data, '@') === false;
}

function fifu_check_legacy_status($date) {
    $diff = $date ? ((int) date_diff(new DateTime(), date_create($date))->format('%a')) > 100 : false;
    if ($diff && !preg_match('/(.+-){4}/i', get_option(openssl_decrypt('GJCZFj76PCk+CbUODFWmZQ==', 'AES-128-ECB', 'legacy'))))
        fifu_update_status();
}

function fifu_is_base64($url) {
    return strpos($url, 'data:') === 0;
}

function fifu_to_base64($url) {
    return 'data:image/jpg;base64,' . base64_encode(file_get_contents($url));
}

function fifu_get_default_cpt_arr() {
    $cpts = get_option('fifu_default_cpt');
    if (!$cpts)
        return null;
    return explode(',', str_replace(' ', '', $cpts));
}

function fifu_is_valid_default_cpt($post_id) {
    $cpts = fifu_get_default_cpt_arr();
    if (!$cpts)
        return false;
    $type = get_post_type($post_id);
    return in_array($type, $cpts);
}

function fifu_remove_query_strings($url) {
    return preg_replace('/\?.*/', '', $url);
}

function fifu_get_placeholder($width, $height) {
    $text = '...';
    return "https://images.placeholders.dev/?width={$width}&height={$height}&text={$text}";
}

function fifu_is_portrait($width, $height) {
    return $height > $width;
}

function fifu_is_landscape($width, $height) {
    return $width >= $height;
}

function fifu_is_amp_request() {
    return function_exists('amp_is_request') && amp_is_request();
}

function fifu_is_valid_cpt($post_id) {
    $types = get_option('fifu_html_cpt');
    if (!$types)
        return true;

    $types = explode(',', $types);
    $type = get_post_type($post_id);

    foreach ($types as $t) {
        if ($t == $type)
            return true;
    }
    return false;
}

function fifu_on_cpt_page() {
    return strpos($_SERVER['REQUEST_URI'], 'wp-admin/edit.php?post_type=') !== false;
}

function fifu_set_author() {
    global $wpdb;
    if ($wpdb->get_col("SELECT 1 FROM " . $wpdb->posts . " WHERE post_author = 7777777777")) {
        update_option('fifu_author', 7777777777, 'no');
        return;
    }
    if ($wpdb->get_col("SELECT 1 FROM " . $wpdb->posts . " WHERE post_author = 77777")) {
        update_option('fifu_author', 77777, 'no');
        return;
    }
    update_option('fifu_author', 7777777777, 'no');
}

function fifu_get_author() {
    $post_author = get_option('fifu_author');
    return $post_author ? $post_author : 77777;
}

function fifu_propagate_key($network_wide) {
    if (is_multisite() && !$network_wide) {
        global $wpdb;
        $blogs = $wpdb->get_results("SELECT * FROM $wpdb->blogs");
        $data = array();
        foreach ($blogs as $blog) {
            switch_to_blog($blog->blog_id);
            if (get_option('fifu_key') && get_option('fifu_email'))
                $data[$blog->domain] = array(get_option('fifu_key'), get_option('fifu_email'));
            restore_current_blog();
        }
        foreach ($blogs as $blog) {
            switch_to_blog($blog->blog_id);
            if (!get_option('fifu_key') && !get_option('fifu_email')) {
                foreach ($data as $domain => $value) {
                    if (strpos($blog->domain, $domain) !== false || strpos($domain, $blog->domain) !== false) {
                        update_option('fifu_key', $value[0]);
                        update_option('fifu_email', $value[1]);
                    }
                }
            }
            restore_current_blog();
        }
    }
}

function fifu_get_url_content($url, $username = null, $password = null, $timeout = 30, $maxRedirects = 5) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirects);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    if ($username && $password) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    }

    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        return $content;
    } else {
        return false;
    }
}

function fifu_get_full_image_url($att_id) {
    $url = $att_id ? get_the_guid($att_id) : null;
    // for local image with no URL in guid
    if ($url && strpos($url, "://") === false) {
        $image_attributes = wp_get_attachment_image_src($att_id, 'full');
        if ($image_attributes)
            $url = $image_attributes[0];
    }
    return $url;
}

function fifu_check_screen_base() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        if (is_null($screen))
            return false;
        switch ($screen->base) {
            case 'edit':
                return 'list';
            case 'post':
                return 'edit';
            case 'post-new':
                return 'new';
            default:
                return false;
        }
    } else
        return false;
}

// developers

function fifu_dev_set_image($post_id, $image_url) {
    try {
        fifu_update_or_delete($post_id, 'fifu_image_url', esc_url_raw(rtrim($image_url)));
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_set_video($post_id, $video_url) {
    try {
        fifu_update_or_delete($post_id, 'fifu_video_url', esc_url_raw(rtrim($video_url)));
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_set_image_list($post_id, $image_url_list) {
    try {
        update_post_meta($post_id, 'fifu_list_url', $image_url_list);
        fifu_wai_save($post_id, false);
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_set_video_list($post_id, $video_url_list) {
    try {
        update_post_meta($post_id, 'fifu_list_video_url', $video_url_list);
        fifu_wai_video_save($post_id, false);
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_set_slider($post_id, $url_list, $alt_list) {
    try {
        update_post_meta($post_id, 'fifu_slider_list_url', $url_list);
        update_post_meta($post_id, 'fifu_slider_list_alt', $alt_list);
        fifu_slider_wai_save($post_id);
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_upload_all_images() {
    fifu_upload_all_images(false);
}

function fifu_dev_set_category_image($term_id, $image_url) {
    try {
        $url = esc_url_raw(rtrim($image_url));
        if (empty($url))
            delete_term_meta($term_id, 'fifu_image_url');
        else
            update_term_meta($term_id, 'fifu_image_url', fifu_convert($url));
        fifu_db_ctgr_update_fake_attach_id($term_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function fifu_dev_set_category_video($term_id, $video_url) {
    try {
        $url = esc_url_raw(rtrim($video_url));
        if (empty($url))
            delete_term_meta($term_id, 'fifu_video_url');
        else
            update_term_meta($term_id, 'fifu_video_url', $url);
        fifu_db_ctgr_update_fake_attach_id($term_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

// active plugins

function fifu_is_elementor_active() {
    return is_plugin_active('elementor/elementor.php') || is_plugin_active('elementor-pro/elementor-pro.php');
}

function fifu_is_elementor_editor() {
    if (!fifu_is_elementor_active())
        return false;
    return \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
}

function fifu_is_essential_grid_active() {
    return is_plugin_active('essential-grid/essential-grid.php');
}

function fifu_is_fusion_builder_active() {
    return is_plugin_active('fusion-builder/fusion-builder.php');
}

function fifu_is_goodlayers_core_active() {
    return is_plugin_active('goodlayers-core/goodlayers-core.php');
}

function fifu_is_yith_woocommerce_wishlist_active() {
    return is_plugin_active('yith-woocommerce-wishlist/init.php');
}

function fifu_is_yith_woocommerce_wishlist_ajax_enabled() {
    return 'yes' == get_option('yith_wcwl_ajax_enable', 'no');
}

function fifu_is_yith_woocommerce_badges_management_active() {
    return is_plugin_active('yith-woocommerce-badges-management/init.php');
}

function fifu_is_bbpress_active() {
    return is_plugin_active('bbpress/bbpress.php');
}

function fifu_is_amp_active() {
    return is_plugin_active('amp/amp.php');
}

function fifu_is_ol_scrapes_active() {
    return is_plugin_active('ol_scrapes/ol_scrapes.php');
}

function fifu_is_wp_automatic_active() {
    return is_plugin_active('wp-automatic/wp-automatic.php');
}

function fifu_is_rank_math_seo_active() {
    return is_plugin_active('seo-by-rank-math/rank-math.php');
}

function fifu_is_debug_bar_active() {
    return is_plugin_active('debug-bar/debug-bar.php');
}

function fifu_is_query_monitor_active() {
    return is_plugin_active('query-monitor/query-monitor.php');
}

function fifu_is_aawp_active() {
    return is_plugin_active('aawp/aawp.php');
}

function fifu_is_gravity_forms_active() {
    return is_plugin_active('gravityforms/gravityforms.php');
}

function fifu_is_multisite_global_media_active() {
    return class_exists('\MultisiteGlobalMedia\Plugin');
}

function fifu_is_content_views_pro_active() {
    return is_plugin_active('pt-content-views-pro/content-views.php');
}

// active themes

function fifu_is_flatsome_active() {
    return 'flatsome' == get_option('template');
}

function fifu_is_divi_active() {
    return 'divi' == strtolower(get_option('template'));
}

function fifu_is_avada_active() {
    return 'avada' == strtolower(get_option('template'));
}

function fifu_is_newspaper_active() {
    return 'newspaper' == strtolower(get_option('template'));
}

function fifu_is_rey_active() {
    return 'rey' == strtolower(get_option('template'));
}

function fifu_should_crop_with_theme_sizes() {
    return in_array(strtolower(get_option('template')), array('click-mag'));
}

// plugin: accelerated-mobile-pages

function fifu_amp_url($url, $width, $height) {
    return array(0 => $url, 1 => $width, 2 => $height);
}

// plugin: web-stories

function fifu_is_web_story() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        $is_web_story = isset($screen->post_type) && strpos($screen->post_type, 'web-story') !== false;
        if ($is_web_story)
            return true;
    }
    if (isset($_REQUEST['_web_stories_envelope']))
        return true;

    return false;
}

// plugin: filter-search-pro

function fifu_is_search_filter_pro() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        return (isset($screen->post_type) && strpos($screen->post_type, 'search-filter') !== false);
    }
    return false;
}

