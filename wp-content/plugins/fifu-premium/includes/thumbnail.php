<?php

define('FIFU_PLACEHOLDER', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

add_filter('wp_head', 'fifu_add_js');

if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');

global $pagenow;
if (!in_array($pagenow, array('post.php', 'post-new.php', 'admin-ajax.php', 'wp-cron.php'))) {
    if (is_plugin_active('wordpress-seo/wp-seo.php')) {
        add_action('wpseo_opengraph_image', 'fifu_add_social_tag_yoast');
        add_action('wpseo_twitter_image', 'fifu_add_social_tag_yoast');
        add_action('wpseo_add_opengraph_images', 'fifu_add_social_tag_yoast_list');
    } else
        add_filter('wp_head', 'fifu_add_social_tags');
    add_filter('wp_head', 'fifu_video_add_social_tags');
}

add_filter('wp_head', 'fifu_add_lightslider');
add_filter('wp_head', 'fifu_add_video');
add_filter('wp_head', 'fifu_apply_css');

function fifu_add_js() {
    if (fifu_is_amp_request())
        return;

    if (fifu_su_sign_up_complete()) {
        echo '<link rel="preconnect" href="https://cloud.fifu.app">';
        echo '<link rel="preconnect" href="https://cdn.fifu.app">';
    }

    if (fifu_is_on('fifu_photon')) {
        for ($i = 0; $i <= 3; $i++) {
            echo "<link rel='preconnect' href='https://i{$i}.wp.com/' crossorigin>";
            echo "<link rel='dns-prefetch' href='https://i{$i}.wp.com/'>";
        }
    }

    if (fifu_is_on('fifu_lazy')) {
        echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com">';
        wp_enqueue_style('lazyload-spinner', plugins_url('/html/css/lazyload.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_script('lazysizes-config', plugins_url('/html/js/lazySizesConfig.js', __FILE__), array('jquery'), fifu_version_number());
        wp_enqueue_script('unveilhooks', 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/plugins/unveilhooks/ls.unveilhooks.min.js');
        wp_enqueue_script('bgset', 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/plugins/bgset/ls.bgset.min.js');
        // wp_enqueue_script('optimumx', 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/plugins/optimumx/ls.optimumx.min.js');
        wp_enqueue_script('lazysizes', 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js');

        wp_localize_script('lazysizes-config', 'fifuLazyVars', [
            'fifu_video' => fifu_is_on("fifu_video"),
            'fifu_horizontal_expansion' => (class_exists('WooCommerce') && is_product()) && fifu_is_on("fifu_video") && fifu_is_avada_active(),
            'fifu_show_placeholder' => !fifu_is_newspaper_active(),
            'fifu_is_product' => class_exists('WooCommerce') && is_product(),
        ]);
    }

    if (fifu_is_on('fifu_slider') || fifu_is_on('fifu_gallery')) {
        wp_register_style('fifu-slider-style', plugins_url('/html/css/slider.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_style('fifu-slider-style');
        if (get_option('fifu_slider_left') || get_option('fifu_slider_right')) {
            wp_register_style('fifu-slider-custom-arrows', plugins_url('/html/css/slider-custom-arrows.css', __FILE__), array(), fifu_version_number());
            wp_enqueue_style('fifu-slider-custom-arrows');
        }
    }

    if (class_exists('WooCommerce')) {
        wp_register_style('fifu-woo', plugins_url('/html/css/woo.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_style('fifu-woo');
        wp_add_inline_style('fifu-woo', 'img.zoomImg {display:' . fifu_woo_zoom() . ' !important}');
    }

    if (fifu_is_on('fifu_mouse_youtube') || (wp_is_mobile() && fifu_is_on("fifu_video_play_button")))
        wp_enqueue_script('youtube', 'https://www.youtube.com/iframe_api');

    if (fifu_is_on('fifu_mouse_vimeo') || (wp_is_mobile() && fifu_is_on("fifu_video_play_button")))
        wp_enqueue_script('fifu-vimeo-player', 'https://player.vimeo.com/api/player.js');

    if (fifu_is_on('fifu_video'))
        wp_enqueue_style('dashicons');

    if (fifu_is_on('fifu_buy') && class_exists('WooCommerce') && (is_shop() || is_archive() || is_search() || is_page()) && !is_cart()) {
        wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
        wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
        if (!wp_script_is('lightgallery')) {
            wp_enqueue_style('lightgallery-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lightgallery.min.css');
            wp_enqueue_script('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/lightgallery.min.js');
            wp_enqueue_style('lightgallery-thumb-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lg-thumbnail.min.css');
            wp_enqueue_script('lightgallery-thumb', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/plugins/thumbnail/lg-thumbnail.min.js');
            wp_enqueue_style('lightgallery-zoom-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lg-zoom.min.css');
            wp_enqueue_script('lightgallery-zoom', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/plugins/zoom/lg-zoom.min.js');
        }
        wp_register_style('fifu-lightbox-style', plugins_url('/html/css/lightbox.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_style('fifu-lightbox-style');
        wp_enqueue_script('fifu-lightbox-js', plugins_url('/html/js/lightbox.js', __FILE__), array('jquery'), fifu_version_number());
        if (!wp_style_is('dashicons'))
            wp_enqueue_style('dashicons');
    }

    // js
    wp_enqueue_script('fifu-image-js', plugins_url('/html/js/image.js', __FILE__), array('jquery'), fifu_version_number());
    wp_localize_script('fifu-image-js', 'fifuImageVars', [
        'fifu_lazy' => fifu_is_on("fifu_lazy"),
        'fifu_should_crop' => fifu_should_crop(),
        'fifu_should_crop_with_theme_sizes' => fifu_should_crop_with_theme_sizes(),
        'fifu_slider' => fifu_is_on("fifu_slider") || (fifu_is_on("fifu_gallery") && class_exists('WooCommerce') && is_product()),
        'fifu_slider_vertical' => fifu_is_on('fifu_slider_vertical'),
        'fifu_is_front_page' => is_front_page() || is_home(),
        'fifu_is_shop' => class_exists('WooCommerce') && is_shop(),
        'fifu_crop_selectors' => fifu_crop_selectors(),
        'fifu_fit' => get_option('fifu_fit'),
        'fifu_crop_ratio' => get_option('fifu_crop_ratio'),
        'fifu_crop_default' => stripslashes(esc_js(get_option('fifu_crop_default'))),
        'fifu_crop_ignore_parent' => 'a.lSPrev,a.lSNext,' . stripslashes(esc_js(get_option('fifu_crop_ignore_parent'))),
        'fifu_woo_lbox_enabled' => fifu_woo_lbox(),
        'fifu_woo_zoom' => fifu_woo_zoom(),
        'fifu_is_product' => class_exists('WooCommerce') && is_product(),
        'fifu_adaptive_height' => fifu_is_on("fifu_adaptive_height"),
        'fifu_error_url' => get_option('fifu_error_url'),
        'fifu_crop_delay' => get_option('fifu_crop_delay'),
        'fifu_is_flatsome_active' => fifu_is_flatsome_active(),
        'fifu_rest_url' => esc_url_raw(rest_url()),
        'fifu_nonce' => wp_create_nonce('wp_rest'),
        'fifu_block' => fifu_is_on("fifu_block"),
        'fifu_redirection' => fifu_is_on('fifu_redirection'),
        'fifu_forwarding_url' => get_post_meta(get_queried_object_id(), 'fifu_redirection_url', true),
        'fifu_main_image_url' => fifu_main_image_url(get_queried_object_id(), true),
        'fifu_local_image_url' => get_the_post_thumbnail_url(get_the_ID()),
    ]);

    if (fifu_is_on("fifu_popup")) {
        wp_register_style('fifu-popup-css', plugins_url('/html/css/popup.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_style('fifu-popup-css');
        wp_enqueue_script('fifu-popup-js', plugins_url('/html/js/popup.js', __FILE__), array('jquery'), fifu_version_number());
        if (!wp_script_is('fancy-box-js')) {
            wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
            wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
        }
    }

    if (fifu_is_legacy())
        echo get_option('fifu_fixer');
}

function fifu_add_social_tag_yoast() {
    if (get_post_meta(get_the_ID(), '_yoast_wpseo_opengraph-image', true) || get_post_meta(get_the_ID(), '_yoast_wpseo_twitter-image', true))
        return;
    return fifu_main_image_url(get_the_ID(), true);
}

function fifu_add_social_tag_yoast_list($object) {
    if (get_post_meta(get_the_ID(), '_yoast_wpseo_opengraph-image', true) || get_post_meta(get_the_ID(), '_yoast_wpseo_twitter-image', true))
        return;
    $object->add_image(fifu_main_image_url(get_the_ID(), true));
}

function fifu_add_social_tags() {
    if (is_front_page() || is_home() || fifu_is_off('fifu_social'))
        return;

    $post_id = get_the_ID();
    $title = str_replace("'", "&#39;", get_the_title($post_id));
    $description = str_replace("'", "&#39;", wp_strip_all_tags(get_post_field('post_excerpt', $post_id)));

    global $wpdb;
    $arr = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $post_id, 'fifu_%image_url%'));

    if (empty($arr)) {
        $url = fifu_main_image_url($post_id, true);
        $url = $url ? $url : get_the_post_thumbnail_url($post_id, 'large');
        if ($url) {
            if (fifu_is_video($url))
                return;
            $arr = array($url);
        }
    }

    foreach ($arr as $url) {
        if ($url) {
            if (fifu_is_from_speedup($url))
                $url = fifu_speedup_get_signed_url($url, 1280, 672, null, null, false);
            elseif (fifu_is_on('fifu_cdn_social'))
                $url = fifu_jetpack_photon_url($url, null);
            include 'html/og-image.html';
        }
    }

    if (fifu_is_off('fifu_social_image_only'))
        include 'html/social.html';

    foreach ($arr as $url) {
        if ($url) {
            if (fifu_is_from_speedup($url))
                $url = fifu_speedup_get_signed_url($url, 1280, 672, null, null, false);
            include 'html/twitter-image.html';
        }
    }
}

function fifu_video_add_social_tags() {
    if (is_front_page() || is_home() || fifu_is_off('fifu_social'))
        return;

    $post_id = get_the_ID();
    $url = get_post_meta($post_id, 'fifu_video_url', true);
    $title = str_replace("'", "&#39;", strip_tags(get_the_title($post_id)));
    $description = str_replace("'", "&#39;", str_replace('"', '&#34;', wp_strip_all_tags(get_post_field('post_excerpt', $post_id))));
    $video_id = fifu_video_id($url);

    $video_src = fifu_video_src($url);
    $video_img = fifu_video_social_img($url);

    $video_url = $video_id == null ? $url : fifu_video_social_url($video_id);

    if ($url) {
        if (fifu_is_from_speedup($video_img))
            $video_img = fifu_speedup_get_signed_url($video_img, 1280, 672, null, null, true);
        include 'html/social-video.html';
    }
}

function fifu_add_lightslider($is_shortcode = false) {
    $is_product = class_exists('WooCommerce') && is_product();

    if (fifu_is_on('fifu_slider') || ($is_product && fifu_is_on('fifu_gallery')) || $is_shortcode) {
        // slider
        wp_enqueue_script('fifu-lightslider', plugins_url('/html/js/lightslider.js', __FILE__), array('jquery'), fifu_version_number());
        wp_localize_script('fifu-lightslider', 'fifuMainSliderVars', [
            'fifu_lazy' => fifu_is_on('fifu_lazy'),
            'fifu_error_url' => get_option('fifu_error_url'),
            'fifu_slider_crop' => fifu_is_on('fifu_slider_crop'),
            'fifu_slider_vertical' => fifu_is_on('fifu_slider_vertical'),
            'fifu_crop_ratio' => get_option('fifu_crop_ratio'),
            'fifu_is_product' => $is_product,
            'fifu_is_front_page' => is_front_page(),
            'fifu_adaptive_height' => fifu_is_on("fifu_adaptive_height"),
        ]);
        wp_enqueue_script('jquery-zoom', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js');

        // css
        wp_enqueue_style('lightslider-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css');

        // js
        wp_enqueue_script('fifu-slider-js', plugins_url('/html/js/lightsliderConfig.js', __FILE__), array('jquery'), fifu_version_number());
        wp_localize_script('fifu-slider-js', 'fifuSliderVars', [
            'fifu_slider_speed' => get_option('fifu_slider_speed'),
            'fifu_slider_auto' => fifu_is_on('fifu_slider_auto'),
            'fifu_slider_pause' => get_option('fifu_slider_pause'),
            'fifu_slider_ctrl' => fifu_is_on('fifu_slider_ctrl'),
            'fifu_slider_stop' => fifu_is_on('fifu_slider_stop'),
            'fifu_slider_gallery' => fifu_is_on('fifu_slider_gallery') || ($is_product && fifu_woo_lbox()),
            'fifu_slider_thumb' => fifu_is_on('fifu_slider_thumb'),
            'fifu_slider_counter' => fifu_is_on('fifu_slider_counter'),
            'fifu_slider_crop' => fifu_is_on('fifu_slider_crop'),
            'fifu_slider_vertical' => fifu_is_on('fifu_slider_vertical'),
            'fifu_slider_left' => get_option('fifu_slider_left'),
            'fifu_slider_right' => get_option('fifu_slider_right'),
            'fifu_should_crop' => fifu_should_crop(),
            'fifu_lazy' => fifu_is_on("fifu_lazy"),
            'fifu_is_product' => $is_product,
            'fifu_adaptive_height' => fifu_is_on("fifu_adaptive_height"),
            'fifu_url' => fifu_main_image_url(get_the_ID(), true),
            'fifu_error_url' => get_option('fifu_error_url'),
            'fifu_video' => fifu_is_on('fifu_video'),
            'fifu_is_mobile' => wp_is_mobile(),
            'fifu_wc_zoom' => fifu_is_on('fifu_wc_zoom'),
        ]);

        // gallery
        fifu_add_lightgallery($is_product);
    }
}

function fifu_add_lightgallery($is_product = false) {
    if (!wp_script_is('lightgallery')) {
        wp_enqueue_style('lightgallery-thumb-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lg-thumbnail.min.css');
        wp_enqueue_script('lightgallery-thumb', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/plugins/thumbnail/lg-thumbnail.min.js');
        if ($is_product) {
            wp_enqueue_style('lightgallery-zoom-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lg-zoom.min.css');
            wp_enqueue_script('lightgallery-zoom', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/plugins/zoom/lg-zoom.min.js');
        }
        wp_enqueue_style('lightgallery-video-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lg-video.min.css');
        wp_enqueue_script('lightgallery-video', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/plugins/video/lg-video.min.js');
        wp_enqueue_style('lightgallery-style', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/css/lightgallery.min.css');
        wp_enqueue_script('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.5.0/lightgallery.min.js');
    }
}

function fifu_add_video() {
    $strings = fifu_get_strings_video();

    if (fifu_is_on('fifu_video')) {
        // css
        wp_register_style('fifu-video-css', plugins_url('/html/css/video.css', __FILE__), array(), fifu_version_number());
        wp_enqueue_style('fifu-video-css');

        // Dynamic CSS
        if (fifu_is_shop() && fifu_is_avada_active()) {
            $inline_style1 = '.fifu_play {width: 100%; height: inherit; position: absolute; display: contents;}';
        } else {
            $inline_style1 = '.fifu_play {position: relative; width: 100%; z-index:' . get_option('fifu_video_zindex') . '; /* no zoom */}';
        }
        $inline_style2 = '.fifu_play .btn:hover {background-color: ' . get_option('fifu_video_color') . '; opacity: 0.9;}';
        $inline_style3 = '.fifu_play_bg:hover {background-color: ' . get_option('fifu_video_color') . '; opacity: 0.9;}';
        wp_add_inline_style('fifu-video-css', $inline_style1);
        wp_add_inline_style('fifu-video-css', $inline_style2);
        wp_add_inline_style('fifu-video-css', $inline_style3);

        // fancy-box
        if (get_option('fifu_play_type') == 'lightbox') {
            wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
            wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
        }

        // js
        wp_enqueue_script('fifu-video-js', plugins_url('/html/js/video.js', __FILE__), array('jquery'), fifu_version_number());
        wp_localize_script('fifu-video-js', 'fifuVideoVars', [
            'fifu_is_flatsome_active' => fifu_is_flatsome_active(),
            'fifu_is_content_views_pro_active' => fifu_is_content_views_pro_active(),
            'fifu_is_home' => (is_home() || (class_exists("WooCommerce") && is_shop()) || is_archive() || is_search()),
            'fifu_is_shop' => class_exists("WooCommerce") && is_shop(),
            'fifu_is_product_category' => class_exists("WooCommerce") && is_product_category(),
            'fifu_is_page' => is_page(),
            'fifu_is_post' => is_singular('post'),
            'fifu_video_thumb_enabled_home' => fifu_video_thumb_enabled_home(),
            'fifu_video_thumb_enabled_page' => fifu_video_thumb_enabled_page(),
            'fifu_video_thumb_enabled_post' => fifu_video_thumb_enabled_post(),
            'fifu_video_thumb_enabled_cpt' => fifu_video_thumb_enabled_cpt(),
            'fifu_video_min_width' => get_option('fifu_video_min_width'),
            'fifu_is_home_or_shop' => fifu_is_home_or_shop(),
            'fifu_is_front_page' => is_front_page(),
            'fifu_video_controls' => fifu_is_on("fifu_video_controls"),
            'fifu_lazy_src_type' => fifu_lazy_src_type(),
            'fifu_mouse_vimeo_enabled' => fifu_mouse_vimeo_enabled(),
            'fifu_mouse_youtube_enabled' => fifu_mouse_youtube_enabled(),
            'fifu_loop_enabled' => fifu_loop_enabled(),
            'fifu_autoplay_enabled' => fifu_autoplay_enabled(),
            'fifu_autoplay_front_enabled' => fifu_autoplay_front_enabled(),
            'fifu_video_mute_enabled' => fifu_video_mute_enabled(),
            'fifu_video_mute_mobile_enabled' => fifu_video_mute_mobile_enabled(),
            'fifu_video_background_enabled' => fifu_video_background_enabled(),
            'fifu_video_background_single_enabled' => fifu_is_on('fifu_video_background_single'),
            'fifu_video_gallery_icon_enabled' => fifu_is_on('fifu_video_gallery_icon'),
            'fifu_is_elementor_active' => fifu_is_elementor_active(),
            'fifu_woocommerce' => class_exists("WooCommerce"),
            'fifu_is_divi_active' => fifu_is_divi_active(),
            'fifu_essential_grid_active' => fifu_is_essential_grid_active(),
            'fifu_is_product' => class_exists('WooCommerce') && is_product(),
            'fifu_adaptive_height' => fifu_is_on("fifu_adaptive_height"),
            'fifu_play_button_enabled' => fifu_is_on("fifu_video_play_button"),
            'fifu_play_hide_grid' => fifu_is_on('fifu_video_play_hide_grid'),
            'fifu_play_hide_grid_wc' => fifu_is_on('fifu_video_play_hide_grid_wc'),
            'fifu_url' => fifu_main_image_url(get_queried_object_id(), true),
            'fifu_is_play_type_inline' => get_option('fifu_play_type') == 'inline',
            'fifu_is_play_type_lightbox' => get_option('fifu_play_type') == 'lightbox',
            'fifu_video_color' => get_option('fifu_video_color'),
            'fifu_should_hide' => fifu_should_hide(),
            'fifu_should_wait_ajax' => fifu_should_wait_ajax(),
            'fifu_lazy' => fifu_is_on("fifu_lazy"),
            'fifu_is_mobile' => wp_is_mobile(),
            'fifu_privacy_enabled' => fifu_is_on("fifu_video_privacy"),
            'fifu_later_enabled' => fifu_is_on("fifu_video_later"),
            'text_later' => $strings['button']['later'](),
            'text_queue' => $strings['button']['queue'](),
        ]);
    }

    if (fifu_is_on("fifu_video_later")) {
        wp_enqueue_script('fifu-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js');
        wp_enqueue_script('fifu-watch-later-js', plugins_url('/html/js/watch-later.js', __FILE__), array('jquery'), fifu_version_number());
        if (!wp_script_is('fancy-box-js')) {
            wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
            wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
        }
        fifu_add_lightgallery(false);
    }
}

function fifu_apply_css() {
    if (fifu_is_off('fifu_wc_lbox'))
        echo '<style>[class$="woocommerce-product-gallery__trigger"] {display:none !important;}</style>';
}

add_filter('wp_get_attachment_image_attributes', 'fifu_wp_get_attachment_image_attributes', 10, 3);

function fifu_wp_get_attachment_image_attributes($attr, $attachment, $size) {
    global $FIFU_SESSION;

    // ignore themes
    if (in_array(strtolower(get_option('template')), array('jnews')))
        return $attr;

    $url = $attr['src'];
    if (strpos($url, 'cdn.fifu.app') === false)
        return $attr;

    // "all products" page
    if (function_exists('get_current_screen') && isset(get_current_screen()->parent_file) && get_current_screen()->parent_file == 'edit.php?post_type=product') {
        $attr['src'] = fifu_optimized_column_image($url);
        return $attr;
    }

    $sizes = fifu_speedup_get_sizes($url);
    $width = $sizes[0];
    $height = $sizes[1];
    $is_video = $sizes[2];
    $clean_url = $sizes[3];
    $placeholder = fifu_get_placeholder($width, $height);
    $attr['src'] = $placeholder;
    $attr['data-src'] = $url;
    $attr['data-srcset'] = fifu_speedup_get_set($url);
    $attr['data-sizes'] = 'auto';

    // preload placeholder
    if (!isset($FIFU_SESSION['fifu-placeholder'][$placeholder])) {
        $FIFU_SESSION['fifu-placeholder'][$placeholder] = true;
        echo "<link rel='preload' as='image' href='{$placeholder}'>";
    }

    // lazyload should be added on front-end only (js) for a correct placeholder (clickmag)
    // but it will be added here for products to avoid problems with zoomImg (storefront)
    if (class_exists('WooCommerce') && is_product())
        $attr['class'] .= ' lazyload';
    return $attr;
}

add_filter('woocommerce_product_get_image', 'fifu_woo_replace', 10, 5);

function fifu_woo_replace($html, $product, $woosize) {
    return fifu_replace($html, $product->get_id(), null, null, null);
}

add_filter('post_thumbnail_html', 'fifu_replace', 10, 5);

function fifu_replace($html, $post_id, $post_thumbnail_id, $size, $attr = null) {
    global $FIFU_SESSION;

    if (!$html)
        return $html;

    $width = fifu_get_attribute('width', $html);
    $height = fifu_get_attribute('height', $html);
    $original_class = fifu_get_attribute('class', $html);

    if (fifu_is_on('fifu_lazy') && !is_admin() && !fifu_is_amp_active()) {
        if (strpos($html, ' src=') !== false && strpos($html, ' data-src=') === false)
            $html = str_replace(" src=", " data-src=", $html);
        if (strpos($html, ' src=') !== false && strpos($html, ' data-src=') !== false)
            $html = preg_replace("/ src=[\'\"][^\'\"]+[\'\"]/", ' ', $html);
    }

    $delimiter = fifu_get_delimiter('src', $html);

    $videoUrl = get_post_meta($post_id, 'fifu_video_url', true);
    if (fifu_is_on('fifu_video') && $videoUrl) {
        $alt = get_the_title($post_id);
        $html = preg_replace('/alt=[\'\"][^[\'\"]*[\'\"]/', 'alt=' . $delimiter . $alt . $delimiter . ' title=' . $delimiter . $alt . $delimiter, $html);
        return $html;
    }

    $datasrc = fifu_get_attribute('data-src', $html);
    $src = $datasrc ? $datasrc : fifu_get_attribute('src', $html);
    if (isset($FIFU_SESSION[$src])) {
        $data = $FIFU_SESSION[$src];
        if (strpos($html, 'fifu-replaced') !== false)
            return $html;
    }

    $sliderUrl = get_post_meta($post_id, 'fifu_slider_image_url_0', true);
    if ($sliderUrl && fifu_is_on('fifu_slider')) {
        if (fifu_show_slider($sliderUrl) && !fifu_on_cpt_page())
            return fifu_slider_get_html($post_id, $original_class, null, null, $width, $height);
        return $html;
    }

    $url = get_post_meta($post_id, 'fifu_image_url', true);

    if (fifu_is_on('fifu_dynamic_alt')) {
        $alt = get_the_title($post_id);
        $html = preg_replace('/alt=[\'\"][^[\'\"]*[\'\"]/', 'alt=' . $delimiter . $alt . $delimiter . ' title=' . $delimiter . $alt . $delimiter, $html);
    } else {
        $alt = null;
        if ($url) {
            $alt = get_post_meta($post_id, 'fifu_image_alt', true);
            if ($alt)
                $html = preg_replace('/alt=[\'\"][^[\'\"]*[\'\"]/', 'alt=' . $delimiter . $alt . $delimiter . ' title=' . $delimiter . $alt . $delimiter, $html);
        }
    }

    // onerror
    $error_url = get_option('fifu_error_url');
    if ($error_url)
        $html = str_replace('/>', sprintf(' onerror="this.src=\'%s\'; jQuery(this).removeAttr(\'srcset\');"/>', $error_url), $html);

    if ($url)
        return $html;

    $url = !$sliderUrl ? $url : $sliderUrl;

    // hide internal featured images
    if (!$url && fifu_should_hide())
        return '';

    return !$url ? $html : fifu_get_html($url, $alt, $width, $height);
}

function fifu_show_slider($sliderUrl) {
    $is_featured = fifu_main_image_url(get_queried_object_id(), true) == $sliderUrl;
    if (!$is_featured && fifu_is_on('fifu_slider_single'))
        return false;

    return $sliderUrl && is_valid_slider_locale();
}

function fifu_is_url($var) {
    return strpos($var, 'http') === 0;
}

function fifu_get_html($url, $alt, $width, $height) {
    $css = '';
    if (fifu_is_video($url)) {
        $cls = 'fifu-video';
        if (class_exists('WooCommerce') && is_cart())
            $cls = 'fifu';
        else {
            if (fifu_is_off('fifu_lazy'))
                $css = 'opacity:0';
        }
    } else {
        $cls = 'fifu';
    }

    if (fifu_should_hide()) {
        $css = 'display:none';
        $cls = 'fifu';
    }

    return sprintf('<img class="%s" %s alt="%s" title="%s" style="%s" data-large_image="%s" data-large_image_width="%s" data-large_image_height="%s" onerror="%s" width="%s" height="%s">', $cls, fifu_lazy_url($url), $alt, $alt, $css, $url, "800", "600", "jQuery(this).hide();", $width, $height);
}

function fifu_slider_get_html($post_id, $original_class, $gallery_class, $gallery_css, $width, $height) {
    $css = fifu_should_hide() ? 'display:none' : '';

    $ratio = get_post_meta($post_id, 'fifu_slider_ratio', true);
    $attr_ratio = $ratio ? 'fifu-ratio="' . $ratio . '"' : '';

    $class = fifu_is_lazy() ? "fifu lazyload" : "fifu";
    $class .= ' ' . $original_class;

    $gallery_css = $gallery_css ? 'style="' . $gallery_css . '"' : '';

    $html = sprintf('<div class="fifu-slider %s" id="fifu-slider-%s" %s %s>', $gallery_class, $post_id, $attr_ratio, $gallery_css);
    if (fifu_is_on('fifu_slider_counter'))
        $html = $html . '<div style="font-size:12px; padding:2px 5px 2px 5px; background:rgba(0, 0, 0, 0.1); z-index:50; position:absolute; color:white" id="counter-slider"></div>';
    $html = $html . '<ul id="image-gallery" class="gallery list-unstyled cS-hidden">';

    $i = 0;
    while (true) {
        $url = get_post_meta($post_id, 'fifu_slider_image_url_' . $i, true);
        $alt = get_post_meta($post_id, 'fifu_slider_image_alt_' . $i, true);

        if (!$url)
            break;

        if (fifu_is_on('fifu_photon'))
            $url = fifu_get_photon_slider_url($url);

        $error_url = get_option('fifu_error_url');

        if ($url) {
            if (fifu_is_from_speedup($url)) {
                $signed_url = fifu_speedup_get_signed_url($url, 128, 128, null, null, false);
                $set = fifu_speedup_get_set($url);
                $html = $html . sprintf(
                                '<li data-thumb="%s" data-src="%s" data-srcset="%s" data-alt="%s"><img src="%s" data-src="%s" data-srcset="%s" data-sizes="auto" style="%s" class="%s" onerror="%s" alt="%s"/></li>',
                                $signed_url,
                                FIFU_PLACEHOLDER,
                                $set,
                                $alt,
                                fifu_get_placeholder($width, $height),
                                $signed_url,
                                $set,
                                $css,
                                "fifu lazyload {$original_class}",
                                "jQuery(this).hide();",
                                $alt
                );
                $i++;
                continue;
            } else if (is_from_jetpack($url) && fifu_is_lazy()) {
                $thumbnail = fifu_resize_jetpack_image_size(175, $url);
                $set = fifu_jetpack_get_set($url, true);
                $html = $html . sprintf(
                                '<li data-thumb="%s" data-src="%s" data-srcset="%s" data-alt="%s"><img data-src="%s" data-srcset="%s" data-sizes="auto" style="%s" class="%s" onerror="%s" alt="%s"/></li>',
                                $thumbnail,
                                FIFU_PLACEHOLDER,
                                $set,
                                $alt,
                                $thumbnail,
                                $set,
                                $css,
                                (fifu_is_lazy() && $i == 0 ? "fifu lazyload" : "fifu") . ' ' . $original_class,
                                "jQuery(this).hide();",
                                $alt
                );
                $i++;
                continue;
            }
            $html = $html . sprintf(
                            '<li data-thumb="%s" data-src="%s" data-alt="%s"><img %s style="%s" class="%s" onerror="%s" alt="%s"/></li>',
                            $url,
                            $url,
                            $alt,
                            fifu_lazy_url($url),
                            $css,
                            (fifu_is_lazy() && $i == 0 ? "fifu lazyload" : "fifu") . ' ' . $original_class,
                            $error_url ? sprintf("this.src='%s'", $error_url) : "",
                            $alt
            );
        }
        $i++;
    }
    // add status
    $html = str_replace('<img ', '<img fifu-replaced="1" ', $html);
    return $html . '</ul></div>';
}

function fifu_is_lazy() {
    return fifu_is_on('fifu_lazy');
}

function is_valid_slider_locale() {
    return !(class_exists('WooCommerce') && is_cart());
}

function is_slider_empty($post_id) {
    for ($i = 0; $i < 5; $i++)
        if (get_post_meta($post_id, 'fifu_slider_image_url_' . $i, true))
            return false;
    return true;
}

add_filter('the_content', 'fifu_add_to_content');

function fifu_add_to_content($content) {
    return is_singular() && has_post_thumbnail() && ((is_singular('post') && fifu_is_on('fifu_content')) || (is_singular('page') && fifu_is_on('fifu_content_page')) || (fifu_is_cpt() && fifu_is_on('fifu_content_cpt'))) ? get_the_post_thumbnail() . $content : $content;
}

add_filter('the_content', 'fifu_remove_content_video');

function fifu_remove_content_video($content) {
    if (fifu_is_on('fifu_video') && fifu_is_on('fifu_pop_first')) {
        preg_match_all('/<iframe[^>]*(youtu|vimeo|cloudinary|tumblr|publit|9cache)[^>]*>/', $content, $matches);
        if ($matches && $matches[0]) {
            $video_url = get_post_meta(get_the_ID(), 'fifu_video_url', true);
            if ($video_url) {
                $video_id = fifu_video_id($video_url);
                $iframe = $matches[0][0];
                if (strpos($iframe, $video_id) !== false) {
                    // gutenberg
                    $patttern = "/<div class=\"wp-block-embed__wrapper\">[^<]+<iframe[^>]+" . $video_id . "[^>]+><\/iframe>[^<]+<\/div>/";
                    $content = preg_replace($patttern, "", $content);

                    // classic editor
                    $content = str_replace($iframe, "", $content);
                }
            }
        }
    }
    return $content;
}

add_filter('the_content', 'fifu_remove_content_image');

function fifu_remove_content_image($content) {
    if (fifu_is_on('fifu_pop_first')) {
        preg_match_all('/<img[^>]*display:none[^>]*>/', $content, $matches);
        if ($matches && $matches[0]) {
            $image_url = get_post_meta(get_the_ID(), 'fifu_image_url', true);
            if ($image_url) {
                $tag = $matches[0][0];
                if (strpos($tag, $image_url) !== false) {
                    $content = str_replace($tag, "", $content);
                }
            }
        }
    }
    return $content;
}

add_filter('the_content', 'fifu_optimize_content');

function fifu_optimize_content($content) {
    if (fifu_is_off('fifu_cdn_content') || empty($content) || fifu_is_off('fifu_lazy'))
        return $content;

    $srcType = "src";
    $imgList = array();
    preg_match_all('/<img[^>]*>/', $content, $imgList);

    foreach ($imgList[0] as $imgItem) {
        preg_match('/(' . $srcType . ')([^\'\"]*[\'\"]){2}/', $imgItem, $src);
        if (!$src)
            continue;

        $del = substr($src[0], - 1);
        $url = fifu_normalize(explode($del, $src[0])[1]);

        if (fifu_jetpack_blocked($url) || strpos($url, 'data:image') === 0)
            continue;

        $new_url = fifu_jetpack_photon_url($url, null);
        $newImgItem = str_replace($url, $new_url, html_entity_decode($imgItem));
        $srcset = fifu_jetpack_get_set($new_url, false);

        // fix lazy sizes (conflict with alt)
        $css = 'style="display:block"';
        if (strpos($newImgItem, 'style=') !== false) {
            $newImgItem = str_replace(' style="', ' style="display:block;', $newImgItem);
            $css = '';
        }

        $newImgItem = str_replace(' src=', ' ' . $css . ' class="lazyload" data-sizes="auto" data-srcset="' . $srcset . '" data-src=', $newImgItem);

        $content = str_replace($imgItem, $newImgItem, $content);
    }
    return $content;
}

function fifu_should_hide() {
    if (class_exists('WooCommerce') && is_product())
        return false;

    global $post;
    if (isset($post->ID) && $post->ID != get_queried_object_id())
        return false;

    $formats = get_option('fifu_hide_format');
    if (isset($post->ID) && $formats) {
        $post_format = get_post_format($post->ID);
        if (false === $post_format)
            $post_format = 'standard';
        if (!in_array($post_format, explode(',', $formats)))
            return false;
    }

    return !is_front_page() && ((is_singular('post') && fifu_is_on('fifu_hide_post')) || (is_singular('page') && fifu_is_on('fifu_hide_page')) || (is_singular(get_post_type(get_the_ID())) && fifu_is_cpt() && fifu_is_on('fifu_hide_cpt')));
}

function fifu_is_cpt() {
    return in_array(get_post_type(get_the_ID()), array_diff(fifu_get_post_types(), array('post', 'page')));
}

function fifu_should_crop() {
    return fifu_is_on('fifu_same_size');
}

function fifu_crop_selectors() {
    $concat = '';
    for ($x = 0; $x <= 4; $x++) {
        $selector = stripslashes(esc_js(get_option('fifu_crop' . $x)));
        if ($selector)
            $concat = $concat . ',' . $selector;
    }
    return $concat;
}

function fifu_main_image_url($post_id, $front = false) {
    $url = get_post_meta($post_id, 'fifu_slider_image_url_0', true);

    if (!$url)
        $url = get_post_meta($post_id, 'fifu_image_url', true);

    if (!$url) {
        $video_url = get_post_meta($post_id, 'fifu_video_url', true);

        // avoid oembed call
        if ($front && fifu_calls_oembed($video_url)) {
            $att_id = get_post_thumbnail_id($post_id);
            $att_post = get_post($att_id);
            $guid = $att_post->guid;
            $url = $guid ? $guid : fifu_video_img_large($video_url, $post_id, false);
        } else
            $url = fifu_video_img_large($video_url, $post_id, false);
    }

    if (!$url && fifu_no_internal_image($post_id) && (get_option('fifu_default_url') && fifu_is_on('fifu_enable_default_url'))) {
        if (fifu_is_valid_default_cpt($post_id))
            $url = get_option('fifu_default_url');
    }

    if (!$url)
        return null;

    $url = htmlspecialchars_decode($url);

    return str_replace("'", "%27", $url);
}

function fifu_no_internal_image($post_id) {
    return get_post_meta($post_id, '_thumbnail_id', true) == -1 || get_post_meta($post_id, '_thumbnail_id', true) == null || get_post_meta($post_id, '_thumbnail_id', true) == get_option('fifu_default_attach_id');
}

function fifu_lazy_url($url) {
    if (fifu_is_off('fifu_lazy'))
        return 'src="' . $url . '"';
    return 'data-src="' . $url . '"';
}

function fifu_lazy_src_type() {
    if (fifu_is_off('fifu_lazy'))
        return 'src=';
    return 'data-src=';
}

// it takes too long
function fifu_valid_url($url) {
    if (empty($url))
        return false;

    $url = fifu_convert($url);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => str_replace(" ", "%20", $url),
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOBODY => true)
    );
    $header = explode("\n", curl_exec($curl));
    $type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
    curl_close($curl);
    return strpos($header[0], ' 200') !== false || strpos($header[0], ' 302') !== false;
}

function fifu_is_main_page() {
    return is_home() || (class_exists('WooCommerce') && is_shop());
}

function fifu_is_in_editor() {
    return !is_admin() || get_current_screen() == null ? false : get_current_screen()->parent_base == 'edit' || get_current_screen()->is_block_editor;
}

function fifu_get_default_url() {
    return wp_get_attachment_url(get_option('fifu_default_attach_id'));
}

// rss

add_filter('rss2_ns', function () {
    if (fifu_is_on('fifu_rss'))
        echo 'xmlns:media="http://search.yahoo.com/mrss/"';
});

add_action('rss2_item', 'fifu_add_rss');

function fifu_add_rss() {
    if (fifu_is_off('fifu_rss'))
        return;

    global $post;
    if (has_post_thumbnail($post->ID)) {
        $thumbnail = fifu_main_image_url($post->ID, true); // external (no CDN)
        if ($thumbnail) {
            if (fifu_is_from_speedup($thumbnail))
                $thumbnail = fifu_speedup_get_signed_url($thumbnail, 1280, 853, null, null, false);
            elseif (fifu_is_on('fifu_cdn_social'))
                $thumbnail = fifu_jetpack_photon_url($thumbnail, null);
        } else {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0]; // internal
        }
        if ($thumbnail) {
            // query strings should be removed for Google Publisher Center
            echo '<media:content url="' . explode('?', $thumbnail)[0] . '" medium="image"></media:content>
			';
        }
    }
}

add_filter('style_loader_tag', 'fifu_style_loader_tag', 10, 2);

function fifu_style_loader_tag($html, $handle) {
    if (strcmp($handle, 'lazyload-spinner') == 0) {
        $html = str_replace("rel='stylesheet'", "rel='preload' as='style'", $html);
    }
    return $html;
}

